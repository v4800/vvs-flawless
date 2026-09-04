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
    };
});
/*
|--------------------------------------------------------------------------
| FORMATAGE DES PRIX
|--------------------------------------------------------------------------
*/

const formatPrice = (price) => {
    if (!price) {
        return '—';
    }

    return `${Number(price).toFixed(0)} €`;
};

/*
|--------------------------------------------------------------------------
| SCROLL COLLECTION
|--------------------------------------------------------------------------
*/

const scrollToCollection = () => {
    document.getElementById('collection')?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    });
};

/*
|--------------------------------------------------------------------------
| VIEWER 3D
|--------------------------------------------------------------------------
|
| Le model-viewer est chargé uniquement côté navigateur.
| Ça évite de casser le SSR / hydratation de Laravel + Inertia.
|
*/

const viewerHost = ref(null);

const modelProgress = ref(0);
const modelLoaded = ref(false);
const modelError = ref(false);
const modelRequested = ref(false);

let modelViewerElement = null;
let revealObserver = null;

const mountModelViewer = async () => {
    try {
        /*
         * Chargement uniquement côté client
         */
        await import('@google/model-viewer');

        if (!viewerHost.value) {
            return;
        }

        const viewer = document.createElement('model-viewer');

        modelViewerElement = viewer;

        /*
         * FICHIER 3D
         */
        viewer.setAttribute('src', '/models/vvs-watch.glb');

        viewer.setAttribute('alt', 'Montre VVS FLAWLESS 3D');

        viewer.setAttribute('poster', '/images/vvs-watch-hero.webp');

        /*
         * CONTRÔLES
         */
        viewer.setAttribute('camera-controls', '');

        viewer.setAttribute('auto-rotate', '');

        viewer.setAttribute('rotation-per-second', '12deg');

        viewer.setAttribute('interaction-prompt', 'none');

        viewer.setAttribute('touch-action', 'pan-y');

        /*
         * CAMÉRA
         */
        viewer.setAttribute('camera-orbit', '0deg 75deg 105%');

        viewer.setAttribute('field-of-view', '30deg');

        viewer.setAttribute('min-field-of-view', '18deg');

        viewer.setAttribute('max-field-of-view', '55deg');

        viewer.setAttribute('min-camera-orbit', 'auto auto 60%');

        viewer.setAttribute('max-camera-orbit', 'auto auto 200%');

        /*
         * ÉCLAIRAGE / RENDU
         */
        viewer.setAttribute('environment-image', 'neutral');

        viewer.setAttribute('exposure', '1.15');

        viewer.setAttribute('shadow-intensity', '1.25');

        viewer.setAttribute('shadow-softness', '0.8');

        viewer.setAttribute('tone-mapping', 'commerce');

        /*
         * Le viewer n'est créé qu'après le clic.
         * Une fois demandé, le modèle peut démarrer immédiatement.
         */
        viewer.setAttribute('loading', 'eager');

        viewer.setAttribute('reveal', 'auto');

        /*
         * DIMENSIONS
         */
        viewer.style.width = '100%';
        viewer.style.height = '100%';
        viewer.style.display = 'block';
        viewer.style.background = 'transparent';

        /*
         * PROGRESSION DU GLB 64 MO
         */
        viewer.addEventListener('progress', (event) => {
            const progress = event.detail?.totalProgress ?? 0;

            modelProgress.value = Math.round(progress * 100);
        });

        /*
         * MODÈLE CHARGÉ
         */
        viewer.addEventListener('load', () => {
            modelProgress.value = 100;
            modelLoaded.value = true;
            modelError.value = false;
        });

        /*
         * ERREUR
         */
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

/*
|--------------------------------------------------------------------------
| ANIMATIONS
|--------------------------------------------------------------------------
*/

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

    /*
    |--------------------------------------------------------------------------
    | ENTRÉE CINÉMATIQUE DU HERO
    |--------------------------------------------------------------------------
    */

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
                scale: [0.86, 1],
                duration: 1450,
            },
            '-=900',
        )
        .add(
            '.hero-badge',
            {
                opacity: [0, 1],
                x: [25, 0],
                scale: [0.92, 1],
                duration: 750,
            },
            '-=650',
        );

    /*
    |--------------------------------------------------------------------------
    | REFLET TITRE
    |--------------------------------------------------------------------------
    */

    animate('.brand-shine', {
        x: ['0%', '560%'],
        opacity: [0, 0.9, 0],
        duration: 1800,
        delay: 900,
        loop: true,
        loopDelay: 2600,
        ease: 'inOutQuad',
    });

    /*
    |--------------------------------------------------------------------------
    | ÉCLATS
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | HALOS
    |--------------------------------------------------------------------------
    */

    animate('.hero-orb', {
        opacity: [0.35, 0.75],

        scale: [0.92, 1.08],

        duration: 3800,

        loop: true,

        alternate: true,

        ease: 'inOutQuad',
    });

    /*
    |--------------------------------------------------------------------------
    | APPARITION DES SECTIONS
    |--------------------------------------------------------------------------
    */

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

                    y: [
                        isCard ? 42 : 30,

                        0,
                    ],

                    scale: [
                        isCard ? 0.96 : 0.985,

                        1,
                    ],

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

