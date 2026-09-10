<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted } from 'vue';
import { animate, createTimeline, stagger } from 'animejs';

import VvsNavigation from '@/components/VvsNavigation.vue';
import StockBadge from '@/components/StockBadge.vue';
import OrderSteps from '@/components/OrderSteps.vue';
import AboutSection from '@/components/AboutSection.vue';
import ContactSection from '@/components/ContactSection.vue';
import FaqSection from '@/components/FaqSection.vue';
import PickupSection from '@/components/PickupSection.vue';
import SourcedWatches from '@/components/SourcedWatches.vue';

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
const guideLinks = page.props.guideLinks;
const localizedRoutes = page.props.localizedRoutes;

const languageLinks = computed(() => {
    const alternates = props.seo.alternates ?? [];

    return {
        fr: alternates.find((alternate) => alternate.hreflang === 'fr-BE')
            ?.href,
        nl: alternates.find((alternate) => alternate.hreflang === 'nl-BE')
            ?.href,
        en: alternates.find((alternate) => alternate.hreflang === 'en-BE')
            ?.href,
    };
});

const startingAtLabel = computed(() => {
    return (
        {
            fr_BE: 'À partir de',
            nl_BE: 'Vanaf',
            en_BE: 'From',
        }[page.props.locale] ?? 'À partir de'
    );
});

const formatPrice = (price) => {
    if (!price) {
        return '—';
    }

    return `${Number(price).toFixed(0)} €`;
};

const watchStartingPrice = (watch) => {
    const prices = [
        watch.japanese_promo_price ?? watch.japanese_price,
        watch.swiss_promo_price ?? watch.swiss_price,
    ]
        .map((price) => Number(price))
        .filter((price) => Number.isFinite(price) && price > 0);

    return prices.length ? Math.min(...prices) : null;
};

