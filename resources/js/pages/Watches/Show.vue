<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
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
});

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

const deliveryOptions = [
    {
        value: 'Remise en main propre',
        description: 'Le point de rencontre est confirmé avec vous.',
    },
    {
        value: 'Livraison',
        description: 'Les modalités de livraison sont confirmées avec vous.',
    },
];

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
    form.post('/reservations', {
        preserveScroll: false,
    });
};
</script>

<template>
    <Head :title="`${watch.name} — VVS FLAWLESS`" />

    <div class="min-h-screen bg-black pb-24 text-white lg:pb-0">
        <a
            href="#main-content"
            class="sr-only z-[100] rounded-lg bg-amber-300 px-4 py-3 font-bold text-black focus:not-sr-only focus:fixed focus:top-4 focus:left-4"
        >
            Aller au contenu
        </a>

        <VvsNavigation
            current="watch"
            back-href="/watches"
            back-label="Collection"
            :watch-href="`/watches/${watch.id}`"
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
                                        Pierre
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
                                        Couleur
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
                            <div
                                class="rounded-2xl border border-white/10 bg-zinc-950/70 p-4"
                            >
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    Pureté
                                </p>

                                <p class="mt-2 font-black text-amber-200">
                                    VVS
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-white/10 bg-zinc-950/70 p-4"
                            >
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    Couleur
                                </p>

                                <p class="mt-2 font-black text-amber-200">D</p>
                            </div>

                            <div
                                class="rounded-2xl border border-white/10 bg-zinc-950/70 p-4"
                            >
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    Réception
                                </p>

                                <p
                                    class="mt-2 text-xs font-black text-amber-200"
                                >
                                    Au choix
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- INFORMATIONS -->

                    <div class="lg:pt-5">
                        <div class="flex items-center gap-3">
                            <span class="h-px w-8 bg-amber-300"></span>

                            <p
                                class="text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
                            >
                                VVS FLAWLESS
                            </p>
                        </div>

                        <h1
                            class="mt-6 max-w-2xl text-4xl leading-[1.03] font-black tracking-[-0.035em] uppercase sm:text-5xl"
                        >
                            {{ watch.name }}
                        </h1>

                        <p
                            class="mt-4 text-xs font-bold tracking-[0.22em] text-zinc-500 uppercase"
                        >
                            Moissanite VVS

                            <span class="mx-2 text-amber-400"> • </span>

                            Couleur D
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
                                        Configuration
                                    </p>

                                    <h2 class="mt-2 text-xl font-black">
                                        Choisissez votre mouvement
                                    </h2>
                                </div>

                                <p
                                    class="hidden text-xs text-zinc-600 sm:block"
                                >
                                    2 versions disponibles
                                </p>
                            </div>

                            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                <Link
                                    :href="`/watches/${watch.id}?movement=Japonais`"
                                    preserve-scroll
                                    :class="[
                                        'group relative overflow-hidden rounded-2xl border p-5 transition duration-300',
                                        form.movement === 'Japonais'
                                            ? 'border-amber-300/70 bg-amber-300/[0.06] shadow-[0_0_35px_rgba(251,191,36,0.08)]'
                                            : 'border-white/10 bg-zinc-950 hover:-translate-y-1 hover:border-white/20',
                                    ]"
                                >
                                    <div
                                        class="flex items-start justify-between gap-4"
                                    >
                                        <div>
                                            <p
                                                class="text-[9px] font-black tracking-[0.22em] text-zinc-500 uppercase"
                                            >
                                                Version
                                            </p>

                                            <p class="mt-2 text-lg font-black">
                                                Japonais
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
                                            class="mt-1 text-3xl font-black text-amber-200"
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
                                        Sélectionner →
                                    </p>
                                </Link>

                                <Link
                                    :href="`/watches/${watch.id}?movement=Suisse`"
                                    preserve-scroll
                                    :class="[
                                        'group relative overflow-hidden rounded-2xl border p-5 transition duration-300',
                                        form.movement === 'Suisse'
                                            ? 'border-amber-300/70 bg-amber-300/[0.06] shadow-[0_0_35px_rgba(251,191,36,0.08)]'
                                            : 'border-white/10 bg-zinc-950 hover:-translate-y-1 hover:border-white/20',
                                    ]"
                                >
                                    <div
                                        class="flex items-start justify-between gap-4"
                                    >
                                        <div>
                                            <p
                                                class="text-[9px] font-black tracking-[0.22em] text-zinc-500 uppercase"
                                            >
                                                Version
                                            </p>

                                            <p class="mt-2 text-lg font-black">
                                                Suisse
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
                                            class="mt-1 text-3xl font-black text-amber-200"
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
                                        Sélectionner →
                                    </p>
                                </Link>
                            </div>
                        </div>

                        <!-- PRIX -->

                        <div
                            class="mt-5 flex items-center justify-between gap-5 rounded-2xl border border-amber-300/20 bg-gradient-to-r from-amber-300/[0.055] to-transparent p-5"
                        >
                            <div>
                                <p
                                    class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                                >
                                    Votre sélection
                                </p>

                                <p class="mt-2 font-bold text-zinc-200">
                                    Mouvement {{ form.movement }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p
                                    v-if="selectedOldPrice > selectedPrice"
                                    class="text-xs text-zinc-600 line-through"
                                >
                                    {{ formatPrice(selectedOldPrice) }}
                                </p>

                                <p class="text-3xl font-black text-amber-200">
                                    {{ formatPrice(selectedPrice) }}
                                </p>
                            </div>
                        </div>

                        <!-- DISPONIBILITÉ -->

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div
                                class="rounded-2xl border border-white/10 bg-zinc-950/70 p-5"
                            >
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    Disponibilité estimée
                                </p>

                                <p class="mt-2 font-bold text-zinc-200">
                                    5–6 jours ouvrables
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-white/10 bg-zinc-950/70 p-5"
                            >
                                <p
                                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                                >
                                    Réception
                                </p>

                                <p class="mt-2 font-bold text-zinc-200">
                                    Remise ou livraison
                                </p>
                            </div>
                        </div>

                        <a
                            href="#reservation"
                            class="mt-6 flex w-full items-center justify-between rounded-2xl bg-amber-300 px-6 py-5 font-black tracking-[0.1em] text-black uppercase transition duration-300 hover:-translate-y-1 hover:bg-amber-200"
                        >
                            <span> Réserver cette montre </span>

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
                            Sélectionnée pour son éclat
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs font-black tracking-[0.18em] text-amber-200 uppercase"
                        >
                            Couleur D
                        </p>

                        <p class="mt-2 text-xs text-zinc-600">
                            Rendu clair et incolore
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs font-black tracking-[0.18em] text-amber-200 uppercase"
                        >
                            Réception au choix
                        </p>

                        <p class="mt-2 text-xs text-zinc-600">
                            Remise ou livraison
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
                        <p
                            class="text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
                        >
                            Votre sélection
                        </p>

                        <h2
                            class="mt-4 text-3xl font-black tracking-[-0.03em] uppercase"
                        >
                            Réserver votre pièce
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-500">
                            Envoyez votre demande. VVS FLAWLESS vous contactera
                            ensuite afin de confirmer les détails et le mode de
                            réception choisi.
                        </p>

                        <div
                            class="mt-7 overflow-hidden rounded-2xl border border-white/10 bg-zinc-950"
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
                                        class="line-clamp-2 text-sm leading-5 font-black uppercase"
                                    >
                                        {{ watch.name }}
                                    </p>

                                    <p
                                        class="mt-2 text-[10px] tracking-[0.15em] text-zinc-500 uppercase"
                                    >
                                        {{ form.movement }}
                                    </p>

                                    <p
                                        class="mt-3 text-xl font-black text-amber-200"
                                    >
                                        {{ formatPrice(selectedPrice) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-4 rounded-2xl border border-amber-300/15 bg-amber-300/[0.025] p-5"
                        >
                            <p class="text-xs font-bold text-zinc-300">
                                ◆ {{ form.delivery_method }}
                            </p>

                            <p class="mt-2 text-xs leading-5 text-zinc-600">
                                Les modalités sont confirmées avec vous après
                                l'envoi de la demande.
                            </p>
                        </div>
                    </div>

                    <!-- FORMULAIRE -->

                    <form
                        class="rounded-3xl border border-white/10 bg-gradient-to-br from-zinc-950 to-black p-6 shadow-[0_30px_100px_rgba(0,0,0,0.35)] sm:p-8"
                        @submit.prevent="submit"
                    >
                        <div
                            class="mb-8 flex items-center justify-between gap-5 border-b border-white/10 pb-6"
                        >
                            <div>
                                <p
                                    class="text-[9px] font-black tracking-[0.3em] text-zinc-600 uppercase"
                                >
                                    Réservation
                                </p>

                                <h3 class="mt-2 text-2xl font-black">
                                    Vos informations
                                </h3>
                            </div>

                            <div
                                class="rounded-full border border-amber-300/20 bg-amber-300/[0.04] px-4 py-2 text-xs font-bold text-amber-200"
                            >
                                {{ form.movement }}
                            </div>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <!-- NOM -->

                            <div>
                                <label
                                    for="reservation-customer-name"
                                    class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                                >
                                    Nom complet
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
                                    placeholder="Votre nom"
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
                                    E-mail
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
                                    placeholder="email@exemple.com"
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
                                    Téléphone
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
                                    Ville
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
                                    placeholder="Ex. Liège"
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
                                    Mode de réception
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
                                                    {{ option.value }}
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
                                    Message

                                    <span
                                        class="tracking-normal text-zinc-700 normal-case"
                                    >
                                        — facultatif
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
                                    placeholder="Une question ou une précision concernant votre réservation ?"
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
                                class="flex flex-col gap-4 rounded-2xl border border-white/10 bg-zinc-950/70 p-5 sm:flex-row sm:items-center sm:justify-between md:col-span-2"
                            >
                                <div>
                                    <p class="font-black text-white">
                                        Une question avant de réserver ?
                                    </p>

                                    <p
                                        class="mt-1 text-xs leading-5 text-zinc-600"
                                    >
                                        Contactez directement VVS FLAWLESS pour
                                        une précision sur la montre ou le
                                        mouvement.
                                    </p>
                                </div>

                                <a
                                    href="https://www.tiktok.com/@vvsflawless43"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="shrink-0 rounded-xl border border-amber-300/25 px-5 py-3 text-center text-xs font-black tracking-[0.1em] text-amber-200 uppercase transition hover:border-amber-300 hover:bg-amber-300 hover:text-black"
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
                                        Je confirme souhaiter réserver cette
                                        montre et être contacté par VVS FLAWLESS
                                        afin de finaliser ma demande et
                                        confirmer le mode de réception choisi.
                                    </span>
                                </label>

                                <p
                                    v-if="form.errors.confirmation"
                                    id="reservation-confirmation-error"
                                    role="alert"
                                    class="mt-2 text-xs text-red-400"
                                >
                                    Vous devez confirmer votre demande.
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
                                            Montant de la pièce
                                        </p>

                                        <p class="mt-2 text-sm text-zinc-400">
                                            Mouvement {{ form.movement }}
                                        </p>
                                    </div>

                                    <p
                                        class="text-3xl font-black text-amber-200"
                                    >
                                        {{ formatPrice(selectedPrice) }}
                                    </p>
                                </div>
                            </div>

                            <!-- BOUTON -->

                            <div class="md:col-span-2">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="group flex w-full items-center justify-between rounded-xl bg-amber-300 px-6 py-5 text-sm font-black tracking-[0.1em] text-black uppercase transition duration-300 hover:bg-amber-200 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <span>
                                        {{
                                            form.processing
                                                ? 'Envoi en cours...'
                                                : 'Envoyer ma réservation'
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
                                    La réservation enregistre votre demande.
                                    Elle est finalisée après confirmation avec
                                    VVS FLAWLESS.
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
                        Moissanite VVS • Couleur D
                    </p>
                </div>

                <div
                    class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 sm:justify-end"
                >
                    <Link
                        href="/confidentialite"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-600 uppercase transition hover:text-amber-300"
                    >
                        Confidentialité
                    </Link>

                    <Link
                        href="/conditions-reservation"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-600 uppercase transition hover:text-amber-300"
                    >
                        Conditions de réservation
                    </Link>

                    <Link
                        href="/watches"
                        class="text-[10px] font-bold tracking-[0.1em] text-zinc-500 uppercase transition hover:text-amber-300"
                    >
                        ← Collection
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
