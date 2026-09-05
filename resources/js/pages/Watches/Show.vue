<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch as vueWatch } from 'vue';
import ReservationTrust from '@/components/ReservationTrust.vue';
import MobileReservationBar from '@/components/MobileReservationBar.vue';
import PurchaseGuide from '@/components/PurchaseGuide.vue';
import RelatedWatches from '@/components/RelatedWatches.vue';
import StockBadge from '@/components/StockBadge.vue';
import VvsNavigation from '@/components/VvsNavigation.vue';

const props = defineProps({
    watch: {
        type: Object,
        required: true,
    },

    selectedMovement: {
        type: String,
        default: 'Japonais',
    },

    relatedWatches: {
        type: Array,
        default: () => [],
    },

    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const localizedRoutes = page.props.localizedRoutes;
const translations = computed(() => page.props.translations);

const form = useForm({
    watch_id: props.watch.id,
    movement: props.selectedMovement === 'Suisse' ? 'Suisse' : 'Japonais',
    customer_name: '',
    email: '',
    phone: '',
    city: '',
    delivery_method: 'Remise en main propre',
    message: '',
    confirmation: false,
});

const deliveryOptions = computed(() => [
    {
        value: 'Remise en main propre',
        label: translations.value.product.handover,
        description: translations.value.product.handover_description,
    },
    {
        value: 'Livraison',
        label: translations.value.product.delivery,
        description: translations.value.product.delivery_description,
    },
]);

const localizedMovement = computed(() =>
    form.movement === 'Suisse'
        ? translations.value.movements.suisse
        : translations.value.movements.japonais,
);

vueWatch(
    () => props.selectedMovement,
    (movement) => {
        form.movement = movement === 'Suisse' ? 'Suisse' : 'Japonais';
    },
    {
        immediate: true,
    },
);

vueWatch(
    () => props.watch.id,
    (watchId) => {
        form.watch_id = watchId;
    },
);

const selectedPrice = computed(() => {
    if (form.movement === 'Suisse') {
        return Number(
            props.watch.swiss_promo_price ?? props.watch.swiss_price ?? 0,
        );
    }

    return Number(
        props.watch.japanese_promo_price ?? props.watch.japanese_price ?? 0,
    );
});

const selectedOldPrice = computed(() => {
    if (form.movement === 'Suisse') {
        return Number(props.watch.swiss_price ?? 0);
    }

    return Number(props.watch.japanese_price ?? 0);
});

const formatPrice = (price) => {
    return `${Number(price).toFixed(0)} €`;
};

const submit = () => {
    form.post(localizedRoutes.reservationStore, {
        preserveScroll: false,
    });
};
</script>

<template>
    <Head :title="seo.title" />

    <div class="min-h-screen bg-black pb-24 text-white lg:pb-0">
        <a
            href="#main-content"
            class="sr-only z-[100] rounded-lg bg-amber-300 px-4 py-3 font-bold text-black focus:not-sr-only focus:fixed focus:top-4 focus:left-4"
        >
            {{ translations.accessibility.skip_content }}
        </a>

        <VvsNavigation
            current="watch"
            :back-label="translations.vvs_navigation.collection"
            :watch-href="`${localizedRoutes.watches}/${watch.id}`"
        />

        <main id="main-content" tabindex="-1">
            <!-- PRODUIT -->

            <section
                class="relative overflow-hidden px-5 pt-10 pb-20 sm:px-6 lg:px-10 lg:pt-14"
            >
                <div
                    class="pointer-events-none absolute top-20 -left-40 -z-10 h-[500px] w-[500px] rounded-full bg-amber-400/[0.05] blur-[150px]"
                ></div>

                <div
                    class="pointer-events-none absolute top-0 right-0 -z-10 h-[650px] w-[650px] rounded-full bg-white/[0.025] blur-[160px]"
                ></div>

                <div
                    class="mx-auto grid max-w-[1400px] gap-10 lg:grid-cols-[1.04fr_0.96fr] lg:gap-16"
                >
                    <!-- IMAGE -->

                    <div>
                        <div class="relative">
                            <div class="absolute top-5 left-5 z-20">
                                <StockBadge
                                    :quantity="watch.stock_quantity"
                                    :availability="watch.availability"
                                />
                            </div>

                            <div
                                class="aspect-square self-start overflow-hidden rounded-3xl bg-zinc-400"
                            >
                                <img
                                    :src="watch.image"
                                    :alt="watch.name"
                                    loading="eager"
                                    fetchpriority="high"
                                    decoding="async"
                                    class="block h-full w-full object-contain"
                                />
                            </div>

                            <div
                                class="absolute right-5 bottom-5 left-5 flex items-center justify-between rounded-2xl border border-white/10 bg-black/75 px-5 py-4 backdrop-blur-xl"
                            >
                                <div>
                                    <p
                                        class="text-[9px] font-black tracking-[0.25em] text-zinc-500 uppercase"
                                    >
                                        {{ translations.product.stone }}
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-bold text-white"
                                    >
                                        Moissanite VVS
                                    </p>
                                </div>

                                <div class="h-8 w-px bg-white/10"></div>

                                <div class="text-right">
                                    <p
                                        class="text-[9px] font-black tracking-[0.25em] text-zinc-500 uppercase"
                                    >
                                        {{ translations.product.color }}
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-bold text-amber-200"
                                    >
                                        D
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-3">
                            <div class="vvs-choice-card rounded-2xl border p-4">
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    {{ translations.product.purity }}
                                </p>

                                <p class="vvs-price mt-2 font-black">VVS</p>
                            </div>

                            <div class="vvs-choice-card rounded-2xl border p-4">
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    {{ translations.product.color }}
                                </p>

                                <p class="vvs-price mt-2 font-black">D</p>
                            </div>

                            <div class="vvs-choice-card rounded-2xl border p-4">
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    {{ translations.product.reception }}
                                </p>

                                <p
                                    class="mt-2 text-xs font-black text-amber-200"
                                >
                                    {{ translations.product.customer_choice }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- INFORMATIONS -->

                    <div class="lg:pt-5">
                        <div class="flex items-center gap-3">
                            <span class="h-px w-8 bg-amber-300"></span>

                            <p class="vvs-eyebrow">VVS FLAWLESS</p>
                        </div>

                        <h1
                            class="vvs-display-title mt-6 max-w-2xl text-5xl sm:text-6xl"
                        >
                            {{ watch.name }}
                        </h1>

                        <p
                            class="mt-4 text-xs font-bold tracking-[0.22em] text-zinc-500 uppercase"
                        >
                            Moissanite VVS

                            <span class="mx-2 text-amber-400"> • </span>

                            {{ translations.product.color }} D
                        </p>

                        <p
                            class="mt-7 max-w-2xl text-base leading-8 text-zinc-400"
                        >
                            {{ watch.description }}
                        </p>

                        <div
                            class="my-9 h-px bg-gradient-to-r from-white/10 via-white/5 to-transparent"
                        ></div>

                        <!-- MOUVEMENTS -->

                        <div>
                            <div class="flex items-end justify-between gap-5">
                                <div>
                                    <p
                                        class="text-[10px] font-black tracking-[0.3em] text-zinc-600 uppercase"
                                    >
                                        {{ translations.product.configuration }}
                                    </p>

                                    <h2 class="vvs-display-title mt-2 text-3xl">
                                        {{
                                            translations.product.choose_movement
                                        }}
                                    </h2>
                                </div>

                                <p
                                    class="hidden text-xs text-zinc-600 sm:block"
                                >
                                    {{ translations.product.two_versions }}
                                </p>
                            </div>

                            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                <Link
                                    :href="`${localizedRoutes.watches}/${watch.id}?movement=Japonais`"
                                    preserve-scroll
                                    :class="[
                                        'vvs-choice-card group relative overflow-hidden rounded-2xl border p-5',
                                        form.movement === 'Japonais'
                                            ? 'vvs-choice-card--featured border-amber-300/70 shadow-[0_0_35px_rgba(251,191,36,0.08)]'
                                            : '',
                                    ]"
                                >
                                    <div
                                        class="flex items-start justify-between gap-4"
                                    >
                                        <div>
                                            <p
                                                class="text-[9px] font-black tracking-[0.22em] text-zinc-500 uppercase"
                                            >
                                                {{
                                                    translations.product.version
                                                }}
                                            </p>

                                            <p class="mt-2 text-lg font-black">
                                                {{
                                                    translations.movements
                                                        .japonais
                                                }}
                                            </p>
                                        </div>

                                        <div
                                            :class="[
                                                'flex h-7 w-7 items-center justify-center rounded-full border text-[10px] font-black transition',
                                                form.movement === 'Japonais'
                                                    ? 'border-amber-300 bg-amber-300 text-black'
                                                    : 'border-white/15 text-transparent',
                                            ]"
                                        >
                                            ✓
                                        </div>
                                    </div>

                                    <div class="mt-7">
                                        <p
                                            v-if="watch.japanese_price"
                                            class="text-xs text-zinc-600 line-through"
                                        >
                                            {{
                                                formatPrice(
                                                    watch.japanese_price,
                                                )
                                            }}
                                        </p>

                                        <p
                                            class="vvs-price mt-1 text-3xl font-black"
                                        >
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
                                    :class="[
                                        'vvs-choice-card group relative overflow-hidden rounded-2xl border p-5',
                                        form.movement === 'Suisse'
                                            ? 'vvs-choice-card--featured border-amber-300/70 shadow-[0_0_35px_rgba(251,191,36,0.08)]'
                                            : '',
                                    ]"
                                >
                                    <div
                                        class="flex items-start justify-between gap-4"
                                    >
                                        <div>
                                            <p
                                                class="text-[9px] font-black tracking-[0.22em] text-zinc-500 uppercase"
                                            >
                                                {{
                                                    translations.product.version
                                                }}
                                            </p>

                                            <p class="mt-2 text-lg font-black">
                                                {{
                                                    translations.movements
                                                        .suisse
                                                }}
                                            </p>
                                        </div>

                                        <div
                                            :class="[
                                                'flex h-7 w-7 items-center justify-center rounded-full border text-[10px] font-black transition',
                                                form.movement === 'Suisse'
                                                    ? 'border-amber-300 bg-amber-300 text-black'
                                                    : 'border-white/15 text-transparent',
                                            ]"
                                        >
                                            ✓
                                        </div>
                                    </div>

                                    <div class="mt-7">
                                        <p
                                            v-if="watch.swiss_price"
                                            class="text-xs text-zinc-600 line-through"
                                        >
                                            {{ formatPrice(watch.swiss_price) }}
                                        </p>

                                        <p
                                            class="vvs-price mt-1 text-3xl font-black"
                                        >
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

                        <!-- PRIX -->

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
                                    {{ translations.product.movement }}
                                    {{ localizedMovement }}
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

                        <!-- DISPONIBILITÉ -->

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="vvs-choice-card rounded-2xl border p-5">
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    {{
                                        translations.product
                                            .estimated_availability
                                    }}
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
                                    {{
                                        translations.product
                                            .handover_or_delivery
                                    }}
                                </p>
                            </div>
                        </div>

                        <a
                            href="#reservation"
                            class="vvs-button-primary mt-6 flex w-full items-center justify-between rounded-2xl px-6 py-5 font-bold tracking-[0.1em] uppercase"
                        >
                            <span>{{
                                translations.product.reserve_watch
                            }}</span>

                            <span class="text-xl"> ↓ </span>
                        </a>
                    </div>
                </div>
            </section>

            <!-- BANDE DE CONFIANCE -->

            <section
                class="border-y border-white/10 bg-zinc-950/60 px-5 py-6 sm:px-6 lg:px-10"
            >
                <div
                    class="mx-auto grid max-w-[1400px] gap-6 text-center sm:grid-cols-3"
                >
                    <div>
                        <p
                            class="text-xs font-black tracking-[0.18em] text-amber-200 uppercase"
                        >
                            Moissanite VVS
                        </p>

                        <p class="mt-2 text-xs text-zinc-600">
                            {{ translations.product.sparkle_text }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs font-black tracking-[0.18em] text-amber-200 uppercase"
                        >
                            {{ translations.product.color }} D
                        </p>

                        <p class="mt-2 text-xs text-zinc-600">
                            {{ translations.product.color_render }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs font-black tracking-[0.18em] text-amber-200 uppercase"
                        >
                            {{ translations.product.reception_choice }}
                        </p>

                        <p class="mt-2 text-xs text-zinc-600">
                            {{ translations.product.handover_or_delivery }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- GUIDE -->

            <PurchaseGuide />

            <!-- RÉSERVATION -->

            <section
                id="reservation"
                class="relative scroll-mt-24 px-5 py-24 sm:px-6 lg:px-10"
            >
                <div
                    class="pointer-events-none absolute top-1/2 left-1/2 -z-10 h-[550px] w-[900px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.035] blur-[150px]"
                ></div>

                <div
                    class="mx-auto grid max-w-[1200px] gap-8 lg:grid-cols-[0.75fr_1.25fr]"
                >
                    <!-- RÉCAP -->

                    <div class="lg:sticky lg:top-28 lg:self-start">
                        <p class="vvs-eyebrow">
                            {{ translations.product.your_selection }}
                        </p>

                        <h2 class="vvs-display-title mt-4 text-4xl">
                            {{ translations.product.reserve_piece }}
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-500">
                            {{ translations.product.reservation_intro }}
                        </p>

                        <div
                            class="vvs-luxury-card mt-7 overflow-hidden rounded-2xl border"
                        >
                            <div class="grid grid-cols-[105px_1fr]">
                                <div class="bg-zinc-400">
                                    <img
                                        :src="watch.image"
                                        :alt="watch.name"
                                        loading="lazy"
                                        decoding="async"
                                        class="h-full w-full object-cover"
                                    />
                                </div>

                                <div class="p-4">
                                    <p
                                        class="vvs-display-title line-clamp-2 text-xl leading-5"
                                    >
                                        {{ watch.name }}
                                    </p>

                                    <p
                                        class="mt-2 text-[10px] tracking-[0.15em] text-zinc-500 uppercase"
                                    >
                                        {{ localizedMovement }}
                                    </p>

                                    <p
                                        class="vvs-price mt-3 text-xl font-black"
                                    >
                                        {{ formatPrice(selectedPrice) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="vvs-choice-card vvs-choice-card--featured mt-4 rounded-2xl border p-5"
                        >
                            <p class="text-xs font-bold text-zinc-300">
                                ◆ {{ form.delivery_method }}
                            </p>

                            <p class="mt-2 text-xs leading-5 text-zinc-600">
                                {{ translations.product.reception_note }}
                            </p>
                        </div>
                    </div>

                    <!-- FORMULAIRE -->

                    <form
                        class="vvs-form vvs-luxury-card rounded-3xl border p-6 sm:p-8"
                        @submit.prevent="submit"
                    >
                        <div
                            class="mb-8 flex items-center justify-between gap-5 border-b border-white/10 pb-6"
                        >
                            <div>
                                <p
                                    class="text-[9px] font-black tracking-[0.3em] text-zinc-600 uppercase"
                                >
                                    {{ translations.product.reservation }}
                                </p>

                                <h3 class="vvs-display-title mt-2 text-3xl">
                                    {{ translations.product.your_information }}
                                </h3>
                            </div>

                            <div
                                class="rounded-full border border-amber-300/20 bg-amber-300/[0.04] px-4 py-2 text-xs font-bold text-amber-200"
                            >
                                {{ localizedMovement }}
                            </div>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <!-- NOM -->

                            <div>
                                <label
                                    for="reservation-customer-name"
                                    class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                                >
                                    {{ translations.product.full_name }}
                                </label>

                                <input
                                    id="reservation-customer-name"
                                    v-model="form.customer_name"
                                    type="text"
                                    required
                                    autocomplete="name"
                                    :aria-invalid="
                                        Boolean(form.errors.customer_name)
                                    "
                                    :aria-describedby="
                                        form.errors.customer_name
                                            ? 'reservation-customer-name-error'
                                            : undefined
                                    "
                                    class="w-full rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                                    :placeholder="
                                        translations.product.name_placeholder
                                    "
                                />

                                <p
                                    v-if="form.errors.customer_name"
                                    id="reservation-customer-name-error"
                                    role="alert"
                                    class="mt-2 text-xs text-red-400"
                                >
                                    {{ form.errors.customer_name }}
                                </p>
                            </div>

                            <!-- EMAIL -->

                            <div>
                                <label
                                    for="reservation-email"
                                    class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                                >
                                    {{ translations.product.email }}
                                </label>

                                <input
                                    id="reservation-email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autocomplete="email"
                                    :aria-invalid="Boolean(form.errors.email)"
                                    :aria-describedby="
                                        form.errors.email
                                            ? 'reservation-email-error'
                                            : undefined
                                    "
                                    class="w-full rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                                    :placeholder="
                                        translations.product.email_placeholder
                                    "
                                />

                                <p
                                    v-if="form.errors.email"
                                    id="reservation-email-error"
                                    role="alert"
                                    class="mt-2 text-xs text-red-400"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- TÉLÉPHONE -->

                            <div>
                                <label
                                    for="reservation-phone"
                                    class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                                >
                                    {{ translations.product.phone }}
                                </label>

                                <input
                                    id="reservation-phone"
                                    v-model="form.phone"
                                    type="tel"
                                    required
                                    autocomplete="tel"
                                    :aria-invalid="Boolean(form.errors.phone)"
                                    :aria-describedby="
                                        form.errors.phone
                                            ? 'reservation-phone-error'
                                            : undefined
                                    "
                                    class="w-full rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                                    placeholder="+32..."
                                />

                                <p
                                    v-if="form.errors.phone"
                                    id="reservation-phone-error"
                                    role="alert"
                                    class="mt-2 text-xs text-red-400"
                                >
                                    {{ form.errors.phone }}
                                </p>
                            </div>

                            <!-- VILLE -->

                            <div>
                                <label
                                    for="reservation-city"
                                    class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                                >
                                    {{ translations.product.city }}
                                </label>

                                <input
                                    id="reservation-city"
                                    v-model="form.city"
                                    type="text"
                                    autocomplete="address-level2"
                                    :aria-invalid="Boolean(form.errors.city)"
                                    :aria-describedby="
                                        form.errors.city
                                            ? 'reservation-city-error'
                                            : undefined
                                    "
                                    class="w-full rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                                    :placeholder="
                                        translations.product.city_placeholder
                                    "
                                />

                                <p
                                    v-if="form.errors.city"
                                    id="reservation-city-error"
                                    role="alert"
                                    class="mt-2 text-xs text-red-400"
                                >
                                    {{ form.errors.city }}
                                </p>
                            </div>

                            <!-- MODE DE RÉCEPTION -->

                            <fieldset
                                class="md:col-span-2"
                                :aria-invalid="
                                    Boolean(form.errors.delivery_method)
                                "
                                :aria-describedby="
                                    form.errors.delivery_method
                                        ? 'reservation-delivery-method-error'
                                        : undefined
                                "
                            >
                                <legend
                                    class="mb-2 text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                                >
                                    {{ translations.product.reception_method }}
                                </legend>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <label
                                        v-for="(
                                            option, index
                                        ) in deliveryOptions"
                                        :key="option.value"
                                        :for="`reservation-delivery-method-${index}`"
                                        :class="[
                                            'cursor-pointer rounded-xl border p-5 transition',
                                            form.delivery_method ===
                                            option.value
                                                ? 'border-amber-300/50 bg-amber-300/[0.05]'
                                                : 'border-white/10 bg-black hover:border-white/20',
                                        ]"
                                    >
                                        <div class="flex items-start gap-3">
                                            <input
                                                :id="`reservation-delivery-method-${index}`"
                                                v-model="form.delivery_method"
                                                type="radio"
                                                name="delivery_method"
                                                :value="option.value"
                                                required
                                                class="mt-1 h-4 w-4 accent-amber-300"
                                            />

                                            <span>
                                                <span
                                                    class="block font-bold text-white"
                                                >
                                                    {{ option.label }}
                                                </span>

                                                <span
                                                    class="mt-1 block text-sm leading-6 text-zinc-500"
                                                >
                                                    {{ option.description }}
                                                </span>
                                            </span>
                                        </div>
                                    </label>
                                </div>

                                <p
                                    v-if="form.errors.delivery_method"
                                    id="reservation-delivery-method-error"
                                    role="alert"
                                    class="mt-2 text-xs text-red-400"
                                >
                                    {{ form.errors.delivery_method }}
                                </p>
                            </fieldset>

                            <!-- MESSAGE -->

                            <div class="md:col-span-2">
                                <label
                                    for="reservation-message"
                                    class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                                >
                                    {{ translations.product.message }}

                                    <span
                                        class="tracking-normal text-zinc-700 normal-case"
                                    >
                                        {{ translations.product.optional }}
                                    </span>
                                </label>

                                <textarea
                                    id="reservation-message"
                                    v-model="form.message"
                                    rows="4"
                                    :aria-invalid="Boolean(form.errors.message)"
                                    :aria-describedby="
                                        form.errors.message
                                            ? 'reservation-message-error'
                                            : undefined
                                    "
                                    class="w-full resize-none rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                                    :placeholder="
                                        translations.product.message_placeholder
                                    "
                                ></textarea>

                                <p
                                    v-if="form.errors.message"
                                    id="reservation-message-error"
                                    role="alert"
                                    class="mt-2 text-xs text-red-400"
                                >
                                    {{ form.errors.message }}
                                </p>
                            </div>

                            <ReservationTrust />
                            <div
                                class="vvs-choice-card flex flex-col gap-4 rounded-2xl border p-5 sm:flex-row sm:items-center sm:justify-between md:col-span-2"
                            >
                                <div>
                                    <p class="font-black text-white">
                                        {{
                                            translations.product
                                                .contact_question
                                        }}
                                    </p>

                                    <p
                                        class="mt-1 text-xs leading-5 text-zinc-600"
                                    >
                                        {{ translations.product.contact_text }}
                                    </p>
                                </div>

                                <a
                                    href="https://www.tiktok.com/@vvsflawless43"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="vvs-button-secondary shrink-0 rounded-xl px-5 py-3 text-center text-xs font-bold tracking-[0.1em] uppercase"
                                >
                                    @vvsflawless43 →
                                </a>
                            </div>

                            <!-- CONFIRMATION -->

                            <div class="md:col-span-2">
                                <label
                                    for="reservation-confirmation"
                                    class="flex cursor-pointer items-start gap-4 rounded-xl border border-white/10 bg-black/60 p-4"
                                >
                                    <input
                                        id="reservation-confirmation"
                                        v-model="form.confirmation"
                                        type="checkbox"
                                        required
                                        :aria-invalid="
                                            Boolean(form.errors.confirmation)
                                        "
                                        :aria-describedby="
                                            form.errors.confirmation
                                                ? 'reservation-confirmation-error'
                                                : undefined
                                        "
                                        class="mt-1 h-4 w-4 accent-amber-300"
                                    />

                                    <span
                                        class="text-xs leading-6 text-zinc-500"
                                    >
                                        {{
                                            translations.product
                                                .confirmation_text
                                        }}
                                    </span>
                                </label>

                                <p
                                    v-if="form.errors.confirmation"
                                    id="reservation-confirmation-error"
                                    role="alert"
                                    class="mt-2 text-xs text-red-400"
                                >
                                    {{
                                        translations.product.confirmation_error
                                    }}
                                </p>
                            </div>

                            <!-- TOTAL -->

                            <div class="md:col-span-2">
                                <div
                                    class="flex items-end justify-between gap-5 border-t border-white/10 pt-6"
                                >
                                    <div>
                                        <p
                                            class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                                        >
                                            {{
                                                translations.product
                                                    .piece_amount
                                            }}
                                        </p>

                                        <p class="mt-2 text-sm text-zinc-400">
                                            {{ translations.product.movement }}
                                            {{ localizedMovement }}
                                        </p>
                                    </div>

                                    <p class="vvs-price text-3xl font-black">
                                        {{ formatPrice(selectedPrice) }}
                                    </p>
                                </div>
                            </div>

                            <!-- BOUTON -->

                            <div class="md:col-span-2">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="vvs-button-primary group flex w-full items-center justify-between rounded-xl px-6 py-5 text-sm font-bold tracking-[0.1em] uppercase disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <span>
                                        {{
                                            form.processing
                                                ? translations.product.sending
                                                : translations.product.submit
                                        }}
                                    </span>

                                    <span
                                        class="text-xl transition duration-300 group-hover:translate-x-1"
                                    >
                                        →
                                    </span>
                                </button>

                                <p
                                    class="mt-4 text-center text-[11px] leading-5 text-zinc-700"
                                >
                                    {{ translations.product.reservation_note }}
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <!-- AUTRES PIÈCES -->

            <RelatedWatches :watches="relatedWatches" />
        </main>

        <!-- FOOTER -->

        <footer class="border-t border-white/10 px-6 py-9">
            <div
                class="mx-auto flex max-w-[1400px] flex-col gap-5 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left"
            >
                <div>
                    <p class="text-sm font-black tracking-[0.12em] uppercase">
                        VVS FLAWLESS
                    </p>

                    <p class="mt-1 text-xs text-zinc-700">
                        {{ translations.product.footer_material }}
                    </p>
                </div>

                <div
                    class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 sm:justify-end"
                >
                    <Link
                        :href="localizedRoutes.privacy"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-600 uppercase transition hover:text-amber-300"
                    >
                        {{ translations.footer.privacy }}
                    </Link>

                    <Link
                        :href="localizedRoutes.reservationTerms"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-600 uppercase transition hover:text-amber-300"
                    >
                        {{ translations.footer.terms }}
                    </Link>

                    <Link
                        :href="localizedRoutes.watches"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                    >
                        {{ translations.footer.collection }}
                    </Link>
                </div>
            </div>
        </footer>

        <MobileReservationBar
            :movement="form.movement"
            :price="selectedPrice"
        />
    </div>
</template>
