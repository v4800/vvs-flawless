<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const localizedRoutes = computed(() => page.props.localizedRoutes);
const hub = computed(() => page.props.seoIntentContent?.hub ?? {});

const cards = computed(() => {
    const routeMap = localizedRoutes.value;

    return (hub.value.cards ?? [])
        .map((card) => ({
            ...card,
            href: routeMap?.[card.route],
        }))
        .filter((card) => card.href);
});
</script>

<template>
    <section
        v-if="cards.length"
        class="border-y border-white/10 bg-[radial-gradient(circle_at_50%_0%,rgba(251,191,36,0.055),transparent_38%),rgba(9,9,11,0.55)] px-5 py-20 sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1400px]">
            <header class="mx-auto max-w-3xl text-center">
                <p class="vvs-eyebrow">{{ hub.eyebrow }}</p>

                <h2 class="vvs-display-title mt-4 text-4xl sm:text-5xl">
                    {{ hub.title }}
                </h2>

                <p class="mt-5 text-sm leading-7 text-zinc-400 sm:text-base">
                    {{ hub.description }}
                </p>
            </header>

            <div class="mt-10 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <Link
                    v-for="card in cards"
                    :key="card.route"
                    :href="card.href"
                    class="vvs-luxury-card vvs-luxury-card--interactive group flex min-h-[230px] flex-col rounded-2xl border p-6"
                >
                    <div
                        aria-hidden="true"
                        class="flex h-9 w-9 items-center justify-center rounded-full border border-amber-300/25 bg-amber-300/[0.06]"
                    >
                        <span
                            class="h-2.5 w-2.5 rotate-45 border border-amber-200/80"
                        ></span>
                    </div>

                    <h3 class="vvs-display-title mt-5 text-2xl leading-tight">
                        {{ card.title }}
                    </h3>

                    <p class="mt-3 flex-1 text-sm leading-6 text-zinc-400">
                        {{ card.text }}
                    </p>

                    <span
                        class="mt-5 text-[10px] font-bold tracking-[0.12em] text-amber-300 uppercase transition group-hover:translate-x-1"
                    >
                        {{ card.cta }} →
                    </span>
                </Link>
            </div>
        </div>
    </section>
</template>
