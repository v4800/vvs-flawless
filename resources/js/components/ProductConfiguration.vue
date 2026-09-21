<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    watch: {
        type: Object,
        required: true,
    },
    translations: {
        type: Object,
        required: true,
    },
    localizedRoutes: {
        type: Object,
        required: true,
    },
    movement: {
        type: String,
        required: true,
    },
    localizedMovement: {
        type: String,
        required: true,
    },
    selectedPrice: {
        type: Number,
        required: true,
    },
    selectedOldPrice: {
        type: Number,
        required: true,
    },
});

const formatPrice = (price) => {
    const numericPrice = Number(price);

    return Number.isFinite(numericPrice) && numericPrice > 0
        ? `${numericPrice.toFixed(0)} €`
        : '—';
};
</script>

<template>
    <div class="w-full min-w-0 max-w-full lg:pt-5">
        <div class="flex items-center gap-3">
            <span aria-hidden="true" class="h-px w-8 bg-amber-300"></span>
            <p class="vvs-eyebrow">VVS FLAWLESS</p>
        </div>

        <h1 class="vvs-watch-title mt-6 max-w-full break-words text-[clamp(2rem,9.5vw,2.5rem)] leading-[1.08] sm:max-w-2xl sm:text-6xl sm:leading-[1.02]">
            {{ watch.name }}
        </h1>

        <p
            class="mt-4 max-w-full break-words text-xs font-bold leading-6 tracking-[0.18em] text-zinc-400 uppercase sm:tracking-[0.22em]"
        >
            Moissanite VVS
            <span aria-hidden="true" class="mx-2 text-amber-400"> • </span>
            {{ translations.product.color }} D
        </p>

        <p class="mt-7 max-w-2xl break-words text-base leading-7 text-zinc-400 sm:leading-8">
            {{ watch.description }}
        </p>

        <div
            aria-hidden="true"
            class="my-9 h-px bg-gradient-to-r from-white/10 via-white/5 to-transparent"
        ></div>

        <div>
            <div class="flex min-w-0 flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-5">
                <div class="min-w-0">
                    <p
                        class="text-[10px] font-black tracking-[0.3em] text-zinc-400 uppercase"
                    >
                        {{ translations.product.configuration }}
                    </p>

                    <h2 class="vvs-display-title mt-2 max-w-full break-words text-[1.75rem] leading-tight sm:text-3xl">
                        {{ translations.product.choose_movement }}
                    </h2>
                </div>

                <p v-if="Number(watch.swiss_promo_price ?? watch.swiss_price ?? 0) > 0" class="hidden text-xs text-zinc-400 sm:block">
                    {{ translations.product.two_versions }}
                </p>
            </div>

            <nav
                v-if="Number(watch.swiss_promo_price ?? watch.swiss_price ?? 0) > 0"
                class="vvs-movement-switch mt-5 inline-flex w-full max-w-md gap-1 rounded-2xl border border-[#b9a17b]/45 bg-[#191917] p-1.5 sm:w-auto"
                :aria-label="translations.product.choose_movement"
            >
                <Link
                    :href="`${localizedRoutes.watches}/${watch.slug}?movement=Japonais`"
                    preserve-scroll
                    :aria-current="movement === 'Japonais' ? 'page' : undefined"
                    :class="[
                        'flex min-h-11 flex-1 items-center justify-center rounded-xl px-4 py-2.5 text-center text-sm font-semibold transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4bf99] sm:flex-none',
                        movement === 'Japonais'
                            ? 'bg-[#d4bf99] text-[#191714]'
                            : 'text-zinc-300 hover:bg-white/5 hover:text-white',
                    ]"
                >
                    {{ translations.movements.japonais }}
                </Link>
                <Link
                    :href="`${localizedRoutes.watches}/${watch.slug}?movement=Suisse`"
                    preserve-scroll
                    :aria-current="movement === 'Suisse' ? 'page' : undefined"
                    :class="[
                        'flex min-h-11 flex-1 items-center justify-center rounded-xl px-4 py-2.5 text-center text-sm font-semibold transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4bf99] sm:flex-none',
                        movement === 'Suisse'
                            ? 'bg-[#d4bf99] text-[#191714]'
                            : 'text-zinc-300 hover:bg-white/5 hover:text-white',
                    ]"
                >
                    {{ translations.movements.suisse }}
                </Link>
            </nav>
        </div>

        <div
            class="vvs-choice-card vvs-choice-card--featured mt-5 flex min-w-0 flex-col gap-4 rounded-2xl border p-5 sm:flex-row sm:items-center sm:justify-between sm:gap-5"
        >
            <div class="min-w-0">
                <p
                    class="text-[9px] font-black tracking-[0.25em] text-zinc-400 uppercase"
                >
                    {{ translations.product.your_selection }}
                </p>

                <p class="mt-2 font-bold text-zinc-200">
                    {{ translations.product.movement }} {{ localizedMovement }}
                </p>
            </div>

            <div class="self-end shrink-0 text-right sm:self-auto">
                <p
                    v-if="selectedOldPrice > selectedPrice"
                    class="text-xs text-zinc-400 line-through"
                >
                    {{ formatPrice(selectedOldPrice) }}
                </p>

                <p class="vvs-price whitespace-nowrap text-3xl font-black">
                    {{ formatPrice(selectedPrice) }}
                </p>
            </div>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-2">
            <div class="vvs-choice-card min-w-0 rounded-2xl border p-4 sm:p-5">
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-400 uppercase"
                >
                    {{ translations.product.estimated_availability }}
                </p>

                <p class="mt-2 font-bold text-zinc-200">
                    {{ translations.product.estimated_delay }}
                </p>
            </div>

            <div class="vvs-choice-card min-w-0 rounded-2xl border p-4 sm:p-5">
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-400 uppercase"
                >
                    {{ translations.product.reception }}
                </p>

                <p class="mt-2 font-bold text-zinc-200">
                    {{ translations.product.handover_or_delivery }}
                </p>
            </div>
        </div>

        <a
            href="#reservation"
            class="vvs-button-primary mt-6 flex w-full min-w-0 items-center justify-between gap-3 rounded-2xl px-4 py-4 text-left text-xs font-bold leading-5 tracking-[0.06em] uppercase focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none sm:gap-4 sm:px-6 sm:py-5 sm:text-sm sm:tracking-[0.1em]"
        >
            <span class="min-w-0 break-words">{{ translations.product.reserve_watch }}</span>
            <span aria-hidden="true" class="shrink-0 text-xl"> ↓ </span>
        </a>
    </div>
</template>
