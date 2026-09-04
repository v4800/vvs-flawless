<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const localizedRoutes = computed(() => page.props.localizedRoutes);
const content = computed(() => page.props.translations.legal.terms);
</script>

<template>
    <Head :title="content.seo_title" />

    <div class="min-h-screen bg-black px-5 py-16 text-white sm:px-6">
        <main class="mx-auto max-w-3xl">
            <Link
                :href="localizedRoutes.watches"
                class="text-xs font-black tracking-[0.15em] text-amber-300 uppercase"
            >
                {{ page.props.translations.legal.back }}
            </Link>

            <p
                class="mt-12 text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
            >
                VVS FLAWLESS
            </p>

            <h1
                class="mt-4 text-4xl font-black tracking-[-0.04em] uppercase sm:text-5xl"
            >
                {{ content.title }}
            </h1>

            <div class="mt-12 space-y-10 text-sm leading-7 text-zinc-400">
                <section
                    v-for="section in content.sections"
                    :key="section.title"
                >
                    <h2 class="text-xl font-black text-white">
                        {{ section.title }}
                    </h2>
                    <p
                        v-for="paragraph in section.paragraphs"
                        :key="paragraph"
                        class="mt-3"
                    >
                        {{ paragraph }}
                    </p>
                </section>
            </div>
        </main>
    </div>
</template>
