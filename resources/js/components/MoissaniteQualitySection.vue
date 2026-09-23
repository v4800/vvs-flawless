<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const locale = computed(() => {
    const value = String(page.props.locale ?? 'fr_BE');

    if (value.startsWith('nl')) return 'nl';
    if (value.startsWith('en')) return 'en';
    if (value.startsWith('de')) return 'de';

    return 'fr';
});

const copy = computed(() => {
    const translations = {
        fr: {
            eyebrow: 'MOISSANITE VVS',
            title: 'Une brillance assumée, sans confusion sur la pierre.',
            intro: 'VVS FLAWLESS commence sa collection avec des montres serties de moissanite. Chaque fiche présente clairement la pierre utilisée afin que vous sachiez exactement ce que vous réservez.',
            stoneLabel: 'Pierre',
            stoneValue: 'Moissanite',
            clarityLabel: 'Clarté',
            clarityValue: 'VVS',
            transparencyLabel: 'Transparence',
            transparencyValue: 'Moissanite ≠ diamant',
            cardOneTitle: 'Pourquoi la moissanite ?',
            cardOneText:
                'La moissanite offre une forte brillance et convient particulièrement aux montres au rendu iced-out recherché par VVS FLAWLESS.',
            cardTwoTitle: 'VVS sur nos fiches',
            cardTwoText:
                'La mention VVS décrit le niveau de clarté annoncé pour la moissanite proposée. Les détails propres à chaque modèle restent indiqués sur sa fiche.',
            cardThreeTitle: 'Présentation claire',
            cardThreeText:
                'Nous présentons ces modèles comme des montres serties de moissanite. Ils ne sont pas décrits comme des montres en diamant.',
            note: 'Les caractéristiques visibles sur la fiche du modèle sélectionné restent la référence pour votre réservation.',
        },

        nl: {
            eyebrow: 'VVS-MOISSANIET',
            title: 'Sterke schittering, met volledige duidelijkheid over de steen.',
            intro: 'VVS FLAWLESS start zijn collectie met horloges bezet met moissaniet. Op elke productpagina wordt duidelijk vermeld welke steen wordt gebruikt.',
            stoneLabel: 'Steen',
            stoneValue: 'Moissaniet',
            clarityLabel: 'Zuiverheid',
            clarityValue: 'VVS',
            transparencyLabel: 'Transparantie',
            transparencyValue: 'Moissaniet ≠ diamant',
            cardOneTitle: 'Waarom moissaniet?',
            cardOneText:
                'Moissaniet biedt een sterke schittering en past bijzonder goed bij de iced-out uitstraling van VVS FLAWLESS.',
            cardTwoTitle: 'VVS op onze productpagina’s',
            cardTwoText:
                'De vermelding VVS beschrijft het opgegeven zuiverheidsniveau van de aangeboden moissaniet. Details per model blijven op de productpagina vermeld.',
            cardThreeTitle: 'Duidelijke presentatie',
            cardThreeText:
                'Wij presenteren deze modellen als horloges bezet met moissaniet. Ze worden niet als diamanten horloges omschreven.',
            note: 'De kenmerken op de productpagina van het gekozen model blijven bepalend voor je reservatie.',
        },

        en: {
            eyebrow: 'VVS MOISSANITE',
            title: 'High brilliance, with complete clarity about the stone.',
            intro: 'VVS FLAWLESS is starting its collection with moissanite-set watches. Each product page clearly identifies the stone used so you know exactly what you are reserving.',
            stoneLabel: 'Stone',
            stoneValue: 'Moissanite',
            clarityLabel: 'Clarity',
            clarityValue: 'VVS',
            transparencyLabel: 'Transparency',
            transparencyValue: 'Moissanite ≠ diamond',
            cardOneTitle: 'Why moissanite?',
            cardOneText:
                'Moissanite delivers strong brilliance and is particularly suited to the iced-out appearance associated with VVS FLAWLESS.',
            cardTwoTitle: 'VVS on our product pages',
            cardTwoText:
                'The VVS designation describes the stated clarity level of the moissanite offered. Model-specific details remain listed on each product page.',
            cardThreeTitle: 'Clear presentation',
            cardThreeText:
                'These models are presented as moissanite-set watches. They are not described as diamond watches.',
            note: 'The specifications shown on the selected model page remain the reference for your reservation.',
        },

        de: {
            eyebrow: 'VVS-MOISSANIT',
            title: 'Starke Brillanz mit vollständiger Klarheit über den Stein.',
            intro: 'VVS FLAWLESS startet seine Kollektion mit Uhren, die mit Moissanit besetzt sind. Auf jeder Produktseite wird der verwendete Stein klar angegeben.',
            stoneLabel: 'Stein',
            stoneValue: 'Moissanit',
            clarityLabel: 'Reinheit',
            clarityValue: 'VVS',
            transparencyLabel: 'Transparenz',
            transparencyValue: 'Moissanit ≠ Diamant',
            cardOneTitle: 'Warum Moissanit?',
            cardOneText:
                'Moissanit bietet eine starke Brillanz und eignet sich besonders für den von VVS FLAWLESS gewünschten Iced-out-Look.',
            cardTwoTitle: 'VVS auf unseren Produktseiten',
            cardTwoText:
                'Die Angabe VVS beschreibt den angegebenen Reinheitsgrad des angebotenen Moissanits. Modellspezifische Details bleiben auf der jeweiligen Produktseite angegeben.',
            cardThreeTitle: 'Klare Darstellung',
            cardThreeText:
                'Diese Modelle werden als mit Moissanit besetzte Uhren präsentiert. Sie werden nicht als Diamantuhren beschrieben.',
            note: 'Die Angaben auf der Produktseite des ausgewählten Modells bleiben die Referenz für deine Reservierung.',
        },
    };

    return translations[locale.value];
});
</script>

