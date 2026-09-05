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
const fieldNumber = (index) => String(index + 1).padStart(2, '0');
</script>

<template>
    <Head :title="seo.title" />

    <div class="guide-page min-h-screen bg-black text-white">
        <VvsNavigation :back-label="translations.vvs_navigation.collection" />

        <main id="main-content" class="overflow-hidden">
            <article>
                <header
                    class="guide-hero relative mx-auto max-w-[1180px] px-5 pt-16 pb-14 sm:px-8 sm:pt-24 sm:pb-20"
                >
                    <div class="guide-orbit" aria-hidden="true"></div>

                    <div class="relative z-10 max-w-4xl">
                        <p
                            class="flex items-center gap-3 text-[11px] font-bold tracking-[0.25em] text-amber-300 uppercase"
                        >
                            <span class="h-px w-9 bg-amber-300/70"></span>
                            {{ guide.eyebrow }}
                        </p>

                        <h1
                            class="vvs-gradient-text vvs-gradient-text--hero vvs-display-title mt-7 max-w-5xl text-5xl sm:text-7xl lg:text-[5.6rem]"
                        >
                            {{ guide.title }}
                        </h1>

                        <p
                            class="vvs-body-copy mt-7 max-w-3xl text-base text-zinc-300 sm:text-lg"
                        >
                            {{ guide.intro }}
                        </p>

                        <div
                            class="guide-lead mt-9 max-w-3xl rounded-2xl border p-6 sm:p-8"
                        >
                            <p
                                class="text-base leading-8 text-zinc-100 sm:text-lg"
                            >
                                {{ guide.answer }}
                            </p>
                        </div>
                    </div>
                </header>

                <section
                    class="border-y border-white/10 px-5 py-16 sm:px-8 sm:py-20"
                >
                    <div class="mx-auto max-w-[1180px]">
                        <header class="max-w-2xl">
                            <h2
                                class="vvs-display-title text-4xl text-white sm:text-6xl"
                            >
                                {{ guide.comparison_title }}
                            </h2>
                            <p
                                class="vvs-body-copy mt-5 text-base text-zinc-400"
                            >
                                {{ guide.comparison_intro }}
                            </p>
                        </header>

                        <div class="mt-10 grid gap-4 md:hidden">
                            <article
                                v-for="row in guide.rows"
                                :key="`mobile-${row.label}`"
                                class="guide-comparison-card overflow-hidden rounded-2xl border border-white/10"
                            >
                                <h3
                                    class="border-b border-white/10 px-5 py-4 text-base font-semibold text-white"
                                >
                                    {{ row.label }}
                                </h3>

                                <dl class="divide-y divide-white/10">
                                    <div class="px-5 py-5">
                                        <dt
                                            class="text-[10px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                                        >
                                            {{ guide.columns.diamond }}
                                        </dt>
                                        <dd
                                            class="mt-2 text-[15px] leading-7 text-zinc-300"
                                        >
                                            {{ row.diamond }}
                                        </dd>
                                    </div>

                                    <div class="px-5 py-5">
                                        <dt
                                            class="text-[10px] font-bold tracking-[0.16em] text-amber-300 uppercase"
                                        >
                                            {{ guide.columns.moissanite }}
                                        </dt>
                                        <dd
                                            class="mt-2 text-[15px] leading-7 text-zinc-100"
                                        >
                                            {{ row.moissanite }}
                                        </dd>
                                    </div>
                                </dl>
                            </article>
                        </div>

                        <div
                            class="guide-table mt-10 hidden overflow-hidden rounded-3xl border border-white/10 md:block"
                        >
                            <table class="w-full border-collapse text-left">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-5 text-[11px] font-bold tracking-[0.14em] text-zinc-500 uppercase"
                                        >
                                            {{ guide.columns.criterion }}
                                        </th>
                                        <th
                                            class="px-6 py-5 text-[11px] font-bold tracking-[0.14em] text-zinc-300 uppercase"
                                        >
                                            {{ guide.columns.diamond }}
                                        </th>
                                        <th
                                            class="px-6 py-5 text-[11px] font-bold tracking-[0.14em] text-amber-300 uppercase"
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
                                            class="px-6 py-6 text-sm font-semibold text-white"
                                        >
                                            {{ row.label }}
                                        </th>
                                        <td
                                            class="px-6 py-6 text-[15px] leading-7 text-zinc-400"
                                        >
                                            {{ row.diamond }}
                                        </td>
                                        <td
                                            class="px-6 py-6 text-[15px] leading-7 text-zinc-200"
                                        >
                                            {{ row.moissanite }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="px-5 py-16 sm:px-8 sm:py-20">
                    <div
                        class="mx-auto grid max-w-[1180px] gap-5 lg:grid-cols-2"
                    >
                        <article
                            class="guide-info-card relative overflow-hidden rounded-3xl border border-white/10 p-7 sm:p-9"
                        >
                            <span
                                class="vvs-display-title absolute top-4 right-6 text-6xl text-white/[0.035]"
                                aria-hidden="true"
                            >
                                01
                            </span>
                            <h2 class="vvs-display-title max-w-md text-4xl">
                                {{ guide.vvs_title }}
                            </h2>
                            <p
                                class="vvs-body-copy mt-6 max-w-xl text-[15px] text-zinc-400"
                            >
                                {{ guide.vvs_text }}
                            </p>
                        </article>

                        <article
                            class="guide-info-card relative overflow-hidden rounded-3xl border border-white/10 p-7 sm:p-9"
                        >
                            <span
                                class="vvs-display-title absolute top-4 right-6 text-6xl text-white/[0.035]"
                                aria-hidden="true"
                            >
                                02
                            </span>
                            <h2 class="vvs-display-title max-w-md text-4xl">
                                {{ guide.tester_title }}
                            </h2>
                            <p
                                class="vvs-body-copy mt-6 max-w-xl text-[15px] text-zinc-400"
                            >
                                {{ guide.tester_text }}
                            </p>
                        </article>
                    </div>
                </section>

                <section class="px-5 pb-16 sm:px-8 sm:pb-20">
                    <div
                        class="guide-report relative mx-auto max-w-[1180px] overflow-hidden rounded-[2rem] border border-amber-200/20"
                    >
                        <div
                            class="relative z-10 grid lg:grid-cols-[0.78fr_1.22fr]"
                        >
                            <div
                                class="border-b border-amber-100/10 p-7 sm:p-10 lg:border-r lg:border-b-0"
                            >
                                <p
                                    class="text-[10px] font-bold tracking-[0.22em] text-amber-300 uppercase"
                                >
                                    {{ guide.report_eyebrow }}
                                </p>
                                <h2
                                    class="vvs-display-title mt-5 text-4xl text-white sm:text-5xl"
                                >
                                    {{ guide.report_title }}
                                </h2>
                                <p
                                    class="vvs-body-copy mt-6 text-[15px] text-zinc-300"
                                >
                                    {{ guide.report_intro }}
                                </p>

                                <div
                                    class="guide-report-mark mt-9 flex aspect-[4/3] items-center justify-center rounded-2xl border border-amber-100/10"
                                    aria-hidden="true"
                                >
                                    <svg
                                        viewBox="0 0 180 150"
                                        class="h-36 w-44 text-amber-200 sm:h-44 sm:w-52"
                                        fill="none"
                                    >
                                        <path
                                            d="M38 54 64 25h52l26 29-52 70L38 54Z"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                        />
                                        <path
                                            d="m38 54 52 70 52-70M38 54h104M64 25l26 29 26-29M64 25 38 54m78-29 26 29M90 54v70"
                                            stroke="currentColor"
                                            stroke-width="1"
                                            opacity=".58"
                                        />
                                        <circle
                                            cx="90"
                                            cy="74"
                                            r="66"
                                            stroke="currentColor"
                                            stroke-dasharray="2 7"
                                            opacity=".2"
                                        />
                                    </svg>
                                </div>

                                <p
                                    class="mt-5 inline-flex rounded-full border border-amber-200/20 bg-amber-200/[0.06] px-4 py-2 text-[10px] font-bold tracking-[0.12em] text-amber-200 uppercase"
                                >
                                    {{ guide.report_badge }}
                                </p>
                            </div>

                            <div class="p-5 sm:p-8">
                                <div
                                    class="guide-report-grid grid overflow-hidden rounded-2xl border border-white/10 sm:grid-cols-2"
                                >
                                    <article
                                        v-for="(
                                            field, index
                                        ) in guide.report_fields"
                                        :key="field.label"
                                        class="guide-report-field relative border-white/10 p-5"
                                    >
                                        <span
                                            class="font-mono text-[10px] tracking-[0.16em] text-amber-300/70"
                                        >
                                            {{ fieldNumber(index) }}
                                        </span>
                                        <h3
                                            class="mt-3 text-sm font-semibold text-white"
                                        >
                                            {{ field.label }}
                                        </h3>
                                        <p
                                            class="mt-2 text-xs leading-6 text-zinc-500"
                                        >
                                            {{ field.text }}
                                        </p>
                                    </article>
                                </div>

                                <p
                                    class="mt-6 border-t border-white/10 pt-6 text-xs leading-6 text-zinc-500"
                                >
                                    {{ guide.report_note }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    class="border-y border-white/10 px-5 py-16 sm:px-8 sm:py-20"
                >
                    <div class="mx-auto max-w-[1180px]">
                        <h2
                            class="vvs-display-title max-w-3xl text-4xl sm:text-6xl"
                        >
                            {{ guide.choice_title }}
                        </h2>

                        <div class="mt-10 grid gap-5 md:grid-cols-2">
                            <article
                                class="guide-choice-card rounded-3xl border border-white/10 p-7 sm:p-9"
                            >
                                <h3 class="vvs-display-title text-4xl">
                                    {{ guide.choice_diamond_title }}
                                </h3>
                                <p
                                    class="vvs-body-copy mt-5 text-[15px] text-zinc-400"
                                >
                                    {{ guide.choice_diamond_text }}
                                </p>
                            </article>

                            <article
                                class="guide-choice-card guide-choice-card--gold rounded-3xl border border-amber-300/20 p-7 sm:p-9"
                            >
                                <h3
                                    class="vvs-display-title text-4xl text-amber-200"
                                >
                                    {{ guide.choice_moissanite_title }}
                                </h3>
                                <p
                                    class="vvs-body-copy mt-5 text-[15px] text-zinc-300"
                                >
                                    {{ guide.choice_moissanite_text }}
                                </p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="px-5 py-16 sm:px-8 sm:py-24">
                    <div
                        class="guide-cta mx-auto max-w-4xl rounded-[2rem] border border-amber-200/20 p-8 text-center sm:p-12"
                    >
                        <h2
                            class="vvs-gradient-text vvs-display-title text-4xl sm:text-6xl"
                        >
                            {{ guide.cta_title }}
                        </h2>
                        <p
                            class="vvs-body-copy mx-auto mt-6 max-w-2xl text-[15px] text-zinc-400"
                        >
                            {{ guide.cta_text }}
                        </p>
                        <Link
                            :href="localizedRoutes.watches"
                            class="vvs-button-primary mt-8 inline-flex rounded-full px-7 py-3.5 text-xs font-bold tracking-[0.12em] uppercase"
                        >
                            {{ guide.cta_label }}
                        </Link>
                    </div>
                </section>

                <section class="px-5 pb-16 sm:px-8 sm:pb-20">
                    <div
                        class="mx-auto grid max-w-[1180px] gap-5 border-t border-white/10 pt-9 md:grid-cols-[0.7fr_1.3fr]"
                    >
                        <div>
                            <h2
                                class="vvs-display-title text-3xl text-zinc-200"
                            >
                                {{ guide.sources_title }}
                            </h2>
                            <p
                                class="mt-3 max-w-sm text-xs leading-6 text-zinc-600"
                            >
                                {{ guide.sources_intro }}
                            </p>
                        </div>
                        <ul class="grid gap-2 sm:grid-cols-2">
                            <li
                                v-for="source in guide.sources"
                                :key="source.url"
                            >
                                <a
                                    :href="source.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="group flex h-full items-start justify-between gap-3 rounded-xl border border-white/[0.06] px-4 py-3 text-xs leading-5 text-zinc-500 transition hover:border-amber-300/25 hover:text-amber-200"
                                >
                                    <span>{{ source.label }}</span>
                                    <span
                                        class="text-amber-300/60 transition group-hover:translate-x-0.5"
                                        aria-hidden="true"
                                    >
                                        ↗
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </section>
            </article>
        </main>

        <footer class="border-t border-white/10 px-6 py-9">
            <nav
                class="mx-auto flex max-w-4xl flex-wrap items-center justify-center gap-x-5 gap-y-3 text-center"
                :aria-label="translations.navigation.main_label"
            >
                <Link
                    :href="localizedRoutes.watches"
                    class="text-xs font-bold tracking-[0.12em] text-amber-300 uppercase"
                >
                    {{ translations.footer.collection }}
                </Link>

                <Link
                    :href="localizedRoutes.about"
                    class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                >
                    {{ translations.navigation.about }}
                </Link>

                <Link
                    :href="localizedRoutes.privacy"
                    class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                >
                    {{ translations.footer.privacy }}
                </Link>

                <Link
                    :href="localizedRoutes.reservationTerms"
                    class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                >
                    {{ translations.footer.terms }}
                </Link>
            </nav>
        </footer>
    </div>
</template>