const scrollToCollection = () => {
    document.getElementById('collection')?.scrollIntoView({
        behavior: 'smooth',
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

        <header
            class="site-header sticky top-0 z-50 border-b border-white/10 bg-black/90 backdrop-blur-xl"
        >
            <div
                class="mx-auto flex h-20 max-w-[1500px] items-center justify-between px-5 md:px-8"
            >
                <Link
                    :href="localizedRoutes.watches"
                    class="group flex flex-col leading-none"
                >
                    <span
                        class="text-xl font-semibold tracking-[0.08em] text-white md:text-2xl"
                    >
                        VVS FLAWLESS
                    </span>
                    <span
                        class="mt-1 text-[9px] tracking-[0.42em] text-amber-300/80 uppercase"
                    >
                        Belgium
                    </span>
                </Link>

                <nav
                    class="hidden items-center gap-10 text-sm font-medium text-zinc-300 md:flex"
                    :aria-label="translations.navigation.main_label"
                >
                    <a href="#collection" class="vvs-nav-link">
                        {{ translations.navigation.watches }}
                    </a>
                    <a href="#concept" class="vvs-nav-link">
                        {{ translations.navigation.about }}
                    </a>
                    <a href="#services" class="vvs-nav-link">
                        {{ translations.navigation.delivery }}
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <nav
                        class="flex shrink-0 items-center rounded-full border border-white/10 p-1 text-[10px] font-bold tracking-wider"
                        :aria-label="translations.language.label"
                    >
                        <Link
                            v-if="languageLinks.fr"
                            :href="languageLinks.fr"
                            :class="[
                                'rounded-full px-2.5 py-1.5 transition',
                                page.props.locale === 'fr_BE'
                                    ? 'bg-amber-300 text-black'
                                    : 'text-zinc-500 hover:text-white',
                            ]"
                        >
                            FR
                        </Link>
                        <Link
                            v-if="languageLinks.nl"
                            :href="languageLinks.nl"
                            :class="[
                                'rounded-full px-2.5 py-1.5 transition',
                                page.props.locale === 'nl_BE'
                                    ? 'bg-amber-300 text-black'
                                    : 'text-zinc-500 hover:text-white',
                            ]"
                        >
                            NL
                        </Link>
                        <Link
                            v-if="languageLinks.en"
                            :href="languageLinks.en"
                            :class="[
                                'rounded-full px-2.5 py-1.5 transition',
                                page.props.locale === 'en_BE'
                                    ? 'bg-amber-300 text-black'
                                    : 'text-zinc-500 hover:text-white',
                            ]"
                        >
                            EN
                        </Link>
                    </nav>

                    <div
                        class="hidden items-center gap-2 lg:flex"
                        aria-label="Zones de remise en main propre"
                    >
                        <Link
                            v-if="languageLinks.fr"
                            :href="languageLinks.fr"
                            class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.025] px-2 py-1 transition hover:border-amber-300/30"
                            title="Belgique — français"
                        >
                            <div
                                class="flex h-4 w-6 overflow-hidden rounded-[2px] border border-white/10"
                            >
                                <span class="flex-1 bg-black"></span>
                                <span class="flex-1 bg-yellow-400"></span>
                                <span class="flex-1 bg-red-600"></span>
                            </div>
                            <span
                                class="text-[8px] font-bold tracking-wider text-zinc-500"
                                >BE</span
                            >
                        </Link>

                        <Link
                            v-if="languageLinks.fr"
                            :href="languageLinks.fr"
                            class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.025] px-2 py-1 transition hover:border-amber-300/30"
                            title="Nord de la France — français"
                        >
                            <div
                                class="flex h-4 w-6 overflow-hidden rounded-[2px] border border-white/10"
                            >
                                <span class="flex-1 bg-blue-700"></span>
                                <span class="flex-1 bg-white"></span>
                                <span class="flex-1 bg-red-600"></span>
                            </div>
                            <span
                                class="text-[8px] font-bold tracking-wider text-zinc-500"
                                >FR</span
                            >
                        </Link>

                        <Link
                            v-if="languageLinks.nl"
                            :href="languageLinks.nl"
                            class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.025] px-2 py-1 transition hover:border-amber-300/30"
                            title="Maastricht et Gulpen — Nederlands"
                        >
                            <div
                                class="flex h-4 w-6 flex-col overflow-hidden rounded-[2px] border border-white/10"
                            >
                                <span class="flex-1 bg-red-600"></span>
                                <span class="flex-1 bg-white"></span>
                                <span class="flex-1 bg-blue-700"></span>
                            </div>
                            <span
                                class="text-[8px] font-bold tracking-wider text-zinc-500"
                                >NL</span
                            >
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <main id="main-content" tabindex="-1">
            <VvsNavigation
                current="collection"
                :show-back="false"
                class="!top-24"
            />

            <section
                class="relative isolate overflow-hidden border-b border-amber-400/20"
            >
                <div class="absolute inset-0 -z-30 bg-black"></div>
                <div
                    class="hero-orb absolute top-10 -left-40 -z-20 h-[500px] w-[500px] rounded-full bg-amber-500/10 blur-[150px]"
                ></div>
                <div
                    class="hero-orb absolute top-0 right-0 -z-20 h-[650px] w-[650px] rounded-full bg-white/[0.04] blur-[160px]"
                ></div>

                <span
                    class="bling-sparkle absolute top-[19%] left-[5%] text-3xl text-amber-200"
                    >✦</span
                >
                <span
                    class="bling-sparkle absolute top-[29%] left-[35%] text-xl text-white"
                    >✦</span
                >
                <span
                    class="bling-sparkle absolute top-[14%] right-[10%] text-3xl text-white"
                    >✦</span
                >
                <span
                    class="bling-sparkle absolute right-[42%] bottom-[20%] text-xl text-amber-300"
                    >✦</span
                >

                <div class="absolute top-0 right-0 hidden h-full w-1.5 lg:flex">
                    <div class="h-full flex-1 bg-black"></div>
                    <div class="h-full flex-1 bg-yellow-400"></div>
                    <div class="h-full flex-1 bg-red-600"></div>
                </div>

                <div
                    class="mx-auto grid min-h-[690px] max-w-[1500px] items-center gap-12 px-6 py-16 lg:grid-cols-[0.95fr_1.05fr] lg:px-10 lg:py-20"
                >
                    <div class="relative z-10">
                        <div
                            class="hero-animate mb-7 flex flex-wrap items-center gap-3"
                        >
                            <span
                                class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                                >Moissanite VVS</span
                            >
                            <span class="text-amber-500">•</span>
                            <span
                                class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                                >Iced Out</span
                            >
                            <span class="text-amber-500">•</span>
                            <span
                                class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                                >Bustdown</span
                            >
                        </div>

                        <h1
                            class="hero-animate relative max-w-3xl overflow-hidden text-[clamp(3rem,17vw,4rem)] leading-[0.82] font-semibold tracking-[-0.055em] uppercase sm:text-[5.5rem] lg:text-[7rem]"
                        >
                            <span
                                class="vvs-gradient-text vvs-gradient-text--hero"
                                >VVS</span
                            >
                            <br />
                            <span
                                class="vvs-gradient-text vvs-gradient-text--hero"
                                >FLAWLESS</span
                            >
                            <span
                                class="absolute top-2 -right-2 hidden text-3xl text-amber-200 sm:block"
                                >✦</span
                            >
                            <span
                                class="brand-shine pointer-events-none absolute top-0 -left-[30%] h-full w-[18%] -skew-x-12 bg-gradient-to-r from-transparent via-white/70 to-transparent blur-sm"
                            ></span>
                        </h1>

                        <div
                            class="hero-animate mt-8 flex max-w-xl items-center gap-4"
                        >
                            <div
                                class="hidden h-px w-12 bg-gradient-to-r from-transparent to-amber-400 sm:block"
                            ></div>
                            <p
                                class="vvs-display-title text-3xl leading-tight text-amber-200 sm:text-4xl"
                            >
                                {{ translations.hero.tagline }}
                            </p>
                        </div>

                        <p
                            class="hero-animate vvs-body-copy mt-7 max-w-xl text-base text-zinc-300 sm:text-lg"
                        >
                            {{ translations.hero.description }}
                        </p>

                        <div
                            class="hero-animate mt-8 grid max-w-2xl grid-cols-2 gap-3 sm:grid-cols-4"
                        >
                            <div class="vvs-choice-card rounded-xl border p-3">
                                <div class="text-xl text-amber-300">◇</div>
                                <p
                                    class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                                >
                                    Moissanite
                                </p>
                                <p class="text-[10px] text-zinc-500">VVS</p>
                            </div>
                            <div class="vvs-choice-card rounded-xl border p-3">
                                <div class="text-lg text-amber-300">✦</div>
                                <p
                                    class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                                >
                                    Couleur D
                                </p>
                                <p class="text-[10px] text-zinc-500">
                                    Éclat net
                                </p>
                            </div>
                            <div class="vvs-choice-card rounded-xl border p-3">
                                <div class="text-lg text-amber-300">◷</div>
                                <p
                                    class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                                >
                                    5–6 jours
                                </p>
                                <p class="text-[10px] text-zinc-500">
                                    Sur réservation
                                </p>
                            </div>
                            <div class="vvs-choice-card rounded-xl border p-3">
                                <div class="text-lg text-amber-300">↗</div>
                                <p
                                    class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                                >
                                    BE • FR • NL
                                </p>
                                <p class="text-[10px] text-zinc-500">
                                    {{ translations.hero.handover }}
                                </p>
                            </div>
                        </div>

                        <div class="hero-animate mt-9 flex flex-wrap gap-4">
                            <button
                                type="button"
                                class="vvs-button-primary rounded-xl px-7 py-4 text-sm font-bold tracking-[0.1em] uppercase"
                                @click="scrollToCollection"
                            >
                                {{ translations.concept.cta }}
                            </button>
                            <a
                                href="#concept"
                                class="vvs-button-secondary rounded-xl px-7 py-4 text-sm font-bold tracking-[0.1em] uppercase"
                            >
                                {{ translations.hero.concept }}
                            </a>
                        </div>
                    </div>

                    <div
                        class="relative flex min-h-[470px] items-center justify-center lg:min-h-[620px]"
                    >
                        <div
                            class="absolute h-[76%] w-[76%] rounded-full bg-amber-300/[0.06] blur-[95px]"
                        ></div>

                        <div
                            class="hero-watch group relative z-10 w-full max-w-[760px] overflow-hidden rounded-[28px] border border-white/10 bg-black shadow-[0_35px_100px_rgba(0,0,0,0.7)]"
                        >
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img
                                    src="/images/vvs-watch-hero.webp"
                                    alt="Montre iced-out VVS FLAWLESS"
                                    width="1536"
                                    height="1024"
                                    fetchpriority="high"
                                    decoding="async"
                                    class="h-full w-full object-contain p-4 transition duration-1000 group-hover:scale-[1.025] sm:p-6"
                                />

                                <div
                                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"
                                ></div>
                                <div
                                    class="pointer-events-none absolute top-0 -left-1/3 h-full w-1/5 -skew-x-12 bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 transition-all duration-1000 group-hover:left-[115%] group-hover:opacity-100"
                                ></div>

                                <div
                                    class="absolute right-0 bottom-0 left-0 flex flex-col gap-3 p-5 sm:flex-row sm:items-end sm:justify-between sm:p-7"
                                >
                                    <div>
                                        <p
                                            class="text-[9px] font-black tracking-[0.24em] text-amber-300 uppercase"
                                        >
                                            Moissanite VVS • Couleur D
                                        </p>
                                        <p
                                            class="vvs-display-title mt-2 text-3xl leading-none text-white sm:text-4xl"
                                        >
                                            Pièce signature
                                        </p>
                                    </div>

                                    <div
                                        class="flex items-center gap-2 text-[9px] font-bold tracking-wider uppercase"
                                    >
                                        <span
                                            class="rounded-full border border-white/10 bg-black/55 px-3 py-2 text-zinc-300 backdrop-blur-md"
                                        >
                                            5–6 jours
                                        </span>
                                        <span
                                            class="rounded-full border border-amber-300/20 bg-black/55 px-3 py-2 text-amber-200 backdrop-blur-md"
                                        >
                                            BE • FR • NL
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                id="collection"
                class="relative scroll-mt-24 overflow-hidden px-5 py-20 sm:px-6 lg:px-10"
            >
                <div
                    class="absolute top-0 left-1/2 -z-10 h-[400px] w-[900px] -translate-x-1/2 rounded-full bg-amber-500/[0.035] blur-[120px]"
                ></div>

                <div class="mx-auto max-w-[1500px]">
                    <header class="reveal-on-scroll mb-12 text-center">
                        <div
                            class="mb-4 flex items-center justify-center gap-3"
                        >
                            <span
                                class="h-px w-12 bg-gradient-to-r from-transparent to-amber-400"
                            ></span>
                            <span
                                class="text-[11px] font-bold tracking-[0.4em] text-amber-300 uppercase"
                            >
                                {{ translations.collection.eyebrow }}
                            </span>
                            <span
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
                            {{ translations.collection.description }}
                        </p>
                    </header>

                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        <SourcedWatches :models="catalogModels" />

                        <article
                            v-for="watch in props.watches"
                            :key="watch.id"
                            class="watch-card reveal-on-scroll vvs-luxury-card vvs-luxury-card--interactive group relative flex flex-col overflow-hidden rounded-2xl border"
                        >
                            <div
                                class="absolute top-0 left-1/2 z-20 h-px w-0 -translate-x-1/2 bg-gradient-to-r from-transparent via-amber-300 to-transparent transition-all duration-500 group-hover:w-[85%]"
                            ></div>

                            <Link
                                :href="`${localizedRoutes.watches}/${watch.id}`"
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
                                    :href="`${localizedRoutes.watches}/${watch.id}`"
                                    class="vvs-choice-card vvs-choice-card--featured mt-4 rounded-xl border px-4 py-3 text-center"
                                >
                                    <p
                                        class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                                    >
                                        {{ startingAtLabel }}
                                    </p>
                                    <p
                                        class="vvs-price mt-1 text-2xl font-black"
                                    >
                                        {{
                                            formatPrice(
                                                watchStartingPrice(watch),
                                            )
                                        }}
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
                                            {{
                                                translations.collection.delivery
                                            }}
                                        </span>
                                        <span
                                            class="font-semibold text-zinc-300"
                                        >
                                            {{ translations.collection.delay }}
                                        </span>
                                    </div>

                                    <Link
                                        :href="`${localizedRoutes.watches}/${watch.id}`"
                                        class="vvs-button-secondary mt-3 flex min-h-11 w-full items-center justify-center gap-3 rounded-xl px-4 py-3 text-xs font-bold tracking-[0.08em] uppercase"
                                    >
                                        {{ translations.collection.view_watch }}
                                        <span>→</span>
                                    </Link>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <OrderSteps />

            <section
                id="concept"
                class="scroll-mt-24 px-5 pb-8 sm:px-6 lg:px-10"
            >
                <div
                    class="reveal-on-scroll vvs-luxury-card mx-auto max-w-[1500px] overflow-hidden rounded-2xl border"
                >
                    <div
                        class="grid items-center gap-8 p-8 md:grid-cols-[auto_1fr_auto] md:p-10"
                    >
                        <div
                            class="flex h-20 w-28 overflow-hidden rounded-lg border border-white/10 shadow-[0_0_30px_rgba(251,191,36,0.15)]"
                        >
                            <span class="flex-1 bg-black"></span>
                            <span class="flex-1 bg-yellow-400"></span>
                            <span class="flex-1 bg-red-600"></span>
                        </div>

                        <div>
                            <p
                                class="text-xs font-black tracking-[0.3em] text-amber-300 uppercase"
                            >
                                {{ translations.concept.eyebrow }}
                            </p>
                            <h2
                                class="vvs-display-title mt-3 text-3xl sm:text-4xl"
                            >
                                {{ translations.concept.title }}
                            </h2>
                            <p
                                class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400"
                            >
                                {{ translations.concept.description }}
                            </p>
                        </div>

                        <button
                            type="button"
                            class="vvs-button-primary rounded-xl px-6 py-4 text-xs font-bold tracking-[0.15em] uppercase"
                            @click="scrollToCollection"
                        >
                            {{ translations.concept.cta }}
                        </button>
                    </div>
                </div>
            </section>

            <section
                id="services"
                class="scroll-mt-24 px-5 py-12 sm:px-6 lg:px-10"
            >
                <div
                    class="reveal-on-scroll vvs-luxury-card mx-auto grid max-w-[1500px] overflow-hidden rounded-2xl border sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        class="border-b border-white/10 p-7 sm:border-r lg:border-b-0"
                    >
                        <div class="text-2xl text-amber-300">◇</div>
                        <h3 class="mt-4 text-sm font-semibold tracking-wide">
                            {{ translations.services.quality_title }}
                        </h3>
                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                            {{ translations.services.quality_text }}
                        </p>
                    </div>
                    <div
                        class="border-b border-white/10 p-7 lg:border-r lg:border-b-0"
                    >
                        <div class="text-2xl text-amber-300">⚙</div>
                        <h3 class="mt-4 text-sm font-semibold tracking-wide">
                            {{ translations.services.movements_title }}
                        </h3>
                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                            {{ translations.services.movements_text }}
                        </p>
                    </div>
                    <div
                        class="border-b border-white/10 p-7 sm:border-r sm:border-b-0"
                    >
                        <div class="text-2xl text-amber-300">↗</div>
                        <h3 class="mt-4 text-sm font-semibold tracking-wide">
                            {{ translations.services.delivery_title }}
                        </h3>
                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                            {{ translations.services.delivery_text }}
                        </p>
                    </div>
                    <div class="p-7">
                        <div class="text-2xl text-amber-300">◷</div>
                        <h3 class="mt-4 text-sm font-semibold tracking-wide">
                            {{ translations.services.delay_title }}
                        </h3>
                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                            {{ translations.services.delay_text }}
                        </p>
                    </div>
                </div>
            </section>

            <AboutSection />
            <PickupSection />
            <FaqSection />
            <ContactSection />
        </main>

        <footer class="border-t border-white/10 bg-black px-6 py-10">
            <div
                class="mx-auto flex max-w-[1500px] flex-col gap-5 text-center md:flex-row md:items-center md:justify-between md:text-left"
            >
                <div>
                    <p class="font-black tracking-[0.12em] uppercase">
                        VVS FLAWLESS
                    </p>
                    <p class="mt-1 text-xs text-zinc-600">
                        {{ translations.footer.tagline }}
                    </p>
                </div>

                <nav
                    class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2"
                    :aria-label="translations.navigation.main_label"
                >
                    <Link
                        :href="localizedRoutes.about"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                    >
                        {{ translations.navigation.about }}
                    </Link>
                    <Link
                        :href="localizedRoutes.diamondGuide"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                    >
                        {{ guideLinks.eyebrow }}
                    </Link>
                    <Link
                        :href="localizedRoutes.privacy"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                    >
                        {{ translations.footer.privacy }}
                    </Link>
                    <Link
                        :href="localizedRoutes.reservationTerms"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                    >
                        {{ translations.footer.terms }}
                    </Link>
                </nav>

                <p class="text-xs text-zinc-500">
                    {{ translations.footer.copyright }}
                </p>
            </div>
        </footer>
    </div>
</template>
