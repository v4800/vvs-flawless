<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    filters: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const copy = computed(() => {
    return (
        {
            fr_BE: {
                search: 'Rechercher une montre',
                placeholder: 'Rechercher un modèle, une référence…',
                searchAction: 'Rechercher',
                sort: 'Trier',
                newest: 'Nouveautés',
                priceAsc: 'Prix croissant',
                priceDesc: 'Prix décroissant',
                name: 'Nom A–Z',
                reset: 'Effacer la recherche et le tri',
            },
            nl_BE: {
                search: 'Horloge zoeken',
                placeholder: 'Zoek een model of referentie…',
                searchAction: 'Zoeken',
                sort: 'Sorteren',
                newest: 'Nieuwste',
                priceAsc: 'Prijs oplopend',
                priceDesc: 'Prijs aflopend',
                name: 'Naam A–Z',
                reset: 'Zoeken en sorteren wissen',
            },
            en_BE: {
                search: 'Search watches',
                placeholder: 'Search a model or reference…',
                searchAction: 'Search',
                sort: 'Sort',
                newest: 'Newest',
                priceAsc: 'Price low to high',
                priceDesc: 'Price high to low',
                name: 'Name A–Z',
                reset: 'Clear search and sorting',
            },
            de_BE: {
                search: 'Uhr suchen',
                placeholder: 'Modell oder Referenz suchen…',
                searchAction: 'Suchen',
                sort: 'Sortieren',
                newest: 'Neueste',
                priceAsc: 'Preis aufsteigend',
                priceDesc: 'Preis absteigend',
                name: 'Name A–Z',
                reset: 'Suche und Sortierung löschen',
            },
        }[page.props.locale] ?? {
            search: 'Rechercher une montre',
            placeholder: 'Rechercher un modèle, une référence…',
            searchAction: 'Rechercher',
            sort: 'Trier',
            newest: 'Nouveautés',
            priceAsc: 'Prix croissant',
            priceDesc: 'Prix décroissant',
            name: 'Nom A–Z',
            reset: 'Effacer la recherche et le tri',
        }
    );
});

const form = reactive({
    q: props.filters.q ?? '',
    sort: props.filters.sort ?? 'newest',
});

const hasActiveSearch = computed(() => {
    return form.q.trim() !== '' || form.sort !== 'newest';
});

const requestParams = () => {
    const params = {};

    if (form.q.trim() !== '') {
        params.q = form.q.trim();
    }

    if (form.sort !== 'newest') {
        params.sort = form.sort;
    }

    return params;
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
    form.q = '';
    form.sort = 'newest';

    router.get(
        page.props.localizedRoutes.watches,
        {},
        {
            preserveScroll: true,
            preserveState: false,
            onSuccess: scrollToCollection,
        },
    );
};
</script>

<template>
    <section
        class="sticky top-0 z-40 relative z-30 border-b border-white/10 bg-[#070707]"
        :aria-label="copy.search"
    >
        <div
            class="mx-auto flex max-w-[1500px] flex-col gap-3 px-5 py-4 sm:px-6 md:flex-row md:items-center lg:px-10"
        >
            <form
                role="search"
                class="flex min-w-0 flex-1"
                @submit.prevent="apply"
            >
                <div class="relative w-full">
                    <label for="collection-search" class="sr-only">
                        {{ copy.search }}
                    </label>

                    <input
                        id="collection-search"
                        v-model="form.q"
                        type="search"
                        name="q"
                        autocomplete="off"
                        :placeholder="copy.placeholder"
                        class="h-12 w-full rounded-xl border border-white/10 bg-[#0b0b0b] px-4 pr-14 text-base text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-300/50 focus:ring-2 focus:ring-amber-300/10"
                    />

                    <button
                        type="submit"
                        :aria-label="copy.searchAction"
                        class="absolute top-1/2 right-2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-white/5 hover:text-amber-200 focus-visible:outline-2 focus-visible:outline-amber-300"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="11" cy="11" r="6.5" />
                            <path d="m16 16 4 4" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="flex min-w-0 items-center gap-2 md:shrink-0">
                <div
                    class="flex h-12 min-w-0 flex-1 items-center rounded-xl border border-white/10 bg-[#0b0b0b] px-3 md:flex-none"
                >
                    <label
                        for="collection-sort"
                        class="mr-2 text-xs font-bold tracking-[0.08em] text-zinc-500 uppercase"
                    >
                        {{ copy.sort }}
                    </label>

                    <select
                        id="collection-sort"
                        v-model="form.sort"
                        name="sort"
                        class="min-w-0 flex-1 bg-transparent text-sm font-semibold text-zinc-200 outline-none md:min-w-36"
                        @change="apply"
                    >
                        <option value="newest">{{ copy.newest }}</option>
                        <option value="price_asc">{{ copy.priceAsc }}</option>
                        <option value="price_desc">{{ copy.priceDesc }}</option>
                        <option value="name">{{ copy.name }}</option>
                    </select>
                </div>

                <button
                    v-if="hasActiveSearch"
                    type="button"
                    :aria-label="copy.reset"
                    class="flex h-12 w-12 items-center justify-center rounded-xl border border-white/10 bg-[#0b0b0b] text-xl text-zinc-500 transition hover:border-white/20 hover:text-white focus-visible:outline-2 focus-visible:outline-amber-300"
                    @click="reset"
                >
                    ×
                </button>
            </div>
        </div>
    </section>
</template>
