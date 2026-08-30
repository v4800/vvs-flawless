<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    reservation: {
        type: Object,
        required: true,
    },

    watch: {
        type: Object,
        required: true,
    },
});

const formatPrice = (price) => {
    return `${Number(price).toLocaleString('fr-BE')} €`;
};
</script>

<template>
    <VvsNavigation
    current="reservation"
    :back-href="`/watches/${watch.id}`"
    back-label="Voir la montre"
    :watch-href="`/watches/${watch.id}`"
/>
    <Head title="Récapitulatif de réservation — VVS FLAWLESS" />

    <main
        class="relative min-h-screen overflow-hidden bg-black px-5 py-12 text-white sm:px-6"
    >
        <!-- HALO -->
        <div
            class="pointer-events-none absolute left-1/2 top-0 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-amber-400/[0.06] blur-[140px]"
        ></div>

        <div class="relative z-10 mx-auto max-w-4xl">

            <!-- CONFIRMATION -->
            <div class="mb-8 text-center">

                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-amber-300/40 bg-amber-300/[0.08] text-2xl font-black text-amber-200"
                >
                    ✓
                </div>

                <p
                    class="mt-6 text-[11px] font-black uppercase tracking-[0.35em] text-amber-300"
                >
                    VVS FLAWLESS
                </p>

                <h1
                    class="mt-3 text-3xl font-black uppercase sm:text-4xl"
                >
                    Réservation enregistrée
                </h1>

                <p
                    class="mx-auto mt-3 max-w-xl text-sm leading-6 text-zinc-400"
                >
                    Voici le récapitulatif complet de votre demande.
                    Une confirmation est également envoyée par e-mail.
                </p>

            </div>

            <!-- BON -->
            <section
                class="overflow-hidden rounded-3xl border border-amber-300/20 bg-zinc-950 shadow-[0_30px_100px_rgba(0,0,0,0.6)]"
            >

                <!-- EN-TÊTE -->
                <div
                    class="flex flex-col gap-6 border-b border-white/10 bg-black px-7 py-7 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p
                            class="text-xl font-black uppercase tracking-[0.08em]"
                        >
                            VVS FLAWLESS
                        </p>

                        <p class="mt-1 text-xs text-amber-300">
                            La culture bustdown arrive en Belgique 🇧🇪
                        </p>
                    </div>

                    <div class="sm:text-right">

                        <p
                            class="text-[10px] font-bold uppercase tracking-[0.25em] text-zinc-500"
                        >
                            Récapitulatif
                        </p>

                        <p
                            class="mt-1 text-lg font-black text-amber-200"
                        >
                            {{ reservation.reservation_number }}
                        </p>

                        <p class="mt-1 text-xs text-zinc-600">
                            {{ reservation.date }}
                        </p>

                    </div>
                </div>

                <!-- MONTRE -->
                <div
                    class="grid border-b border-white/10 md:grid-cols-[280px_1fr]"
                >
                    <div
                        class="flex items-center justify-center bg-zinc-900"
                    >
                        <img
                            :src="watch.image"
                            :alt="watch.name"
                            class="max-h-80 w-full object-contain"
                        />
                    </div>

                    <div class="p-7">

                        <p
                            class="text-[10px] font-black uppercase tracking-[0.25em] text-amber-300"
                        >
                            Votre montre
                        </p>

                        <h2
                            class="mt-3 text-2xl font-black leading-tight"
                        >
                            {{ watch.name }}
                        </h2>

                        <div
                            class="mt-7 grid gap-6 sm:grid-cols-2"
                        >
                            <div>
                                <p class="text-xs text-zinc-600">
                                    Mouvement
                                </p>

                                <p class="mt-1 font-semibold">
                                    {{ reservation.movement }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-zinc-600">
                                    Prix réservé
                                </p>

                                <p
                                    class="mt-1 text-3xl font-black text-amber-200"
                                >
                                    {{ formatPrice(reservation.price) }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- CLIENT -->
                <div class="p-7 sm:p-8">

                    <h3
                        class="text-xs font-black uppercase tracking-[0.25em] text-zinc-500"
                    >
                        Informations client
                    </h3>

                    <div
                        class="mt-5 grid gap-x-12 gap-y-5 sm:grid-cols-2"
                    >
                        <div>
                            <p class="text-xs text-zinc-600">
                                Nom complet
                            </p>

                            <p class="mt-1 font-semibold">
                                {{ reservation.customer_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-zinc-600">
                                E-mail
                            </p>

                            <p class="mt-1 font-semibold">
                                {{ reservation.email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-zinc-600">
                                Téléphone
                            </p>

                            <p class="mt-1 font-semibold">
                                {{ reservation.phone }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-zinc-600">
                                Ville
                            </p>

                            <p class="mt-1 font-semibold">
                                {{
                                    reservation.city
                                    || 'Non renseignée'
                                }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="my-7 border-t border-white/10"
                    ></div>

                    <!-- LIVRAISON -->
                    <div
                        class="grid gap-6 sm:grid-cols-2"
                    >
                        <div>
                            <p class="text-xs text-zinc-600">
                                Mode de remise
                            </p>

                            <p class="mt-1 font-semibold">
                                {{ reservation.delivery_method }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-zinc-600">
                                Statut
                            </p>

                            <span
                                class="mt-1 inline-flex rounded-full border border-amber-300/20 bg-amber-300/[0.05] px-3 py-1 text-xs font-bold text-amber-200"
                            >
                                {{ reservation.status }}
                            </span>
                        </div>
                    </div>

                    <!-- MESSAGE -->
                    <div
                        v-if="reservation.message"
                        class="mt-7 rounded-2xl border border-white/10 bg-black/40 p-5"
                    >
                        <p
                            class="text-xs font-bold uppercase tracking-[0.18em] text-zinc-500"
                        >
                            Message
                        </p>

                        <p
                            class="mt-3 text-sm leading-6 text-zinc-300"
                        >
                            {{ reservation.message }}
                        </p>
                    </div>

                    <!-- SUITE -->
                    <div
                        class="mt-7 rounded-2xl border border-amber-300/20 bg-amber-300/[0.04] p-5"
                    >
                        <p class="font-bold text-amber-200">
                            Et maintenant ?
                        </p>

                        <p
                            class="mt-2 text-sm leading-6 text-zinc-400"
                        >
                            Votre demande est bien enregistrée.
                            VVS FLAWLESS vous contactera pour confirmer
                            la disponibilité, le délai et finaliser votre
                            commande.
                        </p>

                        <p class="mt-3 text-xs text-zinc-600">
                            Ce récapitulatif confirme votre réservation.
                            La commande devient définitive après confirmation
                            avec VVS FLAWLESS.
                        </p>
                    </div>

                    <!-- BOUTONS -->
                    <div
                        class="mt-8 grid gap-3 sm:grid-cols-2"
                    >
                        <Link
                            href="/watches"
                            class="flex items-center justify-center rounded-xl bg-amber-300 px-5 py-4 text-xs font-black uppercase tracking-[0.12em] text-black transition hover:bg-amber-200"
                        >
                            Retour à la collection
                        </Link>

                        <Link
                            :href="`/watches/${watch.id}?movement=${reservation.movement}`"
                            class="flex items-center justify-center rounded-xl border border-amber-300/30 px-5 py-4 text-xs font-black uppercase tracking-[0.12em] text-amber-200 transition hover:bg-amber-300/[0.05]"
                        >
                            Revoir la montre
                        </Link>
                    </div>

                </div>
            </section>

        </div>
    </main>
</template>