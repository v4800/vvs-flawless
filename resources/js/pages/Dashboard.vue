<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    reservations: Array,
});

const statuses = [
    'Nouvelle demande',
    'Confirmée',
    'Commandée',
    'Disponible',
    'Terminée',
    'Annulée',
];

const updateStatus = (reservation, status) => {
    router.patch(
        `/dashboard/reservations/${reservation.id}/status`,
        {
            status,
        },
        {
            preserveScroll: true,
        },
    );
};

const formatDate = (date) => {
    return new Date(date).toLocaleString('fr-BE');
};

const formatSource = (source) => {
    if (!source) {
        return 'Direct / non attribué';
    }

    const sources = {
        tiktok: 'TikTok',
        instagram: 'Instagram',
        facebook: 'Facebook',
        google: 'Google',
        youtube: 'YouTube',
    };

    return sources[source.toLowerCase()] ?? source;
};

const formatMedium = (medium) => {
    if (!medium) {
        return null;
    }

    const mediums = {
        organic_social: 'Réseaux sociaux organiques',
        social: 'Réseaux sociaux',
        organic: 'Organique',
        referral: 'Lien externe',
        email: 'Email',
        cpc: 'Publicité payante',
    };

    return mediums[medium.toLowerCase()] ?? medium;
};

const hasMarketingAttribution = (reservation) => {
    return Boolean(
        reservation.utm_source ||
        reservation.utm_medium ||
        reservation.utm_campaign,
    );
};
</script>

