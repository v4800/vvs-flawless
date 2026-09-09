<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const openIndex = ref(null);
const page = usePage();
const translations = computed(() => page.props.translations);
const faqs = computed(() => translations.value.faq.items);

const toggleFaq = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <section
        id="faq"
        class="relative scroll-mt-24 overflow-hidden px-5 py-24 sm:px-6 lg:px-10"
    >
        <div
            class="pointer-events-none absolute top-1/2 left-1/2 -z-10 h-[600px] w-[900px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.035] blur-[150px]"
        ></div>

        <div class="mx-auto max-w-5xl">
            <header class="mb-14 text-center">
                <div class="mb-4 flex items-center justify-center gap-3">
                    <span
                        class="h-px w-10 bg-gradient-to-r from-transparent to-amber-300"
                    ></span>

                    <span class="vvs-eyebrow">
                        {{ translations.faq.eyebrow }}
                    </span>

                    <span
                        class="h-px w-10 bg-gradient-to-l from-transparent to-amber-300"
                    ></span>
                </div>

                <h2 class="vvs-display-title text-5xl sm:text-6xl">
                    {{ translations.faq.title_before }}

                    <span class="vvs-gradient-text">
                        {{ translations.faq.title_highlight }}
                    </span>
                </h2>

                <p
                    class="mx-auto mt-5 max-w-2xl text-sm leading-6 text-zinc-500 sm:text-base"
                >
                    {{ translations.faq.description }}
                </p>
            </header>

            <div class="space-y-3">
                <div
                    v-for="(faq, index) in faqs"
                    :key="faq.question"
                    :class="[
                        'vvs-choice-card overflow-hidden rounded-2xl border transition-all duration-300',

                        openIndex === index
                            ? 'border-amber-300/40 shadow-[0_0_35px_rgba(251,191,36,0.06)]'
                            : '',
                    ]"
                >
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-6 px-6 py-5 text-left sm:px-7"
                        @click="toggleFaq(index)"
                    >
                        <span
                            :class="[
                                'text-sm font-bold transition sm:text-base',

                                openIndex === index
                                    ? 'text-amber-200'
                                    : 'text-zinc-200',
                            ]"
                        >
                            {{ faq.question }}
                        </span>

                        <span
                            :class="[
                                'flex h-9 w-9 shrink-0 items-center justify-center rounded-full border text-lg transition-all duration-300',

                                openIndex === index
                                    ? 'rotate-45 border-amber-300 bg-amber-300 text-black'
                                    : 'border-white/15 bg-black text-amber-200',
                            ]"
                        >
                            +
                        </span>
                    </button>

                    <div
                        class="grid transition-all duration-300 ease-out"
                        :class="
                            openIndex === index
                                ? 'grid-rows-[1fr] opacity-100'
                                : 'grid-rows-[0fr] opacity-0'
                        "
                    >
                        <div class="overflow-hidden">
                            <div
                                class="border-t border-white/[0.07] px-6 pt-5 pb-6 sm:px-7"
                            >
                                <p
                                    class="max-w-4xl text-sm leading-7 text-zinc-400"
                                >
                                    {{ faq.answer }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
