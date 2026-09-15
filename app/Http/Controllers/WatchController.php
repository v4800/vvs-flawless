<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use App\Support\LocalizedRoute;
use App\Support\MarketingAttribution;
use App\Support\WatchCatalog;
use App\Support\WatchSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Response;

class WatchController extends Controller
{
    public function __construct(
        private readonly WatchCatalog $catalog,
        private readonly WatchSeo $seo,
        private readonly MarketingAttribution $marketingAttribution,
        private readonly LocalizedRoute $localizedRoute
    ) {}

    public function index(Request $request): Response
    {
        $this->marketingAttribution->capture($request);

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'integer', 'min:1'],
            'price_min' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'price_max' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'movement' => ['nullable', Rule::in(['japanese', 'swiss'])],
            'availability' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::in(['newest', 'price_asc', 'price_desc', 'name'])],
        ]);

        $inventory = Watch::query()
            ->select(['id', 'name', 'slug', 'image'])
            ->orderBy('name')
            ->get()
            ->map(
                fn (Watch $watch) => $this->catalog->localizedWatch(
                    $this->catalog->applyCover($watch)
                )
            );

        $query = Watch::query();

        $search = trim((string) ($filters['q'] ?? ''));

        if ($search !== '') {
            $matchingLocalizedIds = $this->matchingLocalizedIds(
                $inventory,
                $search
            );

            $query->where(function (Builder $builder) use (
                $search,
                $matchingLocalizedIds
            ): void {
                $builder
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');

                if ($matchingLocalizedIds !== []) {
                    $builder->orWhereIn('id', $matchingLocalizedIds);
                }
            });
        }

        if (isset($filters['model'])) {
            $query->whereKey((int) $filters['model']);
        }

        if (isset($filters['movement'])) {
            $movement = $filters['movement'];

            $query->where(function (Builder $builder) use ($movement): void {
                if ($movement === 'swiss') {
                    $builder
                        ->whereNotNull('swiss_price')
                        ->orWhereNotNull('swiss_promo_price');

                    return;
                }

                $builder
                    ->whereNotNull('japanese_price')
                    ->orWhereNotNull('japanese_promo_price');
            });
        }

        if (isset($filters['availability'])) {
            $query->where('availability', $filters['availability']);
        }

        $startingPrice = $this->priceExpressionForMovement(
            $filters['movement'] ?? null
        );

        $numericStartingPrice = 'CAST(('
            .$startingPrice
            .') AS DECIMAL(10, 2))';

        if (isset($filters['price_min'])) {
            $query->whereRaw(
                $numericStartingPrice.' >= ?',
                [(float) $filters['price_min']]
            );
        }

        if (isset($filters['price_max'])) {
            $query->whereRaw(
                $numericStartingPrice.' <= ?',
                [(float) $filters['price_max']]
            );
        }

        match ($filters['sort'] ?? 'newest') {
            'price_asc' => $query->orderByRaw($numericStartingPrice.' asc')->orderBy('id'),
            'price_desc' => $query->orderByRaw($numericStartingPrice.' desc')->orderByDesc('id'),
            'name' => $query->orderBy('name')->orderBy('id'),
            default => $query->latest(),
        };

        $paginator = $query
            ->paginate(12)
            ->withQueryString();

        $watches = $paginator
            ->getCollection()
            ->map(
                fn (Watch $watch) => $this->catalog->localizedWatch(
                    $this->catalog->applyCover($watch)
                )
            );

        $paginator->setCollection($watches);

        $seoQuery = $request->query();
        unset($seoQuery['page']);

        $seoHasFilters = collect($seoQuery)
            ->contains(function ($value): bool {
                if (is_array($value)) {
                    return $value !== [];
                }

                return trim((string) $value) !== '';
            });

        return inertia('Watches/Index', [
            'watches' => $watches,
            'catalogModels' => $this->catalog->featuredModels($inventory),
            'filters' => [
                'q' => $search,
                'model' => isset($filters['model'])
                    ? (string) $filters['model']
                    : '',
                'price_min' => isset($filters['price_min'])
                    ? (string) $filters['price_min']
                    : '',
                'price_max' => isset($filters['price_max'])
                    ? (string) $filters['price_max']
                    : '',
                'movement' => $filters['movement'] ?? '',
                'availability' => $filters['availability'] ?? '',
                'sort' => $filters['sort'] ?? 'newest',
            ],
            'filterOptions' => [
                'models' => $inventory
                    ->map(fn (Watch $watch) => [
                        'value' => (string) $watch->id,
                        'label' => $watch->name,
                        'reference' => 'VVS-'.$watch->id,
                    ])
                    ->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)
                    ->values(),
                'availability' => Watch::query()
                    ->whereNotNull('availability')
                    ->where('availability', '!=', '')
                    ->distinct()
                    ->orderBy('availability')
                    ->pluck('availability')
                    ->values(),
                'movements' => array_values(array_filter([
                    $this->movementExists('japanese')
                        ? [
                            'value' => 'japanese',
                            'label' => trans('site.collection.japanese'),
                        ]
                        : null,
                    $this->movementExists('swiss')
                        ? [
                            'value' => 'swiss',
                            'label' => trans('site.collection.swiss'),
                        ]
                        : null,
                ])),
            ],
            'pagination' => [
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'previousUrl' => $paginator->previousPageUrl(),
                'nextUrl' => $paginator->nextPageUrl(),
            ],
            'seo' => $this->seo->collection(
                $watches,
                $paginator->currentPage(),
                $paginator->perPage(),
                $seoHasFilters
            ),
        ]);
    }

    public function redirectLegacy(
        Request $request,
        int $watchId
    ): RedirectResponse {
        $watch = Watch::query()->findOrFail($watchId);

        $url = route(
            $this->localizedRoute->name('watches.show'),
            ['watch' => $watch] + $request->query()
        );

        return redirect()->to($url, 301);
    }

    public function show(
        Request $request,
        Watch $watch
    ): Response {
        $this->marketingAttribution->capture($request);

        $watch = $this->catalog->localizedWatch($watch);
        $gallery = $this->catalog->galleryForWatch($watch);

        if ($gallery !== []) {
            $watch->image = $gallery[0];
        }

        $selectedMovement = $request->query('movement') === 'Suisse'
            ? 'Suisse'
            : 'Japonais';

        $relatedWatches = Watch::query()
            ->where('id', '!=', $watch->id)
            ->select([
                'id',
                'name',
                'slug',
                'image',
                'stock_quantity',
                'japanese_price',
                'japanese_promo_price',
                'swiss_price',
                'swiss_promo_price',
            ])
            ->latest('updated_at')
            ->limit(3)
            ->get()
            ->map(
                fn (Watch $relatedWatch) => $this->catalog->localizedWatch(
                    $this->catalog->applyCover($relatedWatch)
                )
            );

        $productReviews = DB::table('vvs_customer_reviews')
            ->where('status', 'published')
            ->where('watch_id', $watch->id)
            ->select([
                'id',
                'display_name',
                'rating',
                'body',
                'created_at',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return inertia('Watches/Show', [
            'watch' => $watch,
            'gallery' => $gallery,
            'selectedMovement' => $selectedMovement,
            'relatedWatches' => $relatedWatches,
            'reviews' => $productReviews,
            'reviewRoutes' => [
                'index' => route('vvs.reviews.index'),
                'store' => route('vvs.reviews.store'),
            ],
            'seo' => $this->seo->product($watch, $gallery),
        ]);
    }

    /**
     * @param  Collection<int, Watch>  $inventory
     * @return list<int>
     */
    private function matchingLocalizedIds(
        Collection $inventory,
        string $search
    ): array {
        $needle = Str::lower(Str::ascii($search));

        return $inventory
            ->filter(function (Watch $watch) use ($needle): bool {
                $haystack = Str::lower(Str::ascii(
                    $watch->name
                    .' '
                    .$watch->slug
                    .' VVS-'
                    .$watch->id
                ));

                return Str::contains($haystack, $needle);
            })
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    private function movementExists(string $movement): bool
    {
        $columns = $movement === 'swiss'
            ? ['swiss_price', 'swiss_promo_price']
            : ['japanese_price', 'japanese_promo_price'];

        return Watch::query()
            ->where(function (Builder $query) use ($columns): void {
                $query
                    ->whereNotNull($columns[0])
                    ->orWhereNotNull($columns[1]);
            })
            ->exists();
    }

    private function priceExpressionForMovement(?string $movement): string
    {
        if ($movement === 'swiss') {
            return 'COALESCE(swiss_promo_price, swiss_price, price)';
        }

        if ($movement === 'japanese') {
            return 'COALESCE(japanese_promo_price, japanese_price, price)';
        }

        return <<<'SQL'
CASE
    WHEN COALESCE(japanese_promo_price, japanese_price) IS NULL
        THEN COALESCE(swiss_promo_price, swiss_price, price)
    WHEN COALESCE(swiss_promo_price, swiss_price) IS NULL
        THEN COALESCE(japanese_promo_price, japanese_price, price)
    WHEN COALESCE(japanese_promo_price, japanese_price)
        <= COALESCE(swiss_promo_price, swiss_price)
        THEN COALESCE(japanese_promo_price, japanese_price)
    ELSE COALESCE(swiss_promo_price, swiss_price)
END
SQL;
    }
}
