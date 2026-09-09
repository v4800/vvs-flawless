<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    watches: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const localizedRoutes = page.props.localizedRoutes;
const translations = computed(() => page.props.translations);

const startingPrice = (watch) => {
    const prices = [
        watch.japanese_promo_price ?? watch.japanese_price,
        watch.swiss_promo_price ?? watch.swiss_price,
    ]
        .map(Number)
        .filter((price) => Number.isFinite(price) && price > 0);

    if (prices.length === 0) {
        return null;
    }

    return Math.min(...prices);
};

const formatPrice = (price) => {
    return `${Number(price).toFixed(0)} €`;
};
</script>

<template>
    <section
        v-if="watches.length"
        class="border-t border-white/10 px-5 py-20 sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1400px]">
            <div class="mb-10 text-center">
                <p class="vvs-eyebrow">
                    {{ translations.related.eyebrow }}
                </p>

                <h2 class="vvs-display-title mt-4 text-4xl sm:text-5xl">
                    {{ translations.related.title_before }}
                    <span class="vvs-gradient-text">
                        {{ translations.related.title_highlight }}
                    </span>
                </h2>

                <p
                    class="mx-auto mt-4 max-w-xl text-sm leading-6 text-zinc-500"
                >
                    {{ translations.related.description }}
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="watch in watches"
                    :key="watch.id"
                    :href="`${localizedRoutes.watches}/${watch.id}`"
                    class="vvs-luxury-card vvs-luxury-card--interactive group overflow-hidden rounded-2xl border"
                >
                    <div class="h-[280px] overflow-hidden bg-zinc-400">
                        <img
                            v-if="watch.image"
                            :src="watch.image"
                            :alt="watch.name"
                            loading="lazy"
                            decoding="async"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                        />

                        <div
                            v-else
                            class="flex h-full items-center justify-center text-sm text-zinc-700"
                        >
                            {{ translations.related.image_soon }}
                        </div>
                    </div>

                    <div class="p-5">
                        <p
                            class="text-[9px] font-black tracking-[0.2em] text-amber-300 uppercase"
                        >
                            Moissanite VVS • D
                        </p>

                        <h3
                            class="vvs-display-title mt-3 min-h-[48px] text-2xl leading-6"
                        >
                            {{ watch.name }}
                        </h3>

                        <div
                            class="mt-5 flex items-end justify-between gap-4 border-t border-white/10 pt-4"
                        >
                            <div>
                                <p
                                    class="text-[9px] tracking-[0.15em] text-zinc-600 uppercase"
                                >
                                    {{ translations.related.from }}
                                </p>

                                <p
                                    v-if="startingPrice(watch)"
                                    class="vvs-price mt-1 text-xl font-black"
                                >
                                    {{ formatPrice(startingPrice(watch)) }}
                                </p>
                            </div>

                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-amber-300 transition group-hover:border-amber-300/40 group-hover:bg-amber-300 group-hover:text-black"
                            >
                                →
                            </span>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="mt-8 text-center">
                <Link
                    :href="`${localizedRoutes.watches}#collection`"
                    class="vvs-button-secondary inline-flex rounded-xl px-6 py-3.5 text-xs font-bold tracking-[0.1em] uppercase"
                >
                    {{ translations.related.all }}
                </Link>
            </div>
        </div>
    </section>
</template>
