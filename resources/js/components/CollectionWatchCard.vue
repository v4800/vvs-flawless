<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import StockBadge from '@/components/StockBadge.vue';
import WatchCardVisual from '@/components/WatchCardVisual.vue';

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
    return `${localizedRoutes.watches}/${props.watch.slug}`;
});

const displayImage = computed(() => {
    return props.watch.card_image ?? props.watch.image ?? null;
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
        class="watch-card reveal-on-scroll group relative flex min-w-0 flex-col self-stretch overflow-hidden rounded-2xl border border-white/10 bg-[#090909] transition duration-300 hover:border-amber-300/25"
    >
        <Link
            :href="href"
            class="relative block w-full shrink-0 overflow-hidden bg-[#070707] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-amber-300"
            :aria-label="`${translations.collection.view_watch} — ${watch.name}`"
        >
            <WatchCardVisual
                v-if="displayImage"
                :src="displayImage"
                :alt="watch.name"
            />

            <div
                v-else
                class="flex aspect-[3/4] w-full items-center justify-center text-zinc-600"
            >
                {{ translations.collection.image_soon }}
            </div>

            <div class="absolute top-4 left-4 z-20">
                <StockBadge
                    :quantity="watch.stock_quantity"
                    :availability="watch.availability"
                />
            </div>
        </Link>

        <div class="flex flex-1 flex-col p-5">
            <h3
                class="vvs-display-title min-h-[3.75rem] text-center text-2xl leading-tight text-white"
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
                class="mt-4 rounded-xl border border-white/10 bg-black/35 px-4 py-3 text-center transition hover:border-amber-300/25 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
            >
                <p
                    class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                >
                    {{ landingCopy.starting_at }}
                </p>

                <p class="mt-1 text-2xl font-black text-amber-200">
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
                    class="flex min-w-0 flex-col items-start gap-1 border-t border-white/10 pt-3 text-xs min-[380px]:flex-row min-[380px]:items-center min-[380px]:justify-between"
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
                    class="vvs-button-secondary mt-3 flex min-h-11 w-full items-center justify-center gap-3 rounded-xl px-4 py-3 text-xs font-bold tracking-[0.08em] uppercase focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
                >
                    {{ translations.collection.view_watch }}
                    <span aria-hidden="true">→</span>
                </Link>
            </div>
        </div>
    </article>
</template>
