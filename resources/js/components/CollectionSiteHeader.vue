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

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
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
            class="relative mx-auto flex min-h-[72px] max-w-[1500px] items-center justify-between gap-3 px-3 sm:px-5 md:px-8 lg:min-h-[88px]"
        >
            <div class="relative z-20 flex min-w-0 items-center">
                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/[0.035] text-zinc-200 transition hover:border-[#cdb98f]/40 hover:text-[#ddc99f] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4bf99] lg:hidden"
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
                    <a
                        href="#concept"
                        class="vvs-nav-link rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#d4bf99]"
                    >
                        {{ translations.navigation.about }}
                    </a>

                    <a
                        href="#services"
                        class="vvs-nav-link rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#d4bf99]"
                    >
                        {{ translations.navigation.delivery }}
                    </a>

                    <a
                        href="#contact"
                        class="vvs-nav-link rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#d4bf99]"
                    >
                        {{ translations.contact.eyebrow }}
                    </a>
                </nav>
            </div>

            <Link
                :href="localizedRoutes.watches"
                :class="[
                    'absolute left-1/2 z-30 -translate-x-1/2 -translate-y-1/2 rounded-[1.15rem] transition-[top] duration-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#d4bf99] motion-reduce:transition-none',
                    isScrolled ? 'top-1/2' : 'top-[57%]',
                ]"
                aria-label="VVS FLAWLESS"
                @click="closeMobileMenu"
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

            <details
                v-if="languageOptions.length"
                class="group relative z-20 shrink-0"
            >
                <summary
                    class="flex min-h-10 cursor-pointer list-none items-center gap-1.5 rounded-full border border-white/10 bg-[#0c0c0b] px-3 py-2 text-[10px] font-bold text-zinc-200 transition hover:border-[#cdb98f]/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4bf99] [&::-webkit-details-marker]:hidden"
                    :aria-label="`${translations.language.label} : ${currentLanguage.code}`"
                >
                    <span aria-hidden="true">
                        {{ currentLanguage.flag }}
                    </span>

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
                    class="absolute top-full right-0 mt-3 w-44 rounded-2xl border border-white/10 bg-zinc-950/98 p-1.5 shadow-2xl shadow-black/60 backdrop-blur-xl"
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
                            'flex min-h-11 items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs transition focus-visible:outline-2 focus-visible:outline-[#d4bf99]',
                            page.props.locale === language.locale
                                ? 'bg-[#d4bf99] text-[#181612]'
                                : 'text-zinc-300 hover:bg-white/[0.05] hover:text-white',
                        ]"
                    >
                        <span aria-hidden="true">{{ language.flag }}</span>
                        <span class="font-semibold">{{ language.label }}</span>
                    </Link>
                </div>
            </details>
        </div>

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
                    href="#concept"
                    class="rounded-xl px-3 py-3 text-sm font-medium text-zinc-300 transition hover:bg-white/[0.05] hover:text-white focus-visible:outline-2 focus-visible:outline-[#d4bf99]"
                    @click="closeMobileMenu"
                >
                    {{ translations.navigation.about }}
                </a>

                <a
                    href="#services"
                    class="rounded-xl px-3 py-3 text-sm font-medium text-zinc-300 transition hover:bg-white/[0.05] hover:text-white focus-visible:outline-2 focus-visible:outline-[#d4bf99]"
                    @click="closeMobileMenu"
                >
                    {{ translations.navigation.delivery }}
                </a>

                <a
                    href="#contact"
                    class="rounded-xl px-3 py-3 text-sm font-medium text-zinc-300 transition hover:bg-white/[0.05] hover:text-white focus-visible:outline-2 focus-visible:outline-[#d4bf99]"
                    @click="closeMobileMenu"
                >
                    {{ translations.contact.eyebrow }}
                </a>
            </nav>
        </div>
    </header>
</template>
