<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const copy = computed(() => {
    return (
        {
            fr_BE: {
                eyebrow: 'Une commande claire',
                title: 'Du choix à la remise',
                intro: 'Le lieu, l’horaire et les éventuels frais sont confirmés avec vous avant tout acompte.',
                steps: [
                    'Après confirmation des modalités, un acompte de 25 % lance la préparation de la montre.',
                    'Une vidéo de la montre terminée vous est envoyée avant la remise.',
                    'Pour une remise en main propre, le solde de 75 % est réglé au moment convenu.',
                ],
                location: 'La remise n’est pas limitée à Liège : le lieu est convenu avec vous selon la commande.',
                reviewEyebrow: 'Votre expérience compte',
                reviewTitle: 'Avis clients',
                reviewText: 'Les avis publiés proviennent du formulaire du site et passent par une modération avant publication, quelle que soit la note.',
                reviewNote: 'Aucun avis inventé et aucun badge « achat vérifié » automatique.',
                reviewCta: 'Lire ou laisser un avis',
                moderate: 'Modérer les avis',
            },
            nl_BE: {
                eyebrow: 'Een duidelijk proces',
                title: 'Van keuze tot overdracht',
                intro: 'Plaats, tijdstip en eventuele kosten worden met jou bevestigd vóór een voorschot.',
                steps: [
                    'Na bevestiging van de voorwaarden start een voorschot van 25% de voorbereiding.',
                    'Je ontvangt een video van het afgewerkte horloge vóór de overdracht.',
                    'Bij persoonlijke overdracht wordt de resterende 75% betaald op het afgesproken moment.',
                ],
                location: 'Persoonlijke overdracht is niet beperkt tot Luik: de plaats wordt per bestelling afgesproken.',
                reviewEyebrow: 'Jouw ervaring telt',
                reviewTitle: 'Klantenreviews',
                reviewText: 'Gepubliceerde reviews komen uit het formulier op de website en worden vóór publicatie gemodereerd, ongeacht de score.',
                reviewNote: 'Geen verzonnen reviews en geen automatische badge “geverifieerde aankoop”.',
                reviewCta: 'Reviews lezen of schrijven',
                moderate: 'Reviews modereren',
            },
            en_BE: {
                eyebrow: 'A clear process',
                title: 'From selection to handover',
                intro: 'The location, time and any applicable fees are confirmed with you before any deposit.',
                steps: [
                    'Once the terms are confirmed, a 25% deposit starts preparation of the watch.',
                    'A video of the finished watch is sent to you before handover.',
                    'For an in-person handover, the remaining 75% is paid at the agreed time.',
                ],
                location: 'In-person handover is not limited to Liège: the location is agreed according to the order.',
                reviewEyebrow: 'Your experience matters',
                reviewTitle: 'Customer reviews',
                reviewText: 'Published reviews come from the website form and are moderated before publication regardless of the rating.',
                reviewNote: 'No invented reviews and no automatic “verified purchase” badge.',
                reviewCta: 'Read or leave a review',
                moderate: 'Moderate reviews',
            },
            de_BE: {
                eyebrow: 'Ein klarer Ablauf',
                title: 'Von der Auswahl bis zur Übergabe',
                intro: 'Ort, Zeitpunkt und eventuelle Kosten werden vor einer Anzahlung mit Ihnen bestätigt.',
                steps: [
                    'Nach Bestätigung der Bedingungen startet eine Anzahlung von 25 % die Vorbereitung.',
                    'Vor der Übergabe erhalten Sie ein Video der fertigen Uhr.',
                    'Bei persönlicher Übergabe werden die restlichen 75 % zum vereinbarten Zeitpunkt bezahlt.',
                ],
                location: 'Die persönliche Übergabe ist nicht auf Lüttich beschränkt: der Ort wird je nach Bestellung vereinbart.',
                reviewEyebrow: 'Ihre Erfahrung zählt',
                reviewTitle: 'Kundenbewertungen',
                reviewText: 'Veröffentlichte Bewertungen stammen aus dem Formular der Website und werden unabhängig von der Note moderiert.',
                reviewNote: 'Keine erfundenen Bewertungen und kein automatisches Badge „verifizierter Kauf“.',
                reviewCta: 'Bewertungen lesen oder abgeben',
                moderate: 'Bewertungen moderieren',
            },
        }[page.props.locale] ?? null
    ) ?? {
        eyebrow: 'Une commande claire',
        title: 'Du choix à la remise',
        intro: 'Le lieu, l’horaire et les éventuels frais sont confirmés avec vous avant tout acompte.',
        steps: [
            'Après confirmation des modalités, un acompte de 25 % lance la préparation de la montre.',
            'Une vidéo de la montre terminée vous est envoyée avant la remise.',
            'Pour une remise en main propre, le solde de 75 % est réglé au moment convenu.',
        ],
        location: 'La remise n’est pas limitée à Liège : le lieu est convenu avec vous selon la commande.',
        reviewEyebrow: 'Votre expérience compte',
        reviewTitle: 'Avis clients',
        reviewText: 'Les avis publiés proviennent du formulaire du site et passent par une modération avant publication, quelle que soit la note.',
        reviewNote: 'Aucun avis inventé et aucun badge « achat vérifié » automatique.',
        reviewCta: 'Lire ou laisser un avis',
        moderate: 'Modérer les avis',
    };
});
</script>

