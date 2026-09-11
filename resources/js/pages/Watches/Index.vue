<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted } from 'vue';
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

const props = defineProps({
    watches: {
        type: Array,
        default: () => [],
    },
    catalogModels: {
        type: Array,
        default: () => [],
    },
    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const translations = page.props.translations;
const landingCopy = page.props.landingCopy;

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

    animate('.brand-shine', {
        x: ['0%', '560%'],
        opacity: [0, 0.9, 0],
        duration: 1800,
        delay: 900,
        loop: true,
        loopDelay: 2600,
        ease: 'inOutQuad',
    });

    animate('.bling-sparkle', {
        opacity: [0.08, 1, 0.08],
        scale: [0.35, 1.55, 0.35],
        rotate: [0, 45, 90],
        duration: 1450,
        delay: stagger(230),
        loop: true,
        loopDelay: 350,
        ease: 'inOutQuad',
    });

    animate('.hero-orb', {
        opacity: [0.35, 0.75],
        scale: [0.92, 1.08],
        duration: 3800,
        loop: true,
        alternate: true,
        ease: 'inOutQuad',
    });

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

    <div class="min-h-screen bg-black text-white">
        <a
            href="#main-content"
            class="sr-only z-[100] rounded-lg bg-amber-300 px-4 py-3 font-bold text-black focus:not-sr-only focus:fixed focus:top-4 focus:left-4"
        >
            {{ translations.accessibility.skip_content }}
        </a>

        <CollectionSiteHeader :seo="seo" />

        <main id="main-content" tabindex="-1">
            <VvsNavigation
                current="collection"
                :show-back="false"
                class="!top-24"
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

                        <h2 class="vvs-display-title text-5xl sm:text-6xl">
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

                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        <SourcedWatches :models="catalogModels" />

                        <CollectionWatchCard
                            v-for="watch in props.watches"
                            :key="watch.id"
                            :watch="watch"
                        />
                    </div>
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
        </main>

        <CollectionFooter />
    </div>
</template>
