<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Conditions de réservation — VVS FLAWLESS" />

    <div class="min-h-screen bg-black px-5 py-16 text-white sm:px-6">
        <main class="mx-auto max-w-3xl">
            <Link
                href="/watches"
                class="text-xs font-black tracking-[0.15em] text-amber-300 uppercase"
            >
                ← Retour à la collection
            </Link>

            <p
                class="mt-12 text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
            >
                VVS FLAWLESS
            </p>

            <h1
                class="mt-4 text-4xl font-black tracking-[-0.04em] uppercase sm:text-5xl"
            >
                Conditions de réservation
            </h1>

            <div class="mt-12 space-y-10 text-sm leading-7 text-zinc-400">
                <section>
                    <h2 class="text-xl font-black text-white">
                        1. Demande de réservation
                    </h2>

                    <p class="mt-3">
                        L'envoi du formulaire constitue une demande de
                        réservation. Il ne réalise aucun paiement en ligne et ne
                        finalise pas automatiquement la vente.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-black text-white">
                        2. Confirmation
                    </h2>

                    <p class="mt-3">
                        Après réception de la demande, VVS FLAWLESS contacte le
                        client afin de confirmer le modèle, le mouvement choisi,
                        le prix et les modalités de la réservation.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-black text-white">
                        3. Disponibilité
                    </h2>

                    <p class="mt-3">
                        Le délai de disponibilité généralement indiqué est de 5
                        à 6 jours ouvrables. Ce délai reste estimatif jusqu'à
                        confirmation de la réservation.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-black text-white">4. Prix</h2>

                    <p class="mt-3">
                        Le prix correspondant au modèle et au mouvement choisis
                        est affiché sur la fiche de la montre et rappelé lors de
                        la réservation.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-black text-white">5. Paiement</h2>

                    <p class="mt-3">
                        Aucun paiement n'est effectué directement sur le site.
                        Les modalités de paiement sont confirmées avec le client
                        avant la remise ou la livraison.
                    </p>

                    <p class="mt-3">
                        Le paiement peut notamment être effectué en espèces ou
                        par un moyen de paiement électronique convenu lors de la
                        confirmation, dans le respect de la réglementation
                        applicable.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-black text-white">
                        6. Remise en main propre ou livraison
                    </h2>

                    <p class="mt-3">
                        Le client choisit la remise en main propre ou la
                        livraison lors de sa demande. Les modalités précises
                        sont convenues avec le client avant la finalisation de
                        la commande.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-black text-white">
                        7. Informations communiquées
                    </h2>

                    <p class="mt-3">
                        Le client s'engage à fournir des informations correctes
                        afin que VVS FLAWLESS puisse le contacter et traiter sa
                        demande.
                    </p>
                </section>
            </div>
        </main>
    </div>
</template>
