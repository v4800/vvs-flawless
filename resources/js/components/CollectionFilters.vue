<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed, onBeforeUnmount, reactive, ref } from "vue";

const props = defineProps({
    filters: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        default: () => ({
            models: [],
            availability: [],
            movements: [],
        }),
    },
});
const emit = defineEmits(["visual-loading"]);

const page = usePage();
const filtersOpen = ref(false);
const loading = ref(false);
const showLoading = ref(false);
const requestError = ref(false);
let loadingTimer;

const startLoading = () => {
    loading.value = true;
    requestError.value = false;
    loadingTimer = window.setTimeout(() => {
        showLoading.value = true;
        emit("visual-loading", true);
    }, 180);
};

const finishLoading = () => {
    window.clearTimeout(loadingTimer);
    loadingTimer = undefined;
    showLoading.value = false;
    loading.value = false;
    emit("visual-loading", false);
};

onBeforeUnmount(finishLoading);

const copy = computed(() => {
    return (
        {
            fr_BE: {
                search: "Rechercher une montre",
                placeholder: "Rechercher un modèle, une référence ou un style…",
                searchAction: "Rechercher",
                sort: "Trier",
                newest: "Nouveautés",
                priceAsc: "Prix croissant",
                priceDesc: "Prix décroissant",
                name: "Nom A–Z",
                filters: "Filtres",
                filterPanel: "Filtres du catalogue",
                model: "Modèle",
                allModels: "Tous les modèles",
                priceMin: "Prix min.",
                priceMax: "Prix max.",
                movement: "Mouvement",
                allMovements: "Tous les mouvements",
                availability: "Disponibilité",
                allAvailability: "Toutes",
                apply: "Appliquer",
                reset: "Réinitialiser les filtres",
                close: "Fermer les filtres",
                loading: "Recherche des montres en cours…",
                error: "La recherche a échoué. Réessayez.",
                retry: "Réessayer",
            },
            nl_BE: {
                search: "Horloge zoeken",
                placeholder: "Zoek een model, referentie of stijl…",
                searchAction: "Zoeken",
                sort: "Sorteren",
                newest: "Nieuwste",
                priceAsc: "Prijs oplopend",
                priceDesc: "Prijs aflopend",
                name: "Naam A–Z",
                filters: "Filters",
                filterPanel: "Catalogusfilters",
                model: "Model",
                allModels: "Alle modellen",
                priceMin: "Min. prijs",
                priceMax: "Max. prijs",
                movement: "Uurwerk",
                allMovements: "Alle uurwerken",
                availability: "Beschikbaarheid",
                allAvailability: "Alle",
                apply: "Toepassen",
                reset: "Filters wissen",
                close: "Filters sluiten",
                loading: "Horloges zoeken…",
                error: "Zoeken is mislukt. Probeer het opnieuw.",
                retry: "Opnieuw proberen",
            },
            en_BE: {
                search: "Search watches",
                placeholder: "Search a model, reference or style…",
                searchAction: "Search",
                sort: "Sort",
                newest: "Newest",
                priceAsc: "Price low to high",
                priceDesc: "Price high to low",
                name: "Name A–Z",
                filters: "Filters",
                filterPanel: "Catalogue filters",
                model: "Model",
                allModels: "All models",
                priceMin: "Min. price",
                priceMax: "Max. price",
                movement: "Movement",
                allMovements: "All movements",
                availability: "Availability",
                allAvailability: "All",
                apply: "Apply",
                reset: "Reset filters",
                close: "Close filters",
                loading: "Searching for watches…",
                error: "Search failed. Please try again.",
                retry: "Try again",
            },
            de_BE: {
                search: "Uhr suchen",
                placeholder: "Modell, Referenz oder Stil suchen…",
                searchAction: "Suchen",
                sort: "Sortieren",
                newest: "Neueste",
                priceAsc: "Preis aufsteigend",
                priceDesc: "Preis absteigend",
                name: "Name A–Z",
                filters: "Filter",
                filterPanel: "Katalogfilter",
                model: "Modell",
                allModels: "Alle Modelle",
                priceMin: "Min. Preis",
                priceMax: "Max. Preis",
                movement: "Uhrwerk",
                allMovements: "Alle Uhrwerke",
                availability: "Verfügbarkeit",
                allAvailability: "Alle",
                apply: "Anwenden",
                reset: "Filter zurücksetzen",
                close: "Filter schließen",
                loading: "Uhren werden gesucht…",
                error: "Die Suche ist fehlgeschlagen. Bitte erneut versuchen.",
                retry: "Erneut versuchen",
            },
        }[page.props.locale] ?? {
            search: "Search watches",
            placeholder: "Search a model, reference or style…",
            searchAction: "Search",
            sort: "Sort",
            newest: "Newest",
            priceAsc: "Price low to high",
            priceDesc: "Price high to low",
            name: "Name A–Z",
            filters: "Filters",
            filterPanel: "Catalogue filters",
            model: "Model",
            allModels: "All models",
            priceMin: "Min. price",
            priceMax: "Max. price",
            movement: "Movement",
            allMovements: "All movements",
            availability: "Availability",
            allAvailability: "All",
            apply: "Apply",
            reset: "Reset filters",
            close: "Close filters",
            loading: "Searching for watches…",
            error: "Search failed. Please try again.",
            retry: "Try again",
        }
    );
});

