<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const translations = computed(() => page.props.translations);

const models = computed(() => [
    {
        key: 'black-stainless',
        image: '/images/watches/catalog/005-black-stainless/01-front.webp',
        name: translations.value.collection.source_black_title,
    },
    {
        key: 'red-roman',
        image: '/images/watches/catalog/006-red-roman/01-front.webp',
        name: translations.value.collection.source_red_title,
    },
    {
        key: 'jubilee',
        image: '/images/watches/catalog/007-jubilee/01-front.jpg',
        name: translations.value.collection.source_jubilee_title,
    },
]);
</script>

<template>
    <section class="border-y border-white/10 bg-black px-5 py-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-[1500px]">
            <div class="mx-auto mb-10 max-w-2xl text-center">
                <p class="text-[10px] font-black tracking-[0.3em] text-amber-300 uppercase">
                    {{ translations.collection.source_eyebrow }}
                </p>
                <h2 class="vvs-display-title mt-3 text-4xl sm:text-5xl">
                    {{ translations.collection.source_title }}
                </h2>
                <p class="mt-4 text-sm leading-7 text-zinc-500">
                    {{ translations.collection.source_description }}
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <article
                    v-for="model in models"
                    :key="model.key"
                    class="vvs-luxury-card vvs-luxury-card--interactive group overflow-hidden rounded-3xl border"
                >
                    <div class="relative h-[390px] overflow-hidden bg-[radial-gradient(circle_at_50%_35%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_45%,#050505_78%)]">
                        <div class="absolute inset-0 z-10 bg-gradient-to-t from-black via-black/10 to-transparent"></div>
                        <div class="absolute top-1/2 left-1/2 h-60 w-60 -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-300/[0.05] blur-[70px]"></div>
                        <img
                            :src="model.image"
                            :alt="model.name"
                            loading="lazy"
                            decoding="async"
                            class="h-full w-full object-cover contrast-[1.04] saturate-[0.92] transition duration-700 group-hover:scale-[1.05]"
                        />
                        <span class="absolute top-5 left-5 z-20 rounded-full border border-amber-300/25 bg-black/75 px-4 py-2 text-[9px] font-black tracking-[0.18em] text-amber-200 uppercase backdrop-blur">
                            {{ translations.collection.source_badge }}
                        </span>
                    </div>

                    <div class="p-6 text-center">
                        <h3 class="vvs-display-title min-h-[58px] text-2xl leading-[1.05] text-white">
                            {{ model.name }}
                        </h3>
                        <p class="mt-3 text-[11px] font-black tracking-[0.18em] text-amber-300 uppercase">
                            Moissanite VVS
                        </p>
                        <a
                            href="#contact"
                            class="vvs-button-secondary mt-6 flex w-full items-center justify-center gap-3 rounded-xl px-4 py-4 text-xs font-bold tracking-[0.12em] uppercase"
                        >
                            {{ translations.collection.source_request }}
                            <span>→</span>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
