<script setup>
import { usePage } from '@inertiajs/vue3';

const emit = defineEmits(['scroll-to-collection']);

const page = usePage();
const translations = page.props.translations;
const hero = page.props.landingCopy.hero;
</script>

<template>
    <section
        class="vvs-brand-hero vvs-brand-hero--intro relative isolate overflow-hidden border-b border-white/10 bg-[#050505]"
    >
        <div
            class="relative mx-auto flex min-h-[610px] max-w-[1500px] items-center justify-center px-5 py-20 text-center sm:min-h-[680px] sm:px-8 sm:py-24 lg:min-h-[720px] lg:px-10"
        >
            <div
                aria-hidden="true"
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_50%_42%,rgba(190,158,95,0.12),transparent_42%)]"
            ></div>

            <div
                class="relative z-10 mx-auto flex w-full max-w-5xl flex-col items-center"
            >
                <p
                    class="vvs-eyebrow max-w-full text-[10px] tracking-[0.16em] sm:tracking-[0.28em]"
                >
                    {{ hero.badge_stone }} · {{ hero.badge_style }} ·
                    {{ hero.badge_culture }}
                </p>

                <h1
                    class="vvs-brand-wordmark mt-8 w-full max-w-full"
                    aria-label="VVS FLAWLESS"
                >
                    <span class="vvs-brand-wordmark__vvs">VVS</span>
                    <span class="vvs-brand-wordmark__flawless"> FLAWLESS </span>
                </h1>

                <p
                    class="vvs-display-title mt-9 max-w-4xl text-[clamp(2rem,6vw,4.5rem)] leading-[1.02] text-[#f5f0e7]"
                >
                    {{ translations.hero.tagline }}
                </p>

                <p
                    class="vvs-body-copy mx-auto mt-6 max-w-2xl text-sm text-zinc-400 sm:text-base lg:text-lg"
                >
                    {{ translations.hero.description }}
                </p>

                <div
                    class="mt-9 flex w-full max-w-xl flex-col items-stretch justify-center gap-3 sm:flex-row sm:items-center"
                >
                    <button
                        type="button"
                        class="vvs-button-primary min-h-12 rounded-xl px-7 py-4 text-sm font-bold tracking-[0.1em] uppercase"
                        @click="emit('scroll-to-collection')"
                    >
                        {{ translations.concept.cta }}
                    </button>

                    <a
                        href="#concept"
                        class="vvs-button-secondary inline-flex min-h-12 items-center justify-center rounded-xl px-7 py-4 text-sm font-semibold"
                    >
                        {{ translations.hero.concept }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