/*
|--------------------------------------------------------------------------
| NETTOYAGE
|--------------------------------------------------------------------------
*/

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

        <!-- ========================================================= -->
        <!-- HEADER -->
        <!-- ========================================================= -->

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
                        class="text-xl font-black tracking-[0.08em] text-white md:text-2xl"
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
                    <a
                        href="#collection"
                        class="transition hover:text-amber-300"
                    >
                        {{ translations.navigation.watches }}
                    </a>

                    <a href="#concept" class="transition hover:text-amber-300">
                        {{ translations.navigation.about }}
                    </a>

                    <a href="#services" class="transition hover:text-amber-300">
                        {{ translations.navigation.delivery }}
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <nav
                        class="flex items-center rounded-full border border-white/10 p-1 text-[10px] font-black tracking-wider"
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
                    </nav>

                    <span
                        class="hidden text-xs tracking-[0.2em] text-zinc-500 uppercase sm:block"
                    >
                        {{ translations.navigation.country }}
                    </span>

                    <div
                        class="flex h-7 overflow-hidden rounded-sm border border-white/20 shadow-[0_0_18px_rgba(251,191,36,0.15)]"
                        :aria-label="translations.navigation.country"
                    >
                        <span class="w-2.5 bg-black"></span>

                        <span class="w-2.5 bg-yellow-400"></span>

                        <span class="w-2.5 bg-red-600"></span>
                    </div>
                </div>
            </div>
        </header>

        <main id="main-content" tabindex="-1">
            <!-- ========================================================= -->
            <!-- NAVIGATION VVS -->
            <!-- ========================================================= -->

            <VvsNavigation
                current="collection"
                :show-back="false"
                class="!top-24"
            />

            <!-- ========================================================= -->
            <!-- HERO -->
            <!-- ========================================================= -->

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

                <!-- ÉCLATS -->

                <span
                    class="bling-sparkle absolute top-[19%] left-[5%] text-3xl text-amber-200"
                >
                    ✦
                </span>

                <span
                    class="bling-sparkle absolute top-[29%] left-[35%] text-xl text-white"
                >
                    ✦
                </span>

                <span
                    class="bling-sparkle absolute top-[14%] right-[10%] text-3xl text-white"
                >
                    ✦
                </span>

                <span
                    class="bling-sparkle absolute right-[42%] bottom-[20%] text-xl text-amber-300"
                >
                    ✦
                </span>

                <!-- LIGNE BELGIQUE -->

                <div class="absolute top-0 right-0 hidden h-full w-1.5 lg:flex">
                    <div class="h-full flex-1 bg-black"></div>

                    <div class="h-full flex-1 bg-yellow-400"></div>

                    <div class="h-full flex-1 bg-red-600"></div>
                </div>

                <div
                    class="mx-auto grid min-h-[690px] max-w-[1500px] items-center gap-12 px-6 py-16 lg:grid-cols-[0.95fr_1.05fr] lg:px-10 lg:py-20"
                >
                    <!-- HERO TEXTE -->

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
                                Iced Out
                            </span>

                            <span class="text-amber-500"> • </span>

                            <span
                                class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                            >
                                Bustdown
                            </span>
                        </div>

                        <h1
                            class="hero-animate relative max-w-3xl overflow-hidden text-[clamp(3rem,17vw,4rem)] leading-[0.8] font-black tracking-[-0.06em] uppercase sm:text-[5.5rem] lg:text-[7rem]"
                        >
                            <span
                                class="bg-gradient-to-b from-white via-zinc-100 to-zinc-500 bg-clip-text text-transparent"
                            >
                                VVS
                            </span>

                            <br />

                            <span
                                class="bg-gradient-to-b from-white via-zinc-200 to-zinc-500 bg-clip-text text-transparent"
                            >
                                FLAWLESS
                            </span>

                            <span
                                class="absolute top-2 -right-2 hidden text-3xl text-amber-200 sm:block"
                            >
                                ✦
                            </span>

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
                                class="text-2xl leading-tight font-medium text-amber-200 italic sm:text-3xl"
                            >
                                {{ translations.hero.tagline }}
                            </p>
                        </div>

                        <p
                            class="hero-animate mt-7 max-w-xl text-sm leading-7 font-medium tracking-[0.08em] text-zinc-300 uppercase sm:text-base"
                        >
                            {{ translations.hero.description }}
                        </p>

                        <!-- FEATURES -->

                        <div
                            class="hero-animate mt-8 grid max-w-2xl grid-cols-2 gap-3 sm:grid-cols-4"
                        >
                            <div
                                class="rounded-xl border border-white/10 bg-white/[0.03] p-3 backdrop-blur"
                            >
                                <div class="text-xl text-amber-300">◇</div>

                                <p
                                    class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                                >
                                    Moissanite
                                </p>

                                <p class="text-[10px] text-zinc-500">VVS</p>
                            </div>

                            <div
                                class="rounded-xl border border-white/10 bg-white/[0.03] p-3 backdrop-blur"
                            >
                                <div class="text-lg text-amber-300">♢</div>

                                <p
                                    class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                                >
                                    {{ translations.collection.japanese }}
                                </p>

                                <p class="text-[10px] text-zinc-500">
                                    {{ translations.hero.selected }}
                                </p>
                            </div>

                            <div
                                class="rounded-xl border border-white/10 bg-white/[0.03] p-3 backdrop-blur"
                            >
                                <div class="text-lg text-amber-300">✦</div>

                                <p
                                    class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                                >
                                    {{ translations.collection.swiss }}
                                </p>

                                <p class="text-[10px] text-zinc-500">
                                    {{ translations.collection.premium }}
                                </p>
                            </div>

                            <div
                                class="rounded-xl border border-white/10 bg-white/[0.03] p-3 backdrop-blur"
                            >
                                <div class="text-lg text-amber-300">↗</div>

                                <p
                                    class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                                >
                                    {{ translations.navigation.country }}
                                </p>

                                <p class="text-[10px] text-zinc-500">
                                    {{ translations.hero.handover }}
                                </p>
                            </div>
                        </div>

                        <div class="hero-animate mt-9 flex flex-wrap gap-4">
                            <a
                                href="#concept"
                                class="rounded-lg border border-amber-300/50 bg-amber-300/[0.04] px-7 py-4 text-sm font-bold tracking-[0.1em] text-amber-200 uppercase shadow-[0_0_25px_rgba(251,191,36,0.08)] transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:bg-amber-300 hover:text-black"
                            >
                                {{ translations.hero.concept }}
                            </a>
                        </div>
                    </div>

                    <!-- ================================================= -->
                    <!-- MONTRE 3D -->
                    <!-- ================================================= -->

                    <div
                        class="relative flex min-h-[470px] items-center justify-center lg:min-h-[620px]"
                    >
                        <div
                            class="absolute h-[65%] w-[65%] rounded-full border border-amber-400/10 bg-amber-400/[0.025]"
                        ></div>

                        <div
                            class="absolute h-[85%] w-[85%] rounded-full border border-white/[0.04]"
                        ></div>

                        <div
                            class="absolute h-[420px] w-[420px] rounded-full bg-amber-300/10 blur-[90px]"
                        ></div>

                        <div
                            class="hero-watch relative z-10 h-[470px] w-full max-w-[720px] sm:h-[550px] lg:h-[640px]"
                        >
                            <img
                                v-if="!modelRequested"
                                src="/images/vvs-watch-hero.webp"
                                alt="Montre iced-out VVS FLAWLESS"
                                width="1536"
                                height="1024"
                                fetchpriority="high"
                                decoding="async"
                                class="absolute inset-0 h-full w-full object-contain"
                            />

                            <!-- MODEL VIEWER EST CRÉÉ ICI EN JS -->

                            <div
                                ref="viewerHost"
                                class="absolute inset-0"
                            ></div>

                            <!-- CHARGEMENT -->

                            <div
                                v-if="
                                    !modelRequested &&
                                    !modelLoaded &&
                                    !modelError
                                "
                                class="absolute inset-0 z-20 flex items-center justify-center"
                            >
                                <button
                                    type="button"
                                    class="rounded-xl border border-amber-300/40 bg-black/75 px-6 py-4 text-center backdrop-blur-md transition hover:border-amber-300 hover:bg-black/90 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-300"
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

                                    <p
                                        class="mt-2 text-lg font-black text-white"
                                    >
                                        {{ modelProgress }}%
                                    </p>

                                    <div
                                        class="mt-3 h-[2px] w-40 overflow-hidden rounded-full bg-white/10"
                                    >
                                        <div
                                            class="h-full bg-amber-300 transition-all duration-300"
                                            :style="{
                                                width: `${modelProgress}%`,
                                            }"
                                        ></div>
                                    </div>

                                    <p
                                        class="mt-3 text-[9px] tracking-wider text-zinc-600 uppercase"
                                    >
                                        {{
                                            translations.hero.high_quality_model
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- ERREUR -->

                            <div
                                v-if="modelError"
                                class="absolute inset-0 z-20 flex items-center justify-center"
                            >
                                <div
                                    class="rounded-xl border border-red-500/20 bg-black/80 px-6 py-5 text-center"
                                >
                                    <p
                                        class="text-xs font-black tracking-wider text-red-300 uppercase"
                                    >
                                        {{ translations.hero.load_error }}
                                    </p>

                                    <p class="mt-2 text-[10px] text-zinc-500">
                                        /models/vvs-watch.glb
                                    </p>
                                </div>
                            </div>

                            <!-- TEXTE 360 -->

                            <div
                                v-if="modelLoaded"
                                class="pointer-events-none absolute bottom-3 left-1/2 z-30 -translate-x-1/2 rounded-full border border-white/10 bg-black/60 px-4 py-2 text-[9px] font-bold tracking-[0.18em] whitespace-nowrap text-zinc-400 uppercase backdrop-blur-md"
                            >
                                {{ translations.hero.drag_360 }}
                            </div>
                        </div>

                        <!-- BADGE -->

                        <div
                            class="hero-badge absolute right-0 bottom-[8%] z-20 hidden border border-amber-300/50 bg-black/80 px-6 py-5 text-center shadow-[0_0_35px_rgba(251,191,36,0.12)] backdrop-blur md:block"
                        >
                            <p
                                class="text-[9px] font-bold tracking-[0.3em] text-amber-300 uppercase"
                            >
                                360°
                            </p>

                            <p
                                class="mt-2 text-sm leading-5 font-bold uppercase"
                            >
                                Vue
                                <br />
                                Interactive
                            </p>

                            <div
                                class="mx-auto mt-3 flex h-5 w-9 overflow-hidden"
                            >
                                <span class="flex-1 bg-black"></span>

                                <span class="flex-1 bg-yellow-400"></span>

                                <span class="flex-1 bg-red-600"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- COLLECTION -->
            <!-- ========================================================= -->

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

                        <h2
                            class="text-4xl font-black tracking-[-0.03em] uppercase sm:text-5xl"
                        >
                            {{ translations.collection.title_before }}

                            <span class="text-amber-300">
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
                            class="watch-card reveal-on-scroll group relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-b from-zinc-950 to-black transition duration-500 hover:-translate-y-2 hover:border-amber-300/40 hover:shadow-[0_20px_70px_rgba(251,191,36,0.08)]"
                        >
                            <div
                                class="absolute top-0 left-1/2 z-20 h-px w-0 -translate-x-1/2 bg-gradient-to-r from-transparent via-amber-300 to-transparent transition-all duration-500 group-hover:w-[85%]"
                            ></div>

                            <!-- IMAGE -->

                            <div
                                class="relative h-[390px] overflow-hidden bg-zinc-950"
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

                                <span
                                    class="bling-sparkle absolute top-[21%] right-[13%] text-2xl text-white"
                                >
                                    ✦
                                </span>

                                <span
                                    class="bling-sparkle absolute bottom-[22%] left-[13%] text-lg text-amber-200"
                                >
                                    ✦
                                </span>
                            </div>

                            <!-- CONTENU -->

                            <div class="relative p-6">
                                <h3
                                    class="min-h-[58px] text-center text-lg leading-snug font-black tracking-[0.02em] text-white uppercase"
                                >
                                    {{ watch.name }}
                                </h3>

                                <p
                                    class="mt-2 text-center text-[11px] font-bold tracking-[0.18em] text-amber-300 uppercase"
                                >
                                    Moissanite VVS
                                </p>

                                <!-- MOUVEMENTS -->

                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <!-- JAPON -->

                                    <Link
                                        :href="`${localizedRoutes.watches}/${watch.id}?movement=Japonais`"
                                        class="group/version rounded-xl border border-white/10 bg-white/[0.025] p-4 transition duration-300 hover:border-amber-300/50 hover:bg-amber-300/[0.04]"
                                    >
                                        <p
                                            class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase transition group-hover/version:text-amber-200"
                                        >
                                            {{
                                                translations.collection.japanese
                                            }}
                                        </p>

                                        <p
                                            v-if="watch.japanese_price"
                                            class="mt-3 text-xs text-zinc-600 line-through"
                                        >
                                            {{
                                                formatPrice(
                                                    watch.japanese_price,
                                                )
                                            }}
                                        </p>

                                        <p
                                            class="mt-1 text-xl font-black text-amber-200"
                                        >
                                            {{
                                                formatPrice(
                                                    watch.japanese_promo_price ??
                                                        watch.japanese_price,
                                                )
                                            }}
                                        </p>

                                        <p
                                            class="mt-3 text-[9px] tracking-wider text-zinc-600 uppercase"
                                        >
                                            {{ translations.collection.choose }}
                                        </p>
                                    </Link>

                                    <!-- SUISSE -->

                                    <Link
                                        :href="`${localizedRoutes.watches}/${watch.id}?movement=Suisse`"
                                        class="group/version relative overflow-hidden rounded-xl border border-amber-300/20 bg-amber-300/[0.035] p-4 transition duration-300 hover:border-amber-300/60 hover:bg-amber-300/[0.07]"
                                    >
                                        <span
                                            class="absolute top-2 right-2 rounded bg-amber-300 px-1.5 py-0.5 text-[7px] font-black tracking-wider text-black uppercase"
                                            >{{
                                                translations.collection.premium
                                            }}</span
                                        >

                                        <p
                                            class="text-[9px] font-bold tracking-[0.16em] text-zinc-500 uppercase transition group-hover/version:text-amber-200"
                                        >
                                            {{ translations.collection.swiss }}
                                        </p>

                                        <p
                                            v-if="watch.swiss_price"
                                            class="mt-3 text-xs text-zinc-600 line-through"
                                        >
                                            {{ formatPrice(watch.swiss_price) }}
                                        </p>

                                        <p
                                            class="mt-1 text-xl font-black text-amber-200"
                                        >
                                            {{
                                                formatPrice(
                                                    watch.swiss_promo_price ??
                                                        watch.swiss_price,
                                                )
                                            }}
                                        </p>

                                        <p
                                            class="mt-3 text-[9px] tracking-wider text-zinc-600 uppercase"
                                        >
                                            {{ translations.collection.choose }}
                                        </p>
                                    </Link>
                                </div>

                                <p
                                    class="mt-5 line-clamp-2 text-center text-sm leading-6 text-zinc-500"
                                >
                                    {{ watch.description }}
                                </p>

                                <div
                                    class="mt-5 flex items-center justify-between border-t border-white/10 pt-4 text-xs"
                                >
                                    <span class="text-zinc-600">
                                        {{ translations.collection.delivery }}
                                    </span>

                                    <span class="font-semibold text-zinc-300">
                                        {{ translations.collection.delay }}
                                    </span>
                                </div>

                                <Link
                                    :href="`${localizedRoutes.watches}/${watch.id}`"
                                    class="mt-6 flex w-full items-center justify-center gap-3 rounded-lg border border-amber-300/50 bg-black px-4 py-4 text-xs font-black tracking-[0.12em] text-white uppercase shadow-[0_0_20px_rgba(251,191,36,0.05)] transition duration-300 hover:border-amber-300 hover:bg-amber-300 hover:text-black hover:shadow-[0_0_30px_rgba(251,191,36,0.16)]"
                                >
                                    {{ translations.collection.view_watch }}

                                    <span> → </span>
                                </Link>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- COMMENT COMMANDER -->
            <!-- ========================================================= -->

            <OrderSteps />

            <!-- ========================================================= -->
            <!-- CONCEPT -->
            <!-- ========================================================= -->

            <section
                id="concept"
                class="scroll-mt-24 px-5 pb-8 sm:px-6 lg:px-10"
            >
                <div
                    class="reveal-on-scroll mx-auto max-w-[1500px] overflow-hidden rounded-2xl border border-amber-300/30 bg-gradient-to-r from-amber-300/[0.06] via-zinc-950 to-black shadow-[0_0_60px_rgba(251,191,36,0.05)]"
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
                                class="mt-3 text-2xl font-black uppercase sm:text-3xl"
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
                            @click="scrollToCollection"
                            class="rounded-lg border border-amber-300/50 px-6 py-4 text-xs font-black tracking-[0.15em] text-amber-200 uppercase transition hover:bg-amber-300 hover:text-black"
                        >
                            {{ translations.concept.cta }}
                        </button>
                    </div>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- SERVICES -->
            <!-- ========================================================= -->

            <section
                id="services"
                class="scroll-mt-24 px-5 py-12 sm:px-6 lg:px-10"
            >
                <div
                    class="reveal-on-scroll mx-auto grid max-w-[1500px] overflow-hidden rounded-2xl border border-white/10 bg-zinc-950/70 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        class="border-b border-white/10 p-7 sm:border-r lg:border-b-0"
                    >
                        <div class="text-2xl text-amber-300">◇</div>

                        <h3
                            class="mt-4 text-sm font-black tracking-wider uppercase"
                        >
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

                        <h3
                            class="mt-4 text-sm font-black tracking-wider uppercase"
                        >
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

                        <h3
                            class="mt-4 text-sm font-black tracking-wider uppercase"
                        >
                            {{ translations.services.delivery_title }}
                        </h3>

                        <p class="mt-2 text-xs leading-5 text-zinc-500">
                            {{ translations.services.delivery_text }}
                        </p>
                    </div>

                    <div class="p-7">
                        <div class="text-2xl text-amber-300">◷</div>

                        <h3
                            class="mt-4 text-sm font-black tracking-wider uppercase"
                        >
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

        <!-- ========================================================= -->
        <!-- FOOTER -->
        <!-- ========================================================= -->

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

                <p class="text-xs text-zinc-700">
                    {{ translations.footer.copyright }}
                </p>
            </div>
        </footer>
    </div>
</template>
