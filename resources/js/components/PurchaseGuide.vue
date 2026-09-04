<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const translations = computed(() => page.props.translations);
</script>

<template>
    <section class="border-b border-white/10 px-5 py-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-[1200px]">
            <header class="mx-auto max-w-2xl text-center">
                <p
                    class="text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
                >
                    {{ translations.purchase_guide.eyebrow }}
                </p>

                <h2
                    class="mt-4 text-3xl font-black tracking-[-0.035em] uppercase sm:text-4xl"
                >
                    {{ translations.purchase_guide.title_before }}
                    <span class="text-amber-300">
                        {{ translations.purchase_guide.title_highlight }}
                    </span>
                </h2>

                <p class="mt-5 text-sm leading-7 text-zinc-500">
                    {{ translations.purchase_guide.description }}
                </p>
            </header>

            <div class="mt-10 grid gap-5 md:grid-cols-2">
                <!-- JAPONAIS -->

                <div
                    class="rounded-3xl border border-white/10 bg-zinc-950/70 p-6 sm:p-7"
                >
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                    >
                        {{ translations.purchase_guide.movement }}
                    </p>

                    <h3 class="mt-2 text-2xl font-black">
                        {{ translations.collection.japanese }}
                    </h3>

                    <p class="mt-5 text-sm leading-7 text-zinc-500">
                        {{ translations.purchase_guide.japanese_text }}
                    </p>
                </div>

                <!-- SUISSE -->

                <div
                    class="rounded-3xl border border-amber-300/20 bg-amber-300/[0.025] p-6 sm:p-7"
                >
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                    >
                        {{ translations.purchase_guide.movement }}
                    </p>

                    <h3 class="mt-2 text-2xl font-black">
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

            <!-- PROCESSUS -->

            <div
                class="mt-14 rounded-3xl border border-white/10 bg-gradient-to-br from-zinc-950 to-black p-6 sm:p-8"
            >
                <div class="mb-8">
                    <p
                        class="text-[10px] font-black tracking-[0.3em] text-amber-300 uppercase"
                    >
                        {{ translations.purchase_guide.process_eyebrow }}
                    </p>

                    <h3 class="mt-3 text-2xl font-black">
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
