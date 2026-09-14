<script setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();
</script>

<template>
    <section
        id="confiance-et-avis"
        lang="fr"
        class="mx-auto my-12 max-w-[1400px] scroll-mt-24 px-5"
    >
        <div class="grid gap-6 md:grid-cols-2">
            <div class="vvs-luxury-card rounded-3xl border p-7 sm:p-8">
                <p class="vvs-eyebrow">On se rencontre</p>
                <h2 class="vvs-display-title mt-4 text-3xl">
                    Remise en main propre
                </h2>
                <p class="mt-5 leading-7 text-zinc-300">
                    Sur rendez-vous, avec un lieu et un horaire convenus ensemble.
                    Prenez le temps d’examiner la montre et de poser vos questions.
                </p>
                <ul
                    class="mt-5 list-disc space-y-2 pl-5 text-sm leading-6 text-zinc-400"
                >
                    <li>Demandez une vidéo de l’exemplaire proposé.</li>
                    <li>
                        Faites confirmer le modèle, le prix et les modalités de
                        paiement par écrit.
                    </li>
                    <li>Conservez un justificatif de la remise et du paiement.</li>
                </ul>
            </div>

            <div class="vvs-luxury-card rounded-3xl border p-7 sm:p-8">
                <p class="vvs-eyebrow">Votre expérience compte</p>
                <h2 class="vvs-display-title mt-4 text-3xl">Avis clients</h2>
                <p class="mt-5 leading-7 text-zinc-300">
                    Partagez votre expérience, positive ou négative. Les avis sont
                    modérés avant publication, sans sélection selon la note.
                </p>
                <p class="mt-3 text-sm leading-6 text-zinc-400">
                    Aucun avis inventé. Les achats ne sont pas automatiquement
                    vérifiés.
                </p>
                <a
                    href="/avis-clients"
                    class="vvs-button-primary mt-6 inline-flex rounded-xl px-5 py-3 font-bold focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-200"
                >
                    Lire ou laisser un avis
                </a>
                <a
                    v-if="page.props.auth?.user?.is_admin"
                    href="/dashboard/avis"
                    class="mt-4 block text-sm text-amber-200 underline"
                >
                    Modérer les avis
                </a>
            </div>
        </div>
    </section>
</template>
