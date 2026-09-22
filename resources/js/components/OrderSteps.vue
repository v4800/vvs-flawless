<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { animate, stagger } from 'animejs';
import { computed, onMounted, ref } from 'vue';

const page = usePage();

const translations = computed(() => page.props.translations);
const guideLinks = computed(() => page.props.guideLinks);
const localizedRoutes = computed(() => page.props.localizedRoutes);

const section = ref(null);

onMounted(() => {
    const prefersReducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    ).matches;

    if (prefersReducedMotion || !section.value) {
        return;
    }

    const observer = new IntersectionObserver(
        ([entry]) => {
            if (!entry.isIntersecting) {
                return;
            }

            const steps = section.value.querySelectorAll('.order-step');

            const line = section.value.querySelector('.order-line-fill');

            animate(steps, {
                opacity: [0, 1],
                y: [35, 0],
                scale: [0.96, 1],
                delay: stagger(130),
                duration: 850,
                ease: 'outExpo',
            });

            if (line) {
                animate(line, {
                    scaleX: [0, 1],
                    duration: 1600,
                    delay: 300,
                    ease: 'outExpo',
                });
            }

            observer.disconnect();
        },
        {
            threshold: 0.25,
        },
    );

    observer.observe(section.value);
});
</script>

<template>
    <section
        ref="section"
        id="commander"
        class="relative overflow-hidden px-5 py-24 sm:px-6 lg:px-10"
    >
        <div
            class="pointer-events-none absolute top-1/2 left-1/2 -z-10 h-[420px] w-[900px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.04] blur-[130px]"
        ></div>

        <div class="mx-auto max-w-[1500px]">
            <div
                class="vvs-luxury-card mx-auto mb-16 max-w-3xl rounded-3xl border p-6 text-center sm:p-8"
            >
                <p
                    class="text-[10px] font-black tracking-[0.3em] text-amber-300 uppercase"
                >
                    {{ guideLinks.eyebrow }}
                </p>

                <h2 class="vvs-display-title mt-3 text-3xl sm:text-4xl">
                    {{ guideLinks.title }}
                </h2>

                <p
                    class="mx-auto mt-4 max-w-xl text-sm leading-7 text-zinc-500"
                >
                    {{ guideLinks.description }}
                </p>

                <Link
                    :href="localizedRoutes.diamondGuide"
                    class="vvs-button-primary mt-6 inline-flex rounded-full px-6 py-3 text-xs font-bold tracking-[0.12em] uppercase"
                >
                    {{ guideLinks.cta }}
                </Link>
            </div>

            <header class="mb-16 text-center">
                <div class="mb-4 flex items-center justify-center gap-3">
                    <span
                        class="h-px w-10 bg-gradient-to-r from-transparent to-amber-300"
                    ></span>

                    <span class="vvs-eyebrow">
                        {{ translations.order.eyebrow }}
                    </span>

                    <span
                        class="h-px w-10 bg-gradient-to-l from-transparent to-amber-300"
                    ></span>
                </div>

                <h2 class="vvs-display-title text-5xl sm:text-6xl">
                    {{ translations.order.title_before }}
                    <span class="vvs-gradient-text">
                        {{ translations.order.title_highlight }}
                    </span>
                </h2>

                <p
                    class="mx-auto mt-5 max-w-xl text-sm leading-6 text-zinc-500 sm:text-base"
                >
                    {{ translations.order.description }}
                </p>
            </header>

            <div class="relative">
                <!-- LIGNE -->

                <div
                    class="absolute top-9 right-[10%] left-[10%] hidden h-px bg-white/10 lg:block"
                >
                    <div
                        class="order-line-fill h-full origin-left bg-gradient-to-r from-amber-500 via-amber-200 to-amber-500 shadow-[0_0_15px_rgba(251,191,36,0.5)]"
                    ></div>
                </div>

                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-5">
                    <!-- 01 -->

                    <div class="order-step opacity-0">
                        <div
                            class="vvs-choice-card h-full rounded-2xl border p-6"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-amber-300/40 bg-black text-sm font-black text-amber-200"
                            >
                                01
                            </div>

                            <p class="mt-7 text-lg font-black">
                                {{ translations.order.step_1_title }}
                            </p>

                            <p class="mt-3 text-sm leading-6 text-zinc-500">
                                {{ translations.order.step_1_text }}
                            </p>
                        </div>
                    </div>

                    <!-- 02 -->

                    <div class="order-step opacity-0">
                        <div
                            class="vvs-choice-card h-full rounded-2xl border p-6"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-amber-300/40 bg-black text-sm font-black text-amber-200"
                            >
                                02
                            </div>

                            <p class="mt-7 text-lg font-black">
                                {{ translations.order.step_2_title }}
                            </p>

                            <p class="mt-3 text-sm leading-6 text-zinc-500">
                                {{ translations.order.step_2_text }}
                            </p>
                        </div>
                    </div>

                    <!-- 03 -->

                    <div class="order-step opacity-0">
                        <div
                            class="vvs-choice-card vvs-choice-card--featured h-full rounded-2xl border p-6"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-300 text-sm font-black text-black shadow-[0_0_30px_rgba(251,191,36,0.18)]"
                            >
                                03
                            </div>

                            <p class="mt-7 text-lg font-black">
                                {{ translations.order.step_3_title }}
                            </p>

                            <p class="mt-3 text-sm leading-6 text-zinc-500">
                                {{ translations.order.step_3_text }}
                            </p>
                        </div>
                    </div>

                    <!-- 04 -->

                    <div class="order-step opacity-0">
                        <div
                            class="vvs-choice-card h-full rounded-2xl border p-6"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-amber-300/40 bg-black text-sm font-black text-amber-200"
                            >
                                04
                            </div>

                            <p class="mt-7 text-lg font-black">
                                {{ translations.order.step_4_title }}
                            </p>

                            <p class="mt-3 text-sm leading-6 text-zinc-500">
                                {{ translations.order.step_4_text }}
                            </p>
                        </div>
                    </div>

                    <!-- 05 -->

                    <div class="order-step opacity-0">
                        <div
                            class="vvs-choice-card h-full rounded-2xl border p-6"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-amber-300/40 bg-black text-sm font-black text-amber-200"
                            >
                                05
                            </div>

                            <p class="mt-7 text-lg font-black">
                                {{ translations.order.step_5_title }}
                            </p>

                            <p class="mt-3 text-sm leading-6 text-zinc-500">
                                {{ translations.order.step_5_text }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="mx-auto mt-10 max-w-3xl rounded-xl border border-amber-300/15 bg-amber-300/[0.025] px-5 py-4 text-center"
            >
                <p class="text-xs leading-5 text-zinc-500">
                    {{ translations.order.note }}
                </p>
            </div>
        </div>
    </section>
</template>
