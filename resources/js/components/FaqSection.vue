<script setup>
import { ref } from 'vue';

const openIndex = ref(null);

const faqs = [
    {
        question: 'Quelle qualité de moissanite utilisez-vous ?',

        answer: 'Les modèles VVS FLAWLESS sont proposés avec de la moissanite de pureté VVS et de couleur D. La couleur D correspond à un rendu incolore, tandis que VVS désigne le niveau de pureté de la pierre.',
    },

    {
        question: 'La moissanite passe-t-elle au testeur diamant ?',

        answer: 'La moissanite peut réagir positivement sur certains testeurs thermiques utilisés pour le diamant. Un testeur capable de différencier diamant et moissanite pourra cependant identifier les deux pierres séparément.',
    },

    {
        question: 'Quelle différence entre mouvement japonais et suisse ?',

        answer: 'Deux versions peuvent être proposées selon le modèle. Le mouvement japonais correspond à la version standard et la version suisse à l’option supérieure disponible sur la montre concernée.',
    },

    {
        question: 'Comment fonctionne une réservation ?',

        answer: 'Choisissez votre montre et votre mouvement, puis remplissez le formulaire. Votre demande est enregistrée et vous recevez un récapitulatif. VVS FLAWLESS vous contacte ensuite afin de finaliser les détails.',
    },

    {
        question: 'Quand ma montre sera-t-elle disponible ?',

        answer: 'Le délai estimé est généralement de 5 à 6 jours ouvrables. Dès que votre montre est disponible, VVS FLAWLESS vous contacte afin d’organiser la remise.',
    },

    {
        question: 'Comment recevoir ma montre ?',

        answer: 'Vous pouvez choisir une remise en main propre ou une livraison. Les modalités précises sont confirmées avec vous avant la finalisation de la commande.',
    },

    {
        question: 'Comment se passe la remise en main propre ?',

        answer: 'Le lieu exact est convenu directement avec vous après confirmation de votre réservation afin de choisir un point de rencontre adapté.',
    },

    {
        question:
            'Ma réservation est-elle directement une commande définitive ?',

        answer: 'Non. La réservation permet d’enregistrer votre demande et la pièce sélectionnée. La commande est finalisée après confirmation des détails avec VVS FLAWLESS.',
    },
];

const toggleFaq = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <section
        id="faq"
        class="relative scroll-mt-24 overflow-hidden px-5 py-24 sm:px-6 lg:px-10"
    >
        <div
            class="pointer-events-none absolute top-1/2 left-1/2 -z-10 h-[600px] w-[900px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.035] blur-[150px]"
        ></div>

        <div class="mx-auto max-w-5xl">
            <header class="mb-14 text-center">
                <div class="mb-4 flex items-center justify-center gap-3">
                    <span
                        class="h-px w-10 bg-gradient-to-r from-transparent to-amber-300"
                    ></span>

                    <span
                        class="text-[10px] font-black tracking-[0.4em] text-amber-300 uppercase"
                    >
                        Besoin d'informations ?
                    </span>

                    <span
                        class="h-px w-10 bg-gradient-to-l from-transparent to-amber-300"
                    ></span>
                </div>

                <h2
                    class="text-4xl font-black tracking-[-0.04em] uppercase sm:text-5xl"
                >
                    Questions

                    <span class="text-amber-300"> fréquentes </span>
                </h2>

                <p
                    class="mx-auto mt-5 max-w-2xl text-sm leading-6 text-zinc-500 sm:text-base"
                >
                    Tout ce qu'il faut savoir avant de réserver votre montre.
                </p>
            </header>

            <div class="space-y-3">
                <div
                    v-for="(faq, index) in faqs"
                    :key="faq.question"
                    :class="[
                        'overflow-hidden rounded-2xl border transition-all duration-300',

                        openIndex === index
                            ? 'border-amber-300/40 bg-amber-300/[0.035] shadow-[0_0_35px_rgba(251,191,36,0.05)]'
                            : 'border-white/10 bg-zinc-950/70 hover:border-white/20',
                    ]"
                >
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-6 px-6 py-5 text-left sm:px-7"
                        @click="toggleFaq(index)"
                    >
                        <span
                            :class="[
                                'text-sm font-bold transition sm:text-base',

                                openIndex === index
                                    ? 'text-amber-200'
                                    : 'text-zinc-200',
                            ]"
                        >
                            {{ faq.question }}
                        </span>

                        <span
                            :class="[
                                'flex h-9 w-9 shrink-0 items-center justify-center rounded-full border text-lg transition-all duration-300',

                                openIndex === index
                                    ? 'rotate-45 border-amber-300 bg-amber-300 text-black'
                                    : 'border-white/15 bg-black text-amber-200',
                            ]"
                        >
                            +
                        </span>
                    </button>

                    <div
                        class="grid transition-all duration-300 ease-out"
                        :class="
                            openIndex === index
                                ? 'grid-rows-[1fr] opacity-100'
                                : 'grid-rows-[0fr] opacity-0'
                        "
                    >
                        <div class="overflow-hidden">
                            <div
                                class="border-t border-white/[0.07] px-6 pt-5 pb-6 sm:px-7"
                            >
                                <p
                                    class="max-w-4xl text-sm leading-7 text-zinc-400"
                                >
                                    {{ faq.answer }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
