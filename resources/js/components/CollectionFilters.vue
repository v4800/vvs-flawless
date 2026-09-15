<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    filters: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        required: true,
    },
    total: {
        type: Number,
        default: 0,
    },
});

const page = usePage();

const copy = computed(() => {
    const locale = page.props.locale;

    return (
        {
            fr_BE: {
                title: 'Trouver une montre',
                search: 'Recherche',
                searchPlaceholder: 'Nom, référence, cadran, style…',
                model: 'Modèle',
                allModels: 'Tous les modèles',
                minPrice: 'Prix min.',
                maxPrice: 'Prix max.',
                movement: 'Mouvement',
                allMovements: 'Tous',
                availability: 'Disponibilité',
                allAvailability: 'Toutes',
                sort: 'Trier par',
                newest: 'Nouveautés',
                priceAsc: 'Prix croissant',
                priceDesc: 'Prix décroissant',
                name: 'Nom A–Z',
                apply: 'Afficher',
                reset: 'Réinitialiser',
                results: 'modèles',
            },
            nl_BE: {
                title: 'Een horloge vinden',
                search: 'Zoeken',
                searchPlaceholder: 'Naam, referentie, wijzerplaat, stijl…',
                model: 'Model',
                allModels: 'Alle modellen',
                minPrice: 'Min. prijs',
                maxPrice: 'Max. prijs',
                movement: 'Uurwerk',
                allMovements: 'Alle',
                availability: 'Beschikbaarheid',
                allAvailability: 'Alle',
                sort: 'Sorteren op',
                newest: 'Nieuwste',
                priceAsc: 'Prijs oplopend',
                priceDesc: 'Prijs aflopend',
                name: 'Naam A–Z',
                apply: 'Tonen',
                reset: 'Resetten',
                results: 'modellen',
            },
            en_BE: {
                title: 'Find a watch',
                search: 'Search',
                searchPlaceholder: 'Name, reference, dial, style…',
                model: 'Model',
                allModels: 'All models',
                minPrice: 'Min. price',
                maxPrice: 'Max. price',
                movement: 'Movement',
                allMovements: 'All',
                availability: 'Availability',
                allAvailability: 'All',
                sort: 'Sort by',
                newest: 'Newest',
                priceAsc: 'Price low to high',
                priceDesc: 'Price high to low',
                name: 'Name A–Z',
                apply: 'Show',
                reset: 'Reset',
                results: 'models',
            },
            de_BE: {
                title: 'Eine Uhr finden',
                search: 'Suche',
                searchPlaceholder: 'Name, Referenz, Zifferblatt, Stil…',
                model: 'Modell',
                allModels: 'Alle Modelle',
                minPrice: 'Min. Preis',
                maxPrice: 'Max. Preis',
                movement: 'Uhrwerk',
                allMovements: 'Alle',
                availability: 'Verfügbarkeit',
                allAvailability: 'Alle',
                sort: 'Sortieren nach',
                newest: 'Neueste',
                priceAsc: 'Preis aufsteigend',
                priceDesc: 'Preis absteigend',
                name: 'Name A–Z',
                apply: 'Anzeigen',
                reset: 'Zurücksetzen',
                results: 'Modelle',
            },
        }[locale] ?? null
    );
});

const form = reactive({
    q: props.filters.q ?? '',
    model: props.filters.model ?? '',
    price_min: props.filters.price_min ?? '',
    price_max: props.filters.price_max ?? '',
    movement: props.filters.movement ?? '',
    availability: props.filters.availability ?? '',
    sort: props.filters.sort ?? 'newest',
});

const requestParams = () => {
    return Object.fromEntries(
        Object.entries(form).filter(([, value]) => value !== '' && value !== null),
    );
};

const scrollToCollection = () => {
    requestAnimationFrame(() => {
        document.getElementById('collection')?.scrollIntoView({
            behavior: 'auto',
            block: 'start',
        });
    });
};

const apply = () => {
    router.get(page.props.localizedRoutes.watches, requestParams(), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: scrollToCollection,
    });
};

const reset = () => {
    router.get(
        page.props.localizedRoutes.watches,
        {},
        {
            preserveScroll: true,
            preserveState: false,
        },
    );
};
</script>

