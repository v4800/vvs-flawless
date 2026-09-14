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
        { code: 'FR', locale: 'fr_BE', hreflang: 'fr-BE' },
        { code: 'NL', locale: 'nl_BE', hreflang: 'nl-BE' },
        { code: 'EN', locale: 'en_BE', hreflang: 'en-BE' },
        { code: 'DE', locale: 'de_BE', hreflang: 'de-BE' },
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
        class="sticky top-4 z-40 mx-auto mb-8 max-w-6xl px-4 opacity-0 sm:px-0"
    >
        <div
            class="vvs-luxury-card flex min-h-[64px] items-center justify-between gap-3 rounded-2xl border px-4 py-3 backdrop-blur-xl sm:px-5"
        >
            <Link
                v-if="showBack"
                :href="resolvedBackHref"
                :aria-label="`${translations.vvs_navigation.back}: ${resolvedBackLabel}`"
                class="group flex shrink-0 items-center gap-3 rounded-xl focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
            >
                <span
                    aria-hidden="true"
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-amber-300/30 bg-amber-300/[0.06] text-amber-200 transition-all duration-300 group-hover:-translate-x-0.5 group-hover:border-amber-300/70 group-hover:bg-amber-300/[0.12] group-hover:shadow-[0_0_24px_rgba(252,211,77,0.18)]"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-0.5"
                    >
                        <path d="m14.5 5-7 7 7 7" />
                    </svg>
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

            <div class="flex min-w-0 items-center justify-end gap-3">
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
                    class="hidden min-w-0 items-center justify-end overflow-hidden sm:flex"
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
                                'hidden rounded text-xs font-medium whitespace-nowrap transition focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none sm:inline',
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
                                'hidden rounded text-xs font-medium whitespace-nowrap transition focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none sm:inline',
                                step.key === current
                                    ? 'text-amber-200'
                                    : 'text-zinc-400 hover:text-amber-200',
                            ]"
                        >
                            {{ step.label }}
                        </Link>

                        <span
                            v-else
                            class="hidden text-xs font-semibold whitespace-nowrap text-zinc-500 sm:inline"
                        >
                            {{ step.label }}
                        </span>

                        <span
                            v-if="index < steps.length - 1"
                            aria-hidden="true"
                            class="mx-2 hidden items-center text-zinc-700 sm:inline-flex"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-3 w-3"
                            >
                                <path d="m9.5 5 7 7-7 7" />
                            </svg>
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
