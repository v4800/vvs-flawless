<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { animate } from 'animejs';
import { computed, onMounted, ref } from 'vue';

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
const guideLinks = computed(() => page.props.guideLinks);
const localizedRoutes = computed(() => page.props.localizedRoutes);

const resolvedBackHref = computed(
    () => props.backHref ?? localizedRoutes.value.watches,
);

const resolvedBackLabel = computed(
    () => props.backLabel ?? translations.value.vvs_navigation.collection,
);

const languageLinks = computed(() => {
    const alternates = page.props.seo?.alternates ?? [];
   const languages = [
    { code: 'FR', flag: '🇫🇷', locale: 'fr_BE', hreflang: 'fr-BE' },
    { code: 'NL', flag: '🇳🇱', locale: 'nl_BE', hreflang: 'nl-BE' },
    { code: 'EN', flag: '🇬🇧', locale: 'en_BE', hreflang: 'en-BE' },
    { code: 'DE', flag: '🇩🇪', locale: 'de_BE', hreflang: 'de-BE' },
];

    return languages
        .map((language) => ({
            ...language,
            href: alternates.find(
                (alternate) => alternate.hreflang === language.hreflang,
            )?.href,
        }))
        .filter((language) => language.href);
});

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
        href: props.watchHref ? '#model' : null,
        local: true,
    },
    {
        key: 'reservation',
        label: translations.value.vvs_navigation.reservation,
        href: props.watchHref ? '#reservation' : null,
        local: true,
    },
]);

const navigation = ref(null);

onMounted(() => {
    if (!navigation.value) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        navigation.value.style.opacity = '1';

        return;
    }

    animate(navigation.value, {
        opacity: [0, 1],
        y: [-12, 0],
        duration: 650,
        ease: 'outQuart',
    });
});
</script>

<template>
    <div
        ref="navigation"
        class="sticky top-20 z-40 sm:top-24 mx-auto mb-8 max-w-6xl px-4 opacity-0 sm:px-0"
    >
        <div
            class="vvs-luxury-card flex min-h-[64px] flex-col items-stretch gap-3 rounded-2xl border px-4 py-3 backdrop-blur-xl sm:flex-row sm:items-center sm:justify-between sm:px-5"
        >
            <Link
                v-if="showBack"
                :href="resolvedBackHref"
                :aria-label="`${translations.vvs_navigation.back}: ${resolvedBackLabel}`"
                class="group flex shrink-0 items-center gap-3 rounded-xl focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
            >
                <span
                    aria-hidden="true"
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-amber-300/30 bg-amber-300/[0.06] text-lg text-amber-200 transition-all duration-300 group-hover:-translate-x-1 group-hover:border-amber-300 group-hover:bg-amber-300 group-hover:text-black group-hover:shadow-[0_0_25px_rgba(252,211,77,0.25)]"
                >
                    ←
                </span>

                <div class="hidden sm:block">
                    <p
                        class="text-[9px] font-bold tracking-[0.25em] text-zinc-400 uppercase"
                    >
                        {{ translations.vvs_navigation.back }}
                    </p>

                    <p
                        class="text-sm font-semibold text-zinc-200 transition group-hover:text-amber-200"
                    >
                        {{ resolvedBackLabel }}
                    </p>
                </div>
            </Link>

            <div v-else class="flex items-center gap-3">
                <div
                    aria-hidden="true"
                    class="h-2 w-2 rounded-full bg-amber-300 shadow-[0_0_14px_rgba(252,211,77,0.8)]"
                ></div>

                <span
                    class="text-xs font-semibold tracking-[0.18em] text-white"
                >
                    VVS FLAWLESS
                </span>
            </div>

            <div class="flex min-w-0 w-full flex-col items-stretch gap-3 sm:w-auto sm:flex-row sm:items-center sm:justify-end">
                <nav
                    class="hidden shrink-0 items-center gap-3 lg:flex"
                    :aria-label="translations.navigation.main_label"
                >
                    <Link
                        :href="localizedRoutes.about"
                        class="rounded text-[10px] font-semibold tracking-[0.08em] text-zinc-400 uppercase transition hover:text-amber-200 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ translations.navigation.about }}
                    </Link>

                    <span class="text-zinc-800" aria-hidden="true">•</span>

                    <Link
                        :href="localizedRoutes.diamondGuide"
                        class="rounded text-[10px] font-semibold tracking-[0.08em] text-zinc-400 uppercase transition hover:text-amber-200 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ guideLinks.eyebrow }}
                    </Link>
                </nav>

                <nav
                    v-if="showBack && languageLinks.length"
                    class="flex shrink-0 items-center rounded-full border border-white/10 p-0.5 text-[9px] font-bold tracking-wide"
                    :aria-label="translations.language.label"
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
                            'rounded-full px-2 py-1.5 transition focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:outline-none',
                            page.props.locale === language.locale
                                ? 'bg-amber-300 text-black'
                                : 'text-zinc-500 hover:text-white',
                        ]"
                    >
                        {{ language.code }}
                    </Link>
                </nav>

                <nav
                    class="flex min-w-0 w-full items-center justify-between gap-2 overflow-hidden border-t border-white/10 pt-3 sm:w-auto sm:justify-end sm:border-t-0 sm:pt-0"
                    :aria-label="translations.vvs_navigation.label"
                >
                    <template v-for="(step, index) in steps" :key="step.key">
                        <a
                            v-if="step.href && step.local"
                            :href="step.href"
                            :aria-current="
                                step.key === current ? 'location' : undefined
                            "
                            :class="[
                                'inline-flex min-w-0 flex-1 items-center justify-center rounded px-1 py-1 text-[10px] font-medium whitespace-nowrap transition focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none sm:flex-none sm:text-xs',
                                step.key === current
                                    ? 'text-amber-200'
                                    : 'text-zinc-400 hover:text-amber-200',
                            ]"
                        >
                            {{ step.label }}
                        </a>

                        <Link
                            v-else-if="step.href"
                            :href="step.href"
                            :aria-current="
                                step.key === current ? 'page' : undefined
                            "
                            :class="[
                                'inline-flex min-w-0 flex-1 items-center justify-center rounded px-1 py-1 text-[10px] font-medium whitespace-nowrap transition focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none sm:flex-none sm:text-xs',
                                step.key === current
                                    ? 'text-amber-200'
                                    : 'text-zinc-400 hover:text-amber-200',
                            ]"
                        >
                            {{ step.label }}
                        </Link>

                        <span
                            v-else
                            class="inline-flex min-w-0 flex-1 items-center justify-center text-[10px] font-semibold whitespace-nowrap text-zinc-500 sm:flex-none sm:text-xs"
                        >
                            {{ step.label }}
                        </span>

                        <span
                            v-if="index < steps.length - 1"
                            aria-hidden="true"
                            class="shrink-0 text-[10px] text-zinc-700 sm:mx-2 sm:text-xs"
                        >
                            ›
                        </span>
                    </template>

                    <div
                        aria-hidden="true"
                        class="ml-3 hidden h-px w-8 bg-gradient-to-r from-amber-300/70 to-transparent md:block"
                    ></div>
                </nav>
            </div>
        </div>
    </div>
</template>
