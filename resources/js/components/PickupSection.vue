<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const translations = computed(() => page.props.translations);
</script>

<template>
    <section
        id="pickup"
        class="relative scroll-mt-24 px-5 py-20 sm:px-6 lg:px-10"
    >
        <div
            class="vvs-luxury-card mx-auto max-w-[1350px] overflow-hidden rounded-3xl border"
        >
            <div
                class="grid gap-10 p-7 sm:p-10 lg:grid-cols-[1.05fr_0.95fr] lg:p-14"
            >
                <!-- TEXTE -->

                <div>
                    <p class="vvs-eyebrow">
                        {{ translations.pickup.eyebrow }}
                    </p>

                    <h2
                        class="vvs-display-title mt-5 max-w-3xl text-5xl sm:text-6xl"
                    >
                        {{ translations.pickup.title_before }}

                        <span class="vvs-gradient-text">
                            {{ translations.pickup.title_highlight }}
                        </span>
                    </h2>

                    <p class="mt-7 max-w-2xl text-base leading-7 text-zinc-400">
                        {{ translations.pickup.description_1 }}
                    </p>

                    <p class="mt-4 max-w-2xl text-sm leading-6 text-zinc-500">
                        {{ translations.pickup.description_2 }}
                    </p>
                </div>

                <!-- ÉTAPES -->

                <div class="grid gap-4">
                    <div
                        class="vvs-choice-card flex gap-5 rounded-2xl border p-5"
                    >
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-amber-300/30 font-black text-amber-200"
                        >
                            01
                        </div>

                        <div>
                            <p class="font-bold">
                                {{ translations.pickup.step_1_title }}
                            </p>

                            <p class="mt-1 text-sm leading-6 text-zinc-500">
                                {{ translations.pickup.step_1_text }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="vvs-choice-card flex gap-5 rounded-2xl border p-5"
                    >
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-amber-300/30 font-black text-amber-200"
                        >
                            02
                        </div>

                        <div>
                            <p class="font-bold">
                                {{ translations.pickup.step_2_title }}
                            </p>

                            <p class="mt-1 text-sm leading-6 text-zinc-500">
                                {{ translations.pickup.step_2_text }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="vvs-choice-card vvs-choice-card--featured flex gap-5 rounded-2xl border p-5"
                    >
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-amber-300 font-black text-black"
                        >
                            03
                        </div>

                        <div>
                            <p class="font-bold">
                                {{ translations.pickup.step_3_title }}
                            </p>

                            <p class="mt-1 text-sm leading-6 text-zinc-500">
                                {{ translations.pickup.step_3_text }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between rounded-xl border border-white/10 px-5 py-4"
                    >
                        <span class="text-sm text-zinc-500">
                            {{ translations.pickup.delay_label }}
                        </span>

                        <span class="text-sm font-bold text-zinc-200">
                            {{ translations.pickup.delay }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
