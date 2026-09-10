<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    models: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);
</script>

<template>
    <section
        v-if="models.length"
        id="catalog-models"
        class="scroll-mt-24 border-y border-white/10 bg-black px-5 py-16 sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1500px]">
            <header class="mx-auto mb-10 max-w-2xl text-center">
                <p class="vvs-eyebrow">
                    {{ translations.collection.source_eyebrow }}
                </p>
                <h2 class="vvs-display-title mt-3 text-4xl sm:text-5xl">
                    {{ translations.collection.source_title }}
                </h2>
                <p class="mt-4 text-sm leading-7 text-zinc-400">
                    {{ translations.collection.source_description }}
                </p>
            </header>

            <div class="grid gap-6 md:grid-cols-2">
                <article
                    v-for="model in models"
                    :key="model.reference"
                    class="vvs-luxury-card flex min-w-0 flex-col overflow-hidden rounded-2xl border"
                >
                    <div class="aspect-[4/3] overflow-hidden bg-[#0b0a09]">
                        <img
                            :src="model.cardImage"
                            :srcset="`${model.cardImage} 720w, ${model.image} 1448w`"
                            sizes="(min-width: 1536px) 738px, (min-width: 768px) 48vw, 100vw"
                            :alt="model.name"
                            width="1448"
                            height="1086"
                            loading="lazy"
                            decoding="async"
                            class="h-full w-full object-contain"
                        />
                    </div>

                    <div class="flex flex-1 flex-col items-start p-5 sm:p-7">
                        <p
                            class="text-[10px] tracking-[0.18em] text-amber-200/80"
                        >
                            {{ model.reference }}
                        </p>
                        <h3
                            class="vvs-display-title mt-2 text-2xl leading-tight sm:text-3xl"
                        >
                            {{ model.name }}
                        </h3>
                        <p
                            v-if="!model.watchUrl"
                            class="mt-3 text-sm leading-6 text-zinc-400"
                        >
                            {{ translations.collection.source_details }}
                        </p>
                        <div class="mt-auto w-full pt-6">
                            <Link
                                v-if="model.watchUrl"
                                :href="model.watchUrl"
                                class="vvs-button-secondary flex min-h-12 w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold"
                            >
                                {{ translations.collection.source_view }}
                            </Link>
                            <a
                                v-else
                                href="#contact"
                                class="vvs-button-secondary flex min-h-12 w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold"
                            >
                                {{ translations.collection.source_request }}
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
