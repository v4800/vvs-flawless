<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AboutSection from '@/components/AboutSection.vue';
import ContactSection from '@/components/ContactSection.vue';
import PickupSection from '@/components/PickupSection.vue';
import VvsNavigation from '@/components/VvsNavigation.vue';

defineProps({
    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);
const localizedRoutes = computed(() => page.props.localizedRoutes);
</script>

<template>
    <Head :title="seo.title" />

    <div class="min-h-screen bg-black text-white">
        <VvsNavigation :back-label="translations.vvs_navigation.collection" />

        <main id="main-content">
            <header class="mx-auto max-w-4xl px-6 pt-12 text-center sm:pt-20">
                <p
                    class="text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
                >
                    VVS FLAWLESS
                </p>
                <h1 class="vvs-display-title mt-5 text-5xl sm:text-7xl">
                    {{ translations.navigation.about }}
                </h1>
                <p
                    class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-zinc-400 sm:text-base"
                >
                    {{ translations.about.page_intro }}
                </p>
            </header>

            <AboutSection />
            <PickupSection />
            <ContactSection :collection-href="localizedRoutes.watches" />
        </main>

        <footer class="border-t border-white/10 px-6 py-9 text-center">
            <Link
                :href="localizedRoutes.watches"
                class="text-xs font-black tracking-[0.12em] text-amber-300 uppercase"
            >
                {{ translations.footer.collection }}
            </Link>
        </footer>
    </div>
</template>
