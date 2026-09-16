<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import WatchCardVisual from '@/components/WatchCardVisual.vue';

const props = defineProps({
    models: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);

const featuredStartingPrices = {
    'VVS-C002': 650,
    'VVS-C004': 850,
    'VVS-C008': 650,
};

const formatPrice = (price) => `${Number(price).toFixed(0)} €`;

const startingPrice = (model) =>
    featuredStartingPrices[model.reference] ?? null;

const startingAtLabel = computed(() => {
    return (
        {
            fr_BE: 'À partir de',
            nl_BE: 'Vanaf',
            en_BE: 'From',
            de_BE: 'Ab',
        }[page.props.locale] ?? 'À partir de'
    );
});

const detailsLabel = computed(() => {
    return (
        {
            fr_BE: 'Prix et options à confirmer avec vous.',
            nl_BE: 'Prijs en opties worden samen bevestigd.',
            en_BE: 'Price and options are confirmed with you.',
            de_BE: 'Preis und Optionen werden gemeinsam bestätigt.',
        }[page.props.locale] ?? 'Options à confirmer ensemble.'
    );
});

const imageAlt = (model) => {
    return (
        {
            fr_BE: `${model.name}, montre sertie de moissanite VVS couleur D`,
            nl_BE: `${model.name}, horloge bezet met kleur D VVS-moissanite`,
            en_BE: `${model.name}, watch set with colour D VVS moissanite`,
            de_BE: `${model.name}, Uhr mit VVS-Moissanit in Farbe D`,
        }[page.props.locale] ??
        `${model.name}, montre sertie de moissanite VVS couleur D`
    );
};
</script>

<template>
    <article
        v-for="model in models"
        :key="model.reference"
        class="watch-card reveal-on-scroll group relative flex min-w-0 flex-col self-stretch overflow-hidden rounded-2xl border border-white/10 bg-[#090909] transition duration-300 hover:border-amber-300/25"
    >
        <a
            href="#contact"
            class="relative block w-full shrink-0 overflow-hidden bg-[#070707] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-amber-300"
            :aria-label="`${translations.collection.source_request} — ${model.name}`"
        >
            <WatchCardVisual
                :src="model.cardImage || model.image"
                :alt="imageAlt(model)"
            />

            <span
                class="absolute top-4 left-4 z-20 rounded-full border border-amber-300/20 bg-black/80 px-3 py-2 text-[8px] font-black tracking-[0.18em] text-amber-200 uppercase"
            >
                {{ translations.collection.source_eyebrow }}
            </span>
        </a>

        <div class="flex flex-1 flex-col p-5">
            <h3
                class="vvs-display-title min-h-[3.75rem] text-center text-2xl leading-tight text-white"
            >
                {{ model.name }}
            </h3>

            <p
                class="mt-2 text-center text-[11px] font-bold tracking-[0.18em] text-amber-300 uppercase"
            >
                Moissanite VVS
            </p>

            <div
                v-if="startingPrice(model)"
                class="mt-4 rounded-xl border border-white/10 bg-black/35 px-4 py-3 text-center"
            >
                <p
                    class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                >
                    {{ startingAtLabel }}
                </p>

                <p class="mt-1 text-2xl font-black text-amber-200">
                    {{ formatPrice(startingPrice(model)) }}
                </p>
            </div>

            <p class="mt-3 text-center text-sm leading-5 text-zinc-400">
                {{ detailsLabel }}
            </p>

            <div class="mt-auto pt-4">
                <div
                    class="flex min-w-0 flex-col items-start gap-1 border-t border-white/10 pt-3 text-xs min-[380px]:flex-row min-[380px]:items-center min-[380px]:justify-between"
                >
                    <span class="text-zinc-600">
                        {{ translations.collection.delivery }}
                    </span>

                    <span class="font-semibold text-zinc-300">
                        {{ translations.collection.delay }}
                    </span>
                </div>

                <a
                    href="#contact"
                    class="vvs-button-secondary mt-3 flex min-h-11 w-full items-center justify-center gap-3 rounded-xl px-4 py-3 text-xs font-bold tracking-[0.08em] uppercase"
                >
                    {{ translations.collection.source_request }}
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </article>
</template>
