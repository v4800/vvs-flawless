<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import SeoContentHub from '@/components/SeoContentHub.vue';
import VvsNavigation from '@/components/VvsNavigation.vue';

defineProps({
    seo: {
        type: Object,
        required: true,
    },
    guide: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const localizedRoutes = computed(() => page.props.localizedRoutes);
const translations = computed(() => page.props.translations);
</script>

<template>
    <Head :title="seo.title" />

    <div class="min-h-screen bg-black text-white">
        <a
            href="#main-content"
            class="sr-only z-[100] rounded-lg bg-amber-300 px-4 py-3 font-bold text-black focus:not-sr-only focus:fixed focus:top-4 focus:left-4"
        >
            {{ translations.accessibility.skip_content }}
        </a>

        <VvsNavigation
            :back-label="translations.vvs_navigation.collection"
            :back-href="localizedRoutes.watches"
        />

        <main id="main-content" tabindex="-1">
            <header
                class="relative isolate overflow-hidden border-b border-white/10 px-5 pt-10 pb-20 sm:px-6 sm:pt-16 lg:px-10"
            >
                <div
                    class="pointer-events-none absolute top-0 left-1/2 -z-10 h-[480px] w-[900px] -translate-x-1/2 rounded-full bg-amber-400/[0.055] blur-[145px]"
                ></div>

                <div class="mx-auto max-w-4xl text-center">
                    <p class="vvs-eyebrow">{{ guide.eyebrow }}</p>

                    <h1
                        class="vvs-display-title mt-5 text-5xl leading-[0.98] sm:text-6xl lg:text-7xl"
                    >
                        {{ guide.title }}
                    </h1>

                    <p
                        class="mx-auto mt-7 max-w-3xl text-base leading-8 text-zinc-400 sm:text-lg"
                    >
                        {{ guide.intro }}
                    </p>

                    <div
                        class="vvs-luxury-card mx-auto mt-9 max-w-3xl rounded-2xl border border-amber-300/20 p-6 text-left sm:p-8"
                    >
                        <p
                            class="text-[10px] font-black tracking-[0.2em] text-amber-300 uppercase"
                        >
                            VVS FLAWLESS
                        </p>

                        <p class="mt-3 text-base leading-8 text-zinc-200">
                            {{ guide.answer }}
                        </p>
                    </div>
                </div>
            </header>

            <section class="px-5 py-20 sm:px-6 lg:px-10">
                <div class="mx-auto max-w-4xl space-y-5">
                    <article
                        v-for="section in guide.sections"
                        :key="section.title"
                        class="vvs-luxury-card rounded-2xl border p-6 sm:p-8"
                    >
                        <h2 class="vvs-display-title text-3xl sm:text-4xl">
                            {{ section.title }}
                        </h2>

                        <p
                            v-for="paragraph in section.paragraphs"
                            :key="paragraph"
                            class="mt-4 text-sm leading-7 text-zinc-400 sm:text-base sm:leading-8"
                        >
                            {{ paragraph }}
                        </p>
                    </article>
                </div>
            </section>

            <section
                v-if="guide.faq?.length"
                class="border-y border-white/10 bg-zinc-950/40 px-5 py-20 sm:px-6 lg:px-10"
            >
                <div class="mx-auto max-w-4xl">
                    <h2 class="vvs-display-title text-center text-4xl sm:text-5xl">
                        {{ guide.faq_title }}
                    </h2>

                    <div class="mt-9 grid gap-4">
                        <article
                            v-for="item in guide.faq"
                            :key="item.question"
                            class="vvs-choice-card rounded-2xl border p-6"
                        >
                            <h3 class="text-base font-black text-white">
                                {{ item.question }}
                            </h3>
                            <p class="mt-3 text-sm leading-7 text-zinc-500">
                                {{ item.answer }}
                            </p>
                        </article>
                    </div>
                </div>
            </section>

            <section
                v-if="guide.sources?.length"
                class="px-5 py-16 sm:px-6 lg:px-10"
            >
                <div class="mx-auto max-w-4xl">
                    <h2 class="vvs-display-title text-3xl">
                        {{ guide.sources_title }}
                    </h2>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <a
                            v-for="source in guide.sources"
                            :key="source.url"
                            :href="source.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="vvs-button-secondary rounded-xl px-4 py-3 text-xs"
                        >
                            {{ source.label }} ↗
                        </a>
                    </div>
                </div>
            </section>

            <SeoContentHub />

            <section class="px-5 py-20 sm:px-6 lg:px-10">
                <div
                    class="vvs-luxury-card vvs-choice-card--featured mx-auto max-w-4xl rounded-3xl border p-8 text-center sm:p-10"
                >
                    <p class="vvs-eyebrow">VVS FLAWLESS</p>
                    <h2 class="vvs-display-title mt-4 text-4xl sm:text-5xl">
                        {{ guide.cta_title }}
                    </h2>
                    <p
                        class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-zinc-400"
                    >
                        {{ guide.cta_text }}
                    </p>
                    <Link
                        :href="localizedRoutes.watches"
                        class="vvs-button-primary mt-7 inline-flex rounded-xl px-7 py-4 text-xs font-bold tracking-[0.12em] uppercase"
                    >
                        {{ guide.cta_label }} →
                    </Link>
                </div>
            </section>
        </main>
    </div>
</template>
