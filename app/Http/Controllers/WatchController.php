<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use App\Support\LocalizedRoute;
use App\Support\MarketingAttribution;
use App\Support\WatchCatalog;
use App\Support\WatchSeo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $watches = Watch::latest()
            ->get()
            ->map(
                fn (Watch $watch) => $this->catalog->applyCover(
                    $this->catalog->localizedWatch($watch)
                )
            );

        return inertia('Watches/Index', [
            'watches' => $watches,
            'catalogModels' => $this->catalog->featuredModels($watches),
            'seo' => $this->seo->collection($watches),
        ]);
    }

    public function showCatalog(
        Request $request,
        string $catalogSlug
    ): Response {
        $this->marketingAttribution->capture($request);

        $model = $this->catalog->catalogModel($catalogSlug);

        abort_if($model === null, 404);

        return inertia('Watches/CatalogShow', [
            'model' => $model,
            'seo' => [
                'title' => '41 mm · '.$model['name'].' | VVS FLAWLESS',
                'description' => trans('site.collection.source_description'),
                'canonical' => $model['detailUrl'],
            ],
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
                fn (Watch $relatedWatch) => $this->catalog->applyCover(
                    $this->catalog->localizedWatch($relatedWatch)
                )
            );

        return inertia('Watches/Show', [
            'watch' => $watch,
            'gallery' => $gallery,
            'selectedMovement' => $selectedMovement,
            'relatedWatches' => $relatedWatches,
            'seo' => $this->seo->product($watch, $gallery),
        ]);
    }
}
