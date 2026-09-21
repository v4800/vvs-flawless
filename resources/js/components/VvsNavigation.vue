<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    current: {
        type: String,
        default: 'collection',
    },

    backHref: {
        type: String,
        default: null,
    },

    backLabel: {
        type: String,
        default: null,
    },

    watchHref: {
        type: String,
        default: null,
    },

    showBack: {
        type: Boolean,
        default: true,
    },
});

const page = usePage();

const translations = computed(() => page.props.translations);
const localizedRoutes = computed(() => page.props.localizedRoutes);

const resolvedBackHref = computed(
    () => props.backHref ?? localizedRoutes.value.watches,
);

const resolvedBackLabel = computed(
    () =>
        props.backLabel ??
        translations.value.vvs_navigation.collection,
);

const languages = [
    {
        code: 'FR',
        flag: '🇫🇷',
        label: 'Français',
        locale: 'fr_BE',
        hreflang: 'fr-BE',
    },
    {
        code: 'NL',
        flag: '🇳🇱',
        label: 'Nederlands',
        locale: 'nl_BE',
        hreflang: 'nl-BE',
    },
    {
        code: 'EN',
        flag: '🇬🇧',
        label: 'English',
        locale: 'en_BE',
        hreflang: 'en-BE',
    },
    {
        code: 'DE',
        flag: '🇩🇪',
        label: 'Deutsch',
        locale: 'de_BE',
        hreflang: 'de-BE',
    },
];

const languageLinks = computed(() => {
    const alternates = page.props.seo?.alternates ?? [];

    return languages
        .map((language) => ({
            ...language,
            href: alternates.find(
                (alternate) =>
                    alternate.hreflang === language.hreflang,
            )?.href,
        }))
        .filter((language) => language.href);
});

const currentLanguage = computed(
    () =>
        languageLinks.value.find(
            (language) => language.locale === page.props.locale,
        ) ??
        languages.find(
            (language) => language.locale === page.props.locale,
        ) ??
        languages[0],
);

const steps = computed(() => [
    {
        key: 'collection',
        label: translations.value.vvs_navigation.collection,
        href: localizedRoutes.value.watches,
        local: false,
    },
    {
        key: 'watch',
        label: translations.value.vvs_navigation.model,
        href: props.watchHref,
        local: false,
    },
    {
        key: 'reservation',
        label: translations.value.vvs_navigation.reservation,
        href: props.current === 'reservation' ? null : '#reservation',
        local: true,
    },
]);

const currentStepIndex = computed(() => {
    const index = steps.value.findIndex(
        (step) => step.key === props.current,
    );

    return index >= 0 ? index : 0;
});

const currentStep = computed(
    () => steps.value[currentStepIndex.value],
);
</script>

