<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    price: { type: Number, required: true },
    compact: { type: Boolean, default: false },
});
const page = usePage();
const copies = {
    fr_BE: {
        title: 'Votre budget, en clair',
        total: 'Prix de la montre',
        deposit: 'Acompte à la confirmation',
        balance: 'Solde restant',
        note: 'Aucun paiement sur ce formulaire. Nous confirmons les détails et les conditions de l’acompte avec vous avant la commande.',
    },
    nl_BE: {
        title: 'Je budget, duidelijk',
        total: 'Prijs van het horloge',
        deposit: 'Voorschot bij bevestiging',
        balance: 'Resterend saldo',
        note: 'Geen betaling via dit formulier. We bevestigen de details en de voorschotvoorwaarden met je vóór de bestelling.',
    },
    en_BE: {
        title: 'Your budget, clearly',
        total: 'Watch price',
        deposit: 'Deposit on confirmation',
        balance: 'Remaining balance',
        note: 'No payment through this form. We confirm the details and deposit terms with you before placing the order.',
    },
    de_BE: {
        title: 'Ihr Budget im Überblick',
        total: 'Preis der Uhr',
        deposit: 'Anzahlung bei Bestätigung',
        balance: 'Restbetrag',
        note: 'Keine Zahlung über dieses Formular. Details und Anzahlungsbedingungen klären wir vor der Bestellung mit Ihnen.',
    },
};
const copy = computed(() => copies[page.props.locale] ?? copies.fr_BE);
// Mirrors ReservationPayment: standard €100 deposit capped at the watch price.
const totalCents = computed(() => Math.round(props.price * 100));
const depositCents = computed(() => Math.min(10000, totalCents.value));
const format = (cents) =>
    new Intl.NumberFormat(
        String(page.props.locale ?? 'fr_BE').replace('_', '-'),
        { style: 'currency', currency: 'EUR', maximumFractionDigits: 2 },
    ).format(cents / 100);
</script>

<template>
    <aside
        v-if="Number.isFinite(price) && price > 0"
        class="vvs-payment-summary"
        :class="{ 'vvs-payment-summary--compact': compact }"
        :aria-label="copy.title"
    >
        <p v-if="!compact" class="vvs-eyebrow">{{ copy.title }}</p>
        <dl>
            <div v-if="!compact">
                <dt>{{ copy.total }}</dt>
                <dd>{{ format(totalCents) }}</dd>
            </div>
            <div>
                <dt>{{ copy.deposit }}</dt>
                <dd>{{ format(depositCents) }}</dd>
            </div>
            <div>
                <dt>{{ copy.balance }}</dt>
                <dd>{{ format(totalCents - depositCents) }}</dd>
            </div>
        </dl>
        <p class="vvs-payment-summary__note">{{ copy.note }}</p>
    </aside>
</template>
