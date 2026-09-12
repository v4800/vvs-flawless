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

const displayModels = computed(() =>
    props.models.filter((model) => !model.watchUrl),
);

const formatPrice = (price) => `${Number(price).toFixed(0)} €`;

const startingPrice = (model) =>
    featuredStartingPrices[model.reference] ?? null;

const startingAtLabel = computed(() => {
    return (
        {
            fr_BE: 'À partir de',
            nl_BE: 'Vanaf',
            en_BE: 'From',
        }[page.props.locale] ?? 'À partir de'
    );
});

const detailsLabel = computed(() => {
    return (
        {
            fr_BE: 'Options à confirmer ensemble.',
            nl_BE: 'Opties in overleg.',
            en_BE: 'Options confirmed with you.',
        }[page.props.locale] ?? 'Options à confirmer ensemble.'
    );
});

const imageAlt = (model) => {
    return (
        {
            fr_BE: `${model.name}, montre sertie de moissanite VVS couleur D`,
            nl_BE: `${model.name}, horloge bezet met kleur D VVS-moissanite`,
            en_BE: `${model.name}, watch set with colour D VVS moissanite`,
        }[page.props.locale] ??
        `${model.name}, montre sertie de moissanite VVS couleur D`
    );
};
</script>

<template>
    <article
        v-for="model in displayModels"
        :key="model.reference"
        class="watch-card reveal-on-scroll vvs-luxury-card vvs-luxury-card--interactive group relative flex min-w-0 flex-col overflow-hidden rounded-2xl border"
    >
        <div
            class="absolute top-0 left-1/2 z-20 h-px w-0 -translate-x-1/2 bg-gradient-to-r from-transparent via-amber-300 to-transparent transition-all duration-500 group-hover:w-[85%]"
        ></div>

        <a
            href="#contact"
            class="relative block aspect-[4/3] w-full shrink-0 overflow-hidden bg-[radial-gradient(circle_at_50%_35%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_45%,#050505_78%)] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-amber-300"
            :aria-label="`${translations.collection.source_request} — ${model.name}`"
        >
            <div
                class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"
            ></div>

            <div
                class="pointer-events-none absolute top-0 -left-1/2 z-10 h-full w-1/3 -skew-x-12 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 transition-all duration-700 group-hover:left-[120%] group-hover:opacity-100"
            ></div>

            <WatchCardVisual
                :src="model.cardImage"
                :srcset="`${model.cardImage} 720w, ${model.image} 1448w`"
                sizes="(min-width: 1280px) 32vw, (min-width: 640px) 48vw, 100vw"
                :alt="imageAlt(model)"
            />

            <span
                class="absolute top-4 left-4 z-20 rounded-full border border-amber-300/25 bg-black/75 px-3 py-2 text-[8px] font-black tracking-[0.18em] text-amber-200 uppercase backdrop-blur-md"
            >
                {{ translations.collection.source_eyebrow }}
            </span>
        </a>

        <div class="relative flex flex-1 flex-col p-5">
            <h3
                class="vvs-display-title text-center text-2xl leading-tight text-white"
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
                class="vvs-choice-card vvs-choice-card--featured mt-4 rounded-xl border px-4 py-3 text-center"
            >
                <p
                    class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                >
                    {{ startingAtLabel }}
                </p>
                <p class="vvs-price mt-1 text-2xl font-black">
                    {{ formatPrice(startingPrice(model)) }}
                </p>
            </div>

            <p class="mt-3 text-center text-sm leading-5 text-zinc-400">
                {{ detailsLabel }}
            </p>

            <div class="mt-auto pt-4">
                <div
                    class="flex flex-wrap items-center justify-between gap-x-3 gap-y-1 border-t border-white/10 pt-3 text-xs"
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
                    <span>→</span>
                </a>
            </div>
        </div>
    </article>
</template>
