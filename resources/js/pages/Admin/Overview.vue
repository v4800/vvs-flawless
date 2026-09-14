<script setup>
import { Head, Link } from '@inertiajs/vue3';

import AdminLayout from '@/layouts/AdminLayout.vue';

defineProps({
    stats: {
        type: Object,
        required: true,
    },
    recentReservations: {
        type: Array,
        required: true,
    },
    appointments: {
        type: Array,
        required: true,
    },
    activities: {
        type: Array,
        required: true,
    },
});

const formatMoney = (value) =>
    new Intl.NumberFormat('fr-BE', {
        style: 'currency',
        currency: 'EUR',
        maximumFractionDigits: 2,
    }).format(Number(value ?? 0));

const formatDate = (value) => {
    if (!value) {
        return 'Non renseigné';
    }

    return new Intl.DateTimeFormat('fr-BE', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
};
</script>

<template>
    <Head title="Administration VVS FLAWLESS">
        <meta name="robots" content="noindex,nofollow,noarchive" />
    </Head>

    <AdminLayout
        title="Vue d’ensemble"
        subtitle="Les informations utiles pour gérer les ventes et remises en main propre."
    >
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <article
                class="rounded-2xl border border-white/10 bg-zinc-950 p-5"
            >
                <p class="text-xs font-bold text-zinc-500">
                    Nouvelles demandes
                </p>
                <p class="mt-3 text-3xl font-black text-amber-200">
                    {{ stats.new }}
                </p>
            </article>

            <article
                class="rounded-2xl border border-white/10 bg-zinc-950 p-5"
            >
                <p class="text-xs font-bold text-zinc-500">
                    Acomptes confirmés
                </p>
                <p class="mt-3 text-3xl font-black">
                    {{ stats.deposit_paid }}
                </p>
            </article>

            <article
                class="rounded-2xl border border-white/10 bg-zinc-950 p-5"
            >
                <p class="text-xs font-bold text-zinc-500">
                    Rendez-vous planifiés
                </p>
                <p class="mt-3 text-3xl font-black">
                    {{ stats.appointments }}
                </p>
            </article>

            <article
                class="rounded-2xl border border-white/10 bg-zinc-950 p-5"
            >
                <p class="text-xs font-bold text-zinc-500">
                    Ventes terminées
                </p>
                <p class="mt-3 text-3xl font-black">
                    {{ stats.completed }}
                </p>
            </article>

            <article
                class="rounded-2xl border border-amber-300/20 bg-amber-300/5 p-5 sm:col-span-2 xl:col-span-1"
            >
                <p class="text-xs font-bold text-amber-200">
                    Montant encaissé confirmé
                </p>
                <p class="mt-3 text-3xl font-black text-amber-200">
                    {{ formatMoney(stats.confirmed_revenue) }}
                </p>
            </article>
        </section>

        <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
            <section
                class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950"
            >
                <div
                    class="flex items-center justify-between gap-4 border-b border-white/10 px-5 py-4"
                >
                    <div>
                        <h2 class="font-black">Réservations récentes</h2>
                        <p class="mt-1 text-xs text-zinc-500">
                            Les huit dernières demandes actives
                        </p>
                    </div>
                    <Link
                        href="/admin/reservations"
                        class="text-xs font-black text-amber-200 hover:text-amber-100"
                    >
                        Tout afficher
                    </Link>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="text-xs text-zinc-500">
                            <tr>
                                <th class="px-5 py-4 font-bold">Référence</th>
                                <th class="px-5 py-4 font-bold">Client</th>
                                <th class="px-5 py-4 font-bold">Montre</th>
                                <th class="px-5 py-4 font-bold">Statut</th>
                                <th class="px-5 py-4 text-right font-bold">
                                    Prix
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr
                                v-for="reservation in recentReservations"
                                :key="reservation.id"
                                class="hover:bg-white/[0.025]"
                            >
                                <td class="px-5 py-4">
                                    <Link
                                        :href="
                                            '/admin/reservations?q=' +
                                            encodeURIComponent(
                                                reservation.reservation_number,
                                            )
                                        "
                                        class="font-bold text-amber-200"
                                    >
                                        {{ reservation.reservation_number }}
                                    </Link>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-bold">
                                        {{ reservation.customer_name }}
                                    </p>
                                    <p class="mt-1 text-xs text-zinc-600">
                                        {{ reservation.city || 'Ville inconnue' }}
                                    </p>
                                </td>
                                <td class="max-w-56 truncate px-5 py-4 text-zinc-300">
                                    {{ reservation.watch_name }}
                                </td>
                                <td class="px-5 py-4 text-zinc-300">
                                    {{ reservation.status }}
                                </td>
                                <td class="px-5 py-4 text-right font-black">
                                    {{ formatMoney(reservation.price) }}
                                </td>
                            </tr>
                            <tr v-if="recentReservations.length === 0">
                                <td
                                    colspan="5"
                                    class="px-5 py-12 text-center text-zinc-600"
                                >
                                    Aucune réservation active.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <aside class="space-y-6">
                <section
                    class="rounded-3xl border border-white/10 bg-zinc-950 p-5"
                >
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="font-black">Prochains rendez-vous</h2>
                        <Link
                            href="/admin/reservations?status=Rendez-vous%20planifi%C3%A9&sort=appointment"
                            class="text-xs font-black text-amber-200"
                        >
                            Voir
                        </Link>
                    </div>

                    <div
                        v-if="appointments.length"
                        class="mt-4 divide-y divide-white/10"
                    >
                        <article
                            v-for="appointment in appointments"
                            :key="appointment.id"
                            class="py-4 first:pt-0 last:pb-0"
                        >
                            <p class="text-xs font-bold text-amber-200">
                                {{ formatDate(appointment.appointment_at) }}
                            </p>
                            <p class="mt-2 font-bold">
                                {{ appointment.customer_name }}
                            </p>
                            <p class="mt-1 text-xs text-zinc-500">
                                {{
                                    appointment.handover_address ||
                                    appointment.city ||
                                    'Adresse à confirmer'
                                }}
                            </p>
                        </article>
                    </div>
                    <p v-else class="mt-4 text-sm text-zinc-600">
                        Aucun rendez-vous à venir.
                    </p>
                </section>

                <section
                    class="rounded-3xl border border-white/10 bg-zinc-950 p-5"
                >
                    <h2 class="font-black">Activité récente</h2>
                    <div
                        v-if="activities.length"
                        class="mt-4 divide-y divide-white/10"
                    >
                        <article
                            v-for="activity in activities"
                            :key="activity.id"
                            class="py-4 first:pt-0 last:pb-0"
                        >
                            <p class="text-sm font-bold">
                                {{ activity.status }}
                            </p>
                            <p class="mt-1 text-xs text-zinc-500">
                                {{ activity.reservation_number }}
                                <span v-if="activity.customer_name">
                                    · {{ activity.customer_name }}
                                </span>
                            </p>
                            <p class="mt-1 text-xs text-zinc-600">
                                {{ formatDate(activity.created_at) }}
                                <span v-if="activity.changed_by">
                                    · {{ activity.changed_by }}
                                </span>
                            </p>
                        </article>
                    </div>
                    <p v-else class="mt-4 text-sm text-zinc-600">
                        Aucun changement récent.
                    </p>
                </section>
            </aside>
        </div>
    </AdminLayout>
</template>
