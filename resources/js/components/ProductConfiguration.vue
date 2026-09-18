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

const formatPrice = (price) => `${Number(price).toFixed(0)} €`;
</script>

<template>
    <div class="w-full min-w-0 max-w-full lg:pt-5">
        <div class="flex items-center gap-3">
            <span aria-hidden="true" class="h-px w-8 bg-amber-300"></span>
            <p class="vvs-eyebrow">VVS FLAWLESS</p>
        </div>

        <h1 class="vvs-display-title mt-6 max-w-full break-words text-[clamp(2rem,9.5vw,2.5rem)] leading-[1.02] sm:max-w-2xl sm:text-6xl sm:leading-[0.98]">
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

                <p class="hidden text-xs text-zinc-400 sm:block">
                    {{ translations.product.two_versions }}
                </p>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <Link
                    :href="`${localizedRoutes.watches}/${watch.slug}?movement=Japonais`"
                    preserve-scroll
                    :aria-current="movement === 'Japonais' ? 'true' : undefined"
                    :class="[
                        'vvs-choice-card group relative min-w-0 overflow-hidden rounded-2xl border p-5 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        movement === 'Japonais'
                            ? 'vvs-choice-card--featured border-amber-300/70 shadow-[0_0_35px_rgba(251,191,36,0.08)]'
                            : '',
                    ]"
                >
                    <div class="flex min-w-0 items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p
                                class="text-[9px] font-black tracking-[0.22em] text-zinc-400 uppercase"
                            >
                                {{ translations.product.version }}
                            </p>

                            <p class="mt-2 text-lg font-black">
                                {{ translations.movements.japonais }}
                            </p>
                        </div>

                        <div
                            aria-hidden="true"
                            :class="[
                                'flex h-7 w-7 shrink-0 items-center justify-center rounded-full border text-[10px] font-black transition',
                                movement === 'Japonais'
                                    ? 'border-amber-300 bg-amber-300 text-black'
                                    : 'border-white/15 text-transparent',
                            ]"
                        >
                            ✓
                        </div>
                    </div>

                    <div class="mt-7">
                        <p
                            v-if="watch.japanese_promo_price"
                            class="text-xs text-zinc-400 line-through"
                        >
                            {{ formatPrice(watch.japanese_price) }}
                        </p>

                        <p class="vvs-price mt-1 whitespace-nowrap text-3xl font-black">
                            {{
                                formatPrice(
                                    watch.japanese_promo_price ??
                                        watch.japanese_price,
                                )
                            }}
                        </p>
                    </div>

                    <p
                        class="mt-4 text-[10px] tracking-[0.15em] text-zinc-400 uppercase"
                    >
                        {{ translations.product.select }}
                    </p>
                </Link>

                <Link
                    :href="`${localizedRoutes.watches}/${watch.slug}?movement=Suisse`"
                    preserve-scroll
                    :aria-current="movement === 'Suisse' ? 'true' : undefined"
                    :class="[
                        'vvs-choice-card group relative min-w-0 overflow-hidden rounded-2xl border p-5 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        movement === 'Suisse'
                            ? 'vvs-choice-card--featured border-amber-300/70 shadow-[0_0_35px_rgba(251,191,36,0.08)]'
                            : '',
                    ]"
                >
                    <div class="flex min-w-0 items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p
                                class="text-[9px] font-black tracking-[0.22em] text-zinc-400 uppercase"
                            >
                                {{ translations.product.version }}
                            </p>

                            <p class="mt-2 text-lg font-black">
                                {{ translations.movements.suisse }}
                            </p>
                        </div>

                        <div
                            aria-hidden="true"
                            :class="[
                                'flex h-7 w-7 shrink-0 items-center justify-center rounded-full border text-[10px] font-black transition',
                                movement === 'Suisse'
                                    ? 'border-amber-300 bg-amber-300 text-black'
                                    : 'border-white/15 text-transparent',
                            ]"
                        >
                            ✓
                        </div>
                    </div>

                    <div class="mt-7">
                        <p
                            v-if="watch.swiss_promo_price"
                            class="text-xs text-zinc-400 line-through"
                        >
                            {{ formatPrice(watch.swiss_price) }}
                        </p>

                        <p class="vvs-price mt-1 whitespace-nowrap text-3xl font-black">
                            {{
                                formatPrice(
                                    watch.swiss_promo_price ??
                                        watch.swiss_price,
                                )
                            }}
                        </p>
                    </div>

                    <p
                        class="mt-4 text-[10px] tracking-[0.15em] text-zinc-400 uppercase"
                    >
                        {{ translations.product.select }}
                    </p>
                </Link>
            </div>
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
