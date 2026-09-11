<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import StockBadge from '@/components/StockBadge.vue';

const props = defineProps({
    watch: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const translations = page.props.translations;
const landingCopy = page.props.landingCopy;
const localizedRoutes = page.props.localizedRoutes;

const href = computed(() => {
    return `${localizedRoutes.watches}/${props.watch.id}`;
});

const startingPrice = computed(() => {
    const prices = [
        props.watch.japanese_promo_price ?? props.watch.japanese_price,
        props.watch.swiss_promo_price ?? props.watch.swiss_price,
    ]
        .map((price) => Number(price))
        .filter((price) => Number.isFinite(price) && price > 0);

    return prices.length ? Math.min(...prices) : null;
});

const formattedPrice = computed(() => {
    if (!startingPrice.value) {
        return '—';
    }

    return `${Number(startingPrice.value).toFixed(0)} €`;
});
</script>

<template>
    <article
        class="watch-card reveal-on-scroll vvs-luxury-card vvs-luxury-card--interactive group relative flex flex-col overflow-hidden rounded-2xl border"
    >
        <div
            class="absolute top-0 left-1/2 z-20 h-px w-0 -translate-x-1/2 bg-gradient-to-r from-transparent via-amber-300 to-transparent transition-all duration-500 group-hover:w-[85%]"
        ></div>

        <Link
            :href="href"
            class="relative block aspect-[4/3] w-full shrink-0 overflow-hidden bg-[radial-gradient(circle_at_50%_35%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_45%,#050505_78%)] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-amber-300"
            :aria-label="`${translations.collection.view_watch} — ${watch.name}`"
        >
            <div
                class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"
            ></div>
            <div
                class="absolute top-1/2 left-1/2 h-60 w-60 -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.04] blur-[70px]"
            ></div>
            <div
                class="pointer-events-none absolute top-0 -left-1/2 z-10 h-full w-1/3 -skew-x-12 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 transition-all duration-700 group-hover:left-[120%] group-hover:opacity-100"
            ></div>

            <img
                v-if="watch.image"
                :src="watch.image"
                :alt="watch.name"
                loading="lazy"
                decoding="async"
                class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-500 motion-safe:group-hover:scale-[1.02]"
            />
            <div
                v-else
                class="flex h-full items-center justify-center text-zinc-600"
            >
                {{ translations.collection.image_soon }}
            </div>

            <div class="absolute top-4 left-4 z-20">
                <StockBadge
                    :quantity="watch.stock_quantity"
                    :availability="watch.availability"
                />
            </div>

            <span
                class="bling-sparkle absolute top-[21%] right-[13%] text-2xl text-white"
                >✦</span
            >
            <span
                class="bling-sparkle absolute bottom-[22%] left-[13%] text-lg text-amber-200"
                >✦</span
            >
        </Link>

        <div class="relative flex flex-1 flex-col p-5">
            <h3
                class="vvs-display-title text-center text-2xl leading-tight text-white"
            >
                {{ watch.name }}
            </h3>

            <p
                class="mt-2 text-center text-[11px] font-bold tracking-[0.18em] text-amber-300 uppercase"
            >
                Moissanite VVS
            </p>

            <Link
                :href="href"
                class="vvs-choice-card vvs-choice-card--featured mt-4 rounded-xl border px-4 py-3 text-center"
            >
                <p
                    class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                >
                    {{ landingCopy.starting_at }}
                </p>
                <p class="vvs-price mt-1 text-2xl font-black">
                    {{ formattedPrice }}
                </p>
            </Link>

            <p
                v-if="watch.short_description"
                class="mt-3 text-center text-sm leading-5 text-zinc-400"
            >
                {{ watch.short_description }}
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

                <Link
                    :href="href"
                    class="vvs-button-secondary mt-3 flex min-h-11 w-full items-center justify-center gap-3 rounded-xl px-4 py-3 text-xs font-bold tracking-[0.08em] uppercase"
                >
                    {{ translations.collection.view_watch }}
                    <span>→</span>
                </Link>
            </div>
        </div>
    </article>
</template>
