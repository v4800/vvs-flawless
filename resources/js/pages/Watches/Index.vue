<script setup>
import CustomerConfidence from '@/components/CustomerConfidence.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted } from 'vue';
import { animate, createTimeline, stagger } from 'animejs';

import VvsNavigation from '@/components/VvsNavigation.vue';
import OrderSteps from '@/components/OrderSteps.vue';
import AboutSection from '@/components/AboutSection.vue';
import ContactSection from '@/components/ContactSection.vue';
import FaqSection from '@/components/FaqSection.vue';
import PickupSection from '@/components/PickupSection.vue';
import SourcedWatches from '@/components/SourcedWatches.vue';
import SeoContentHub from '@/components/SeoContentHub.vue';
import CollectionSiteHeader from '@/components/CollectionSiteHeader.vue';
import CollectionHero from '@/components/CollectionHero.vue';
import CollectionWatchCard from '@/components/CollectionWatchCard.vue';
import CollectionValueSections from '@/components/CollectionValueSections.vue';
import CollectionFooter from '@/components/CollectionFooter.vue';
import CollectionFilters from '@/components/CollectionFilters.vue';
import CollectionPagination from '@/components/CollectionPagination.vue';

const props = defineProps({
    watches: {
        type: Array,
        default: () => [],
    },
    catalogModels: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const translations = page.props.translations;
const landingCopy = page.props.landingCopy;

const noResultsCopy = computed(() => {
    return (
        {
            fr_BE: {
                title: 'Aucune montre ne correspond à votre recherche',
                text: 'Essayez un autre nom, une autre référence ou effacez la recherche.',
            },
            nl_BE: {
                title: 'Geen horloge komt overeen met je zoekopdracht',
                text: 'Probeer een andere naam of referentie, of wis de zoekopdracht.',
            },
            en_BE: {
                title: 'No watch matches your search',
                text: 'Try another name or reference, or clear the search.',
            },
            de_BE: {
                title: 'Keine Uhr entspricht deiner Suche',
                text: 'Versuche einen anderen Namen oder eine andere Referenz oder lösche die Suche.',
            },
        }[page.props.locale] ?? {
            title: 'No watch matches your search',
            text: 'Try another name or reference, or clear the search.',
        }
    );
});

const normalizeSearchText = (value) => {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim();
};

const hiddenFiltersActive = computed(() => {
    return [
        'model',
        'price_min',
        'price_max',
        'movement',
        'availability',
    ].some((key) => {
        const value = props.filters[key];

        return value !== '' && value !== null && value !== undefined;
    });
});

const visibleSourcedModels = computed(() => {
    const needle = normalizeSearchText(props.filters.q);

    let models = props.catalogModels.filter((model) => !model.watchUrl);

    if (needle !== '') {
        models = models.filter((model) => {
            return normalizeSearchText(
                `${model.name} ${model.reference}`,
            ).includes(needle);
        });
    }

    const sort = props.filters.sort ?? 'newest';

    if (sort === 'name') {
        return [...models].sort((a, b) =>
            a.name.localeCompare(b.name, undefined, {
                sensitivity: 'base',
            }),
        );
    }

    if (sort === 'price_asc' || sort === 'price_desc') {
        return [];
    }

    return models;
});

const showSourcedModels = computed(() => {
    return (
        props.pagination.currentPage === 1 &&
        !hiddenFiltersActive.value &&
        visibleSourcedModels.value.length > 0
    );
});

const scrollToCollection = () => {
    const prefersReducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    ).matches;

    document.getElementById('collection')?.scrollIntoView({
        behavior: prefersReducedMotion ? 'auto' : 'smooth',
        block: 'start',
    });
};

let revealObserver = null;

onMounted(() => {
    const prefersReducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    ).matches;

    if (prefersReducedMotion) {
        document
            .querySelectorAll('.hero-animate, .hero-watch, .reveal-on-scroll')
            .forEach((element) => {
                element.style.opacity = '1';
                element.style.transform = 'none';
            });

        return;
    }

    const heroTimeline = createTimeline({
        defaults: {
            ease: 'outExpo',
        },
    });

    heroTimeline
        .add('.site-header', {
            opacity: [0, 1],
            y: [-18, 0],
            duration: 700,
        })
        .add(
            '.hero-animate',
            {
                opacity: [0, 1],
                y: [34, 0],
                duration: 900,
                delay: stagger(90),
            },
            '-=420',
        )
        .add(
            '.hero-watch',
            {
                opacity: [0, 1],
                x: [85, 0],
                scale: [0.9, 1],
                duration: 1450,
            },
            '-=900',
        );

    const revealElements = document.querySelectorAll('.reveal-on-scroll');

    revealElements.forEach((element) => {
        element.style.opacity = '0';
    });

    revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                const element = entry.target;
                const isCard = element.classList.contains('watch-card');

                animate(element, {
                    opacity: [0, 1],
                    y: [isCard ? 42 : 30, 0],
                    scale: [isCard ? 0.96 : 0.985, 1],
                    duration: isCard ? 850 : 950,
                    ease: 'outExpo',
                });

                revealObserver?.unobserve(element);
            });
        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px',
        },
    );

    revealElements.forEach((element) => {
        revealObserver.observe(element);
    });
});