<template>
    <main class="min-h-screen bg-black px-6 py-12 text-white">
        <div class="mx-auto max-w-7xl">
            <!-- HEADER -->
            <div class="mb-10">
                <p class="text-sm tracking-[0.35em] text-zinc-500 uppercase">
                    VVS FLAWLESS
                </p>

                <h1 class="mt-2 text-4xl font-bold md:text-5xl">
                    Réservations
                </h1>

                <p class="mt-4 text-zinc-400">
                    Gérez les demandes reçues depuis votre catalogue.
                </p>

                <div
                    class="mt-6 inline-flex rounded-full border border-white/10 bg-white/[0.04] px-4 py-2 text-sm"
                >
                    {{ reservations.length }} demande(s)
                </div>
            </div>

            <!-- AUCUNE RÉSERVATION -->
            <div
                v-if="reservations.length === 0"
                class="rounded-3xl border border-white/10 bg-zinc-950 p-10 text-center"
            >
                <p class="text-xl font-semibold">Aucune réservation</p>

                <p class="mt-2 text-zinc-500">
                    Les nouvelles demandes apparaîtront ici.
                </p>
            </div>

            <!-- RÉSERVATIONS -->
            <div v-else class="space-y-6">
                <article
                    v-for="reservation in reservations"
                    :key="reservation.id"
                    class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950"
                >
                    <div class="grid lg:grid-cols-[240px_1fr]">
                        <!-- PHOTO -->
                        <div class="bg-zinc-900">
                            <img
                                v-if="reservation.watch?.image"
                                :src="reservation.watch.image"
                                :alt="reservation.watch.name"
                                class="h-full min-h-[240px] w-full object-cover"
                            />
                        </div>

                        <!-- INFORMATIONS -->
                        <div class="p-6 md:p-8">
                            <!-- TOP -->
                            <div
                                class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between"
                            >
                                <div>
                                    <p
                                        class="text-xs tracking-[0.25em] text-zinc-500 uppercase"
                                    >
                                        {{ reservation.reservation_number }}
                                    </p>

                                    <h2 class="mt-2 text-2xl font-semibold">
                                        {{ reservation.watch?.name }}
                                    </h2>

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span
                                            class="rounded-full border border-white/10 bg-white/[0.05] px-3 py-1 text-xs"
                                        >
                                            Mouvement {{ reservation.movement }}
                                        </span>

                                        <span
                                            v-if="reservation.price !== null"
                                            class="rounded-full border border-white/10 bg-white/[0.05] px-3 py-1 text-xs"
                                        >
                                            {{
                                                Number(
                                                    reservation.price,
                                                ).toFixed(0)
                                            }}
                                            €
                                        </span>

                                        <span
                                            v-if="
                                                hasMarketingAttribution(
                                                    reservation,
                                                )
                                            "
                                            class="rounded-full border border-amber-400/20 bg-amber-400/[0.08] px-3 py-1 text-xs text-amber-200"
                                        >
                                            {{
                                                formatSource(
                                                    reservation.utm_source,
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <!-- STATUT -->
                                <div class="min-w-[200px]">
                                    <label
                                        class="mb-2 block text-xs tracking-wider text-zinc-500 uppercase"
                                    >
                                        Statut
                                    </label>

                                    <select
                                        :value="reservation.status"
                                        @change="
                                            updateStatus(
                                                reservation,
                                                $event.target.value,
                                            )
                                        "
                                        class="w-full rounded-xl border border-white/10 bg-zinc-900 px-4 py-3 text-sm text-white outline-none"
                                    >
                                        <option
                                            v-for="status in statuses"
                                            :key="status"
                                            :value="status"
                                        >
                                            {{ status }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- CLIENT -->
                            <div
                                class="mt-8 grid gap-5 border-t border-white/10 pt-6 md:grid-cols-2 lg:grid-cols-4"
                            >
                                <div>
                                    <p class="text-xs text-zinc-500">Client</p>

                                    <p class="mt-1 font-medium">
                                        {{ reservation.customer_name }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-zinc-500">
                                        Téléphone
                                    </p>

                                    <a
                                        :href="`tel:${reservation.phone}`"
                                        class="mt-1 block font-medium hover:underline"
                                    >
                                        {{ reservation.phone }}
                                    </a>
                                </div>

                                <div>
                                    <p class="text-xs text-zinc-500">Email</p>

                                    <a
                                        :href="`mailto:${reservation.email}`"
                                        class="mt-1 block font-medium break-all hover:underline"
                                    >
                                        {{ reservation.email }}
                                    </a>
                                </div>

                                <div>
                                    <p class="text-xs text-zinc-500">Ville</p>

                                    <p class="mt-1 font-medium">
                                        {{
                                            reservation.city || 'Non renseignée'
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- COMMANDE -->
                            <div class="mt-6 grid gap-5 md:grid-cols-3">
                                <div>
                                    <p class="text-xs text-zinc-500">
                                        Mode de réception
                                    </p>

                                    <p class="mt-1 font-medium">
                                        {{ reservation.delivery_method }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-zinc-500">
                                        Date de la demande
                                    </p>

                                    <p class="mt-1 font-medium">
                                        {{ formatDate(reservation.created_at) }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-zinc-500">ID</p>

                                    <p class="mt-1 font-medium">
                                        #{{ reservation.id }}
                                    </p>
                                </div>
                            </div>

                            <!-- MARKETING -->
                            <div
                                v-if="hasMarketingAttribution(reservation)"
                                class="mt-6 rounded-2xl border border-amber-400/15 bg-amber-400/[0.04] p-5"
                            >
                                <p
                                    class="text-xs tracking-[0.2em] text-amber-300/70 uppercase"
                                >
                                    Provenance marketing
                                </p>

                                <div class="mt-4 grid gap-5 md:grid-cols-3">
                                    <div>
                                        <p class="text-xs text-zinc-500">
                                            Source
                                        </p>

                                        <p class="mt-1 font-medium">
                                            {{
                                                formatSource(
                                                    reservation.utm_source,
                                                )
                                            }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-zinc-500">
                                            Canal
                                        </p>

                                        <p class="mt-1 font-medium">
                                            {{
                                                formatMedium(
                                                    reservation.utm_medium,
                                                ) || 'Non renseigné'
                                            }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-zinc-500">
                                            Campagne
                                        </p>

                                        <p class="mt-1 font-medium">
                                            {{
                                                reservation.utm_campaign ||
                                                'Non renseignée'
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- MESSAGE -->
                            <div
                                v-if="reservation.message"
                                class="mt-6 rounded-2xl border border-white/10 bg-black/30 p-5"
                            >
                                <p class="text-xs text-zinc-500">
                                    Message du client
                                </p>

                                <p class="mt-2 leading-6 text-zinc-300">
                                    {{ reservation.message }}
                                </p>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </main>
</template>
