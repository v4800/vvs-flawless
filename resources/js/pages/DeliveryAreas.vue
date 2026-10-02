<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import VvsNavigation from '@/components/VvsNavigation.vue';
import CollectionFooter from '@/components/CollectionFooter.vue';

defineProps({
    seo: { type: Object, required: true },
    copy: { type: Object, required: true },
});

const page = usePage();
const collectionHref = computed(() => page.props.localizedRoutes.watches);
const backLabel = computed(() => page.props.translations.vvs_navigation.collection);
</script>

<template>
    <Head :title="seo.title" />

    <div class="vvs-storefront min-h-screen text-white">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-lg focus:bg-amber-300 focus:px-4 focus:py-3 focus:text-black">
            {{ page.props.translations.accessibility.skip_content }}
        </a>

        <VvsNavigation :back-href="collectionHref" :back-label="backLabel" />

        <main id="main-content" tabindex="-1" class="px-5 pb-24 sm:px-6 lg:px-10">
            <header class="mx-auto max-w-4xl pt-14 text-center sm:pt-20">
                <p class="vvs-eyebrow">{{ copy.eyebrow }}</p>
                <h1 class="vvs-display-title mt-5 text-[clamp(2.7rem,7vw,5rem)] leading-tight">
                    {{ copy.title }}
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-zinc-300">
                    {{ copy.intro }}
                </p>
            </header>

            <section class="mx-auto mt-16 max-w-6xl" :aria-label="copy.zones_title">
                <h2 class="text-2xl font-semibold sm:text-3xl">{{ copy.zones_title }}</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <article
                        v-for="zone in copy.zones"
                        :key="zone.code"
                        class="vvs-luxury-card min-w-0 rounded-2xl border p-6"
                    >
                        <p class="text-xs font-bold tracking-[0.18em] text-amber-200">{{ zone.code }}</p>
                        <h3 class="mt-3 text-xl font-semibold">{{ zone.name }}</h3>
                        <p class="mt-3 text-sm leading-7 text-zinc-300">{{ zone.detail }}</p>
                    </article>
                </div>
            </section>

            <section class="vvs-luxury-card mx-auto mt-10 max-w-6xl rounded-2xl border border-amber-300/20 p-7 sm:p-10">
                <h2 class="text-2xl font-semibold">{{ copy.cost_title }}</h2>
                <p class="mt-4 max-w-4xl text-sm leading-8 text-zinc-300 sm:text-base">{{ copy.cost_text }}</p>
            </section>

            <section class="mx-auto mt-16 max-w-6xl">
                <h2 class="text-2xl font-semibold sm:text-3xl">{{ copy.steps_title }}</h2>
                <ol class="mt-6 grid gap-4 md:grid-cols-3">
                    <li
                        v-for="(step, index) in copy.steps"
                        :key="index"
                        class="rounded-2xl border border-white/10 bg-white/[0.035] p-6"
                    >
                        <span class="text-sm font-bold text-amber-200">0{{ index + 1 }}</span>
                        <p class="mt-4 text-sm leading-7 text-zinc-300">{{ step }}</p>
                    </li>
                </ol>
                <Link
                    :href="collectionHref"
                    class="mt-10 inline-flex min-h-12 items-center rounded-full bg-amber-300 px-7 text-sm font-bold text-black transition hover:bg-amber-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                >
                    {{ copy.cta }}
                </Link>
            </section>
        </main>
        <CollectionFooter />
    </div>
</template>
