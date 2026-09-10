<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    models: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);

const featuredStartingPrices = {
    'VVS-C002': 850,
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
            class="relative block h-[390px] overflow-hidden bg-[radial-gradient(circle_at_50%_35%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_45%,#050505_78%)] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-amber-300"
            :aria-label="`${translations.collection.source_request} — ${model.name}`"
        >
            <div
                class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"
            ></div>

            <div
                class="pointer-events-none absolute top-0 -left-1/2 z-10 h-full w-1/3 -skew-x-12 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 transition-all duration-700 group-hover:left-[120%] group-hover:opacity-100"
            ></div>

            <img
                :src="model.cardImage"
                :srcset="`${model.cardImage} 720w, ${model.image} 1448w`"
                sizes="(min-width: 1280px) 32vw, (min-width: 640px) 48vw, 100vw"
                :alt="model.name"
                width="1448"
                height="1086"
                loading="lazy"
                decoding="async"
                class="h-full w-full object-contain transition duration-700 group-hover:scale-[1.04]"
            />

            <span
                class="absolute top-4 left-4 z-20 rounded-full border border-amber-300/25 bg-black/75 px-3 py-2 text-[8px] font-black tracking-[0.18em] text-amber-200 uppercase backdrop-blur-md"
            >
                {{ translations.collection.source_eyebrow }}
            </span>
        </a>

        <div class="relative flex flex-1 flex-col p-6">
            <h3
                class="vvs-display-title min-h-[58px] text-center text-2xl leading-[1.05] text-white"
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
                class="vvs-choice-card vvs-choice-card--featured mt-6 rounded-xl border p-4 text-center"
            >
                <p
                    class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                >
                    {{ startingAtLabel }}
                </p>
                <p class="vvs-price mt-2 text-2xl font-black">
                    {{ formatPrice(startingPrice(model)) }}
                </p>
            </div>

            <p
                class="mt-5 line-clamp-2 text-center text-sm leading-6 text-zinc-500"
            >
                {{ translations.collection.source_details }}
            </p>

            <div
                class="mt-5 flex items-center justify-between border-t border-white/10 pt-4 text-xs"
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
                class="vvs-button-secondary mt-6 flex w-full items-center justify-center gap-3 rounded-xl px-4 py-4 text-xs font-bold tracking-[0.12em] uppercase"
            >
                {{ translations.collection.source_request }}
                <span>→</span>
            </a>
        </div>
    </article>
</template>
