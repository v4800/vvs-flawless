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
        default: '/watches',
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

const resolvedBackLabel = computed(
    () => props.backLabel ?? translations.value.vvs_navigation.collection,
);

const navigation = ref(null);

const steps = computed(() => {
    const items = [
        {
            label: translations.value.vvs_navigation.collection,
            href: '/watches',
            key: 'collection',
        },
    ];

    if (props.current === 'watch' || props.current === 'reservation') {
        items.push({
            label: translations.value.vvs_navigation.model,
            href: props.watchHref,
            key: 'watch',
        });
    }

    if (props.current === 'reservation') {
        items.push({
            label: translations.value.vvs_navigation.reservation,
            href: null,
            key: 'reservation',
        });
    }

    return items;
});

onMounted(() => {
    if (!navigation.value) {
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
            class="flex min-h-[64px] items-center justify-between gap-4 rounded-2xl border border-white/10 bg-black/75 px-4 py-3 shadow-[0_15px_60px_rgba(0,0,0,0.45)] backdrop-blur-xl sm:px-5"
        >
            <!-- RETOUR -->

            <Link
                v-if="showBack"
                :href="backHref"
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

                <span class="text-xs font-black tracking-[0.18em] text-white">
                    VVS FLAWLESS
                </span>
            </div>

            <!-- BREADCRUMB -->

            <nav
                class="flex min-w-0 items-center justify-end overflow-hidden"
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
</template>
