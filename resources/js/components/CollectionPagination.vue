<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const copy = computed(() => {
    return (
        {
            fr_BE: {
                previous: 'Page précédente',
                next: 'Page suivante',
                page: 'Page',
                of: 'sur',
                shown: 'résultats',
            },
            nl_BE: {
                previous: 'Vorige pagina',
                next: 'Volgende pagina',
                page: 'Pagina',
                of: 'van',
                shown: 'resultaten',
            },
            en_BE: {
                previous: 'Previous page',
                next: 'Next page',
                page: 'Page',
                of: 'of',
                shown: 'results',
            },
            de_BE: {
                previous: 'Vorherige Seite',
                next: 'Nächste Seite',
                page: 'Seite',
                of: 'von',
                shown: 'Ergebnisse',
            },
        }[page.props.locale] ?? null
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
        class="mt-10 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 sm:flex-row"
        aria-label="Pagination"
    >
        <Link
            v-if="pagination.previousUrl"
            :href="pagination.previousUrl"
            preserve-scroll
            @success="scrollToCollection"
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

        <Link
            v-if="pagination.nextUrl"
            :href="pagination.nextUrl"
            preserve-scroll
            @success="scrollToCollection"
            class="vvs-button-secondary flex min-h-11 min-w-36 items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
        >
            {{ copy.next }} →
        </Link>
        <span v-else class="hidden min-w-36 sm:block"></span>
    </nav>
</template>