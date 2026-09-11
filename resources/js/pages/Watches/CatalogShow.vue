<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import CollectionFooter from '@/components/CollectionFooter.vue';
import ContactSection from '@/components/ContactSection.vue';
import ProductGallery from '@/components/ProductGallery.vue';
import VvsNavigation from '@/components/VvsNavigation.vue';

const props = defineProps({
    model: {
        type: Object,
        required: true,
    },
    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);
const localizedRoutes = page.props.localizedRoutes;

const gallery = computed(() => {
    if (Array.isArray(props.model.gallery) && props.model.gallery.length) {
        return props.model.gallery;
    }

    return props.model.image ? [props.model.image] : [];
});

const activeImage = ref(gallery.value[0] ?? props.model.image);

watch(
    gallery,
    (images) => {
        activeImage.value = images[0] ?? props.model.image;
    },
    { deep: true },
);

const product = computed(() => ({
    name: `41 mm · ${props.model.name}`,
    availability: 'Sur commande',
    stock_quantity: null,
}));

const featuredStartingPrices = {
    'VVS-C002': 650,
    'VVS-C004': 850,
    'VVS-C008': 650,
};

const startingPrice = computed(
    () => featuredStartingPrices[props.model.reference] ?? null,
);

const startingAtLabel = computed(() => {
    return (
        {
            fr_BE: 'À partir de',
            nl_BE: 'Vanaf',
            en_BE: 'From',
        }[page.props.locale] ?? 'À partir de'
    );
});

const formatPrice = (price) => `${Number(price).toFixed(0)} €`;
</script>

<template>
    <Head :title="seo.title">
        <meta name="description" :content="seo.description" />
        <link rel="canonical" :href="seo.canonical" />
    </Head>

    <div class="min-h-screen bg-black text-white">
        <a
            href="#main-content"
            class="sr-only z-[100] rounded-lg bg-amber-300 px-4 py-3 font-bold text-black focus:not-sr-only focus:fixed focus:top-4 focus:left-4"
        >
            {{ translations.accessibility.skip_content }}
        </a>

        <VvsNavigation
            current="watch"
            :back-label="translations.vvs_navigation.collection"
            :watch-href="model.detailUrl"
        />

        <main id="main-content" tabindex="-1">
            <section
                id="model"
                class="relative scroll-mt-28 overflow-hidden px-5 pt-10 pb-20 sm:px-6 lg:px-10 lg:pt-14"
            >
                <div
                    aria-hidden="true"
                    class="pointer-events-none absolute top-20 -left-40 -z-10 h-[500px] w-[500px] rounded-full bg-amber-400/[0.05] blur-[150px]"
                ></div>

                <div
                    aria-hidden="true"
                    class="pointer-events-none absolute top-0 right-0 -z-10 h-[650px] w-[650px] rounded-full bg-white/[0.025] blur-[160px]"
                ></div>

                <div
                    class="mx-auto grid max-w-[1400px] gap-10 lg:grid-cols-[1.04fr_0.96fr] lg:gap-16"
                >
                    <ProductGallery
                        :watch="product"
                        :gallery="gallery"
                        :active-image="activeImage"
                        :translations="translations"
                        @select-image="activeImage = $event"
                    />

                    <div class="lg:pt-5">
                        <div class="flex items-center gap-3">
                            <span
                                aria-hidden="true"
                                class="h-px w-8 bg-amber-300"
                            ></span>
                            <p class="vvs-eyebrow">VVS FLAWLESS</p>
                        </div>

                        <h1
                            class="vvs-display-title mt-6 max-w-2xl text-5xl sm:text-6xl"
                        >
                            41 mm · {{ model.name }}
                        </h1>

                        <p
                            class="mt-4 text-xs font-bold tracking-[0.22em] text-zinc-400 uppercase"
                        >
                            Moissanite VVS
                            <span
                                aria-hidden="true"
                                class="mx-2 text-amber-400"
                            >
                                •
                            </span>
                            {{ translations.product.color }} D
                        </p>

                        <p
                            class="mt-7 max-w-2xl text-base leading-8 text-zinc-400"
                        >
                            {{ translations.collection.source_details }}
                        </p>

                        <div
                            aria-hidden="true"
                            class="my-9 h-px bg-gradient-to-r from-white/10 via-white/5 to-transparent"
                        ></div>

                        <div
                            v-if="startingPrice"
                            class="vvs-choice-card vvs-choice-card--featured rounded-2xl border p-6"
                        >
                            <p
                                class="text-[10px] font-black tracking-[0.22em] text-zinc-400 uppercase"
                            >
                                {{ startingAtLabel }}
                            </p>

                            <p class="vvs-price mt-2 text-4xl font-black">
                                {{ formatPrice(startingPrice) }}
                            </p>

                            <p class="mt-3 text-sm text-zinc-400">
                                {{ translations.collection.source_description }}
                            </p>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="vvs-choice-card rounded-2xl border p-5">
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-400 uppercase"
                                >
                                    {{ translations.product.estimated_availability }}
                                </p>

                                <p class="mt-2 font-bold text-zinc-200">
                                    {{ translations.product.estimated_delay }}
                                </p>
                            </div>

                            <div class="vvs-choice-card rounded-2xl border p-5">
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-400 uppercase"
                                >
                                    {{ translations.product.reception }}
                                </p>

                                <p class="mt-2 font-bold text-zinc-200">
                                    {{ translations.product.handover_or_delivery }}
                                </p>
                            </div>
                        </div>

                        <a
                            href="#contact"
                            class="vvs-button-primary mt-6 flex w-full items-center justify-between rounded-2xl px-6 py-5 font-bold tracking-[0.1em] uppercase focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                        >
                            <span>{{ translations.collection.source_request }}</span>
                            <span aria-hidden="true" class="text-xl">↓</span>
                        </a>
                    </div>
                </div>
            </section>

            <ContactSection :collection-href="localizedRoutes.watches" />
        </main>

        <CollectionFooter />
    </div>
</template>
