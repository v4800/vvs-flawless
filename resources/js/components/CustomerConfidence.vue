<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const copies = {
    fr_BE: {
        eyebrow: 'Une commande claire',
        title: 'Du choix à la remise',
        intro: 'Le lieu, l’horaire et les éventuels frais sont confirmés avec vous avant tout acompte.',
        steps: [
            'Après confirmation des modalités, un acompte de 25 % lance la préparation de la montre.',
            'Une vidéo de la montre terminée vous est envoyée avant la remise.',
            'Pour une remise en main propre, le solde de 75 % est réglé au moment convenu.',
        ],
        location:
            'La remise n’est pas limitée à Liège : le lieu est convenu avec vous selon la commande.',
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
        location:
            'Persoonlijke overdracht is niet beperkt tot Luik: de plaats wordt per bestelling afgesproken.',
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
        location:
            'In-person handover is not limited to Liège: the location is agreed according to the order.',
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
        location:
            'Die persönliche Übergabe ist nicht auf Lüttich beschränkt: der Ort wird je nach Bestellung vereinbart.',
    },
};

const copy = computed(() => copies[page.props.locale] ?? copies.fr_BE);
</script>

<template>
    <section
        id="confiance-et-avis"
        class="mx-auto my-14 max-w-[1400px] scroll-mt-28 px-5"
    >
        <div class="mx-auto max-w-4xl">
            <article class="vvs-luxury-card rounded-3xl border p-7 sm:p-8">
                <p
                    class="text-[10px] font-bold tracking-[0.24em] text-amber-300 uppercase"
                >
                    {{ copy.eyebrow }}
                </p>

                <h2
                    class="vvs-display-title mt-3 text-3xl text-white sm:text-4xl"
                >
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
        </div>
    </section>
</template>
