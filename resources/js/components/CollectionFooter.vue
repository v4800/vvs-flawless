<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const translations = page.props.translations;
const localizedRoutes = page.props.localizedRoutes;
const hubCards = computed(() => page.props.seoIntentContent?.hub?.cards ?? []);

const guideLinks = computed(() => {
    return hubCards.value
        .map((card) => ({
            title: card.title,
            href: localizedRoutes?.[card.route],
        }))
        .filter((card) => card.href);
});
</script>

<template>
    <footer class="border-t border-white/10 bg-black px-6 py-10">
        <div
            class="mx-auto flex max-w-[1500px] flex-col gap-5 text-center md:flex-row md:items-center md:justify-between md:text-left"
        >
            <div>
                <p class="font-black tracking-[0.12em] uppercase">
                    VVS FLAWLESS
                </p>
                <p class="mt-1 text-xs text-zinc-600">
                    {{ translations.footer.tagline }}
                </p>
            </div>

            <nav
                class="flex max-w-3xl flex-wrap items-center justify-center gap-x-5 gap-y-2"
                :aria-label="translations.navigation.main_label"
            >
                <Link
                    :href="localizedRoutes.about"
                    class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                >
                    {{ translations.navigation.about }}
                </Link>

                <Link
                    v-for="guide in guideLinks"
                    :key="guide.href"
                    :href="guide.href"
                    class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                >
                    {{ guide.title }}
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

            <p class="text-xs text-zinc-500">
                {{ translations.footer.copyright }}
            </p>
        </div>
    </footer>
</template>
