<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const translations = computed(() => page.props.translations);
</script>

<template>
    <section
        id="about"
        class="relative scroll-mt-24 overflow-hidden px-5 py-24 sm:px-6 lg:px-10"
    >
        <div
            class="pointer-events-none absolute top-1/2 left-1/2 -z-10 h-[500px] w-[900px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.035] blur-[140px]"
        ></div>

        <div
            class="mx-auto grid max-w-[1350px] gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:items-center"
        >
            <!-- IDENTITÉ -->

            <div>
                <div class="mb-5 flex items-center gap-3">
                    <span class="h-px w-10 bg-amber-300"></span>

                    <span class="vvs-eyebrow">
                        {{ translations.about.eyebrow }}
                    </span>
                </div>

                <h2 class="vvs-display-title text-5xl sm:text-6xl lg:text-7xl">
                    {{ translations.about.title_before }}
                    <span class="vvs-gradient-text">
                        {{ translations.about.title_highlight }}
                    </span>

                    <br />

                    {{ translations.about.title_after }}
                </h2>

                <div
                    class="mt-8 flex h-8 w-14 overflow-hidden rounded border border-white/15"
                >
                    <span class="flex-1 bg-black"></span>
                    <span class="flex-1 bg-yellow-400"></span>
                    <span class="flex-1 bg-red-600"></span>
                </div>
            </div>

            <!-- TEXTE -->

            <div class="vvs-luxury-card rounded-3xl border p-7 sm:p-9">
                <p class="text-lg leading-8 text-zinc-300">
                    {{ translations.about.paragraph_1 }}
                </p>

                <p class="mt-5 leading-7 text-zinc-500">
                    {{ translations.about.paragraph_2 }}
                </p>

                <p class="mt-5 leading-7 text-zinc-500">
                    {{ translations.about.paragraph_3_before }}
                    <strong class="font-semibold text-zinc-300">
                        {{ translations.about.paragraph_3_strong }} </strong
                    >{{ translations.about.paragraph_3_after }}
                </p>

                <div class="mt-8 grid gap-3 sm:grid-cols-3">
                    <div class="vvs-choice-card rounded-xl border p-4">
                        <p class="vvs-price text-xl font-black">VVS</p>

                        <p
                            class="mt-1 text-[10px] tracking-wider text-zinc-500 uppercase"
                        >
                            {{ translations.about.purity }}
                        </p>
                    </div>

                    <div class="vvs-choice-card rounded-xl border p-4">
                        <p class="vvs-price text-xl font-black">D</p>

                        <p
                            class="mt-1 text-[10px] tracking-wider text-zinc-500 uppercase"
                        >
                            {{ translations.about.color }}
                        </p>
                    </div>

                    <div class="vvs-choice-card rounded-xl border p-4">
                        <p class="vvs-price text-xl font-black">BE</p>

                        <p
                            class="mt-1 text-[10px] tracking-wider text-zinc-500 uppercase"
                        >
                            {{ translations.about.belgian_project }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