<template>
    <section
        id="confiance-et-avis"
        class="mx-auto my-14 max-w-[1400px] scroll-mt-28 px-5"
    >
        <div class="grid gap-6 lg:grid-cols-2">
            <article
                class="vvs-luxury-card rounded-3xl border p-7 sm:p-8"
            >
                <p
                    class="text-[10px] font-bold tracking-[0.24em] text-amber-300 uppercase"
                >
                    {{ copy.eyebrow }}
                </p>

                <h2 class="vvs-display-title mt-3 text-3xl text-white sm:text-4xl">
                    {{ copy.title }}
                </h2>

                <p class="mt-4 max-w-xl leading-7 text-zinc-300">
                    {{ copy.intro }}
                </p>

                <ol class="mt-6 space-y-4">
                    <li
                        v-for="(step, index) in copy.steps"
                        :key="step"
                        class="flex gap-4"
                    >
                        <span
                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-amber-300/25 bg-amber-300/10 text-xs font-black text-amber-200"
                        >
                            {{ index + 1 }}
                        </span>
                        <span class="pt-0.5 text-sm leading-6 text-zinc-300">
                            {{ step }}
                        </span>
                    </li>
                </ol>

                <p
                    class="mt-6 border-t border-white/10 pt-5 text-sm leading-6 text-zinc-400"
                >
                    {{ copy.location }}
                </p>
            </article>

            <article
                class="vvs-luxury-card rounded-3xl border p-7 sm:p-8"
            >
                <p
                    class="text-[10px] font-bold tracking-[0.24em] text-amber-300 uppercase"
                >
                    {{ copy.reviewEyebrow }}
                </p>

                <h2 class="vvs-display-title mt-3 text-3xl text-white sm:text-4xl">
                    {{ copy.reviewTitle }}
                </h2>

                <p class="mt-4 leading-7 text-zinc-300">
                    {{ copy.reviewText }}
                </p>

                <p class="mt-3 text-sm leading-6 text-zinc-500">
                    {{ copy.reviewNote }}
                </p>

                <a
                    href="/avis-clients"
                    class="vvs-button-primary mt-7 inline-flex min-h-11 items-center justify-center rounded-xl px-5 py-3 text-sm font-bold"
                >
                    {{ copy.reviewCta }}
                </a>

                <a
                    v-if="page.props.auth?.user?.is_admin"
                    href="/dashboard/avis"
                    class="mt-5 block w-fit rounded text-sm text-amber-200 underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-300"
                >
                    {{ copy.moderate }}
                </a>
            </article>
        </div>
    </section>
</template>