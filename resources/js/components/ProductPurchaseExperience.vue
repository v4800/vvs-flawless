<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    watch: { type: Object, required: true },
    movement: { type: String, default: '' },
    selectedPrice: { type: [Number, String, null], default: null },
});

const page = usePage();

const locale = computed(() => {
    const value = String(page.props.locale ?? 'fr_BE');
    if (value.startsWith('nl')) return 'nl';
    if (value.startsWith('en')) return 'en';
    if (value.startsWith('de')) return 'de';
    return 'fr';
});

const copy = computed(
    () =>
        ({
            fr: {
                eyebrow: 'RÉSERVATION',
                title: 'Votre montre, simplement.',
                intro: 'Un acompte fixe de 100 € confirme votre réservation. Le solde est réglé lors de la remise convenue.',
                summary: 'Votre sélection',
                stone: 'Pierre',
                stoneValue: 'Moissanite VVS',
                movement: 'Mouvement',
                movementFallback: 'Selon la version choisie',
                availability: 'Disponibilité estimée',
                availabilityValue: '5–6 jours ouvrables',
                deposit: 'Acompte',
                depositValue: '100 €',
                total: 'Prix de la montre',
                steps: [
                    {
                        number: '01',
                        title: 'Modèle sélectionné',
                        text: 'La fiche consultée définit le modèle et sa finition.',
                    },
                    {
                        number: '02',
                        title: 'Acompte de 100 €',
                        text: 'L’acompte confirme la réservation de votre pièce.',
                    },
                    {
                        number: '03',
                        title: 'Préparation',
                        text: 'La disponibilité estimée est de 5–6 jours ouvrables.',
                    },
                    {
                        number: '04',
                        title: 'Remise et solde',
                        text: 'Le montant restant est réglé lors de la remise convenue.',
                    },
                ],
                note: 'Le prix et les caractéristiques affichés sur la fiche du modèle restent la référence de votre réservation.',
            },
            nl: {
                eyebrow: 'RESERVATIE',
                title: 'Je horloge, eenvoudig geregeld.',
                intro: 'Een vast voorschot van € 100 bevestigt je reservatie. Het resterende bedrag wordt betaald bij de afgesproken overhandiging.',
                summary: 'Jouw selectie',
                stone: 'Steen',
                stoneValue: 'VVS-moissaniet',
                movement: 'Uurwerk',
                movementFallback: 'Volgens de gekozen versie',
                availability: 'Geschatte beschikbaarheid',
                availabilityValue: '5–6 werkdagen',
                deposit: 'Voorschot',
                depositValue: '€ 100',
                total: 'Prijs van het horloge',
                steps: [
                    {
                        number: '01',
                        title: 'Geselecteerd model',
                        text: 'De productpagina bepaalt het model en de afwerking.',
                    },
                    {
                        number: '02',
                        title: 'Voorschot van € 100',
                        text: 'Het voorschot bevestigt de reservatie van je horloge.',
                    },
                    {
                        number: '03',
                        title: 'Voorbereiding',
                        text: 'De geschatte beschikbaarheid bedraagt 5–6 werkdagen.',
                    },
                    {
                        number: '04',
                        title: 'Overhandiging en saldo',
                        text: 'Het resterende bedrag wordt betaald bij de afgesproken overhandiging.',
                    },
                ],
                note: 'De prijs en kenmerken op de productpagina blijven de referentie voor je reservatie.',
            },
            en: {
                eyebrow: 'RESERVATION',
                title: 'Your watch, kept simple.',
                intro: 'A fixed €100 deposit confirms your reservation. The remaining balance is paid at the agreed handover.',
                summary: 'Your selection',
                stone: 'Stone',
                stoneValue: 'VVS moissanite',
                movement: 'Movement',
                movementFallback: 'Based on selected version',
                availability: 'Estimated availability',
                availabilityValue: '5–6 business days',
                deposit: 'Deposit',
                depositValue: '€100',
                total: 'Watch price',
                steps: [
                    {
                        number: '01',
                        title: 'Selected model',
                        text: 'The product page defines the model and its finish.',
                    },
                    {
                        number: '02',
                        title: '€100 deposit',
                        text: 'The deposit confirms the reservation of your watch.',
                    },
                    {
                        number: '03',
                        title: 'Preparation',
                        text: 'Estimated availability is 5–6 business days.',
                    },
                    {
                        number: '04',
                        title: 'Handover and balance',
                        text: 'The remaining balance is paid at the agreed handover.',
                    },
                ],
                note: 'The price and specifications shown on the product page remain the reference for your reservation.',
            },
            de: {
                eyebrow: 'RESERVIERUNG',
                title: 'Deine Uhr, einfach reserviert.',
                intro: 'Eine feste Anzahlung von 100 € bestätigt deine Reservierung. Der Restbetrag wird bei der vereinbarten Übergabe bezahlt.',
                summary: 'Deine Auswahl',
                stone: 'Stein',
                stoneValue: 'VVS-Moissanit',
                movement: 'Uhrwerk',
                movementFallback: 'Je nach gewählter Version',
                availability: 'Geschätzte Verfügbarkeit',
                availabilityValue: '5–6 Werktage',
                deposit: 'Anzahlung',
                depositValue: '100 €',
                total: 'Preis der Uhr',
                steps: [
                    {
                        number: '01',
                        title: 'Ausgewähltes Modell',
                        text: 'Die Produktseite legt Modell und Ausführung fest.',
                    },
                    {
                        number: '02',
                        title: '100 € Anzahlung',
                        text: 'Die Anzahlung bestätigt die Reservierung deiner Uhr.',
                    },
                    {
                        number: '03',
                        title: 'Vorbereitung',
                        text: 'Die geschätzte Verfügbarkeit beträgt 5–6 Werktage.',
                    },
                    {
                        number: '04',
                        title: 'Übergabe und Restbetrag',
                        text: 'Der Restbetrag wird bei der vereinbarten Übergabe bezahlt.',
                    },
                ],
                note: 'Preis und Eigenschaften auf der Produktseite bleiben die Referenz für deine Reservierung.',
            },
        })[locale.value],
);

