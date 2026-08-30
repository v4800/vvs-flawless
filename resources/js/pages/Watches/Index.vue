<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {
    computed,
    onBeforeUnmount,
    onMounted,
    ref,
} from 'vue';

import {
    animate,
    createTimeline,
    stagger,
} from 'animejs';

import StockBadge from '@/components/StockBadge.vue';
import OrderSteps from '@/components/OrderSteps.vue';
import AboutSection from '@/components/AboutSection.vue';
import PickupSection from '@/components/PickupSection.vue';
import FaqSection from '@/components/FaqSection.vue';
import ContactSection from '@/components/ContactSection.vue';

const props = defineProps({
    watches: {
        type: Array,
        default: () => [],
    },
});

const mobileMenuOpen = ref(false);

const heroWatch = computed(() => {
    return (
        props.watches.find(
            (watch) => watch.image
        )
        ?? props.watches[0]
        ?? null
    );
});

const formatPrice = (price) => {
    if (!price) {
        return '—';
    }

    return `${Number(price).toFixed(0)} €`;
};

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
};

let revealObserver = null;

onMounted(() => {
    const prefersReducedMotion =
        window.matchMedia(
            '(prefers-reduced-motion: reduce)'
        ).matches;

    if (prefersReducedMotion) {
        document
            .querySelectorAll(
                '.hero-animate, .hero-watch, .reveal-on-scroll'
            )
            .forEach((element) => {
                element.style.opacity = '1';
                element.style.transform = 'none';
            });

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | HERO
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
            duration: 650,
        })
        .add(
            '.hero-animate',
            {
                opacity: [0, 1],
                y: [26, 0],
                duration: 850,
                delay: stagger(80),
            },
            '-=350'
        )
        .add(
            '.hero-watch',
            {
                opacity: [0, 1],
                x: [55, 0],
                scale: [0.92, 1],
                duration: 1200,
            },
            '-=800'
        );

    /*
    |--------------------------------------------------------------------------
    | MONTRE HERO
    |--------------------------------------------------------------------------
    */

    animate('.hero-watch', {
        y: [0, -9],
        duration: 3400,
        delay: 1400,
        loop: true,
        alternate: true,
        ease: 'inOutQuad',
    });

    /*
    |--------------------------------------------------------------------------
    | REFLET LOGO
    |--------------------------------------------------------------------------
    */

    animate('.brand-shine', {
        x: ['0%', '560%'],
        opacity: [0, 0.75, 0],
        duration: 1700,
        delay: 1000,
        loop: true,
        loopDelay: 3200,
        ease: 'inOutQuad',
    });

    /*
    |--------------------------------------------------------------------------
    | ÉCLATS
    |--------------------------------------------------------------------------
    */

    animate('.bling-sparkle', {
        opacity: [0.08, 0.9, 0.08],
        scale: [0.4, 1.4, 0.4],
        rotate: [0, 45, 90],
        duration: 1600,
        delay: stagger(260),
        loop: true,
        loopDelay: 500,
        ease: 'inOutQuad',
    });

    /*
    |--------------------------------------------------------------------------
    | APPARITION DES SECTIONS
    |--------------------------------------------------------------------------
    */

    const elements =
        document.querySelectorAll(
            '.reveal-on-scroll'
        );

    elements.forEach((element) => {
        element.style.opacity = '0';
    });

    revealObserver =
        new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    const element =
                        entry.target;

                    animate(element, {
                        opacity: [0, 1],
                        y: [30, 0],
                        scale: [0.98, 1],
                        duration: 850,
                        ease: 'outExpo',
                    });

                    revealObserver?.unobserve(
                        element
                    );
                });
            },
            {
                threshold: 0.1,
                rootMargin:
                    '0px 0px -30px 0px',
            }
        );

    elements.forEach((element) => {
        revealObserver.observe(element);
    });
});

onBeforeUnmount(() => {
    revealObserver?.disconnect();
});
</script>

