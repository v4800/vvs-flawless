<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref } from 'vue';

defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const loading = ref(false);
const showProgress = ref(false);
const requestError = ref(false);
let progressTimer;

const startLoading = () => {
    loading.value = true;
    requestError.value = false;
    window.clearTimeout(progressTimer);
    progressTimer = window.setTimeout(() => {
        showProgress.value = true;
    }, 180);
};

const finishLoading = () => {
    window.clearTimeout(progressTimer);
    progressTimer = undefined;
    loading.value = false;
    showProgress.value = false;
};

onBeforeUnmount(finishLoading);

const copy = computed(() => {
    return (
        {
            fr_BE: {
                previous: 'Page précédente',
                next: 'Page suivante',
                page: 'Page',
                of: 'sur',
                shown: 'résultats',
                navigation: 'Pages de la collection',
                loading: 'Chargement de la page en cours…',
                error: 'La page n’a pas pu être chargée. Réessayez.',
            },
            nl_BE: {
                previous: 'Vorige pagina',
                next: 'Volgende pagina',
                page: 'Pagina',
                of: 'van',
                shown: 'resultaten',
                navigation: 'Collectiepagina’s',
                loading: 'Pagina wordt geladen…',
                error: 'De pagina kon niet worden geladen. Probeer opnieuw.',
            },
            en_BE: {
                previous: 'Previous page',
                next: 'Next page',
                page: 'Page',
                of: 'of',
                shown: 'results',
                navigation: 'Collection pages',
                loading: 'Loading page…',
                error: 'The page could not be loaded. Please try again.',
            },
            de_BE: {
                previous: 'Vorherige Seite',
                next: 'Nächste Seite',
                page: 'Seite',
                of: 'von',
                shown: 'Ergebnisse',
                navigation: 'Kollektionsseiten',
                loading: 'Seite wird geladen…',
                error: 'Die Seite konnte nicht geladen werden. Bitte erneut versuchen.',
            },
        }[page.props.locale] ?? {
            previous: 'Previous page',
            next: 'Next page',
            page: 'Page',
            of: 'of',
            shown: 'results',
            navigation: 'Collection pages',
            loading: 'Loading page…',
            error: 'The page could not be loaded. Please try again.',
        }
    );
});

const scrollToCollection = () => {
    requestAnimationFrame(() => {
        document.getElementById('collection')?.scrollIntoView({
            behavior: 'auto',
            block: 'start',
        });
    });
};
</script>

<template>
    <nav
        v-if="pagination.lastPage > 1"
        class="relative mt-10 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 sm:flex-row"
        :aria-label="copy.navigation"
        :aria-busy="loading"
    >
        <div
            v-if="showProgress"
            class="vvs-request-progress"
            aria-hidden="true"
        ></div>
        <Link
            v-if="pagination.previousUrl"
            :href="pagination.previousUrl"
            preserve-scroll
            @start="startLoading"
            @success="scrollToCollection"
            @error="requestError = true"
            @finish="finishLoading"
            class="vvs-button-secondary flex min-h-11 min-w-36 items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
        >
            ← {{ copy.previous }}
        </Link>
        <span v-else class="hidden min-w-36 sm:block"></span>

        <p class="text-center text-sm text-zinc-400">
            <span class="font-semibold text-zinc-200">
                {{ copy.page }} {{ pagination.currentPage }} {{ copy.of }}
                {{ pagination.lastPage }}
            </span>
            <span class="mt-1 block text-xs text-zinc-600">
                {{ pagination.from }}–{{ pagination.to }} /
                {{ pagination.total }} {{ copy.shown }}
            </span>
        </p>

        <span v-if="showProgress" role="status" class="sr-only">{{
            copy.loading
        }}</span>
        <span
            v-if="requestError && !loading"
            role="alert"
            class="w-full text-center text-sm text-red-200 sm:order-last"
        >
            {{ copy.error }}
        </span>

        <Link
            v-if="pagination.nextUrl"
            :href="pagination.nextUrl"
            preserve-scroll
            @start="startLoading"
            @success="scrollToCollection"
            @error="requestError = true"
            @finish="finishLoading"
            class="vvs-button-secondary flex min-h-11 min-w-36 items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
        >
            {{ copy.next }} →
        </Link>
        <span v-else class="hidden min-w-36 sm:block"></span>
    </nav>
</template>
