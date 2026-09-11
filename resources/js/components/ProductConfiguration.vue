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
    <div class="lg:pt-5">
        <div class="flex items-center gap-3">
            <span aria-hidden="true" class="h-px w-8 bg-amber-300"></span>
            <p class="vvs-eyebrow">VVS FLAWLESS</p>
        </div>

        <h1 class="vvs-display-title mt-6 max-w-2xl text-5xl sm:text-6xl">
            {{ watch.name }}
        </h1>

        <p
            class="mt-4 text-xs font-bold tracking-[0.22em] text-zinc-500 uppercase"
        >
            Moissanite VVS
            <span aria-hidden="true" class="mx-2 text-amber-400"> • </span>
            {{ translations.product.color }} D
        </p>

        <p class="mt-7 max-w-2xl text-base leading-8 text-zinc-400">
            {{ watch.description }}
        </p>

        <div
            aria-hidden="true"
            class="my-9 h-px bg-gradient-to-r from-white/10 via-white/5 to-transparent"
        ></div>

        <div>
            <div class="flex items-end justify-between gap-5">
                <div>
                    <p
                        class="text-[10px] font-black tracking-[0.3em] text-zinc-600 uppercase"
                    >
                        {{ translations.product.configuration }}
                    </p>

                    <h2 class="vvs-display-title mt-2 text-3xl">
                        {{ translations.product.choose_movement }}
                    </h2>
                </div>

                <p class="hidden text-xs text-zinc-600 sm:block">
                    {{ translations.product.two_versions }}
                </p>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <Link
                    :href="`${localizedRoutes.watches}/${watch.id}?movement=Japonais`"
                    preserve-scroll
                    :aria-current="movement === 'Japonais' ? 'true' : undefined"
                    :class="[
                        'vvs-choice-card group relative overflow-hidden rounded-2xl border p-5 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        movement === 'Japonais'
                            ? 'vvs-choice-card--featured border-amber-300/70 shadow-[0_0_35px_rgba(251,191,36,0.08)]'
                            : '',
                    ]"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-[9px] font-black tracking-[0.22em] text-zinc-500 uppercase"
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
                                'flex h-7 w-7 items-center justify-center rounded-full border text-[10px] font-black transition',
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
                            class="text-xs text-zinc-600 line-through"
                        >
                            {{ formatPrice(watch.japanese_price) }}
                        </p>

                        <p class="vvs-price mt-1 text-3xl font-black">
                            {{
                                formatPrice(
                                    watch.japanese_promo_price ??
                                        watch.japanese_price,
                                )
                            }}
                        </p>
                    </div>

                    <p
                        class="mt-4 text-[10px] tracking-[0.15em] text-zinc-600 uppercase"
                    >
                        {{ translations.product.select }}
                    </p>
                </Link>

                <Link
                    :href="`${localizedRoutes.watches}/${watch.id}?movement=Suisse`"
                    preserve-scroll
                    :aria-current="movement === 'Suisse' ? 'true' : undefined"
                    :class="[
                        'vvs-choice-card group relative overflow-hidden rounded-2xl border p-5 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                        movement === 'Suisse'
                            ? 'vvs-choice-card--featured border-amber-300/70 shadow-[0_0_35px_rgba(251,191,36,0.08)]'
                            : '',
                    ]"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-[9px] font-black tracking-[0.22em] text-zinc-500 uppercase"
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
                                'flex h-7 w-7 items-center justify-center rounded-full border text-[10px] font-black transition',
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
                            class="text-xs text-zinc-600 line-through"
                        >
                            {{ formatPrice(watch.swiss_price) }}
                        </p>

                        <p class="vvs-price mt-1 text-3xl font-black">
                            {{
                                formatPrice(
                                    watch.swiss_promo_price ??
                                        watch.swiss_price,
                                )
                            }}
                        </p>
                    </div>

                    <p
                        class="mt-4 text-[10px] tracking-[0.15em] text-zinc-600 uppercase"
                    >
                        {{ translations.product.select }}
                    </p>
                </Link>
            </div>
        </div>

        <div
            class="vvs-choice-card vvs-choice-card--featured mt-5 flex items-center justify-between gap-5 rounded-2xl border p-5"
        >
            <div>
                <p
                    class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                >
                    {{ translations.product.your_selection }}
                </p>

                <p class="mt-2 font-bold text-zinc-200">
                    {{ translations.product.movement }} {{ localizedMovement }}
                </p>
            </div>

            <div class="text-right">
                <p
                    v-if="selectedOldPrice > selectedPrice"
                    class="text-xs text-zinc-600 line-through"
                >
                    {{ formatPrice(selectedOldPrice) }}
                </p>

                <p class="vvs-price text-3xl font-black">
                    {{ formatPrice(selectedPrice) }}
                </p>
            </div>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-2">
            <div class="vvs-choice-card rounded-2xl border p-5">
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                >
                    {{ translations.product.estimated_availability }}
                </p>

                <p class="mt-2 font-bold text-zinc-200">
                    {{ translations.product.estimated_delay }}
                </p>
            </div>

            <div class="vvs-choice-card rounded-2xl border p-5">
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
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
            class="vvs-button-primary mt-6 flex w-full items-center justify-between rounded-2xl px-6 py-5 font-bold tracking-[0.1em] uppercase focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
        >
            <span>{{ translations.product.reserve_watch }}</span>
            <span aria-hidden="true" class="text-xl"> ↓ </span>
        </a>
    </div>
</template>