const movementLabel = computed(
    () => String(props.movement ?? '').trim() || copy.value.movementFallback,
);

const formattedPrice = computed(() => {
    const numeric = Number(props.selectedPrice);
    if (!Number.isFinite(numeric) || numeric <= 0) return null;

    const locales = { fr: 'fr-BE', nl: 'nl-BE', en: 'en-BE', de: 'de-BE' };

    return new Intl.NumberFormat(locales[locale.value], {
        style: 'currency',
        currency: 'EUR',
        maximumFractionDigits: 0,
    }).format(numeric);
});
</script>

<template>
    <section
        class="px-5 py-12 sm:px-6 sm:py-16 lg:px-10"
        aria-labelledby="reservation-premium-title"
    >
        <div class="mx-auto max-w-[1400px]">
            <div
                class="flex flex-col gap-5 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between"
            >
                <div class="max-w-2xl">
                    <p
                        class="text-xs font-bold tracking-[0.3em] text-amber-300"
                    >
                        {{ copy.eyebrow }}
                    </p>
                    <h2
                        id="reservation-premium-title"
                        class="vvs-display-title mt-4 text-3xl leading-tight text-white sm:text-4xl"
                    >
                        {{ copy.title }}
                    </h2>
                    <p
                        class="mt-4 max-w-xl text-sm leading-7 text-zinc-400 sm:text-base"
                    >
                        {{ copy.intro }}
                    </p>
                </div>

                <div
                    v-if="formattedPrice"
                    class="shrink-0 text-left sm:text-right"
                >
                    <p
                        class="text-[11px] font-semibold tracking-[0.18em] text-zinc-500 uppercase"
                    >
                        {{ copy.total }}
                    </p>
                    <p class="vvs-price mt-1 text-2xl font-black">
                        {{ formattedPrice }}
                    </p>
                </div>
            </div>

            <div
                class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,0.82fr)_minmax(0,1.18fr)]"
            >
                <div
                    class="vvs-choice-card rounded-2xl border border-white/10 p-5 sm:p-6"
                >
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p
                                class="text-[11px] font-semibold tracking-[0.18em] text-zinc-500 uppercase"
                            >
                                {{ copy.summary }}
                            </p>
                            <p class="mt-2 text-lg font-semibold text-white">
                                {{ watch.name }}
                            </p>
                        </div>
                        <span
                            class="h-2.5 w-2.5 shrink-0 rounded-full bg-amber-300 shadow-[0_0_18px_rgba(252,211,77,0.55)]"
                            aria-hidden="true"
                        ></span>
                    </div>

                    <dl class="mt-6 divide-y divide-white/10">
                        <div
                            class="flex items-center justify-between gap-5 py-4 first:pt-0"
                        >
                            <dt class="text-sm text-zinc-500">
                                {{ copy.stone }}
                            </dt>
                            <dd
                                class="text-right text-sm font-semibold text-white"
                            >
                                {{ copy.stoneValue }}
                            </dd>
                        </div>
                        <div
                            class="flex items-center justify-between gap-5 py-4"
                        >
                            <dt class="text-sm text-zinc-500">
                                {{ copy.movement }}
                            </dt>
                            <dd
                                class="text-right text-sm font-semibold text-white"
                            >
                                {{ movementLabel }}
                            </dd>
                        </div>
                        <div
                            class="flex items-center justify-between gap-5 py-4"
                        >
                            <dt class="text-sm text-zinc-500">
                                {{ copy.availability }}
                            </dt>
                            <dd
                                class="text-right text-sm font-semibold text-white"
                            >
                                {{ copy.availabilityValue }}
                            </dd>
                        </div>
                        <div
                            class="flex items-center justify-between gap-5 pt-4"
                        >
                            <dt class="text-sm text-zinc-500">
                                {{ copy.deposit }}
                            </dt>
                            <dd
                                class="vvs-price text-right text-base font-black"
                            >
                                {{ copy.depositValue }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <ol class="grid gap-3 sm:grid-cols-2">
                    <li
                        v-for="step in copy.steps"
                        :key="step.number"
                        class="rounded-2xl border border-white/10 bg-white/[0.018] p-5 transition-colors duration-300 hover:border-amber-300/20 hover:bg-white/[0.028] sm:p-6"
                    >
                        <div class="flex items-start gap-4">
                            <span
                                class="mt-0.5 text-xs font-bold tracking-[0.18em] text-amber-300"
                                >{{ step.number }}</span
                            >
                            <div>
                                <h3 class="text-base font-semibold text-white">
                                    {{ step.title }}
                                </h3>
                                <p class="mt-2 text-sm leading-6 text-zinc-500">
                                    {{ step.text }}
                                </p>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>

            <p class="mt-5 text-xs leading-6 text-zinc-600">{{ copy.note }}</p>
        </div>
    </section>
</template>