<template>
    <section
        aria-labelledby="catalog-filters-title"
        class="vvs-luxury-card mb-8 rounded-2xl border p-4 sm:p-5"
    >
        <div
            class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between"
        >
            <div>
                <h3
                    id="catalog-filters-title"
                    class="text-base font-semibold text-white"
                >
                    {{ copy.title }}
                </h3>
                <p class="mt-1 text-sm text-zinc-500">
                    {{ total }} {{ copy.results }}
                </p>
            </div>

            <button
                type="button"
                class="min-h-11 self-start rounded-lg px-3 text-sm font-semibold text-zinc-400 underline decoration-white/20 underline-offset-4 transition hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300 sm:self-auto"
                @click="reset"
            >
                {{ copy.reset }}
            </button>
        </div>

        <form
            class="grid gap-3 md:grid-cols-2 xl:grid-cols-12"
            role="search"
            @submit.prevent="apply"
        >
            <label class="xl:col-span-4">
                <span class="mb-1.5 block text-xs font-semibold text-zinc-300">
                    {{ copy.search }}
                </span>
                <input
                    v-model.trim="form.q"
                    type="search"
                    name="q"
                    autocomplete="off"
                    :placeholder="copy.searchPlaceholder"
                    class="min-h-11 w-full rounded-xl border border-white/10 bg-black/55 px-4 text-base text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-300/60 focus:ring-2 focus:ring-amber-300/10"
                />
            </label>

            <label class="xl:col-span-3">
                <span class="mb-1.5 block text-xs font-semibold text-zinc-300">
                    {{ copy.model }}
                </span>
                <select
                    v-model="form.model"
                    name="model"
                    class="min-h-11 w-full rounded-xl border border-white/10 bg-black/55 px-3 text-base text-white outline-none transition focus:border-amber-300/60 focus:ring-2 focus:ring-amber-300/10"
                >
                    <option value="">{{ copy.allModels }}</option>
                    <option
                        v-for="model in options.models"
                        :key="model.value"
                        :value="model.value"
                    >
                        {{ model.label }} · {{ model.reference }}
                    </option>
                </select>
            </label>

            <label class="xl:col-span-2">
                <span class="mb-1.5 block text-xs font-semibold text-zinc-300">
                    {{ copy.movement }}
                </span>
                <select
                    v-model="form.movement"
                    name="movement"
                    class="min-h-11 w-full rounded-xl border border-white/10 bg-black/55 px-3 text-base text-white outline-none transition focus:border-amber-300/60 focus:ring-2 focus:ring-amber-300/10"
                >
                    <option value="">{{ copy.allMovements }}</option>
                    <option
                        v-for="movement in options.movements"
                        :key="movement.value"
                        :value="movement.value"
                    >
                        {{ movement.label }}
                    </option>
                </select>
            </label>

            <label class="xl:col-span-3">
                <span class="mb-1.5 block text-xs font-semibold text-zinc-300">
                    {{ copy.availability }}
                </span>
                <select
                    v-model="form.availability"
                    name="availability"
                    class="min-h-11 w-full rounded-xl border border-white/10 bg-black/55 px-3 text-base text-white outline-none transition focus:border-amber-300/60 focus:ring-2 focus:ring-amber-300/10"
                >
                    <option value="">{{ copy.allAvailability }}</option>
                    <option
                        v-for="availability in options.availability"
                        :key="availability"
                        :value="availability"
                    >
                        {{ availability }}
                    </option>
                </select>
            </label>

            <label class="xl:col-span-2">
                <span class="mb-1.5 block text-xs font-semibold text-zinc-300">
                    {{ copy.minPrice }}
                </span>
                <input
                    v-model="form.price_min"
                    type="number"
                    inputmode="numeric"
                    min="0"
                    step="10"
                    name="price_min"
                    class="min-h-11 w-full rounded-xl border border-white/10 bg-black/55 px-4 text-base text-white outline-none transition focus:border-amber-300/60 focus:ring-2 focus:ring-amber-300/10"
                />
            </label>

            <label class="xl:col-span-2">
                <span class="mb-1.5 block text-xs font-semibold text-zinc-300">
                    {{ copy.maxPrice }}
                </span>
                <input
                    v-model="form.price_max"
                    type="number"
                    inputmode="numeric"
                    min="0"
                    step="10"
                    name="price_max"
                    class="min-h-11 w-full rounded-xl border border-white/10 bg-black/55 px-4 text-base text-white outline-none transition focus:border-amber-300/60 focus:ring-2 focus:ring-amber-300/10"
                />
            </label>

            <label class="xl:col-span-4">
                <span class="mb-1.5 block text-xs font-semibold text-zinc-300">
                    {{ copy.sort }}
                </span>
                <select
                    v-model="form.sort"
                    name="sort"
                    class="min-h-11 w-full rounded-xl border border-white/10 bg-black/55 px-3 text-base text-white outline-none transition focus:border-amber-300/60 focus:ring-2 focus:ring-amber-300/10"
                >
                    <option value="newest">{{ copy.newest }}</option>
                    <option value="price_asc">{{ copy.priceAsc }}</option>
                    <option value="price_desc">{{ copy.priceDesc }}</option>
                    <option value="name">{{ copy.name }}</option>
                </select>
            </label>

            <div class="flex items-end xl:col-span-4">
                <button
                    type="submit"
                    class="vvs-button-primary min-h-11 w-full rounded-xl px-5 py-3 text-sm font-bold tracking-[0.08em] uppercase focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
                >
                    {{ copy.apply }}
                </button>
            </div>
        </form>
    </section>
</template>