const form = reactive({
    q: props.filters.q ?? "",
    model: props.filters.model ?? "",
    price_min: props.filters.price_min ?? "",
    price_max: props.filters.price_max ?? "",
    movement: props.filters.movement ?? "",
    availability: props.filters.availability ?? "",
    sort: props.filters.sort ?? "newest",
});

const filteredModels = computed(() => {
    return Array.isArray(props.options.models) ? props.options.models : [];
});
const hasActiveFilters = computed(() => {
    return (
        form.q.trim() !== "" ||
        form.model !== "" ||
        form.price_min !== "" ||
        form.price_max !== "" ||
        form.movement !== "" ||
        form.availability !== "" ||
        form.sort !== "newest"
    );
});

const secondaryFilterCount = computed(() => {
    return [
        form.model,
        form.price_min,
        form.price_max,
        form.movement,
        form.availability,
    ].filter((value) => value !== "" && value !== null && value !== undefined)
        .length;
});

const availabilityLabel = (value) => {
    const normalized = String(value ?? "")
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .toLowerCase()
        .trim();

    const labels = {
        fr_BE: {
            "sur commande": "Sur commande",
            precommande: "Précommande",
            disponible: "Disponible",
            "en stock": "En stock",
            indisponible: "Indisponible",
            rupture: "Rupture",
            epuise: "Épuisé",
        },
        nl_BE: {
            "sur commande": "Op bestelling",
            precommande: "Voorbestelling",
            disponible: "Beschikbaar",
            "en stock": "Op voorraad",
            indisponible: "Niet beschikbaar",
            rupture: "Uitverkocht",
            epuise: "Uitverkocht",
        },
        en_BE: {
            "sur commande": "Made to order",
            precommande: "Pre-order",
            disponible: "Available",
            "en stock": "In stock",
            indisponible: "Unavailable",
            rupture: "Out of stock",
            epuise: "Sold out",
        },
        de_BE: {
            "sur commande": "Auf Bestellung",
            precommande: "Vorbestellung",
            disponible: "Verfügbar",
            "en stock": "Auf Lager",
            indisponible: "Nicht verfügbar",
            rupture: "Nicht auf Lager",
            epuise: "Ausverkauft",
        },
    };

    return labels[page.props.locale]?.[normalized] ?? value;
};

const requestParams = () => {
    const params = {};

    if (form.q.trim() !== "") params.q = form.q.trim();
    if (form.model !== "") params.model = form.model;
    if (form.price_min !== "") params.price_min = form.price_min;
    if (form.price_max !== "") params.price_max = form.price_max;
    if (form.movement !== "") params.movement = form.movement;
    if (form.availability !== "") params.availability = form.availability;
    if (form.sort !== "newest") params.sort = form.sort;

    return params;
};

const scrollToCollection = () => {
    requestAnimationFrame(() => {
        document.getElementById("collection")?.scrollIntoView({
            behavior: "auto",
            block: "start",
        });
    });
};

const apply = ({ closePanel = false } = {}) => {
    if (loading.value) return;

    if (closePanel) {
        filtersOpen.value = false;
    }

    startLoading();
    router.get(page.props.localizedRoutes.watches, requestParams(), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: scrollToCollection,
        onError: () => {
            requestError.value = true;
        },
        onFinish: finishLoading,
    });
};

const reset = () => {
    if (loading.value) return;

    form.q = "";
    form.model = "";
    form.price_min = "";
    form.price_max = "";
    form.movement = "";
    form.availability = "";
    form.sort = "newest";
    filtersOpen.value = false;
    startLoading();

    router.get(
        page.props.localizedRoutes.watches,
        {},
        {
            preserveScroll: true,
            preserveState: false,
            onSuccess: scrollToCollection,
            onError: () => {
                requestError.value = true;
            },
            onFinish: finishLoading,
        },
    );
};
</script>

