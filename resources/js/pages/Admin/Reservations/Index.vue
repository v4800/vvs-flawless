<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

defineOptions({
    layout: null,
});

const props = defineProps({
    reservations: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    filterOptions: {
        type: Object,
        required: true,
    },
    workflowStatuses: {
        type: Array,
        required: true,
    },
});

const filterState = reactive({
    q: props.filters.q ?? '',
    status: props.filters.status ?? '',
    date_from: props.filters.date_from ?? '',
    date_to: props.filters.date_to ?? '',
    watch_id: props.filters.watch_id ?? '',
    city: props.filters.city ?? '',
    source: props.filters.source ?? '',
    show_archived: Boolean(props.filters.show_archived),
    show_test: Boolean(props.filters.show_test),
    sort: props.filters.sort ?? 'latest',
});

const expanded = ref(new Set());
const drafts = reactive({});
const saving = reactive({});
const saved = reactive({});
const formErrors = reactive({});
const copiedReference = ref(null);

const statCards = [
    { key: 'new', label: 'Nouvelles demandes' },
    { key: 'contacted', label: 'Clients contactés' },
    { key: 'deposit_paid', label: 'Acomptes reçus' },
    { key: 'preparing', label: 'En préparation' },
    { key: 'appointment', label: 'Rendez-vous' },
    { key: 'completed', label: 'Ventes terminées' },
    { key: 'cancelled', label: 'Annulées' },
];

const formatMoney = (value) => {
    if (value === null || value === undefined) {
        return 'Non renseigné';
    }

    return new Intl.NumberFormat('fr-BE', {
        style: 'currency',
        currency: 'EUR',
        maximumFractionDigits: 2,
    }).format(Number(value));
};

