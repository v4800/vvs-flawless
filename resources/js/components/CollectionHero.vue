<script setup>
import { usePage } from '@inertiajs/vue3';

const emit = defineEmits(['scroll-to-collection']);

const page = usePage();
const translations = page.props.translations;
const hero = page.props.landingCopy.hero;

</script>

<template>
    <section
        class="relative isolate overflow-hidden border-b border-white/10 bg-[#060606]"
    >
        <div class="absolute top-0 right-0 hidden h-full w-1.5 lg:flex">
            <div class="h-full flex-1 bg-black"></div>
            <div class="h-full flex-1 bg-yellow-400"></div>
            <div class="h-full flex-1 bg-red-600"></div>
        </div>

        <div
            class="mx-auto grid min-h-[650px] max-w-[1500px] items-center gap-12 px-6 py-16 lg:grid-cols-[0.92fr_1.08fr] lg:px-10 lg:py-20"
        >
            <div class="relative z-10">
                <p
                    class="hero-animate text-xs font-bold tracking-[0.24em] text-amber-300 uppercase"
                >
                    {{ hero.badge_stone }} · {{ hero.badge_style }} ·
                    {{ hero.badge_culture }}
                </p>

                <div
                    class="hero-animate mt-7 w-full min-w-0 max-w-[560px]"
                >
                    <div
                        class="aspect-[2.2/1] w-full overflow-hidden"
                    >
                        <img
                            src="/images/branding/vvs-flawless-logo.svg"
                            alt="VVS FLAWLESS — Depuis 2026"
                            width="1280"
                            height="1024"
                            decoding="async"
                            class="block h-full w-full object-cover object-center"
                        />
                    </div>
                </div>

                <p
                    class="hero-animate vvs-body-copy mt-6 max-w-xl text-base text-zinc-400 sm:text-lg"
                >
                    {{ translations.hero.description }}
                </p>

                <div class="hero-animate mt-9 flex min-w-0 max-w-full flex-col items-start gap-4 sm:flex-row sm:items-center sm:gap-6">
                    <button
                        type="button"
                        class="vvs-button-primary rounded-xl px-7 py-4 text-sm font-bold tracking-[0.1em] uppercase"
                        @click="emit('scroll-to-collection')"
                    >
                        {{ translations.concept.cta }}
                    </button>

                    <a
                        href="#concept"
                        class="max-w-full break-words rounded text-sm font-semibold text-zinc-400 underline decoration-white/20 underline-offset-4 transition hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-300"
                    >
                        {{ translations.hero.concept }}
                    </a>
                </div>
            </div>

            <div
                class="relative flex min-h-[440px] items-center justify-center lg:min-h-[600px]"
            >
                <div
                    class="hero-watch relative z-10 w-full max-w-[760px] overflow-hidden rounded-[24px] border border-white/10 bg-black shadow-[0_30px_80px_rgba(0,0,0,0.55)]"
                >
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img
                            src="/images/vvs-watch-hero.webp"
                            :alt="hero.image_alt"
                            width="1536"
                            height="1024"
                            fetchpriority="high"
                            decoding="async"
                            class="block h-full w-full object-cover object-center"
                        />

                        <div
                            class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-transparent"
                        ></div>

                        <div
                            class="absolute right-0 bottom-0 left-0 p-5 sm:p-7"
                        >
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