<template>
    <Head
        title="VVS FLAWLESS — Montres Iced-Out Belgique"
    />

    <div
        class="min-h-screen overflow-x-hidden bg-black text-white"
    >

        <!-- ========================================================= -->
        <!-- HEADER -->
        <!-- ========================================================= -->

        <header
            class="site-header sticky top-0 z-50 border-b border-white/10 bg-black/95 backdrop-blur-xl"
        >
            <div
                class="mx-auto flex h-[72px] max-w-[1500px] items-center justify-between px-5 md:h-20 md:px-8"
            >

                <!-- LOGO -->

                <a
                    href="/watches"
                    class="flex flex-col leading-none"
                    @click="closeMobileMenu"
                >
                    <span
                        class="text-[19px] font-black tracking-[0.06em] text-white sm:text-xl md:text-2xl"
                    >
                        VVS FLAWLESS
                    </span>

                    <span
                        class="mt-1.5 text-[8px] font-bold uppercase tracking-[0.4em] text-amber-300/90"
                    >
                        Belgium
                    </span>
                </a>

                <!-- NAVIGATION DESKTOP -->

                <nav
                    class="hidden items-center gap-8 text-sm font-medium text-zinc-400 md:flex"
                    aria-label="Navigation principale"
                >
                    <a
                        href="#collection"
                        class="transition hover:text-amber-300"
                    >
                        Montres
                    </a>

                    <a
                        href="#about"
                        class="transition hover:text-amber-300"
                    >
                        À propos
                    </a>

                    <a
                        href="#pickup"
                        class="transition hover:text-amber-300"
                    >
                        Remise
                    </a>

                    <a
                        href="#faq"
                        class="transition hover:text-amber-300"
                    >
                        FAQ
                    </a>

                    <a
                        href="#contact"
                        class="transition hover:text-amber-300"
                    >
                        Contact
                    </a>
                </nav>

                <!-- DROITE -->

                <div
                    class="flex items-center gap-3"
                >
                    <span
                        class="hidden text-xs uppercase tracking-[0.2em] text-zinc-600 lg:block"
                    >
                        Belgique
                    </span>

                    <!-- DRAPEAU -->

                    <div
                        class="flex h-7 overflow-hidden rounded border border-white/20"
                        aria-label="Belgique"
                    >
                        <span
                            class="w-2.5 bg-black"
                        ></span>

                        <span
                            class="w-2.5 bg-yellow-400"
                        ></span>

                        <span
                            class="w-2.5 bg-red-600"
                        ></span>
                    </div>

                    <!-- MENU MOBILE -->

                    <button
                        type="button"
                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/[0.025] md:hidden"
                        :aria-expanded="mobileMenuOpen"
                        aria-label="Ouvrir le menu"
                        @click="
                            mobileMenuOpen =
                                !mobileMenuOpen
                        "
                    >
                        <div
                            class="flex w-4 flex-col gap-[4px]"
                        >
                            <span
                                :class="[
                                    'h-px w-full bg-white transition duration-300',

                                    mobileMenuOpen
                                        ? 'translate-y-[5px] rotate-45'
                                        : ''
                                ]"
                            ></span>

                            <span
                                :class="[
                                    'h-px w-full bg-white transition duration-300',

                                    mobileMenuOpen
                                        ? 'opacity-0'
                                        : ''
                                ]"
                            ></span>

                            <span
                                :class="[
                                    'h-px w-full bg-white transition duration-300',

                                    mobileMenuOpen
                                        ? '-translate-y-[5px] -rotate-45'
                                        : ''
                                ]"
                            ></span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- ===================================================== -->
            <!-- MENU MOBILE -->
            <!-- ===================================================== -->

            <div
                class="overflow-hidden border-white/10 bg-black transition-all duration-300 md:hidden"
                :class="
                    mobileMenuOpen
                        ? 'max-h-[380px] border-t opacity-100'
                        : 'max-h-0 border-t-0 opacity-0'
                "
            >
                <nav
                    class="px-5 py-5"
                    aria-label="Navigation mobile"
                >
                    <div
                        class="grid gap-1"
                    >
                        <a
                            href="#collection"
                            class="flex items-center justify-between rounded-xl px-4 py-3.5 text-sm font-bold text-zinc-300 transition hover:bg-white/[0.04] hover:text-amber-300"
                            @click="closeMobileMenu"
                        >
                            Montres
                            <span class="text-zinc-700">
                                ↓
                            </span>
                        </a>

                        <a
                            href="#about"
                            class="flex items-center justify-between rounded-xl px-4 py-3.5 text-sm font-bold text-zinc-300 transition hover:bg-white/[0.04] hover:text-amber-300"
                            @click="closeMobileMenu"
                        >
                            À propos
                            <span class="text-zinc-700">
                                ↓
                            </span>
                        </a>

                        <a
                            href="#pickup"
                            class="flex items-center justify-between rounded-xl px-4 py-3.5 text-sm font-bold text-zinc-300 transition hover:bg-white/[0.04] hover:text-amber-300"
                            @click="closeMobileMenu"
                        >
                            Remise
                            <span class="text-zinc-700">
                                ↓
                            </span>
                        </a>

                        <a
                            href="#faq"
                            class="flex items-center justify-between rounded-xl px-4 py-3.5 text-sm font-bold text-zinc-300 transition hover:bg-white/[0.04] hover:text-amber-300"
                            @click="closeMobileMenu"
                        >
                            FAQ
                            <span class="text-zinc-700">
                                ↓
                            </span>
                        </a>

                        <a
                            href="#contact"
                            class="flex items-center justify-between rounded-xl px-4 py-3.5 text-sm font-bold text-zinc-300 transition hover:bg-white/[0.04] hover:text-amber-300"
                            @click="closeMobileMenu"
                        >
                            Contact
                            <span class="text-zinc-700">
                                ↓
                            </span>
                        </a>
                    </div>
                </nav>
            </div>
        </header>

        <main>

            <!-- ========================================================= -->
            <!-- HERO -->
            <!-- ========================================================= -->

            <section
                class="relative isolate overflow-hidden border-b border-white/10"
            >
                <div
                    class="absolute inset-0 -z-30 bg-black"
                ></div>

                <div
                    class="absolute -left-48 top-10 -z-20 h-[420px] w-[420px] rounded-full bg-amber-500/[0.07] blur-[140px] sm:h-[500px] sm:w-[500px]"
                ></div>

                <div
                    class="absolute right-0 top-0 -z-20 h-[500px] w-[500px] rounded-full bg-white/[0.025] blur-[150px] lg:h-[650px] lg:w-[650px]"
                ></div>

                <!-- ÉCLATS -->

                <span
                    class="bling-sparkle absolute left-[8%] top-[19%] hidden text-xl text-amber-200 sm:block"
                >
                    ✦
                </span>

                <span
                    class="bling-sparkle absolute right-[9%] top-[18%] text-lg text-white/60 sm:text-2xl"
                >
                    ✦
                </span>

                <div
                    class="mx-auto grid max-w-[1500px] gap-4 px-6 pb-16 pt-14 sm:px-7 sm:pb-20 sm:pt-16 lg:min-h-[680px] lg:grid-cols-[0.95fr_1.05fr] lg:items-center lg:gap-10 lg:px-10 lg:py-16"
                >

                    <!-- HERO TEXTE -->

                    <div
                        class="relative z-10"
                    >
                        <p
                            class="hero-animate text-[10px] font-black uppercase tracking-[0.3em] text-amber-300 sm:text-xs"
                        >
                            Moissanite VVS

                            <span
                                class="mx-1 text-amber-500/60"
                            >
                                •
                            </span>

                            Couleur D
                        </p>

                        <!-- TITRE -->

                        <h1
                            class="hero-animate relative mt-6 max-w-3xl overflow-hidden text-[3.05rem] font-black uppercase leading-[0.84] tracking-[-0.055em] min-[380px]:text-[3.35rem] sm:text-[5.5rem] lg:text-[7rem]"
                        >
                            <span
                                class="bg-gradient-to-b from-white via-zinc-100 to-zinc-500 bg-clip-text text-transparent"
                            >
                                VVS
                            </span>

                            <br />

                            <span
                                class="bg-gradient-to-b from-white via-zinc-200 to-zinc-600 bg-clip-text text-transparent"
                            >
                                FLAWLESS
                            </span>

                            <span
                                class="brand-shine pointer-events-none absolute -left-[30%] top-0 h-full w-[18%] -skew-x-12 bg-gradient-to-r from-transparent via-white/70 to-transparent blur-sm"
                            ></span>
                        </h1>

                        <!-- SIGNATURE -->

                        <p
                            class="hero-animate mt-8 max-w-xl text-[1.45rem] font-medium italic leading-[1.2] text-amber-200 sm:text-3xl"
                        >
                            La culture bustdown arrive
                            en Belgique

                            <span
                                class="not-italic"
                            >
                                🇧🇪
                            </span>
                        </p>

                        <!-- DESCRIPTION -->

                        <p
                            class="hero-animate mt-7 max-w-xl text-sm leading-7 text-zinc-400 sm:text-base"
                        >
                            Montres iced-out serties de
                            moissanite VVS couleur D,
                            proposées avec plusieurs
                            configurations de mouvement.
                        </p>

                        <!-- CTA -->

                        <div
                            class="hero-animate mt-8 grid gap-3 sm:flex sm:flex-wrap sm:gap-4"
                        >
                            <a
                                href="#collection"
                                class="flex w-full items-center justify-center rounded-xl bg-amber-300 px-7 py-4 text-xs font-black uppercase tracking-[0.1em] text-black transition hover:-translate-y-1 hover:bg-amber-200 sm:w-auto"
                            >
                                Voir la collection
                            </a>

                            <a
                                href="#commander"
                                class="flex w-full items-center justify-center rounded-xl border border-white/15 bg-white/[0.025] px-7 py-4 text-xs font-black uppercase tracking-[0.1em] text-white transition hover:-translate-y-1 hover:border-amber-300/50 hover:text-amber-200 sm:w-auto"
                            >
                                Comment commander
                            </a>
                        </div>

                        <!-- QUALITÉ -->

                        <div
                            class="hero-animate mt-8 grid grid-cols-3 gap-2 sm:max-w-xl sm:gap-3"
                        >
                            <div
                                class="rounded-xl border border-white/[0.08] bg-white/[0.018] px-2 py-3 text-center"
                            >
                                <p
                                    class="text-xs font-black text-amber-200"
                                >
                                    VVS
                                </p>

                                <p
                                    class="mt-1 text-[7px] font-bold uppercase tracking-[0.12em] text-zinc-600 sm:text-[8px]"
                                >
                                    Pureté
                                </p>
                            </div>

                            <div
                                class="rounded-xl border border-white/[0.08] bg-white/[0.018] px-2 py-3 text-center"
                            >
                                <p
                                    class="text-xs font-black text-amber-200"
                                >
                                    D
                                </p>

                                <p
                                    class="mt-1 text-[7px] font-bold uppercase tracking-[0.12em] text-zinc-600 sm:text-[8px]"
                                >
                                    Couleur
                                </p>
                            </div>

                            <div
                                class="rounded-xl border border-white/[0.08] bg-white/[0.018] px-2 py-3 text-center"
                            >
                                <p
                                    class="text-xs font-black text-amber-200"
                                >
                                    BE
                                </p>

                                <p
                                    class="mt-1 text-[7px] font-bold uppercase tracking-[0.12em] text-zinc-600 sm:text-[8px]"
                                >
                                    Main propre
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- HERO IMAGE -->

                    <div
                        class="relative mt-8 flex min-h-[320px] items-center justify-center sm:min-h-[430px] lg:mt-0 lg:min-h-[590px]"
                    >
                        <div
                            class="absolute h-[280px] w-[280px] rounded-full bg-amber-300/[0.07] blur-[80px] sm:h-[450px] sm:w-[450px] sm:blur-[100px]"
                        ></div>

                        <div
                            class="absolute h-[85%] w-[85%] rounded-full border border-white/[0.04] sm:h-[80%] sm:w-[80%]"
                        ></div>

                        <img
                            v-if="heroWatch?.image"
                            :src="heroWatch.image"
                            :alt="heroWatch.name"
                            class="hero-watch relative z-10 max-h-[360px] w-full max-w-[390px] object-contain drop-shadow-[0_30px_60px_rgba(0,0,0,0.8)] sm:max-h-[500px] sm:max-w-[520px] lg:max-h-[620px] lg:max-w-[650px]"
                        />

                        <div
                            v-else
                            class="relative z-10 flex h-72 w-full items-center justify-center rounded-3xl border border-white/10 bg-zinc-950 text-zinc-600"
                        >
                            VVS FLAWLESS
                        </div>
                    </div>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- COLLECTION -->
            <!-- ========================================================= -->

            <section
                id="collection"
                class="relative scroll-mt-20 overflow-hidden px-4 py-20 sm:px-6 sm:py-24 lg:px-10"
            >
                <div
                    class="absolute left-1/2 top-20 -z-10 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-amber-400/[0.025] blur-[150px]"
                ></div>

                <div
                    class="mx-auto max-w-[1500px]"
                >

                    <!-- TITRE -->

                    <header
                        class="reveal-on-scroll mb-10 text-center sm:mb-12"
                    >
                        <p
                            class="text-[9px] font-black uppercase tracking-[0.4em] text-amber-300 sm:text-[10px]"
                        >
                            Notre collection
                        </p>

                        <h2
                            class="mt-4 text-3xl font-black uppercase tracking-[-0.04em] sm:text-5xl"
                        >
                            Choisis ta

                            <span
                                class="text-amber-300"
                            >
                                pièce
                            </span>
                        </h2>

                        <p
                            class="mx-auto mt-5 max-w-2xl text-sm leading-6 text-zinc-500"
                        >
                            Moissanite VVS • Couleur D.
                            Sélectionne ton modèle puis
                            ton mouvement.
                        </p>
                    </header>

                    <!-- PRODUITS -->

                    <div
                        class="grid gap-5 sm:grid-cols-2 sm:gap-6 xl:grid-cols-3"
                    >
                        <article
                            v-for="watch in watches"
                            :key="watch.id"
                            class="reveal-on-scroll group relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-b from-zinc-950 to-black transition duration-500 hover:-translate-y-2 hover:border-amber-300/40 hover:shadow-[0_20px_70px_rgba(251,191,36,0.08)]"
                        >

                            <!-- ================================================= -->
                            <!-- IMAGE CLIQUABLE PC + MOBILE -->
                            <!-- ================================================= -->

                            <Link
                                :href="`/watches/${watch.id}`"
                                class="group/image relative block h-[310px] cursor-pointer overflow-hidden bg-zinc-950 sm:h-[350px] lg:h-[390px]"
                                :aria-label="`Voir ${watch.name}`"
                            >

                                <!-- STOCK -->

                                <div
                                    class="pointer-events-none absolute left-3 top-3 z-20 sm:left-4 sm:top-4"
                                >
                                    <StockBadge
                                        :quantity="watch.stock_quantity"
                                    />
                                </div>

                                <!-- IMAGE -->

                                <img
                                    v-if="watch.image"
                                    :src="watch.image"
                                    :alt="watch.name"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition duration-700 group-hover/image:scale-[1.055]"
                                />

                                <div
                                    v-else
                                    class="flex h-full items-center justify-center text-zinc-600"
                                >
                                    Image bientôt
                                </div>

                                <!-- LÉGER ASSOMBRISSEMENT AU SURVOL -->

                                <div
                                    class="pointer-events-none absolute inset-0 bg-black/0 transition duration-500 group-hover/image:bg-black/10"
                                ></div>

                                <!-- REFLET -->

                                <div
                                    class="pointer-events-none absolute -left-1/2 top-0 z-10 h-full w-1/3 -skew-x-12 bg-gradient-to-r from-transparent via-white/25 to-transparent opacity-0 transition-all duration-700 group-hover/image:left-[120%] group-hover/image:opacity-100"
                                ></div>

                                <!-- INDICATION PC -->

                                <div
                                    class="pointer-events-none absolute inset-x-0 bottom-0 z-20 hidden translate-y-full bg-gradient-to-t from-black/95 via-black/60 to-transparent px-5 pb-5 pt-16 transition-transform duration-300 group-hover/image:translate-y-0 md:block"
                                >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-[10px] font-black uppercase tracking-[0.18em] text-white"
                                        >
                                            Voir la montre
                                        </span>

                                        <span
                                            class="text-xl text-amber-300 transition duration-300 group-hover/image:translate-x-1"
                                        >
                                            →
                                        </span>
                                    </div>
                                </div>

                                <!-- PETITE INDICATION MOBILE -->

                                <div
                                    class="pointer-events-none absolute bottom-3 right-3 z-20 flex h-9 w-9 items-center justify-center rounded-full border border-white/15 bg-black/70 text-sm text-amber-300 backdrop-blur-md md:hidden"
                                >
                                    →
                                </div>
                            </Link>

                            <!-- ================================================= -->
                            <!-- CONTENU -->
                            <!-- ================================================= -->

                            <div
                                class="p-5 sm:p-6"
                            >

                                <!-- NOM -->

                                <h3
                                    class="min-h-[50px] text-center text-base font-black uppercase leading-snug sm:min-h-[58px] sm:text-lg"
                                >
                                    {{ watch.name }}
                                </h3>

                                <!-- QUALITÉ -->

                                <p
                                    class="mt-2 text-center text-[9px] font-black uppercase tracking-[0.18em] text-amber-300 sm:text-[10px]"
                                >
                                    Moissanite VVS • D
                                </p>

                                <!-- MOUVEMENTS -->

                                <div
                                    class="mt-5 grid grid-cols-2 gap-2.5 sm:mt-6 sm:gap-3"
                                >

                                    <!-- JAPONAIS -->

                                    <Link
                                        :href="`/watches/${watch.id}?movement=Japonais`"
                                        class="rounded-xl border border-white/10 bg-white/[0.025] p-3.5 transition hover:border-amber-300/40 sm:p-4"
                                    >
                                        <p
                                            class="text-[8px] font-bold uppercase tracking-wider text-zinc-500 sm:text-[9px]"
                                        >
                                            Japonais
                                        </p>

                                        <p
                                            v-if="watch.japanese_price"
                                            class="mt-3 text-[10px] text-zinc-600 line-through sm:text-xs"
                                        >
                                            {{
                                                formatPrice(
                                                    watch.japanese_price
                                                )
                                            }}
                                        </p>

                                        <p
                                            class="mt-1 text-lg font-black text-amber-200 sm:text-xl"
                                        >
                                            {{
                                                formatPrice(
                                                    watch.japanese_promo_price
                                                    ?? watch.japanese_price
                                                )
                                            }}
                                        </p>
                                    </Link>

                                    <!-- SUISSE -->

                                    <Link
                                        :href="`/watches/${watch.id}?movement=Suisse`"
                                        class="rounded-xl border border-amber-300/20 bg-amber-300/[0.03] p-3.5 transition hover:border-amber-300/50 sm:p-4"
                                    >
                                        <p
                                            class="text-[8px] font-bold uppercase tracking-wider text-zinc-500 sm:text-[9px]"
                                        >
                                            Suisse
                                        </p>

                                        <p
                                            v-if="watch.swiss_price"
                                            class="mt-3 text-[10px] text-zinc-600 line-through sm:text-xs"
                                        >
                                            {{
                                                formatPrice(
                                                    watch.swiss_price
                                                )
                                            }}
                                        </p>

                                        <p
                                            class="mt-1 text-lg font-black text-amber-200 sm:text-xl"
                                        >
                                            {{
                                                formatPrice(
                                                    watch.swiss_promo_price
                                                    ?? watch.swiss_price
                                                )
                                            }}
                                        </p>
                                    </Link>
                                </div>

                                <!-- DESCRIPTION -->

                                <p
                                    class="mt-5 line-clamp-2 text-center text-xs leading-5 text-zinc-500 sm:text-sm sm:leading-6"
                                >
                                    {{ watch.description }}
                                </p>

                                <!-- DISPONIBILITÉ -->

                                <div
                                    class="mt-5 flex items-center justify-between gap-3 border-t border-white/10 pt-4 text-[10px] sm:text-xs"
                                >
                                    <span
                                        class="text-zinc-600"
                                    >
                                        Disponibilité
                                    </span>

                                    <span
                                        class="text-right font-semibold text-zinc-300"
                                    >
                                        5–6 jours ouvrables
                                    </span>
                                </div>

                                <!-- BOUTON -->

                                <Link
                                    :href="`/watches/${watch.id}`"
                                    class="mt-5 flex w-full items-center justify-center gap-3 rounded-xl border border-amber-300/40 px-4 py-4 text-[10px] font-black uppercase tracking-[0.1em] transition hover:bg-amber-300 hover:text-black sm:mt-6 sm:text-xs"
                                >
                                    Voir la montre

                                    <span>
                                        →
                                    </span>
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
            <!-- À PROPOS -->
            <!-- ========================================================= -->

            <AboutSection />

            <!-- ========================================================= -->
            <!-- REMISE -->
            <!-- ========================================================= -->

            <PickupSection />

            <!-- ========================================================= -->
            <!-- FAQ -->
            <!-- ========================================================= -->

            <FaqSection />

            <!-- ========================================================= -->
            <!-- CONTACT -->
            <!-- ========================================================= -->

            <ContactSection />

        </main>

        <!-- ========================================================= -->
        <!-- FOOTER -->
        <!-- ========================================================= -->

        <footer
            class="border-t border-white/10 bg-black px-5 py-10 sm:px-6 sm:py-12"
        >
            <div
                class="mx-auto grid max-w-[1500px] gap-7 text-center md:grid-cols-3 md:items-center md:text-left"
            >

                <!-- LOGO -->

                <div>
                    <p
                        class="font-black uppercase tracking-[0.12em] text-white"
                    >
                        VVS FLAWLESS
                    </p>
                </div>

                <!-- NAVIGATION -->

                <div
                    class="flex flex-wrap justify-center gap-x-5 gap-y-3 text-xs text-zinc-600"
                >
                    <a
                        href="#collection"
                        class="transition hover:text-amber-300"
                    >
                        Montres
                    </a>

                    <a
                        href="#about"
                        class="transition hover:text-amber-300"
                    >
                        À propos
                    </a>

                    <a
                        href="#pickup"
                        class="transition hover:text-amber-300"
                    >
                        Remise
                    </a>

                    <a
                        href="#faq"
                        class="transition hover:text-amber-300"
                    >
                        FAQ
                    </a>

                    <a
                        href="#contact"
                        class="transition hover:text-amber-300"
                    >
                        Contact
                    </a>
                </div>

                <!-- COPYRIGHT -->

                <div
                    class="md:text-right"
                >
                    <p
                        class="text-xs text-zinc-700"
                    >
                        © 2026 VVS FLAWLESS
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>