<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
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
const translations = computed(() => page.props.translations);
const localizedRoutes = computed(() => page.props.localizedRoutes);
const guide = computed(() => props.guide);
</script>

<template>
    <Head :title="seo.title" />

    <div class="min-h-screen bg-black text-white">
        <VvsNavigation :back-label="translations.vvs_navigation.collection" />

        <main id="main-content">
            <header class="mx-auto max-w-4xl px-6 pt-12 text-center sm:pt-20">
                <p
                    class="text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
                >
                    {{ guide.eyebrow }}
                </p>

                <h1
                    class="vvs-display-title mt-5 text-4xl sm:text-6xl lg:text-7xl"
                >
                    {{ guide.title }}
                </h1>

                <p
                    class="mx-auto mt-6 max-w-3xl text-sm leading-7 text-zinc-400 sm:text-base"
                >
                    {{ guide.intro }}
                </p>
            </header>

            <section class="mx-auto max-w-4xl px-5 py-12 sm:px-6 sm:py-16">
                <div
                    class="rounded-3xl border border-amber-300/20 bg-amber-300/[0.035] p-6 sm:p-8"
                >
                    <p
                        class="text-[10px] font-black tracking-[0.3em] text-amber-300 uppercase"
                    >
                        {{ guide.answer_title }}
                    </p>

                    <p
                        class="mt-4 text-base leading-8 text-zinc-200 sm:text-lg"
                    >
                        {{ guide.answer }}
                    </p>
                </div>
            </section>

            <section
                class="border-y border-white/10 px-5 py-16 sm:px-6 lg:px-10"
            >
                <div class="mx-auto max-w-[1100px]">
                    <header class="max-w-2xl">
                        <h2 class="vvs-display-title text-4xl sm:text-5xl">
                            {{ guide.comparison_title }}
                        </h2>
                        <p class="mt-4 text-sm leading-7 text-zinc-500">
                            {{ guide.comparison_intro }}
                        </p>
                    </header>

                    <div
                        class="mt-9 overflow-x-auto rounded-3xl border border-white/10"
                    >
                        <table
                            class="w-full min-w-[760px] border-collapse text-left"
                        >
                            <thead class="bg-zinc-950">
                                <tr>
                                    <th
                                        class="px-5 py-4 text-xs font-black tracking-[0.16em] text-zinc-500 uppercase"
                                    >
                                        {{ guide.columns.criterion }}
                                    </th>
                                    <th
                                        class="px-5 py-4 text-xs font-black tracking-[0.16em] text-zinc-300 uppercase"
                                    >
                                        {{ guide.columns.diamond }}
                                    </th>
                                    <th
                                        class="px-5 py-4 text-xs font-black tracking-[0.16em] text-amber-300 uppercase"
                                    >
                                        {{ guide.columns.moissanite }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="row in guide.rows"
                                    :key="row.label"
                                    class="border-t border-white/10"
                                >
                                    <th
                                        class="px-5 py-5 text-sm font-black text-white"
                                    >
                                        {{ row.label }}
                                    </th>
                                    <td
                                        class="px-5 py-5 text-sm leading-7 text-zinc-500"
                                    >
                                        {{ row.diamond }}
                                    </td>
                                    <td
                                        class="px-5 py-5 text-sm leading-7 text-zinc-300"
                                    >
                                        {{ row.moissanite }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="px-5 py-16 sm:px-6 lg:px-10">
                <div class="mx-auto grid max-w-[1100px] gap-6 lg:grid-cols-2">
                    <article
                        class="rounded-3xl border border-white/10 bg-zinc-950/60 p-6 sm:p-8"
                    >
                        <h2 class="vvs-display-title text-3xl">
                            {{ guide.vvs_title }}
                        </h2>
                        <p class="mt-5 text-sm leading-7 text-zinc-400">
                            {{ guide.vvs_text }}
                        </p>
                    </article>

                    <article
                        class="rounded-3xl border border-white/10 bg-zinc-950/60 p-6 sm:p-8"
                    >
                        <h2 class="vvs-display-title text-3xl">
                            {{ guide.tester_title }}
                        </h2>
                        <p class="mt-5 text-sm leading-7 text-zinc-400">
                            {{ guide.tester_text }}
                        </p>
                    </article>
                </div>
            </section>

            <section
                class="border-y border-white/10 px-5 py-16 sm:px-6 lg:px-10"
            >
                <div class="mx-auto max-w-[1100px]">
                    <h2
                        class="vvs-display-title text-center text-4xl sm:text-5xl"
                    >
                        {{ guide.choice_title }}
                    </h2>

                    <div class="mt-9 grid gap-5 md:grid-cols-2">
                        <article
                            class="rounded-3xl border border-white/10 bg-zinc-950/60 p-6 sm:p-8"
                        >
                            <h3 class="vvs-display-title text-3xl">
                                {{ guide.choice_diamond_title }}
                            </h3>
                            <p class="mt-4 text-sm leading-7 text-zinc-500">
                                {{ guide.choice_diamond_text }}
                            </p>
                        </article>

                        <article
                            class="rounded-3xl border border-amber-300/20 bg-amber-300/[0.025] p-6 sm:p-8"
                        >
                            <h3
                                class="vvs-display-title text-3xl text-amber-300"
                            >
                                {{ guide.choice_moissanite_title }}
                            </h3>
                            <p class="mt-4 text-sm leading-7 text-zinc-400">
                                {{ guide.choice_moissanite_text }}
                            </p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="px-5 py-16 text-center sm:px-6 lg:px-10">
                <div
                    class="mx-auto max-w-3xl rounded-3xl border border-amber-300/20 bg-gradient-to-br from-zinc-950 to-black p-7 sm:p-10"
                >
                    <h2 class="vvs-display-title text-4xl sm:text-5xl">
                        {{ guide.cta_title }}
                    </h2>
                    <p
                        class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-zinc-500"
                    >
                        {{ guide.cta_text }}
                    </p>
                    <Link
                        :href="localizedRoutes.watches"
                        class="mt-7 inline-flex rounded-full bg-amber-300 px-6 py-3 text-xs font-black tracking-[0.12em] text-black uppercase transition hover:bg-amber-200"
                    >
                        {{ guide.cta_label }}
                    </Link>
                </div>
            </section>

            <section class="px-5 pb-16 sm:px-6 lg:px-10">
                <div class="mx-auto max-w-3xl border-t border-white/10 pt-8">
                    <h2
                        class="text-sm font-black tracking-[0.14em] text-zinc-300 uppercase"
                    >
                        {{ guide.sources_title }}
                    </h2>
                    <p class="mt-3 text-xs leading-6 text-zinc-600">
                        {{ guide.sources_intro }}
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li v-for="source in guide.sources" :key="source.url">
                            <a
                                :href="source.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-xs font-bold text-zinc-400 underline decoration-white/20 underline-offset-4 hover:text-amber-300"
                            >
                                {{ source.label }}
                            </a>
                        </li>
                    </ul>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/10 px-6 py-9 text-center">
            <Link
                :href="localizedRoutes.watches"
                class="text-xs font-black tracking-[0.12em] text-amber-300 uppercase"
            >
                {{ translations.footer.collection }}
            </Link>
        </footer>
    </div>
</template>
