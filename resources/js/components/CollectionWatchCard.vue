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
        props.watch.japanese_price !== null &&
        props.watch.japanese_price !== undefined
            ? (props.watch.japanese_promo_price ?? props.watch.japanese_price)
            : null,
        props.watch.swiss_price !== null &&
        props.watch.swiss_price !== undefined
            ? (props.watch.swiss_promo_price ?? props.watch.swiss_price)
            : null,
    ]
        .map((price) => Number(price))
        .filter((price) => Number.isFinite(price) && price > 0);

    if (prices.length) return Math.min(...prices);

    const singlePrice = Number(props.watch.promo_price ?? props.watch.price);
    return Number.isFinite(singlePrice) && singlePrice > 0 ? singlePrice : null;
});

const formattedPrice = computed(() => {
    if (!startingPrice.value) {
        return '—';
    }

    return `${Number(startingPrice.value).toFixed(0)} €`;
});
</script>

<template>
    <article class="watch-card vvs-catalog-card">
        <Link
            :href="href"
            class="vvs-catalog-card__image"
            :aria-label="`${translations.collection.view_watch} — ${watch.name}`"
        >
            <WatchCardVisual
                v-if="displayImage"
                :src="displayImage"
                :alt="watch.name"
            />
            <div v-else class="vvs-catalog-card__placeholder">
                {{ translations.collection.image_soon }}
            </div>
            <StockBadge
                class="vvs-catalog-stock"
                :quantity="watch.stock_quantity"
                :availability="watch.availability"
            />
        </Link>
        <div class="vvs-catalog-card__body">
            <p class="vvs-editorial-label">Moissanite VVS</p>
            <h3>
                <Link :href="href">{{ watch.name }}</Link>
            </h3>
            <p
                v-if="watch.short_description"
                class="vvs-catalog-card__description"
            >
                {{ watch.short_description }}
            </p>
            <div class="vvs-catalog-card__price-row">
                <p>
                    <span class="vvs-catalog-card__price-label">{{
                        landingCopy.starting_at
                    }}</span
                    ><span class="vvs-catalog-card__price">{{
                        formattedPrice
                    }}</span>
                </p>
                <Link
                    :href="href"
                    class="vvs-catalog-card__action"
                    :aria-label="`${translations.collection.view_watch} — ${watch.name}`"
                    ><span aria-hidden="true">↗</span></Link
                >
            </div>
            <p class="vvs-catalog-card__delivery">
                <span>{{ translations.collection.delivery }}</span
                ><span>{{
                    watch.delivery_estimate ?? translations.collection.delay
                }}</span>
            </p>
        </div>
    </article>
</template>
