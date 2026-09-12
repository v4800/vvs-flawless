<script setup>
import { usePage } from '@inertiajs/vue3';

const emit = defineEmits(['scroll-to-collection']);

const page = usePage();
const translations = page.props.translations;
const hero = page.props.landingCopy.hero;
</script>

<template>
    <section
        class="relative isolate overflow-hidden border-b border-amber-400/20"
    >
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
                    >
                        {{ hero.badge_stone }}
                    </span>
                    <span class="text-amber-500">•</span>
                    <span
                        class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                    >
                        {{ hero.badge_style }}
                    </span>
                    <span class="text-amber-500">•</span>
                    <span
                        class="text-xs font-semibold tracking-[0.25em] text-amber-300 uppercase"
                    >
                        {{ hero.badge_culture }}
                    </span>
                </div>

                <h1
                    class="hero-animate relative max-w-3xl overflow-hidden text-[clamp(3rem,17vw,4rem)] leading-[0.82] font-semibold tracking-[-0.055em] uppercase sm:text-[5.5rem] lg:text-[7rem]"
                >
                    <span class="vvs-gradient-text vvs-gradient-text--hero">
                        VVS
                    </span>
                    <br />
                    <span class="vvs-gradient-text vvs-gradient-text--hero">
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

                <div class="hero-animate mt-8 flex max-w-xl items-center gap-4">
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
                            {{ hero.stone_title }}
                        </p>
                        <p class="text-[10px] text-zinc-500">
                            {{ hero.stone_note }}
                        </p>
                    </div>
                    <div class="vvs-choice-card rounded-xl border p-3">
                        <div class="text-lg text-amber-300">✦</div>
                        <p
                            class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                        >
                            {{ hero.color_title }}
                        </p>
                        <p class="text-[10px] text-zinc-500">
                            {{ hero.color_note }}
                        </p>
                    </div>
                    <div class="vvs-choice-card rounded-xl border p-3">
                        <div class="text-lg text-amber-300">◷</div>
                        <p
                            class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                        >
                            {{ hero.lead_time_title }}
                        </p>
                        <p class="text-[10px] text-zinc-500">
                            {{ hero.lead_time_note }}
                        </p>
                    </div>
                    <div class="vvs-choice-card rounded-xl border p-3">
                        <div class="text-lg text-amber-300">↗</div>
                        <p
                            class="mt-2 text-[10px] font-bold tracking-wider uppercase"
                        >
                            {{ hero.markets_title }}
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
                        @click="emit('scroll-to-collection')"
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
                            :alt="hero.image_alt"
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
                                    {{ hero.signature_material }}
                                </p>
                                <p
                                    class="vvs-display-title mt-2 text-3xl leading-none text-white sm:text-4xl"
                                >
                                    {{ hero.signature_title }}
                                </p>
                            </div>

                            <div
                                class="flex items-center gap-2 text-[9px] font-bold tracking-wider uppercase"
                            >
                                <span
                                    class="rounded-full border border-white/10 bg-black/55 px-3 py-2 text-zinc-300 backdrop-blur-md"
                                >
                                    {{ hero.lead_time_title }}
                                </span>
                                <span
                                    class="rounded-full border border-amber-300/20 bg-black/55 px-3 py-2 text-amber-200 backdrop-blur-md"
                                >
                                    {{ hero.markets_title }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