const formatDate = (value) => {
    if (!value) {
        return 'Non renseigné';
    }

    return new Intl.DateTimeFormat('fr-BE', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
};

const toDateTimeLocal = (value) => {
    if (!value) {
        return '';
    }

    const date = new Date(value);
    const pad = (part) => String(part).padStart(2, '0');

    return [
        date.getFullYear(),
        '-',
        pad(date.getMonth() + 1),
        '-',
        pad(date.getDate()),
        'T',
        pad(date.getHours()),
        ':',
        pad(date.getMinutes()),
    ].join('');
};

const watchName = (reservation) =>
    reservation.watch_name_snapshot ||
    reservation.watch?.name ||
    'Montre supprimée';

const watchImage = (reservation) =>
    reservation.watch_image_snapshot || reservation.watch?.image || null;

const sourceLabel = (reservation) => {
    const source = reservation.utm_source || 'Direct / non attribué';
    const details = [reservation.utm_medium, reservation.utm_campaign]
        .filter(Boolean)
        .join(' · ');

    return details ? source + ' · ' + details : source;
};

const toggleDetails = (id) => {
    const next = new Set(expanded.value);

    if (next.has(id)) {
        next.delete(id);
    } else {
        next.add(id);
    }

    expanded.value = next;
};

const draftFor = (reservation) => {
    if (!drafts[reservation.id]) {
        drafts[reservation.id] = {
            status: reservation.status,
            deposit_paid_at: toDateTimeLocal(reservation.deposit_paid_at),
            balance_paid_at: toDateTimeLocal(reservation.balance_paid_at),
            appointment_at: toDateTimeLocal(reservation.appointment_at),
            handover_address: reservation.handover_address ?? '',
            travel_fee: reservation.travel_fee ?? 0,
            admin_notes: reservation.admin_notes ?? '',
            is_test: Boolean(reservation.is_test),
        };
    }

    return drafts[reservation.id];
};

const cleanFilters = () =>
    Object.fromEntries(
        Object.entries(filterState)
            .filter(([, value]) => value !== '' && value !== false)
            .map(([key, value]) => [key, value === true ? 1 : value]),
    );

const applyFilters = () => {
    router.get('/admin/reservations', cleanFilters(), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const resetFilters = () => {
    Object.assign(filterState, {
        q: '',
        status: '',
        date_from: '',
        date_to: '',
        watch_id: '',
        city: '',
        source: '',
        show_archived: false,
        show_test: false,
        sort: 'latest',
    });

    router.get('/admin/reservations', {}, {
        preserveScroll: true,
        replace: true,
    });
};

const saveReservation = (reservation) => {
    const data = { ...draftFor(reservation) };

    saving[reservation.id] = true;
    saved[reservation.id] = false;
    formErrors[reservation.id] = {};

    router.patch('/admin/reservations/' + reservation.id, data, {
        preserveScroll: true,
        onSuccess: () => {
            saved[reservation.id] = true;
            window.setTimeout(() => {
                saved[reservation.id] = false;
            }, 2500);
        },
        onError: (errors) => {
            formErrors[reservation.id] = errors;
        },
        onFinish: () => {
            saving[reservation.id] = false;
        },
    });
};

const quickUpdateStatus = (reservation, status) => {
    draftFor(reservation).status = status;
    saveReservation(reservation);
};

const setPaymentNow = (reservation, field) => {
    draftFor(reservation)[field] = toDateTimeLocal(new Date().toISOString());
};

const archiveReservation = (reservation) => {
    const archived = !reservation.archived_at;
    const question = archived
        ? 'Archiver cette réservation ? Elle ne sera pas supprimée.'
        : 'Restaurer cette réservation ?';

    if (!window.confirm(question)) {
        return;
    }

    router.patch(
        '/admin/reservations/' + reservation.id + '/archive',
        { archived },
        { preserveScroll: true },
    );
};

const copyReference = async (reservation) => {
    await navigator.clipboard.writeText(reservation.reservation_number);
    copiedReference.value = reservation.id;

    window.setTimeout(() => {
        copiedReference.value = null;
    }, 1800);
};

const normalizedPhoneNumber = (phone) => {
    const raw = String(phone ?? '').trim();
    let digits = raw.replace(/\D/g, '');

    if (digits.startsWith('00')) {
        digits = digits.slice(2);
    } else if (digits.startsWith('0')) {
        digits = '32' + digits.slice(1);
    }

    return digits;
};

const phoneHref = (reservation) => {
    const phone = normalizedPhoneNumber(reservation.phone);

    return phone ? 'tel:+' + phone : '#';
};

const whatsappHref = (reservation) => {
    const phone = normalizedPhoneNumber(reservation.phone);
    const message = encodeURIComponent(
        'Bonjour ' +
            reservation.customer_name +
            ', je vous contacte au sujet de votre réservation ' +
            reservation.reservation_number +
            ' chez VVS FLAWLESS.',
    );

    return phone ? 'https://wa.me/' + phone + '?text=' + message : '#';
};

const gmailHref = (reservation) => {
    const subject = encodeURIComponent(
        'Votre réservation ' +
            reservation.reservation_number +
            ' — VVS FLAWLESS',
    );
    const body = encodeURIComponent(
        'Bonjour ' +
            reservation.customer_name +
            ',\n\nJe vous contacte au sujet de votre réservation ' +
            reservation.reservation_number +
            '.\n\nVVS FLAWLESS',
    );

    return (
        'https://mail.google.com/mail/?view=cm&fs=1&to=' +
        encodeURIComponent(reservation.email) +
        '&su=' +
        subject +
        '&body=' +
        body
    );
};

const reviewMailto = (reservation) => {
    const subject = encodeURIComponent(
        'Votre avis sur votre expérience VVS FLAWLESS',
    );
    const reviewUrl = window.location.origin + '/#avis-clients';
    const body = encodeURIComponent(
        'Bonjour ' +
            reservation.customer_name +
            ',\n\nMerci pour votre confiance. Vous pouvez partager votre expérience VVS FLAWLESS ici :\n' +
            reviewUrl +
            '\n\nVotre avis ne sera publié qu’après validation.\n\nVVS FLAWLESS',
    );

    return (
        'mailto:' +
        encodeURIComponent(reservation.email) +
        '?subject=' +
        subject +
        '&body=' +
        body
    );
};

const paginationLabel = (label) =>
    label
        .replace('&laquo; Previous', 'Précédent')
        .replace('Next &raquo;', 'Suivant');

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <Head title="Administration des réservations">
        <meta name="robots" content="noindex,nofollow,noarchive" />
    </Head>

    <main class="min-h-screen bg-[#050505] text-white">
        <header
            class="sticky top-0 z-40 border-b border-white/10 bg-black/90 backdrop-blur-xl"
        >
            <div
                class="mx-auto flex max-w-[1500px] items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8"
            >
                <div>
                    <p
                        class="text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
                    >
                        VVS FLAWLESS
                    </p>
                    <p class="mt-1 text-sm font-semibold text-zinc-300">
                        Administration
                    </p>
                </div>

                <nav class="flex items-center gap-2" aria-label="Administration">
                    <span
                        class="rounded-full border border-amber-300/30 bg-amber-300/10 px-4 py-2 text-xs font-bold text-amber-200"
                    >
                        Réservations
                    </span>
                    <button
                        type="button"
                        class="rounded-full border border-white/10 px-4 py-2 text-xs font-bold text-zinc-300 transition hover:border-white/30 hover:text-white"
                        @click="logout"
                    >
                        Déconnexion
                    </button>
                </nav>
            </div>
        </header>

        <div class="mx-auto max-w-[1500px] px-4 py-8 sm:px-6 lg:px-8">
            <section
                class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
            >
                <div>
                    <p
                        class="text-xs font-black tracking-[0.28em] text-zinc-500 uppercase"
                    >
                        Suivi commercial 25 / 75
                    </p>
                    <h1
                        class="mt-2 text-3xl font-black tracking-tight sm:text-4xl"
                    >
                        Réservations
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm text-zinc-400">
                        Acompte de 25 %, préparation, vidéo, rendez-vous puis
                        solde de 75 % pendant la remise en main propre.
                    </p>
                </div>

                <div class="text-sm text-zinc-400">
                    <strong class="text-white">{{ reservations.total }}</strong>
                    résultat(s)
                </div>
            </section>

            <section
                class="mt-7 grid grid-cols-2 gap-3 md:grid-cols-4 xl:grid-cols-7"
                aria-label="Résumé des réservations"
            >
                <article
                    v-for="card in statCards"
                    :key="card.key"
                    class="rounded-2xl border border-white/10 bg-white/[0.035] p-4"
                >
                    <div class="h-1 w-8 rounded-full bg-amber-300"></div>
                    <p class="mt-4 text-2xl font-black">
                        {{ stats[card.key] ?? 0 }}
                    </p>
                    <p class="mt-1 text-xs leading-5 text-zinc-400">
                        {{ card.label }}
                    </p>
                </article>
            </section>

            <section
                class="mt-7 rounded-3xl border border-white/10 bg-zinc-950/80 p-4 sm:p-5"
            >
                <form
                    class="grid gap-3 md:grid-cols-2 xl:grid-cols-4"
                    @submit.prevent="applyFilters"
                >
                    <label class="xl:col-span-2">
                        <span class="sr-only">Rechercher</span>
                        <input
                            v-model.trim="filterState.q"
                            type="search"
                            placeholder="Nom, email, téléphone, référence, ville ou montre"
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none transition placeholder:text-zinc-600 focus:border-amber-300/60"
                        />
                    </label>

                    <label>
                        <span class="sr-only">Statut</span>
                        <select
                            v-model="filterState.status"
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none focus:border-amber-300/60"
                        >
                            <option value="">Tous les statuts</option>
                            <option
                                v-for="status in filterOptions.statuses"
                                :key="status"
                                :value="status"
                            >
                                {{ status }}
                            </option>
                        </select>
                    </label>

                    <label>
                        <span class="sr-only">Montre</span>
                        <select
                            v-model="filterState.watch_id"
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none focus:border-amber-300/60"
                        >
                            <option value="">Toutes les montres</option>
                            <option
                                v-for="watch in filterOptions.watches"
                                :key="watch.id"
                                :value="String(watch.id)"
                            >
                                {{ watch.name }}
                            </option>
                        </select>
                    </label>

                    <label>
                        <span class="sr-only">Ville</span>
                        <select
                            v-model="filterState.city"
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none focus:border-amber-300/60"
                        >
                            <option value="">Toutes les villes</option>
                            <option
                                v-for="city in filterOptions.cities"
                                :key="city"
                                :value="city"
                            >
                                {{ city }}
                            </option>
                        </select>
                    </label>

                    <label>
                        <span class="sr-only">Source</span>
                        <select
                            v-model="filterState.source"
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none focus:border-amber-300/60"
                        >
                            <option value="">Toutes les sources</option>
                            <option
                                v-for="source in filterOptions.sources"
                                :key="source"
                                :value="source"
                            >
                                {{ source }}
                            </option>
                        </select>
                    </label>

                    <label>
                        <span class="sr-only">Date de début</span>
                        <input
                            v-model="filterState.date_from"
                            type="date"
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none focus:border-amber-300/60"
                        />
                    </label>

                    <label>
                        <span class="sr-only">Date de fin</span>
                        <input
                            v-model="filterState.date_to"
                            type="date"
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none focus:border-amber-300/60"
                        />
                    </label>

                    <label>
                        <span class="sr-only">Tri</span>
                        <select
                            v-model="filterState.sort"
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none focus:border-amber-300/60"
                        >
                            <option value="latest">Plus récentes</option>
                            <option value="oldest">Plus anciennes</option>
                            <option value="appointment">
                                Prochains rendez-vous
                            </option>
                        </select>
                    </label>

                    <div
                        class="flex flex-wrap items-center gap-4 rounded-xl border border-white/10 bg-black px-4 py-3 text-xs text-zinc-300"
                    >
                        <label class="flex items-center gap-2">
                            <input
                                v-model="filterState.show_test"
                                type="checkbox"
                                class="accent-amber-300"
                            />
                            Afficher les tests
                        </label>
                        <label class="flex items-center gap-2">
                            <input
                                v-model="filterState.show_archived"
                                type="checkbox"
                                class="accent-amber-300"
                            />
                            Afficher les archives
                        </label>
                    </div>

                    <div class="flex gap-2 xl:col-span-2">
                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-amber-300 px-5 py-3 text-sm font-black text-black transition hover:bg-amber-200"
                        >
                            Appliquer
                        </button>
                        <button
                            type="button"
                            class="rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-zinc-300 hover:border-white/30"
                            @click="resetFilters"
                        >
                            Réinitialiser
                        </button>
                    </div>
                </form>
            </section>

            <section v-if="reservations.data.length" class="mt-6 space-y-4">
                <article
                    v-for="reservation in reservations.data"
                    :key="reservation.id"
                    class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950"
                >
                    <div class="grid md:grid-cols-[150px_1fr]">
                        <div class="relative min-h-36 bg-black">
                            <img
                                v-if="watchImage(reservation)"
                                :src="watchImage(reservation)"
                                :alt="watchName(reservation)"
                                class="absolute inset-0 h-full w-full object-cover"
                            />
                            <div
                                v-else
                                class="flex h-full min-h-36 items-center justify-center text-xs text-zinc-600"
                            >
                                Photo indisponible
                            </div>
                        </div>

                        <div class="min-w-0 p-4 sm:p-5">
                            <div
                                class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between"
                            >
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p
                                            class="text-[10px] font-black tracking-[0.24em] text-zinc-500 uppercase"
                                        >
                                            {{
                                                reservation.reservation_number
                                            }}
                                        </p>
                                        <span
                                            v-if="reservation.is_test"
                                            class="rounded-full bg-blue-400/10 px-2 py-1 text-[10px] font-bold text-blue-300"
                                        >
                                            TEST
                                        </span>
                                        <span
                                            v-if="reservation.archived_at"
                                            class="rounded-full bg-zinc-700 px-2 py-1 text-[10px] font-bold"
                                        >
                                            ARCHIVÉE
                                        </span>
                                    </div>

                                    <h2
                                        class="mt-2 truncate text-lg font-black sm:text-xl"
                                    >
                                        {{ watchName(reservation) }}
                                    </h2>

                                    <p class="mt-1 text-sm text-zinc-400">
                                        {{ reservation.customer_name }}
                                        <span v-if="reservation.city">
                                            · {{ reservation.city }}
                                        </span>
                                        · {{ formatDate(reservation.created_at) }}
                                    </p>

                                    <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                        <span
                                            class="rounded-full border border-white/10 px-3 py-1.5"
                                        >
                                            {{ reservation.movement }}
                                        </span>
                                        <span
                                            class="rounded-full border border-white/10 px-3 py-1.5 font-bold text-amber-200"
                                        >
                                            {{ formatMoney(reservation.price) }}
                                        </span>
                                        <span
                                            class="rounded-full border border-white/10 px-3 py-1.5"
                                        >
                                            Encaissé confirmé :
                                            {{
                                                formatMoney(
                                                    reservation.confirmed_paid_amount,
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="flex flex-col gap-2 sm:flex-row xl:w-[520px]"
                                >
                                    <label class="min-w-0 flex-1">
                                        <span class="sr-only">Statut</span>
                                        <select
                                            :value="draftFor(reservation).status"
                                            class="w-full rounded-xl border border-amber-300/30 bg-black px-3 py-2.5 text-xs font-bold text-amber-100 outline-none"
                                            @change="
                                                quickUpdateStatus(
                                                    reservation,
                                                    $event.target.value,
                                                )
                                            "
                                        >
                                            <option
                                                v-if="
                                                    !workflowStatuses.includes(
                                                        reservation.status,
                                                    )
                                                "
                                                :value="reservation.status"
                                            >
                                                {{
                                                    reservation.status
                                                }}
                                                (ancien)
                                            </option>
                                            <option
                                                v-for="status in workflowStatuses"
                                                :key="status"
                                                :value="status"
                                            >
                                                {{ status }}
                                            </option>
                                        </select>
                                    </label>

                                    <button
                                        type="button"
                                        class="rounded-xl border border-white/10 px-4 py-2.5 text-xs font-bold hover:border-white/30"
                                        @click="toggleDetails(reservation.id)"
                                    >
                                        {{
                                            expanded.has(reservation.id)
                                                ? 'Fermer'
                                                : 'Détails'
                                        }}
                                    </button>
                                </div>
                            </div>

                            <div
                                class="mt-4 flex flex-wrap items-center gap-2 border-t border-white/10 pt-4"
                            >
                                <a
                                    :href="phoneHref(reservation)"
                                    class="rounded-lg bg-white/5 px-3 py-2 text-xs font-bold hover:bg-white/10"
                                >
                                    Appeler
                                </a>
                                <a
                                    :href="whatsappHref(reservation)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-lg bg-emerald-500/10 px-3 py-2 text-xs font-bold text-emerald-200 hover:bg-emerald-500/20"
                                >
                                    WhatsApp
                                </a>
                                <a
                                    :href="gmailHref(reservation)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-lg bg-white/5 px-3 py-2 text-xs font-bold hover:bg-white/10"
                                >
                                    Email
                                </a>
                                <button
                                    type="button"
                                    class="rounded-lg bg-white/5 px-3 py-2 text-xs font-bold hover:bg-white/10"
                                    @click="copyReference(reservation)"
                                >
                                    {{
                                        copiedReference === reservation.id
                                            ? 'Référence copiée'
                                            : 'Copier la référence'
                                    }}
                                </button>
                                <a
                                    v-if="reservation.review_invitation_allowed"
                                    :href="reviewMailto(reservation)"
                                    class="rounded-lg bg-amber-300 px-3 py-2 text-xs font-black text-black hover:bg-amber-200"
                                >
                                    Inviter à laisser un avis
                                </a>
                                <span
                                    v-if="saving[reservation.id]"
                                    class="text-xs text-zinc-500"
                                >
                                    Enregistrement…
                                </span>
                                <span
                                    v-if="saved[reservation.id]"
                                    class="text-xs font-bold text-emerald-300"
                                >
                                    Modification enregistrée
                                </span>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="expanded.has(reservation.id)"
                        class="border-t border-white/10 p-4 sm:p-6"
                    >
                        <div
                            class="grid gap-6 xl:grid-cols-[1.25fr_0.75fr]"
                        >
                            <form
                                class="grid gap-4 sm:grid-cols-2"
                                @submit.prevent="saveReservation(reservation)"
                            >
                                <div
                                    class="rounded-2xl border border-white/10 bg-black/50 p-4 sm:col-span-2"
                                >
                                    <p
                                        class="text-xs font-black text-amber-200"
                                    >
                                        Paiement convenu
                                    </p>
                                    <div
                                        class="mt-3 grid gap-3 text-sm sm:grid-cols-3"
                                    >
                                        <p>
                                            Prix :
                                            <strong>{{
                                                formatMoney(reservation.price)
                                            }}</strong>
                                        </p>
                                        <p>
                                            Acompte 25 % :
                                            <strong>{{
                                                formatMoney(
                                                    reservation.deposit_amount,
                                                )
                                            }}</strong>
                                        </p>
                                        <p>
                                            Solde 75 % :
                                            <strong>{{
                                                formatMoney(
                                                    reservation.balance_amount,
                                                )
                                            }}</strong>
                                        </p>
                                    </div>
                                </div>

                                <label class="text-xs text-zinc-400">
                                    Acompte reçu le
                                    <div class="mt-2 flex gap-2">
                                        <input
                                            v-model="
                                                draftFor(reservation)
                                                    .deposit_paid_at
                                            "
                                            type="datetime-local"
                                            class="min-w-0 flex-1 rounded-xl border border-white/10 bg-black px-3 py-2.5 text-white"
                                        />
                                        <button
                                            type="button"
                                            class="rounded-xl border border-white/10 px-3 text-white"
                                            @click="
                                                setPaymentNow(
                                                    reservation,
                                                    'deposit_paid_at',
                                                )
                                            "
                                        >
                                            Maintenant
                                        </button>
                                    </div>
                                </label>

                                <label class="text-xs text-zinc-400">
                                    Solde reçu pendant la remise
                                    <div class="mt-2 flex gap-2">
                                        <input
                                            v-model="
                                                draftFor(reservation)
                                                    .balance_paid_at
                                            "
                                            type="datetime-local"
                                            class="min-w-0 flex-1 rounded-xl border border-white/10 bg-black px-3 py-2.5 text-white"
                                        />
                                        <button
                                            type="button"
                                            class="rounded-xl border border-white/10 px-3 text-white"
                                            @click="
                                                setPaymentNow(
                                                    reservation,
                                                    'balance_paid_at',
                                                )
                                            "
                                        >
                                            Maintenant
                                        </button>
                                    </div>
                                </label>

                                <label class="text-xs text-zinc-400">
                                    Date et heure du rendez-vous
                                    <input
                                        v-model="
                                            draftFor(reservation).appointment_at
                                        "
                                        type="datetime-local"
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-black px-3 py-2.5 text-white"
                                    />
                                </label>

                                <label class="text-xs text-zinc-400">
                                    Frais de déplacement
                                    <input
                                        v-model="
                                            draftFor(reservation).travel_fee
                                        "
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-black px-3 py-2.5 text-white"
                                    />
                                </label>

                                <label
                                    class="text-xs text-zinc-400 sm:col-span-2"
                                >
                                    Adresse de remise
                                    <input
                                        v-model="
                                            draftFor(reservation)
                                                .handover_address
                                        "
                                        type="text"
                                        maxlength="500"
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-black px-3 py-2.5 text-white"
                                        placeholder="Adresse confirmée avant l’acompte"
                                    />
                                </label>

                                <label
                                    class="text-xs text-zinc-400 sm:col-span-2"
                                >
                                    Notes privées administrateur
                                    <textarea
                                        v-model="
                                            draftFor(reservation).admin_notes
                                        "
                                        rows="4"
                                        maxlength="10000"
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-black px-3 py-2.5 text-white"
                                        placeholder="Ces notes ne sont jamais montrées au client."
                                    ></textarea>
                                </label>

                                <label
                                    class="flex items-center gap-2 text-xs text-zinc-300"
                                >
                                    <input
                                        v-model="draftFor(reservation).is_test"
                                        type="checkbox"
                                        class="accent-amber-300"
                                    />
                                    Réservation de test
                                </label>

                                <div
                                    v-if="
                                        Object.keys(
                                            formErrors[reservation.id] || {},
                                        ).length
                                    "
                                    class="rounded-xl border border-red-400/30 bg-red-400/10 p-3 text-xs text-red-200 sm:col-span-2"
                                >
                                    Vérifie les champs : certaines informations
                                    ne sont pas valides.
                                </div>

                                <div
                                    class="flex flex-wrap gap-2 sm:col-span-2"
                                >
                                    <button
                                        type="submit"
                                        :disabled="saving[reservation.id]"
                                        class="rounded-xl bg-amber-300 px-5 py-3 text-sm font-black text-black disabled:opacity-50"
                                    >
                                        Enregistrer
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-xl border border-white/10 px-5 py-3 text-sm font-bold text-zinc-300"
                                        @click="
                                            archiveReservation(reservation)
                                        "
                                    >
                                        {{
                                            reservation.archived_at
                                                ? 'Restaurer'
                                                : 'Archiver'
                                        }}
                                    </button>
                                </div>
                            </form>

                            <aside class="space-y-4">
                                <div
                                    class="rounded-2xl border border-white/10 bg-black/50 p-4"
                                >
                                    <p
                                        class="text-xs font-black text-amber-200"
                                    >
                                        Client
                                    </p>
                                    <dl
                                        class="mt-3 space-y-3 text-sm text-zinc-300"
                                    >
                                        <div>
                                            <dt class="text-xs text-zinc-600">
                                                Téléphone
                                            </dt>
                                            <dd>{{ reservation.phone }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-zinc-600">
                                                Email
                                            </dt>
                                            <dd class="break-all">
                                                {{ reservation.email }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-zinc-600">
                                                Réception
                                            </dt>
                                            <dd>
                                                {{
                                                    reservation.delivery_method
                                                }}
                                            </dd>
                                        </div>
                                        <div v-if="reservation.message">
                                            <dt class="text-xs text-zinc-600">
                                                Message
                                            </dt>
                                            <dd class="whitespace-pre-wrap">
                                                {{ reservation.message }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div
                                    class="rounded-2xl border border-white/10 bg-black/50 p-4"
                                >
                                    <p
                                        class="text-xs font-black text-amber-200"
                                    >
                                        Attribution
                                    </p>
                                    <p class="mt-3 text-sm text-zinc-300">
                                        {{ sourceLabel(reservation) }}
                                    </p>
                                    <p
                                        v-if="reservation.referrer"
                                        class="mt-2 break-all text-xs text-zinc-500"
                                    >
                                        Référent : {{ reservation.referrer }}
                                    </p>
                                    <p
                                        v-if="reservation.landing_page"
                                        class="mt-2 break-all text-xs text-zinc-500"
                                    >
                                        Arrivée :
                                        {{ reservation.landing_page }}
                                    </p>
                                </div>

                                <div
                                    class="rounded-2xl border border-white/10 bg-black/50 p-4"
                                >
                                    <p
                                        class="text-xs font-black text-amber-200"
                                    >
                                        Historique des statuts
                                    </p>
                                    <ol
                                        v-if="
                                            reservation.status_histories.length
                                        "
                                        class="mt-3 space-y-3"
                                    >
                                        <li
                                            v-for="history in reservation.status_histories"
                                            :key="history.id"
                                            class="border-l border-amber-300/30 pl-3 text-xs text-zinc-400"
                                        >
                                            <strong class="text-white">{{
                                                history.new_status
                                            }}</strong>
                                            <br />
                                            {{ formatDate(history.created_at) }}
                                            <span v-if="history.changed_by">
                                                · {{ history.changed_by }}
                                            </span>
                                        </li>
                                    </ol>
                                    <p v-else class="mt-3 text-xs text-zinc-600">
                                        Aucun changement enregistré depuis la
                                        mise à niveau.
                                    </p>
                                </div>
                            </aside>
                        </div>
                    </div>
                </article>
            </section>

            <section
                v-else
                class="mt-6 rounded-3xl border border-white/10 bg-zinc-950 p-12 text-center"
            >
                <p class="text-xl font-black">Aucune réservation trouvée</p>
                <p class="mt-2 text-sm text-zinc-500">
                    Modifie les filtres ou réinitialise la recherche.
                </p>
            </section>

            <nav
                v-if="reservations.links.length > 3"
                class="mt-8 flex flex-wrap justify-center gap-2"
                aria-label="Pagination"
            >
                <component
                    :is="link.url ? Link : 'span'"
                    v-for="link in reservations.links"
                    :key="link.label"
                    :href="link.url || undefined"
                    preserve-scroll
                    :class="[
                        'rounded-lg border px-3 py-2 text-xs font-bold',
                        link.active
                            ? 'border-amber-300 bg-amber-300 text-black'
                            : link.url
                              ? 'border-white/10 text-zinc-300 hover:border-white/30'
                              : 'cursor-not-allowed border-white/5 text-zinc-700',
                    ]"
                >
                    {{ paginationLabel(link.label) }}
                </component>
            </nav>
        </div>
    </main>
</template>
