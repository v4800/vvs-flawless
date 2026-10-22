<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({ seo: { type: Object, required: true } });
const page = usePage();
const translations = page.props.translations;
const mobileMenuOpen = ref(false);
const languages = [
    { code: 'FR', locale: 'fr_BE', hreflang: 'fr-BE', label: 'Français' },
    { code: 'NL', locale: 'nl_BE', hreflang: 'nl-BE', label: 'Nederlands' },
    { code: 'EN', locale: 'en_BE', hreflang: 'en-BE', label: 'English' },
    { code: 'DE', locale: 'de_BE', hreflang: 'de-BE', label: 'Deutsch' },
];
const languageOptions = computed(() =>
    languages
        .map((language) => ({
            ...language,
            href: (props.seo.alternates ?? []).find(
                (alternate) => alternate.hreflang === language.hreflang,
            )?.href,
        }))
        .filter((language) => language.href),
);
const currentLanguage = computed(
    () =>
        languages.find((language) => language.locale === page.props.locale) ??
        languages[0],
);
const navigation = computed(() => [
    { href: '#collection', label: translations.navigation.watches },
    { href: '#about', label: translations.navigation.about },
    { href: '#pickup', label: translations.navigation.delivery },
    { href: '#contact', label: translations.contact.eyebrow },
]);
</script>

<template>
    <header class="vvs-site-header" @keydown.esc="mobileMenuOpen = false">
        <div class="vvs-site-header__inner">
            <Link
                :href="page.props.localizedRoutes.watches"
                class="vvs-site-header__brand"
                aria-label="VVS FLAWLESS"
                @click="mobileMenuOpen = false"
            >
                <img
                    src="/images/vvs-flawless-profile-112.webp"
                    alt="VVS FLAWLESS"
                    width="64"
                    height="64"
                />
                <span aria-hidden="true"
                    >VVS FLAWLESS<span class="vvs-site-header__country">{{
                        page.props.landingCopy.brand_country
                    }}</span></span
                >
            </Link>
            <nav
                class="vvs-site-header__desktop"
                :aria-label="translations.navigation.main_label"
            >
                <a
                    v-for="item in navigation"
                    :key="item.href"
                    :href="item.href"
                    >{{ item.label }}</a
                >
            </nav>
            <div class="vvs-site-header__tools">
                <details
                    v-if="languageOptions.length"
                    class="vvs-language-menu"
                >
                    <summary
                        :aria-label="`${translations.language.label} : ${currentLanguage.code}`"
                    >
                        {{ currentLanguage.code }}
                        <span aria-hidden="true">⌄</span>
                    </summary>
                    <div class="vvs-language-menu__options">
                        <Link
                            v-for="language in languageOptions"
                            :key="language.locale"
                            :href="language.href"
                            :hreflang="language.hreflang"
                            :lang="language.hreflang"
                            :aria-current="
                                language.locale === page.props.locale
                                    ? 'page'
                                    : undefined
                            "
                            >{{ language.label }}</Link
                        >
                    </div>
                </details>
                <button
                    type="button"
                    class="vvs-site-header__toggle"
                    :aria-label="translations.navigation.main_label"
                    :aria-expanded="mobileMenuOpen"
                    aria-controls="vvs-mobile-menu"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        aria-hidden="true"
                    >
                        <path v-if="mobileMenuOpen" d="m6 6 12 12M6 18 18 6" />
                        <path v-else d="M4 8h16M4 16h16" />
                    </svg>
                </button>
            </div>
        </div>
        <nav
            v-if="mobileMenuOpen"
            id="vvs-mobile-menu"
            class="vvs-site-header__mobile"
            :aria-label="translations.navigation.main_label"
        >
            <a
                v-for="item in navigation"
                :key="item.href"
                :href="item.href"
                @click="mobileMenuOpen = false"
                >{{ item.label }} <span aria-hidden="true">↗</span></a
            >
        </nav>
    </header>
</template>