<template>
    <section
        class="relative overflow-hidden border-y border-white/10 bg-black/35 px-5 py-16 sm:px-6 sm:py-20 lg:px-10"
        :aria-labelledby="'moissanite-quality-title'"
    >
        <div
            class="pointer-events-none absolute -top-32 right-0 h-80 w-80 rounded-full bg-amber-300/[0.05] blur-[120px]"
        ></div>

        <div class="relative mx-auto max-w-[1400px]">
            <header class="mx-auto max-w-3xl text-center">
                <p class="text-xs font-bold tracking-[0.32em] text-amber-300">
                    {{ copy.eyebrow }}
                </p>

                <h2
                    id="moissanite-quality-title"
                    class="vvs-display-title mt-5 text-3xl leading-tight text-white sm:text-4xl lg:text-5xl"
                >
                    {{ copy.title }}
                </h2>

                <p
                    class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-zinc-300 sm:text-base"
                >
                    {{ copy.intro }}
                </p>
            </header>

            <dl class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-3">
                <div
                    class="rounded-2xl border border-white/10 bg-white/[0.035] px-5 py-5"
                >
                    <dt
                        class="text-xs tracking-[0.18em] text-zinc-500 uppercase"
                    >
                        {{ copy.stoneLabel }}
                    </dt>
                    <dd class="mt-2 text-base font-semibold text-white">
                        {{ copy.stoneValue }}
                    </dd>
                </div>

                <div
                    class="rounded-2xl border border-white/10 bg-white/[0.035] px-5 py-5"
                >
                    <dt
                        class="text-xs tracking-[0.18em] text-zinc-500 uppercase"
                    >
                        {{ copy.clarityLabel }}
                    </dt>
                    <dd class="mt-2 text-base font-semibold text-white">
                        {{ copy.clarityValue }}
                    </dd>
                </div>

                <div class="vvs-choice-card rounded-2xl border p-5">
                    <dt
                        class="text-xs tracking-[0.18em] text-zinc-500 uppercase"
                    >
                        {{ copy.transparencyLabel }}
                    </dt>
                    <dd class="vvs-price mt-2 text-base font-black">
                        {{ copy.transparencyValue }}
                    </dd>
                </div>
            </dl>

            <div class="mt-8 grid gap-4 lg:grid-cols-3">
                <article
                    class="vvs-luxury-card rounded-3xl border border-white/10 p-6 sm:p-7"
                >
                    <p
                        class="text-xs font-bold tracking-[0.22em] text-amber-300"
                    >
                        01
                    </p>
                    <h3 class="mt-4 text-xl font-semibold text-white">
                        {{ copy.cardOneTitle }}
                    </h3>
                    <p class="mt-3 text-sm leading-7 text-zinc-400">
                        {{ copy.cardOneText }}
                    </p>
                </article>

                <article
                    class="vvs-luxury-card rounded-3xl border border-white/10 p-6 sm:p-7"
                >
                    <p
                        class="text-xs font-bold tracking-[0.22em] text-amber-300"
                    >
                        02
                    </p>
                    <h3 class="mt-4 text-xl font-semibold text-white">
                        {{ copy.cardTwoTitle }}
                    </h3>
                    <p class="mt-3 text-sm leading-7 text-zinc-400">
                        {{ copy.cardTwoText }}
                    </p>
                </article>

                <article
                    class="vvs-luxury-card rounded-3xl border border-white/10 p-6 sm:p-7"
                >
                    <p
                        class="text-xs font-bold tracking-[0.22em] text-amber-300"
                    >
                        03
                    </p>
                    <h3 class="mt-4 text-xl font-semibold text-white">
                        {{ copy.cardThreeTitle }}
                    </h3>
                    <p class="mt-3 text-sm leading-7 text-zinc-400">
                        {{ copy.cardThreeText }}
                    </p>
                </article>
            </div>

            <p
                class="mx-auto mt-8 max-w-3xl text-center text-xs leading-6 text-zinc-500"
            >
                {{ copy.note }}
            </p>
        </div>
    </section>
</template>