onBeforeUnmount(() => {
    revealObserver?.disconnect();
});
</script>

<template>
    <Head :title="seo.title" />

    <div class="vvs-storefront min-h-screen text-white">
        <a
            href="#main-content"
            class="sr-only z-[100] rounded-lg bg-amber-300 px-4 py-3 font-bold text-black focus:not-sr-only focus:fixed focus:top-4 focus:left-4"
        >
            {{ translations.accessibility.skip_content }}
        </a>

        <CollectionSiteHeader :seo="seo" />

        <main id="main-content" tabindex="-1">
            <div class="collection-toolbar-shell pt-5 pb-5 sm:pt-6 sm:pb-6">
                <CollectionFilters :filters="props.filters" />
            </div>

            <VvsNavigation
                current="collection"
                :show-back="false"
                class="!static !mt-3 !mb-8 md:!sticky md:!top-24"
            />

            <CollectionHero @scroll-to-collection="scrollToCollection" />

            <section
                id="collection"
                class="relative scroll-mt-24 overflow-hidden px-5 py-20 sm:px-6 lg:px-10"
            >
                <div
                    aria-hidden="true"
                    class="absolute top-0 left-1/2 -z-10 h-[400px] w-[900px] -translate-x-1/2 rounded-full bg-amber-500/[0.035] blur-[120px]"
                ></div>

                <div class="mx-auto max-w-[1500px]">
                    <header class="reveal-on-scroll mb-12 text-center">
                        <div
                            class="mb-4 flex items-center justify-center gap-3"
                        >
                            <span
                                aria-hidden="true"
                                class="h-px w-12 bg-gradient-to-r from-transparent to-amber-400"
                            ></span>
                            <span
                                class="text-[11px] font-bold tracking-[0.4em] text-amber-300 uppercase"
                            >
                                {{ translations.collection.eyebrow }}
                            </span>
                            <span
                                aria-hidden="true"
                                class="h-px w-12 bg-gradient-to-l from-transparent to-amber-400"
                            ></span>
                        </div>

                        <h2 class="vvs-display-title min-w-0 max-w-full break-words text-4xl leading-[0.96] sm:text-5xl lg:text-6xl">
                            {{ translations.collection.title_before }}
                            <span class="vvs-gradient-text">
                                {{ translations.collection.title_highlight }}
                            </span>
                        </h2>

                        <p
                            class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-zinc-500 sm:text-base"
                        >
                            {{ landingCopy.collection_description }}
                        </p>
                    </header>

                    <div
                        v-if="showSourcedModels || props.watches.length"
                        class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3"
                    >
                        <SourcedWatches
                            v-if="showSourcedModels"
                            :models="visibleSourcedModels"
                        />

                        <CollectionWatchCard
                            v-for="watch in props.watches"
                            :key="watch.id"
                            :watch="watch"
                        />
                    </div>

                    <div
                        v-else
                        class="vvs-luxury-card rounded-2xl border px-6 py-14 text-center"
                        role="status"
                    >
                        <p class="vvs-display-title text-3xl text-white">
                            {{ noResultsCopy.title }}
                        </p>
                        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-zinc-400">
                            {{ noResultsCopy.text }}
                        </p>
                    </div>

                    <CollectionPagination :pagination="props.pagination" />
                </div>
            </section>

            <SeoContentHub />
            <OrderSteps />

            <CollectionValueSections
                @scroll-to-collection="scrollToCollection"
            />

            <AboutSection />
            <PickupSection />
            <FaqSection />
            <ContactSection />
            <CustomerConfidence />
        </main>

        <CollectionFooter />
    </div>
</template>