<template>
    <section
        class="relative z-30 border-b border-white/10 bg-[#070707]"
        :aria-label="copy.search"
        :aria-busy="loading"
        @keydown.esc="filtersOpen = false"
    >
        <div class="mx-auto max-w-[1500px] px-5 py-4 sm:px-6 lg:px-10">
            <div
                class="flex min-w-0 flex-col gap-3 md:flex-row md:items-center"
            >
                <form
                    role="search"
                    class="flex min-w-0 flex-1"
                    @submit.prevent="apply()"
                >
                    <div class="vvs-search-shell relative w-full">
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
                            class="vvs-search-input h-12 w-full rounded-[15px] px-5 pr-16 text-base text-white outline-none"
                        />

                        <button
                            type="submit"
                            :aria-label="copy.searchAction"
                            :disabled="loading"
                            class="vvs-search-submit absolute top-1/2 right-1.5 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-[11px] outline-none"
                        >
                            <span
                                v-if="showLoading"
                                class="vvs-loading-indicator"
                                aria-hidden="true"
                            ></span>
                            <svg
                                v-else
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
                            class="mr-2 text-xs font-bold tracking-[0.08em] text-zinc-400 uppercase"
                        >
                            {{ copy.sort }}
                        </label>

                        <select
                            id="collection-sort"
                            v-model="form.sort"
                            name="sort"
                            class="min-h-11 min-w-0 flex-1 bg-transparent text-sm font-semibold text-zinc-200 outline-none md:min-w-36"
                            @change="apply()"
                        >
                            <option value="newest">{{ copy.newest }}</option>
                            <option value="price_asc">
                                {{ copy.priceAsc }}
                            </option>
                            <option value="price_desc">
                                {{ copy.priceDesc }}
                            </option>
                            <option value="name">{{ copy.name }}</option>
                        </select>
                    </div>

                    <button
                        type="button"
                        class="relative flex h-12 min-h-11 shrink-0 items-center justify-center gap-2 rounded-xl border border-white/10 bg-[#0b0b0b] px-4 text-xs font-black tracking-[0.08em] text-zinc-300 uppercase transition hover:border-amber-300/30 hover:text-amber-200 focus-visible:outline-2 focus-visible:outline-amber-300"
                        :aria-expanded="filtersOpen"
                        aria-controls="collection-filter-panel"
                        @click="filtersOpen = !filtersOpen"
                    >
                        {{ copy.filters }}

                        <span
                            v-if="secondaryFilterCount"
                            class="flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-300 px-1.5 text-[10px] font-black text-black"
                        >
                            {{ secondaryFilterCount }}
                        </span>
                    </button>

                    <button
                        v-if="hasActiveFilters"
                        type="button"
                        :aria-label="copy.reset"
                        :disabled="loading"
                        class="flex h-12 min-h-11 w-12 min-w-11 items-center justify-center rounded-xl border border-white/10 bg-[#0b0b0b] text-xl text-zinc-500 transition hover:border-white/20 hover:text-white focus-visible:outline-2 focus-visible:outline-amber-300"
                        @click="reset"
                    >
                        ×
                    </button>
                </div>
            </div>

            <div v-if="loading" role="status" class="sr-only">
                {{ copy.loading }}
            </div>
            <div
                v-if="requestError && !loading"
                role="alert"
                class="mt-3 flex flex-wrap items-center gap-3 rounded-xl border border-red-300/20 bg-red-400/5 px-3 text-sm text-red-200"
            >
                <span>{{ copy.error }}</span>
                <button
                    type="button"
                    class="min-h-11 rounded-lg border border-red-200/25 px-3 font-semibold focus-visible:outline-2 focus-visible:outline-amber-300"
                    @click="apply()"
                >
                    {{ copy.retry }}
                </button>
            </div>

            <Transition
                enter-active-class="transition duration-200 ease-out motion-reduce:transition-none"
                enter-from-class="-translate-y-1 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in motion-reduce:transition-none"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="-translate-y-1 opacity-0"
            >
                <div
                    v-if="filtersOpen"
                    id="collection-filter-panel"
                    class="mt-3 rounded-2xl border border-white/10 bg-[#0a0a0a] p-4 shadow-2xl shadow-black/40 sm:p-5"
                >
                    <div class="mb-4 flex items-center justify-between gap-4">
                        <p
                            class="text-xs font-black tracking-[0.14em] text-zinc-300 uppercase"
                        >
                            {{ copy.filterPanel }}
                        </p>

                        <button
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-xl text-zinc-500 transition hover:bg-white/5 hover:text-white focus-visible:outline-2 focus-visible:outline-amber-300"
                            :aria-label="copy.close"
                            @click="filtersOpen = false"
                        >
                            ×
                        </button>
                    </div>

                    <div
                        class="grid min-w-0 gap-3 sm:grid-cols-2 xl:grid-cols-3"
                    >
                        <label class="min-w-0">
                            <span
                                class="mb-1.5 block text-[10px] font-bold tracking-[0.12em] text-zinc-500 uppercase"
                            >
                                {{ copy.model }}
                            </span>
                            <select
                                v-model="form.model"
                                class="h-12 w-full min-w-0 rounded-xl border border-white/10 bg-[#101010] px-3 text-sm text-zinc-200 outline-none focus:border-amber-300/50 focus:ring-2 focus:ring-amber-300/10"
                            >
                                <option value="">
                                    {{ copy.allModels }}
                                </option>
                                <option
                                    v-for="model in filteredModels"
                                    :key="model.value"
                                    :value="model.value"
                                >
                                    {{ model.label }} · {{ model.reference }}
                                </option>
                            </select>
                        </label>

                        <div class="grid min-w-0 grid-cols-2 gap-2">
                            <label class="min-w-0">
                                <span
                                    class="mb-1.5 block text-[10px] font-bold tracking-[0.12em] text-zinc-500 uppercase"
                                >
                                    {{ copy.priceMin }}
                                </span>
                                <input
                                    v-model="form.price_min"
                                    type="number"
                                    inputmode="numeric"
                                    min="0"
                                    step="1"
                                    class="h-12 w-full min-w-0 rounded-xl border border-white/10 bg-[#101010] px-3 text-sm text-zinc-200 outline-none focus:border-amber-300/50 focus:ring-2 focus:ring-amber-300/10"
                                />
                            </label>

                            <label class="min-w-0">
                                <span
                                    class="mb-1.5 block text-[10px] font-bold tracking-[0.12em] text-zinc-500 uppercase"
                                >
                                    {{ copy.priceMax }}
                                </span>
                                <input
                                    v-model="form.price_max"
                                    type="number"
                                    inputmode="numeric"
                                    min="0"
                                    step="1"
                                    class="h-12 w-full min-w-0 rounded-xl border border-white/10 bg-[#101010] px-3 text-sm text-zinc-200 outline-none focus:border-amber-300/50 focus:ring-2 focus:ring-amber-300/10"
                                />
                            </label>
                        </div>

                        <label class="min-w-0">
                            <span
                                class="mb-1.5 block text-[10px] font-bold tracking-[0.12em] text-zinc-500 uppercase"
                            >
                                {{ copy.movement }}
                            </span>
                            <select
                                v-model="form.movement"
                                class="h-12 w-full min-w-0 rounded-xl border border-white/10 bg-[#101010] px-3 text-sm text-zinc-200 outline-none focus:border-amber-300/50 focus:ring-2 focus:ring-amber-300/10"
                            >
                                <option value="">
                                    {{ copy.allMovements }}
                                </option>
                                <option
                                    v-for="movement in options.movements ?? []"
                                    :key="movement.value"
                                    :value="movement.value"
                                >
                                    {{ movement.label }}
                                </option>
                            </select>
                        </label>

                        <label class="min-w-0">
                            <span
                                class="mb-1.5 block text-[10px] font-bold tracking-[0.12em] text-zinc-500 uppercase"
                            >
                                {{ copy.availability }}
                            </span>
                            <select
                                v-model="form.availability"
                                class="h-12 w-full min-w-0 rounded-xl border border-white/10 bg-[#101010] px-3 text-sm text-zinc-200 outline-none focus:border-amber-300/50 focus:ring-2 focus:ring-amber-300/10"
                            >
                                <option value="">
                                    {{ copy.allAvailability }}
                                </option>
                                <option
                                    v-for="availability in options.availability ??
                                    []"
                                    :key="availability"
                                    :value="availability"
                                >
                                    {{ availabilityLabel(availability) }}
                                </option>
                            </select>
                        </label>

                        <div
                            class="flex min-w-0 items-end gap-2 sm:col-span-2 xl:col-span-1"
                        >
                            <button
                                type="button"
                                :disabled="loading"
                                class="h-12 flex-1 rounded-xl bg-amber-300 px-5 text-xs font-black tracking-[0.1em] text-black uppercase transition hover:bg-amber-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
                                @click="apply({ closePanel: true })"
                            >
                                {{ copy.apply }}
                            </button>

                            <button
                                v-if="hasActiveFilters"
                                type="button"
                                :disabled="loading"
                                class="h-12 rounded-xl border border-white/10 bg-black/30 px-4 text-xs font-bold text-zinc-400 transition hover:border-white/20 hover:text-white focus-visible:outline-2 focus-visible:outline-amber-300"
                                @click="reset"
                            >
                                {{ copy.reset }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
        <div
            v-if="showLoading"
            aria-hidden="true"
            class="vvs-request-progress"
        ></div>
    </section>
</template>
