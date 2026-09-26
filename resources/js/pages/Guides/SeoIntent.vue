<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import GuideEditorialTrust from '@/components/GuideEditorialTrust.vue';
import GuideContents from '@/components/GuideContents.vue';
import SeoContentHub from '@/components/SeoContentHub.vue';
import VvsNavigation from '@/components/VvsNavigation.vue';

const props = defineProps({
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

const guideContents = computed(() =>
    (props.guide.sections ?? []).map((section, index) => ({
        id: `guide-section-${index + 1}`,
        label: section.title,
    })),
);
</script>

<template>
    <Head :title="seo.title" />

    <div class="vvs-storefront min-h-screen text-white">
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

            <GuideEditorialTrust />

            <GuideContents
                :items="guideContents"
                :has-faq="Boolean(guide.faq?.length)"
            />

            <section class="px-5 py-20 sm:px-6 lg:px-10">
                <div class="mx-auto max-w-4xl space-y-5">
                    <article
                        v-for="(section, index) in guide.sections"
                        :id="`guide-section-${index + 1}`"
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
                id="guide-faq"
                class="border-y border-white/10 bg-zinc-950/40 px-5 py-20 sm:px-6 lg:px-10"
            >
                <div class="mx-auto max-w-4xl">
                    <h2
                        class="vvs-display-title text-center text-4xl sm:text-5xl"
                    >
                        {{ guide.faq_title }}
                    </h2>

                    <div class="mt-9 grid gap-4">
                        <details
                            v-for="item in guide.faq"
                            :key="item.question"
                            class="vvs-choice-card group rounded-2xl border p-6"
                        >
                            <summary
                                class="flex cursor-pointer list-none items-center justify-between gap-5 text-left text-base font-black text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-300"
                            >
                                <span>{{ item.question }}</span>

                                <span
                                    aria-hidden="true"
                                    class="shrink-0 text-xl font-light text-amber-300 transition-transform group-open:rotate-45"
                                >
                                    +
                                </span>
                            </summary>

                            <p class="mt-4 text-sm leading-7 text-zinc-400">
                                {{ item.answer }}
                            </p>
                        </details>
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
