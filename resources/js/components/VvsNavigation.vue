<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { animate } from 'animejs';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

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
const languageLinks = computed(() => {
    const alternates = page.props.seo?.alternates ?? [];

    return {
        fr: alternates.find((alternate) => alternate.hreflang === 'fr-BE')
            ?.href,
        nl: alternates.find((alternate) => alternate.hreflang === 'nl-BE')
            ?.href,
        en: alternates.find((alternate) => alternate.hreflang === 'en-BE')
            ?.href,
    };
});

const resolvedBackHref = computed(
    () => props.backHref ?? localizedRoutes.value.watches,
);

const resolvedBackLabel = computed(
    () => props.backLabel ?? translations.value.vvs_navigation.collection,
);

const navigation = ref(null);
const headerControlCleanups = [];

const visitLanguage = (href) => {
    if (href) {
        router.visit(href);
    }
};

const setupHeaderControls = () => {
    const header = document.querySelector('.site-header');

    if (!header) {
        return;
    }

    const languageNav = Array.from(header.querySelectorAll('nav')).find(
        (nav) =>
            nav.getAttribute('aria-label') ===
            translations.value.language.label,
    );

    if (languageNav) {
        languageNav.style.flexShrink = '0';
        languageNav.style.whiteSpace = 'nowrap';
        languageNav.style.overflow = 'visible';

        languageNav.querySelectorAll('a').forEach((link) => {
            link.style.paddingInline = '0.55rem';
        });

        const hasEnglish = Array.from(languageNav.querySelectorAll('a')).some(
            (link) => link.textContent?.trim().toUpperCase() === 'EN',
        );

        if (!hasEnglish && languageLinks.value.en) {
            const englishLink = document.createElement('a');
            englishLink.href = languageLinks.value.en;
            englishLink.textContent = 'EN';
            englishLink.className =
                page.props.locale === 'en_BE'
                    ? 'rounded-full bg-amber-300 px-2 py-1.5 text-black transition'
                    : 'rounded-full px-2 py-1.5 text-zinc-500 transition hover:text-white';
            englishLink.setAttribute('aria-label', 'English');

            const handleEnglishClick = (event) => {
                event.preventDefault();
                visitLanguage(languageLinks.value.en);
            };

            englishLink.addEventListener('click', handleEnglishClick);
            languageNav.appendChild(englishLink);

            headerControlCleanups.push(() => {
                englishLink.removeEventListener('click', handleEnglishClick);
                englishLink.remove();
            });
        }
    }

    const flagLanguageLinks = [
        {
            selector: '[title="Belgique"]',
            href: languageLinks.value.fr,
            label: 'Français',
        },
        {
            selector: '[title="Nord de la France"]',
            href: languageLinks.value.fr,
            label: 'Français',
        },
        {
            selector: '[title="Maastricht et Gulpen"]',
            href: languageLinks.value.nl,
            label: 'Nederlands',
        },
    ];

    flagLanguageLinks.forEach(({ selector, href, label }) => {
        const flag = header.querySelector(selector);

        if (!flag || !href) {
            return;
        }

        const handleVisit = () => visitLanguage(href);
        const handleKeydown = (event) => {
            if (event.key !== 'Enter' && event.key !== ' ') {
                return;
            }

            event.preventDefault();
            handleVisit();
        };

        flag.setAttribute('role', 'link');
        flag.setAttribute('tabindex', '0');
        flag.setAttribute('aria-label', `Afficher le site en ${label}`);
        flag.classList.add(
            'cursor-pointer',
            'transition',
            'hover:border-amber-300/40',
            'hover:bg-amber-300/[0.06]',
        );
        flag.addEventListener('click', handleVisit);
        flag.addEventListener('keydown', handleKeydown);

        headerControlCleanups.push(() => {
            flag.removeEventListener('click', handleVisit);
            flag.removeEventListener('keydown', handleKeydown);
        });
    });
};

onMounted(() => {
    setupHeaderControls();

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

onBeforeUnmount(() => {
    headerControlCleanups.splice(0).forEach((cleanup) => cleanup());
});
</script>

<template>
    <div
        ref="navigation"
        class="sticky top-4 z-40 mx-auto mb-8 max-w-6xl px-4 opacity-0 sm:px-0"
    >
        <div
            class="vvs-luxury-card flex min-h-[64px] items-center justify-between gap-4 rounded-2xl border px-4 py-3 backdrop-blur-xl sm:px-5"
        >
            <Link
                v-if="showBack"
                :href="resolvedBackHref"
                class="group flex shrink-0 items-center gap-3"
            >
                <span
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-amber-300/30 bg-amber-300/[0.06] text-lg text-amber-200 transition-all duration-300 group-hover:-translate-x-1 group-hover:border-amber-300 group-hover:bg-amber-300 group-hover:text-black group-hover:shadow-[0_0_25px_rgba(252,211,77,0.25)]"
                >
                    ←
                </span>

                <div class="hidden sm:block">
                    <p
                        class="text-[9px] font-bold tracking-[0.25em] text-zinc-600 uppercase"
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
                        class="text-[10px] font-semibold tracking-[0.08em] text-zinc-500 uppercase transition hover:text-amber-200"
                    >
                        {{ translations.navigation.about }}
                    </Link>

                    <span class="text-zinc-800" aria-hidden="true">•</span>

                    <Link
                        :href="localizedRoutes.diamondGuide"
                        class="text-[10px] font-semibold tracking-[0.08em] text-zinc-500 uppercase transition hover:text-amber-200"
                    >
                        {{ guideLinks.eyebrow }}
                    </Link>
                </nav>

                <nav
                    class="hidden min-w-0 items-center justify-end overflow-hidden sm:flex"
                    :aria-label="translations.vvs_navigation.label"
                >
                    <template v-for="(step, index) in steps" :key="step.key">
                        <Link
                            v-if="step.href && step.key !== current"
                            :href="step.href"
                            class="hidden text-xs font-medium whitespace-nowrap text-zinc-500 transition hover:text-amber-200 sm:inline"
                        >
                            {{ step.label }}
                        </Link>

                        <span
                            v-else
                            :class="[
                                'text-xs font-semibold whitespace-nowrap',

                                step.key === current
                                    ? 'text-amber-200'
                                    : 'hidden text-zinc-500 sm:inline',
                            ]"
                        >
                            {{ step.label }}
                        </span>

                        <span
                            v-if="index < steps.length - 1"
                            class="mx-2 hidden text-zinc-700 sm:inline"
                        >
                            ›
                        </span>
                    </template>

                    <div
                        class="ml-3 hidden h-px w-8 bg-gradient-to-r from-amber-300/70 to-transparent md:block"
                    ></div>
                </nav>
            </div>
        </div>
    </div>
</template>
