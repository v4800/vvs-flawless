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
    };
});
</script>

<template>
    <header
        class="site-header sticky top-0 z-50 border-b border-white/10 bg-black/90 backdrop-blur-xl"
    >
        <div
            class="mx-auto flex h-20 max-w-[1500px] items-center justify-between px-5 md:px-8"
        >
            <Link
                :href="localizedRoutes.watches"
                class="group flex flex-col leading-none"
            >
                <span
                    class="text-xl font-semibold tracking-[0.08em] text-white md:text-2xl"
                >
                    VVS FLAWLESS
                </span>
                <span
                    class="mt-1 text-[9px] tracking-[0.42em] text-amber-300/80 uppercase"
                >
                    {{ landingCopy.brand_country }}
                </span>
            </Link>

            <nav
                class="hidden items-center gap-10 text-sm font-medium text-zinc-300 md:flex"
                :aria-label="translations.navigation.main_label"
            >
                <a href="#collection" class="vvs-nav-link">
                    {{ translations.navigation.watches }}
                </a>
                <a href="#concept" class="vvs-nav-link">
                    {{ translations.navigation.about }}
                </a>
                <a href="#services" class="vvs-nav-link">
                    {{ translations.navigation.delivery }}
                </a>
            </nav>

            <div class="flex items-center gap-3">
                <nav
                    class="flex shrink-0 items-center rounded-full border border-white/10 p-1 text-[10px] font-bold tracking-wider"
                    :aria-label="translations.language.label"
                >
                    <Link
                        v-if="languageLinks.fr"
                        :href="languageLinks.fr"
                        :class="[
                            'rounded-full px-2.5 py-1.5 transition',
                            page.props.locale === 'fr_BE'
                                ? 'bg-amber-300 text-black'
                                : 'text-zinc-500 hover:text-white',
                        ]"
                    >
                        FR
                    </Link>
                    <Link
                        v-if="languageLinks.nl"
                        :href="languageLinks.nl"
                        :class="[
                            'rounded-full px-2.5 py-1.5 transition',
                            page.props.locale === 'nl_BE'
                                ? 'bg-amber-300 text-black'
                                : 'text-zinc-500 hover:text-white',
                        ]"
                    >
                        NL
                    </Link>
                    <Link
                        v-if="languageLinks.en"
                        :href="languageLinks.en"
                        :class="[
                            'rounded-full px-2.5 py-1.5 transition',
                            page.props.locale === 'en_BE'
                                ? 'bg-amber-300 text-black'
                                : 'text-zinc-500 hover:text-white',
                        ]"
                    >
                        EN
                    </Link>
                </nav>

                <div
                    class="hidden items-center gap-2 lg:flex"
                    :aria-label="landingCopy.handover_aria"
                >
                    <Link
                        v-if="languageLinks.fr"
                        :href="languageLinks.fr"
                        class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.025] px-2 py-1 transition hover:border-amber-300/30"
                        :title="landingCopy.handover_be_title"
                    >
                        <div
                            class="flex h-4 w-6 overflow-hidden rounded-[2px] border border-white/10"
                        >
                            <span class="flex-1 bg-black"></span>
                            <span class="flex-1 bg-yellow-400"></span>
                            <span class="flex-1 bg-red-600"></span>
                        </div>
                        <span
                            class="text-[8px] font-bold tracking-wider text-zinc-500"
                            >BE</span
                        >
                    </Link>

                    <Link
                        v-if="languageLinks.fr"
                        :href="languageLinks.fr"
                        class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.025] px-2 py-1 transition hover:border-amber-300/30"
                        :title="landingCopy.handover_fr_title"
                    >
                        <div
                            class="flex h-4 w-6 overflow-hidden rounded-[2px] border border-white/10"
                        >
                            <span class="flex-1 bg-blue-700"></span>
                            <span class="flex-1 bg-white"></span>
                            <span class="flex-1 bg-red-600"></span>
                        </div>
                        <span
                            class="text-[8px] font-bold tracking-wider text-zinc-500"
                            >FR</span
                        >
                    </Link>

                    <Link
                        v-if="languageLinks.nl"
                        :href="languageLinks.nl"
                        class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.025] px-2 py-1 transition hover:border-amber-300/30"
                        :title="landingCopy.handover_nl_title"
                    >
                        <div
                            class="flex h-4 w-6 flex-col overflow-hidden rounded-[2px] border border-white/10"
                        >
                            <span class="flex-1 bg-red-600"></span>
                            <span class="flex-1 bg-white"></span>
                            <span class="flex-1 bg-blue-700"></span>
                        </div>
                        <span
                            class="text-[8px] font-bold tracking-wider text-zinc-500"
                            >NL</span
                        >
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>
