<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { animate, createTimeline, stagger } from 'animejs';

import VvsNavigation from '@/components/VvsNavigation.vue';
import StockBadge from '@/components/StockBadge.vue';
import OrderSteps from '@/components/OrderSteps.vue';
import AboutSection from '@/components/AboutSection.vue';
import ContactSection from '@/components/ContactSection.vue';
import FaqSection from '@/components/FaqSection.vue';
import PickupSection from '@/components/PickupSection.vue';

const props = defineProps({
    watches: {
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

const seoGuides = computed(() => [
    {
        href: localizedRoutes.diamondGuide,
        label: translations.seo_hub.diamond,
    },
    {
        href: localizedRoutes.vvsGuide,
        label: translations.seo_hub.vvs,
    },
    {
        href: localizedRoutes.menWomenGuide,
        label: translations.seo_hub.men_women,
    },
    {
        href: localizedRoutes.belgiumGuide,
        label: translations.seo_hub.belgium,
    },
]);

const formatPrice = (price) => {
    if (!price) {
        return '—';
    }

    return `${Number(price).toFixed(0)} €`;
};

const hasJapanesePromotion = (watch) =>
    watch.japanese_promo_price &&
    Number(watch.japanese_promo_price) < Number(watch.japanese_price);

const hasSwissPromotion = (watch) =>
    watch.swiss_promo_price &&
    Number(watch.swiss_promo_price) < Number(watch.swiss_price);

const scrollToCollection = () => {
    document.getElementById('collection')?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    });
};

const viewerHost = ref(null);
const modelProgress = ref(0);
const modelLoaded = ref(false);
const modelError = ref(false);
const modelRequested = ref(false);

let modelViewerElement = null;
let revealObserver = null;

const mountModelViewer = async () => {
    try {
        await import('@google/model-viewer');

        if (!viewerHost.value) {
            return;
        }

        const viewer = document.createElement('model-viewer');
        modelViewerElement = viewer;

        viewer.setAttribute('src', '/models/vvs-watch.glb');
        viewer.setAttribute('alt', 'Montre VVS FLAWLESS 3D');
        viewer.setAttribute('poster', '/images/vvs-watch-hero.webp');
        viewer.setAttribute('camera-controls', '');
        viewer.setAttribute('auto-rotate', '');
        viewer.setAttribute('rotation-per-second', '12deg');
        viewer.setAttribute('interaction-prompt', 'none');
        viewer.setAttribute('touch-action', 'pan-y');
        viewer.setAttribute('camera-orbit', '0deg 75deg 105%');
        viewer.setAttribute('field-of-view', '30deg');
        viewer.setAttribute('min-field-of-view', '18deg');
        viewer.setAttribute('max-field-of-view', '55deg');
        viewer.setAttribute('min-camera-orbit', 'auto auto 60%');
        viewer.setAttribute('max-camera-orbit', 'auto auto 200%');
        viewer.setAttribute('environment-image', 'neutral');
        viewer.setAttribute('exposure', '1.15');
        viewer.setAttribute('shadow-intensity', '1.25');
        viewer.setAttribute('shadow-softness', '0.8');
        viewer.setAttribute('tone-mapping', 'commerce');
        viewer.setAttribute('loading', 'eager');
        viewer.setAttribute('reveal', 'auto');

        viewer.style.width = '100%';
        viewer.style.height = '100%';
        viewer.style.display = 'block';
        viewer.style.background = 'transparent';

        viewer.addEventListener('progress', (event) => {
            const progress = event.detail?.totalProgress ?? 0;
            modelProgress.value = Math.round(progress * 100);
        });

        viewer.addEventListener('load', () => {
            modelProgress.value = 100;
            modelLoaded.value = true;
            modelError.value = false;
        });

        viewer.addEventListener('error', () => {
            modelError.value = true;
            modelLoaded.value = false;
        });

        viewerHost.value.replaceChildren(viewer);
    } catch (error) {
        console.error('Erreur chargement montre 3D :', error);
        modelError.value = true;
    }
};

const requestModelViewer = () => {
    if (modelRequested.value || modelLoaded.value) {
        return;
    }

    modelRequested.value = true;
    mountModelViewer();
};

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

    const heroTimeline = createTimeline({ defaults: { ease: 'outExpo' } });

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
                scale: [0.86, 1],
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
    modelViewerElement?.remove();
    modelViewerElement = null;
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
                        class="flex items-center rounded-full border border-white/10 p-1 text-[10px] font-bold tracking-wider"
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
                            {{ translations.language.fr }}
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
                            {{ translations.language.nl }}
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
                            {{ translations.language.en }}
                        </Link>
                    </nav>
                </div>
            </div>
        </header>

        <main id="main-content" tabindex="-1">
            <VvsNavigation current="collection" :show-back="false" class="!top-24" />

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
                >
                    ✦
                </span>
                <span
                    class="bling-sparkle absolute top-[14%] right-[10%] text-3xl text-white"
                >
                    ✦
                </span>

                <div
                    class="mx-auto grid min-h-[690px] max-w-[1500px] items-center gap-12 px-6 py-16 lg:grid-cols-[0.95fr_1.05fr] lg:px-10 lg:py-20"
                >
                    <div class="relative z-10">
                        <div
                            class="hero-animate mb-7 flex flex-wrap items-center gap-3"
                        >
                            <span
                                class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                            >
                                Moissanite VVS
                            </span>
                            <span class="text-amber-500"> • </span>
                            <span
                                class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                            >
                                Diamond look
                            </span>
                            <span class="text-amber-500"> • </span>
                            <span
                                class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                            >
                                Iced Out
                            </span>
                        </div>

                        <h1
                            class="hero-animate relative max-w-3xl overflow-hidden text-[clamp(3rem,17vw,4rem)] leading-[0.82] font-semibold tracking-[-0.055em] uppercase sm:text-[5.5rem] lg:text-[7rem]"
                        >
                            <span
                                class="vvs-gradient-text vvs-gradient-text--hero"
                            >
                                VVS
                            </span>
                            <br />
                            <span
                                class="vvs-gradient-text vvs-gradient-text--hero"
                            >
                                FLAWLESS
                            </span>
                            <span
                                class="brand-shine pointer-events-none absolute top-0 -left-[30%] h-full w-[18%] -skew-x-12 bg-gradient-to-r from-transparent via-white/70 to-transparent blur-sm"
                            ></span>
                        </h1>

                        <p
                            class="hero-animate vvs-display-title mt-8 max-w-xl text-3xl leading-tight text-amber-200 sm:text-4xl"
                        >
                            {{ translations.hero.tagline }}
                        </p>

                        <p
                            class="hero-animate vvs-body-copy mt-7 max-w-xl text-base text-zinc-300 sm:text-lg"
                        >
                            {{ translations.hero.description }}
                        </p>

                        <div class="hero-animate mt-9 flex flex-wrap gap-4">
                            <a
                                href="#collection"
                                class="vvs-button-primary rounded-xl px-7 py-4 text-sm font-bold tracking-[0.1em] uppercase"
                            >
                                {{ translations.collection.view_watch }}
                            </a>
                            <Link
                                :href="localizedRoutes.diamondGuide"
                                class="vvs-button-secondary rounded-xl px-7 py-4 text-sm font-bold tracking-[0.1em] uppercase"
                            >
                                {{ translations.seo_hub.diamond }}
                            </Link>
                        </div>
                    </div>

                    <div
                        class="relative flex min-h-[470px] items-center justify-center lg:min-h-[620px]"
                    >
                        <div
                            class="hero-watch relative z-10 h-[470px] w-full max-w-[720px] sm:h-[550px] lg:h-[640px]"
                        >
                            <img
                                v-if="!modelRequested"
                                src="/images/vvs-watch-hero.webp"
                                alt="Montre iced-out VVS FLAWLESS sertie de moissanite"
                                width="1536"
                                height="1024"
                                fetchpriority="high"
                                decoding="async"
                                class="absolute inset-0 h-full w-full object-contain"
                            />

                            <div ref="viewerHost" class="absolute inset-0"></div>

                            <div
                                v-if="!modelRequested && !modelLoaded && !modelError"
                                class="absolute inset-0 z-20 flex items-center justify-center"
                            >
                                <button
                                    type="button"
                                    class="vvs-button-secondary rounded-xl px-6 py-4 text-center backdrop-blur-md"
                                    @click="requestModelViewer"
                                >
                                    <span
                                        class="block text-[10px] font-black tracking-[0.25em] text-amber-300 uppercase"
                                    >
                                        {{ translations.hero.view_3d }}
                                    </span>
                                    <span
                                        class="mt-2 block text-[9px] tracking-wider text-zinc-400 uppercase"
                                    >
                                        {{ translations.hero.click_to_load }}
                                    </span>
                                </button>
                            </div>

                            <div
                                v-else-if="!modelLoaded && !modelError"
                                class="pointer-events-none absolute inset-0 z-20 flex items-center justify-center"
                            >
                                <div
                                    class="rounded-xl border border-white/10 bg-black/70 px-6 py-4 text-center backdrop-blur-md"
                                >
                                    <p
                                        class="text-[10px] font-black tracking-[0.25em] text-amber-300 uppercase"
                                    >
                                        {{ translations.hero.loading_3d }}
                                    </p>
                                    <p class="mt-2 text-lg font-black text-white">
                                        {{ modelProgress }}%
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="modelError"
                                class="absolute inset-0 z-20 flex items-center justify-center"
                            >
                                <div
                                    class="rounded-xl border border-red-500/20 bg-black/80 px-6 py-5 text-center"
                                >
                                    {{ translations.hero.load_error }}
                                </div>
                            </div>

                            <div
                                v-if="modelLoaded"
                                class="pointer-events-none absolute bottom-3 left-1/2 z-30 -translate-x-1/2 rounded-full border border-white/10 bg-black/60 px-4 py-2 text-[9px] font-bold tracking-[0.18em] whitespace-nowrap text-zinc-400 uppercase backdrop-blur-md"
                            >
                                {{ translations.hero.drag_360 }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                id="collection"
                class="relative scroll-mt-24 overflow-hidden px-5 py-20 sm:px-6 lg:px-10"
            >
                <div class="mx-auto max-w-[1500px]">
                    <header class="reveal-on-scroll mb-12 text-center">
                        <span
                            class="text-[11px] font-bold tracking-[0.4em] text-amber-300 uppercase"
                        >
                            {{ translations.collection.eyebrow }}
                        </span>
                        <h2 class="vvs-display-title mt-4 text-5xl sm:text-6xl">
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
                        <article
                            v-for="watch in props.watches"
                            :key="watch.id"
                            class="watch-card reveal-on-scroll vvs-luxury-card vvs-luxury-card--interactive group relative overflow-hidden rounded-2xl border"
                        >
                            <div
                                class="relative h-[390px] overflow-hidden bg-zinc-950"
                            >
                                <img
                                    v-if="watch.image"
                                    :src="watch.image"
                                    :alt="watch.name"
                                    loading="lazy"
                                    decoding="async"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.06]"
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
                            </div>

                            <div class="relative p-6">
                                <h3
                                    class="vvs-display-title min-h-[58px] text-center text-2xl leading-[1.05] text-white"
                                >
                                    {{ watch.name }}
                                </h3>
                                <p
                                    class="mt-2 text-center text-[11px] font-bold tracking-[0.18em] text-amber-300 uppercase"
                                >
                                    Moissanite VVS · Diamond look · Iced-out
                                </p>

                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <Link
                                        :href="`${localizedRoutes.watches}/${watch.id}?movement=Japonais`"
                                        class="vvs-choice-card group/version rounded-xl border p-4"
                                    >
                                        <p
                                            class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                                        >
                                            {{ translations.collection.japanese }}
                                        </p>
                                        <p
                                            v-if="hasJapanesePromotion(watch)"
                                            class="mt-3 text-xs text-zinc-600 line-through"
                                        >
                                            {{ formatPrice(watch.japanese_price) }}
                                        </p>
                                        <p class="vvs-price mt-1 text-xl font-black">
                                            {{
                                                formatPrice(
                                                    watch.japanese_promo_price ??
                                                        watch.japanese_price,
                                                )
                                            }}
                                        </p>
                                    </Link>

                                    <Link
                                        :href="`${localizedRoutes.watches}/${watch.id}?movement=Suisse`"
                                        class="vvs-choice-card vvs-choice-card--featured group/version relative overflow-hidden rounded-xl border p-4"
                                    >
                                        <p
                                            class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                                        >
                                            {{ translations.collection.swiss }}
                                        </p>
                                        <p
                                            v-if="hasSwissPromotion(watch)"
                                            class="mt-3 text-xs text-zinc-600 line-through"
                                        >
                                            {{ formatPrice(watch.swiss_price) }}
                                        </p>
                                        <p class="vvs-price mt-1 text-xl font-black">
                                            {{
                                                formatPrice(
                                                    watch.swiss_promo_price ??
                                                        watch.swiss_price,
                                                )
                                            }}
                                        </p>
                                    </Link>
                                </div>

                                <p
                                    class="mt-5 line-clamp-2 text-center text-sm leading-6 text-zinc-500"
                                >
                                    {{ watch.description }}
                                </p>

                                <Link
                                    :href="`${localizedRoutes.watches}/${watch.id}`"
                                    class="vvs-button-secondary mt-6 flex w-full items-center justify-center gap-3 rounded-xl px-4 py-4 text-xs font-bold tracking-[0.12em] uppercase"
                                >
                                    {{ translations.collection.view_watch }} →
                                </Link>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section class="px-5 pb-16 sm:px-6 lg:px-10">
                <div
                    class="vvs-luxury-card mx-auto max-w-[1500px] rounded-2xl border p-7 sm:p-10"
                >
                    <div class="max-w-3xl">
                        <p class="vvs-eyebrow">{{ translations.seo_hub.eyebrow }}</p>
                        <h2 class="vvs-display-title mt-3 text-3xl sm:text-4xl">
                            {{ translations.seo_hub.title }}
                        </h2>
                        <p class="mt-4 text-sm leading-7 text-zinc-400 sm:text-base">
                            {{ translations.seo_hub.description }}
                        </p>
                    </div>

                    <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <Link
                            v-for="guide in seoGuides"
                            :key="guide.href"
                            :href="guide.href"
                            class="vvs-choice-card rounded-xl border p-5 text-sm font-semibold text-zinc-200 transition hover:text-amber-200"
                        >
                            {{ guide.label }} →
                        </Link>
                    </div>
                </div>
            </section>

            <OrderSteps />

            <section
                id="concept"
                class="scroll-mt-24 px-5 pb-8 sm:px-6 lg:px-10"
            >
                <div
                    class="reveal-on-scroll vvs-luxury-card mx-auto max-w-[1500px] overflow-hidden rounded-2xl border p-8 md:p-10"
                >
                    <p class="text-xs font-black tracking-[0.3em] text-amber-300 uppercase">
                        {{ translations.concept.eyebrow }}
                    </p>
                    <h2 class="vvs-display-title mt-3 text-3xl sm:text-4xl">
                        {{ translations.concept.title }}
                    </h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400">
                        {{ translations.concept.description }}
                    </p>
                    <button
                        type="button"
                        @click="scrollToCollection"
                        class="vvs-button-primary mt-6 rounded-xl px-6 py-4 text-xs font-bold tracking-[0.15em] uppercase"
                    >
                        {{ translations.concept.cta }}
                    </button>
                </div>
            </section>

            <section
                id="services"
                class="scroll-mt-24 px-5 py-12 sm:px-6 lg:px-10"
            >
                <div
                    class="reveal-on-scroll vvs-luxury-card mx-auto grid max-w-[1500px] overflow-hidden rounded-2xl border sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div class="border-b border-white/10 p-7 sm:border-r lg:border-b-0">
                        <h3 class="text-sm font-semibold tracking-wide">
                            {{ translations.services.quality_title }}
                        </h3>
                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                            {{ translations.services.quality_text }}
                        </p>
                    </div>
                    <div class="border-b border-white/10 p-7 lg:border-r lg:border-b-0">
                        <h3 class="text-sm font-semibold tracking-wide">
                            {{ translations.services.movements_title }}
                        </h3>
                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                            {{ translations.services.movements_text }}
                        </p>
                    </div>
                    <div class="border-b border-white/10 p-7 sm:border-r sm:border-b-0">
                        <h3 class="text-sm font-semibold tracking-wide">
                            {{ translations.services.delivery_title }}
                        </h3>
                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                            {{ translations.services.delivery_text }}
                        </p>
                    </div>
                    <div class="p-7">
                        <h3 class="text-sm font-semibold tracking-wide">
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
                    <p class="font-black tracking-[0.12em] uppercase">VVS FLAWLESS</p>
                    <p class="mt-1 text-xs text-zinc-600">
                        {{ translations.footer.tagline }}
                    </p>
                </div>
                <p class="text-xs text-zinc-700">
                    {{ translations.footer.copyright }}
                </p>
            </div>
        </footer>
    </div>
</template>
