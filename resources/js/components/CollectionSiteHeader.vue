<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({
    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const translations = page.props.translations;
const localizedRoutes = page.props.localizedRoutes;

const isScrolled = ref(false);
const mobileMenuOpen = ref(false);
const catalogueOpen = ref(false);

let scrollFrame = null;

const languages = [
    {
        code: 'FR',
        locale: 'fr_BE',
        hreflang: 'fr-BE',
        flag: '🇫🇷',
        label: 'Français',
    },
    {
        code: 'NL',
        locale: 'nl_BE',
        hreflang: 'nl-BE',
        flag: '🇳🇱',
        label: 'Nederlands',
    },
    {
        code: 'EN',
        locale: 'en_BE',
        hreflang: 'en-BE',
        flag: '🇬🇧',
        label: 'English',
    },
    {
        code: 'DE',
        locale: 'de_BE',
        hreflang: 'de-BE',
        flag: '🇩🇪',
        label: 'Deutsch',
    },
];

const languageOptions = computed(() => {
    const alternates = props.seo.alternates ?? [];

    return languages
        .map((language) => ({
            ...language,
            href: alternates.find(
                (alternate) => alternate.hreflang === language.hreflang,
            )?.href,
        }))
        .filter((language) => language.href);
});

const currentLanguage = computed(
    () =>
        languageOptions.value.find(
            (language) => language.locale === page.props.locale,
        ) ??
        languages.find((language) => language.locale === page.props.locale) ??
        languages[0],
);

const catalogueDescription = computed(
    () =>
        translations.navigation.catalogue_description ??
        translations.collection.description,
);

const syncScrollState = () => {
    isScrolled.value = window.scrollY > 56;
};

const handleScroll = () => {
    if (scrollFrame !== null) {
        return;
    }

    scrollFrame = window.requestAnimationFrame(() => {
        syncScrollState();
        scrollFrame = null;
    });
};

const closeNavigationPanels = () => {
    mobileMenuOpen.value = false;
    catalogueOpen.value = false;
};

onMounted(() => {
    syncScrollState();

    window.addEventListener('scroll', handleScroll, {
        passive: true,
    });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);

    if (scrollFrame !== null) {
        window.cancelAnimationFrame(scrollFrame);
    }
});
</script>

<template>
    <header
        class="site-header sticky top-0 z-50 border-b border-white/10 bg-black/90 backdrop-blur-xl"
    >
        <div
            :class="[
                'relative mx-auto flex max-w-[1500px] items-center justify-between gap-3 px-3 transition-[min-height] duration-300 motion-reduce:transition-none sm:px-5 md:px-8',
                isScrolled
                    ? 'min-h-16 lg:min-h-[72px]'
                    : 'min-h-[72px] lg:min-h-[88px]',
            ]"
        >
            <!-- GAUCHE : menu mobile / navigation desktop -->
            <div class="relative z-20 flex min-w-0 items-center">
                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/[0.035] text-zinc-200 transition hover:border-amber-300/40 hover:text-amber-200 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none lg:hidden"
                    :aria-label="translations.navigation.main_label"
                    aria-controls="vvs-mobile-menu"
                    :aria-expanded="mobileMenuOpen"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <svg
                        aria-hidden="true"
                        viewBox="0 0 24 24"
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    >
                        <path d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>

                <nav
                    class="hidden items-center gap-8 text-sm font-medium text-zinc-300 lg:flex"
                    :aria-label="translations.navigation.main_label"
                >
                    <div class="relative">
                        <button
                            type="button"
                            class="vvs-nav-link flex items-center gap-1.5 rounded focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                            aria-controls="vvs-catalogue-menu"
                            :aria-expanded="catalogueOpen"
                            @click="catalogueOpen = !catalogueOpen"
                        >
                            {{ translations.navigation.watches }}

                            <span
                                aria-hidden="true"
                                :class="[
                                    'text-[10px] text-zinc-500 transition-transform duration-200 motion-reduce:transition-none',
                                    catalogueOpen ? 'rotate-180' : '',
                                ]"
                            >
                                ▾
                            </span>
                        </button>

                        <div
                            v-if="catalogueOpen"
                            id="vvs-catalogue-menu"
                            class="absolute top-full left-0 mt-4 w-72 rounded-2xl border border-white/10 bg-zinc-950/95 p-2 shadow-2xl shadow-black/50 backdrop-blur-xl"
                        >
                            <a
                                href="#collection"
                                class="block rounded-xl px-4 py-3 transition hover:bg-white/[0.05] focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:outline-none"
                                @click="closeNavigationPanels"
                            >
                                <span
                                    class="block text-sm font-semibold text-white"
                                >
                                    {{ translations.navigation.watches }}
                                </span>

                                <span
                                    class="mt-1.5 block text-[11px] leading-5 text-zinc-400"
                                >
                                    {{ catalogueDescription }}
                                </span>
                            </a>
                        </div>
                    </div>

                    <a
                        href="#concept"
                        class="vvs-nav-link rounded focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ translations.navigation.about }}
                    </a>

                    <a
                        href="#services"
                        class="vvs-nav-link rounded focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ translations.navigation.delivery }}
                    </a>
                </nav>
            </div>

            <!-- CENTRE : logo VVS -->
            <Link
                :href="localizedRoutes.watches"
                :class="[
                    'absolute left-1/2 z-30 -translate-x-1/2 -translate-y-1/2 rounded-[1.15rem] transition-[top] duration-300 motion-reduce:transition-none focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                    isScrolled ? 'top-1/2' : 'top-[57%]',
                ]"
                aria-label="VVS FLAWLESS"
                @click="closeNavigationPanels"
            >
                <img
                    src="/images/vvs-flawless-profile.webp"
                    alt="VVS FLAWLESS"
                    width="112"
                    height="112"
                    :class="[
                        'rounded-[1.15rem] object-cover shadow-[0_16px_45px_rgba(0,0,0,0.45)] ring-1 ring-white/10 transition-[width,height,box-shadow] duration-300 motion-reduce:transition-none',
                        isScrolled
                            ? 'h-11 w-11 sm:h-12 sm:w-12 lg:h-14 lg:w-14'
                            : 'h-20 w-20 sm:h-[88px] sm:w-[88px] lg:h-28 lg:w-28',
                    ]"
                />
            </Link>

            <!-- DROITE MOBILE : langue compacte -->
            <details
                class="group relative z-20 shrink-0 lg:hidden"
            >
                <summary
                    class="flex cursor-pointer list-none items-center gap-1.5 rounded-full border border-white/10 bg-black/30 px-2.5 py-2 text-[10px] font-bold text-zinc-200 transition hover:border-amber-300/30 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:outline-none [&::-webkit-details-marker]:hidden"
                    :aria-label="translations.language.label"
                >
                    <span aria-hidden="true">
                        {{ currentLanguage.flag }}
                    </span>

                    <span>
                        {{ currentLanguage.code }}
                    </span>

                    <span
                        aria-hidden="true"
                        class="text-[8px] text-zinc-500 transition-transform group-open:rotate-180"
                    >
                        ▾
                    </span>
                </summary>

                <div
                    class="absolute top-full right-0 mt-3 w-44 rounded-2xl border border-white/10 bg-zinc-950/95 p-1.5 shadow-2xl shadow-black/50 backdrop-blur-xl"
                >
                    <Link
                        v-for="language in languageOptions"
                        :key="language.hreflang"
                        :href="language.href"
                        :hreflang="language.hreflang"
                        :aria-current="
                            page.props.locale === language.locale
                                ? 'page'
                                : undefined
                        "
                        :class="[
                            'flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs transition focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:outline-none',
                            page.props.locale === language.locale
                                ? 'bg-amber-300 text-black'
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

            <!-- DROITE DESKTOP : langues horizontales -->
            <nav
                v-if="languageOptions.length"
                class="relative z-20 hidden shrink-0 items-center rounded-full border border-white/10 bg-black/20 p-1 text-[9px] font-bold tracking-wide lg:flex"
                :aria-label="translations.language.label"
            >
                <Link
                    v-for="language in languageOptions"
                    :key="language.hreflang"
                    :href="language.href"
                    :hreflang="language.hreflang"
                    :aria-current="
                        page.props.locale === language.locale
                            ? 'page'
                            : undefined
                    "
                    :class="[
                        'rounded-full px-2.5 py-1.5 transition focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        page.props.locale === language.locale
                            ? 'bg-amber-300 text-black'
                            : 'text-zinc-400 hover:text-white',
                    ]"
                >
                    {{ language.code }}
                </Link>
            </nav>
        </div>

        <!-- MENU MOBILE -->
        <div
            v-if="mobileMenuOpen"
            id="vvs-mobile-menu"
            class="border-t border-white/10 bg-black/95 px-3 pt-3 pb-4 backdrop-blur-xl sm:px-5 lg:hidden"
        >
            <nav
                class="mx-auto grid max-w-[1500px] gap-1"
                :aria-label="translations.navigation.main_label"
            >
                <a
                    href="#collection"
                    class="rounded-xl px-3 py-3 transition hover:bg-white/[0.05] focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:outline-none"
                    @click="closeNavigationPanels"
                >
                    <span class="block text-sm font-semibold text-white">
                        {{ translations.navigation.watches }}
                    </span>

                    <span
                        class="mt-1 block max-w-xl text-[11px] leading-5 text-zinc-400"
                    >
                        {{ catalogueDescription }}
                    </span>
                </a>

                <a
                    href="#concept"
                    class="rounded-xl px-3 py-2.5 text-sm font-medium text-zinc-300 transition hover:bg-white/[0.05] hover:text-white focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:outline-none"
                    @click="closeNavigationPanels"
                >
                    {{ translations.navigation.about }}
                </a>

                <a
                    href="#services"
                    class="rounded-xl px-3 py-2.5 text-sm font-medium text-zinc-300 transition hover:bg-white/[0.05] hover:text-white focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:outline-none"
                    @click="closeNavigationPanels"
                >
                    {{ translations.navigation.delivery }}
                </a>
            </nav>
        </div>
    </header>
</template>