<template>
    <div
        class="sticky top-3 z-40 mx-auto mb-6 max-w-6xl px-3 sm:top-4 sm:px-4"
    >
        <div
            class="flex min-h-[60px] items-center justify-between gap-3 rounded-2xl border border-white/10 bg-[#0b0b0a]/95 px-3 py-2.5 shadow-[0_14px_38px_rgba(0,0,0,0.28)] backdrop-blur-xl sm:px-4"
        >
            <Link
                v-if="showBack"
                :href="resolvedBackHref"
                :aria-label="`${translations.vvs_navigation.back}: ${resolvedBackLabel}`"
                class="group flex min-w-0 shrink items-center gap-2.5 rounded-xl focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4bf99]"
            >
                <span
                    aria-hidden="true"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-[#9f895f]/50 text-[#d8c49d] transition group-hover:border-[#d4bf99]"
                >
                    <svg
                        viewBox="0 0 24 24"
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                </span>

                <span
                    class="truncate text-xs font-semibold text-zinc-200"
                >
                    {{ resolvedBackLabel }}
                </span>
            </Link>

            <div
                v-else
                class="truncate text-xs font-semibold tracking-[0.15em] text-zinc-200"
            >
                VVS FLAWLESS
            </div>

            <div class="flex min-w-0 items-center justify-end gap-2 sm:gap-3">
                <details
                    v-if="languageLinks.length"
                    class="group relative shrink-0"
                >
                    <summary
                        class="flex min-h-9 cursor-pointer list-none items-center gap-1.5 rounded-full border border-white/10 bg-[#10100f] px-2.5 py-1.5 text-[9px] font-bold text-zinc-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4bf99] [&::-webkit-details-marker]:hidden"
                        :aria-label="`${translations.language.label} : ${currentLanguage.code}`"
                    >
                        <span>{{ currentLanguage.code }}</span>

                        <svg
                            aria-hidden="true"
                            viewBox="0 0 20 20"
                            class="h-3 w-3 text-zinc-500 transition-transform group-open:rotate-180"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="m6 8 4 4 4-4" />
                        </svg>
                    </summary>

                    <div
                        class="absolute top-full right-0 z-50 mt-3 w-44 rounded-2xl border border-white/10 bg-zinc-950/98 p-1.5 shadow-2xl shadow-black/60"
                    >
                        <Link
                            v-for="language in languageLinks"
                            :key="language.hreflang"
                            :href="language.href"
                            :hreflang="language.hreflang"
                            :aria-current="
                                page.props.locale === language.locale
                                    ? 'page'
                                    : undefined
                            "
                            :class="[
                                'flex min-h-11 items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs transition focus-visible:outline-2 focus-visible:outline-[#d4bf99]',
                                page.props.locale === language.locale
                                    ? 'bg-[#d4bf99] text-[#181612]'
                                    : 'text-zinc-300 hover:bg-white/[0.05] hover:text-white',
                            ]"
                        >
                            <span aria-hidden="true">
                                {{ language.flag }}
                            </span>
                            <span class="font-semibold">
                                {{ language.label }}
                            </span>
                        </Link>
                    </div>
                </details>

                <nav
                    class="hidden items-center md:flex"
                    :aria-label="translations.vvs_navigation.label"
                >
                    <template
                        v-for="(step, index) in steps"
                        :key="step.key"
                    >
                        <a
                            v-if="step.href && step.local"
                            :href="step.href"
                            :aria-current="
                                step.key === current
                                    ? 'location'
                                    : undefined
                            "
                            :class="[
                                'relative rounded px-4 py-3 text-[11px] font-medium transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4bf99]',
                                step.key === current
                                    ? 'text-[#ddc99f] after:absolute after:right-4 after:bottom-1 after:left-4 after:h-px after:bg-[#bca267]'
                                    : 'text-zinc-500 hover:text-zinc-200',
                            ]"
                        >
                            {{ step.label }}
                        </a>

                        <Link
                            v-else-if="step.href"
                            :href="step.href"
                            :aria-current="
                                step.key === current
                                    ? 'page'
                                    : undefined
                            "
                            :class="[
                                'relative rounded px-4 py-3 text-[11px] font-medium transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4bf99]',
                                step.key === current
                                    ? 'text-[#ddc99f] after:absolute after:right-4 after:bottom-1 after:left-4 after:h-px after:bg-[#bca267]'
                                    : 'text-zinc-500 hover:text-zinc-200',
                            ]"
                        >
                            {{ step.label }}
                        </Link>

                        <span
                            v-else
                            :class="[
                                'relative px-4 py-3 text-[11px] font-medium',
                                step.key === current
                                    ? 'text-[#ddc99f] after:absolute after:right-4 after:bottom-1 after:left-4 after:h-px after:bg-[#bca267]'
                                    : 'text-zinc-600',
                            ]"
                        >
                            {{ step.label }}
                        </span>

                        <span
                            v-if="index < steps.length - 1"
                            aria-hidden="true"
                            class="h-1 w-1 rounded-full bg-zinc-800"
                        ></span>
                    </template>
                </nav>

                <div class="min-w-0 text-right md:hidden">
                    <p
                        class="truncate text-[10px] font-semibold text-[#ddc99f]"
                    >
                        {{ currentStep.label }}
                    </p>
                    <p class="mt-0.5 text-[9px] text-zinc-600">
                        {{ currentStepIndex + 1 }} / {{ steps.length }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>