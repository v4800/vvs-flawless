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
        class="watch-card vvs-catalog-card"
    >
        <a
            href="#contact"
            class="vvs-catalog-card__image"
            :aria-label="`${translations.collection.source_request} — ${model.name}`"
        >
            <WatchCardVisual
                :src="model.cardImage || model.image"
                :alt="imageAlt(model)"
            />
            <span class="vvs-catalog-source-label">{{
                translations.collection.source_eyebrow
            }}</span>
        </a>
        <div class="vvs-catalog-card__body">
            <p class="vvs-editorial-label">Moissanite VVS</p>
            <h3>
                <a href="#contact">{{ model.name }}</a>
            </h3>
            <p
                v-if="startingPrice(model)"
                class="vvs-catalog-card__description"
            >
                {{ detailsLabel }}
            </p>
            <div class="vvs-catalog-card__price-row">
                <p v-if="startingPrice(model)">
                    <span class="vvs-catalog-card__price-label">{{
                        startingAtLabel
                    }}</span>
                    <span class="vvs-catalog-card__price">{{
                        formatPrice(startingPrice(model))
                    }}</span>
                </p>
                <p v-else class="vvs-catalog-card__description">
                    {{ translations.collection.source_details }}
                </p>
                <a
                    href="#contact"
                    class="vvs-catalog-card__action"
                    :aria-label="`${translations.collection.source_request} — ${model.name}`"
                    ><span aria-hidden="true">↗</span></a
                >
            </div>
            <p class="vvs-catalog-card__delivery">
                <span>{{ translations.collection.delivery }}</span
                ><span>{{ translations.collection.delay }}</span>
            </p>
        </div>
    </article>
</template>
