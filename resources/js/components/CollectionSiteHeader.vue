<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const translations = page.props.translations;
const landingCopy = page.props.landingCopy;
const localizedRoutes = page.props.localizedRoutes;

const languageLinks = computed(() => {
    const alternates = props.seo.alternates ?? [];

    return {
        fr: alternates.find((alternate) => alternate.hreflang === 'fr-BE')
            ?.href,
        nl: alternates.find((alternate) => alternate.hreflang === 'nl-BE')
            ?.href,
        en: alternates.find((alternate) => alternate.hreflang === 'en-BE')
            ?.href,
        de: alternates.find((alternate) => alternate.hreflang === 'de-BE')
            ?.href,
    };
});
</script>

<template>
    <header
        class="site-header sticky top-0 z-50 border-b border-white/10 bg-black/90 backdrop-blur-xl"
    >
        <div
            class="mx-auto flex min-h-20 max-w-[1500px] items-center justify-between gap-3 px-4 py-3 sm:px-5 md:px-8"
        >
            <Link
                :href="localizedRoutes.watches"
                class="group flex min-w-0 items-center gap-2.5 rounded focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none sm:gap-3"
            >
                <img
                    src="/images/vvs-flawless-profile.webp"
                    alt="VVS FLAWLESS"
                    width="44"
                    height="44"
                    class="h-10 w-10 shrink-0 rounded-lg object-cover sm:h-11 sm:w-11"
                />

                <div class="min-w-0 leading-none">
                    <span
                        class="block whitespace-nowrap text-[15px] font-semibold tracking-[0.06em] text-white min-[380px]:text-base sm:text-xl sm:tracking-[0.08em] md:text-2xl"
                    >
                        VVS FLAWLESS
                    </span>

                    <span
                        class="mt-1.5 block whitespace-nowrap text-[8px] tracking-[0.3em] text-amber-300/80 uppercase sm:text-[9px] sm:tracking-[0.42em]"
                    >
                        {{ landingCopy.brand_country }}
                    </span>
                </div>
            </Link>

            <nav
                class="hidden items-center gap-10 text-sm font-medium text-zinc-300 md:flex"
                :aria-label="translations.navigation.main_label"
            >
                <a
                    href="#collection"
                    class="vvs-nav-link rounded focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                >
                    {{ translations.navigation.watches }}
                </a>
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

            <nav
                class="flex shrink-0 items-center rounded-full border border-white/10 p-0.5 text-[9px] font-bold tracking-wider sm:p-1 sm:text-[10px]"
                :aria-label="translations.language.label"
            >
                <Link
                    v-if="languageLinks.fr"
                    :href="languageLinks.fr"
                    hreflang="fr-BE"
                    :aria-current="
                        page.props.locale === 'fr_BE' ? 'page' : undefined
                    "
                    :class="[
                        'rounded-full px-2 py-1.5 transition focus-visible:ring-2 sm:px-2.5 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        page.props.locale === 'fr_BE'
                            ? 'bg-amber-300 text-black'
                            : 'text-zinc-400 hover:text-white',
                    ]"
                >
                    FR
                </Link>
                <Link
                    v-if="languageLinks.nl"
                    :href="languageLinks.nl"
                    hreflang="nl-BE"
                    :aria-current="
                        page.props.locale === 'nl_BE' ? 'page' : undefined
                    "
                    :class="[
                        'rounded-full px-2 py-1.5 transition focus-visible:ring-2 sm:px-2.5 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        page.props.locale === 'nl_BE'
                            ? 'bg-amber-300 text-black'
                            : 'text-zinc-400 hover:text-white',
                    ]"
                >
                    NL
                </Link>
                <Link
                    v-if="languageLinks.en"
                    :href="languageLinks.en"
                    hreflang="en-BE"
                    :aria-current="
                        page.props.locale === 'en_BE' ? 'page' : undefined
                    "
                    :class="[
                        'rounded-full px-2 py-1.5 transition focus-visible:ring-2 sm:px-2.5 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        page.props.locale === 'en_BE'
                            ? 'bg-amber-300 text-black'
                            : 'text-zinc-400 hover:text-white',
                    ]"
                >
                    EN
                </Link>
                <Link
                    v-if="languageLinks.de"
                    :href="languageLinks.de"
                    hreflang="de-BE"
                    :aria-current="
                        page.props.locale === 'de_BE' ? 'page' : undefined
                    "
                    :class="[
                        'rounded-full px-2 py-1.5 transition focus-visible:ring-2 sm:px-2.5 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        page.props.locale === 'de_BE'
                            ? 'bg-amber-300 text-black'
                            : 'text-zinc-400 hover:text-white',
                    ]"
                >
                    DE
                </Link>
            </nav>
        </div>
    </header>
</template>
