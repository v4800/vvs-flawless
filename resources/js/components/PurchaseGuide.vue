<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const translations = computed(() => page.props.translations);
const guideLinks = computed(() => page.props.guideLinks);
const localizedRoutes = computed(() => page.props.localizedRoutes);
</script>

<template>
    <section class="border-b border-white/10 px-5 py-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-[1200px]">
            <header class="mx-auto max-w-2xl text-center">
                <p class="vvs-eyebrow">
                    {{ translations.purchase_guide.eyebrow }}
                </p>

                <h2 class="vvs-display-title mt-4 text-4xl sm:text-5xl">
                    {{ translations.purchase_guide.title_before }}
                    <span class="vvs-gradient-text">
                        {{ translations.purchase_guide.title_highlight }}
                    </span>
                </h2>

                <p class="mt-5 text-sm leading-7 text-zinc-500">
                    {{ translations.purchase_guide.description }}
                </p>
            </header>

            <div class="mt-10 grid gap-5 md:grid-cols-2">
                <!-- JAPONAIS -->

                <div class="vvs-choice-card rounded-3xl border p-6 sm:p-7">
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                    >
                        {{ translations.purchase_guide.movement }}
                    </p>

                    <h3 class="vvs-display-title mt-2 text-3xl">
                        {{ translations.collection.japanese }}
                    </h3>

                    <p class="mt-5 text-sm leading-7 text-zinc-500">
                        {{ translations.purchase_guide.japanese_text }}
                    </p>
                </div>

                <!-- SUISSE -->

                <div
                    class="vvs-choice-card vvs-choice-card--featured rounded-3xl border p-6 sm:p-7"
                >
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                    >
                        {{ translations.purchase_guide.movement }}
                    </p>

                    <h3 class="vvs-display-title mt-2 text-3xl">
                        {{ translations.collection.swiss }}
                    </h3>

                    <p class="mt-5 text-sm leading-7 text-zinc-500">
                        {{ translations.purchase_guide.swiss_text }}
                    </p>
                </div>
            </div>

            <p
                class="mx-auto mt-5 max-w-3xl text-center text-xs leading-6 text-zinc-700"
            >
                {{ translations.purchase_guide.details_note }}
            </p>

            <div
                class="vvs-luxury-card mx-auto mt-8 max-w-3xl rounded-3xl border p-5 text-center sm:p-6"
            >
                <p
                    class="text-[9px] font-black tracking-[0.24em] text-amber-300 uppercase"
                >
                    {{ guideLinks.eyebrow }}
                </p>
                <h3 class="vvs-display-title mt-3 text-2xl sm:text-3xl">
                    {{ guideLinks.title }}
                </h3>
                <p
                    class="mx-auto mt-3 max-w-xl text-xs leading-6 text-zinc-500"
                >
                    {{ guideLinks.description }}
                </p>
                <Link
                    :href="localizedRoutes.diamondGuide"
                    class="mt-5 inline-flex text-xs font-black tracking-[0.12em] text-amber-300 uppercase underline decoration-amber-300/30 underline-offset-4 hover:text-amber-200"
                >
                    {{ guideLinks.cta }}
                </Link>
            </div>

            <!-- PROCESSUS -->

            <div class="vvs-luxury-card mt-14 rounded-3xl border p-6 sm:p-8">
                <div class="mb-8">
                    <p
                        class="text-[10px] font-black tracking-[0.3em] text-amber-300 uppercase"
                    >
                        {{ translations.purchase_guide.process_eyebrow }}
                    </p>

                    <h3 class="vvs-display-title mt-3 text-3xl">
                        {{ translations.purchase_guide.process_title }}
                    </h3>
                </div>

                <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <p class="text-3xl font-black text-amber-300">01</p>

                        <p class="mt-3 font-black">
                            {{ translations.purchase_guide.choose }}
                        </p>

                        <p class="mt-2 text-xs leading-6 text-zinc-600">
                            {{ translations.purchase_guide.choose_text }}
                        </p>
                    </div>

                    <div>
                        <p class="text-3xl font-black text-amber-300">02</p>

                        <p class="mt-3 font-black">
                            {{ translations.purchase_guide.reserve }}
                        </p>

                        <p class="mt-2 text-xs leading-6 text-zinc-600">
                            {{ translations.purchase_guide.reserve_text }}
                        </p>
                    </div>

                    <div>
                        <p class="text-3xl font-black text-amber-300">03</p>

                        <p class="mt-3 font-black">
                            {{ translations.purchase_guide.confirm }}
                        </p>

                        <p class="mt-2 text-xs leading-6 text-zinc-600">
                            {{ translations.purchase_guide.confirm_text }}
                        </p>
                    </div>

                    <div>
                        <p class="text-3xl font-black text-amber-300">04</p>

                        <p class="mt-3 font-black">
                            {{ translations.purchase_guide.receive }}
                        </p>

                        <p class="mt-2 text-xs leading-6 text-zinc-600">
                            {{ translations.purchase_guide.receive_text }}
                        </p>
                    </div>
                </div>

                <div
                    class="mt-8 grid gap-3 border-t border-white/10 pt-6 sm:grid-cols-2"
                >
                    <div
                        class="rounded-2xl border border-white/10 bg-black/50 p-4"
                    >
                        <p
                            class="text-[9px] font-black tracking-[0.18em] text-zinc-600 uppercase"
                        >
                            {{ translations.purchase_guide.delay_label }}
                        </p>

                        <p class="mt-2 font-bold text-zinc-200">
                            {{ translations.purchase_guide.delay }}
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-black/50 p-4"
                    >
                        <p
                            class="text-[9px] font-black tracking-[0.18em] text-zinc-600 uppercase"
                        >
                            {{ translations.purchase_guide.delivery_label }}
                        </p>

                        <p class="mt-2 font-bold text-zinc-200">
                            {{ translations.purchase_guide.delivery }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
