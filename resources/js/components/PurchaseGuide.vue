<template>
    <section class="border-b border-white/10 px-5 py-20 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-[1200px]">
            <header class="mx-auto max-w-2xl text-center">
                <p
                    class="text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
                >
                    Avant de réserver
                </p>

                <h2
                    class="mt-4 text-3xl font-black tracking-[-0.035em] uppercase sm:text-4xl"
                >
                    Choisir votre
                    <span class="text-amber-300">configuration</span>
                </h2>

                <p class="mt-5 text-sm leading-7 text-zinc-500">
                    Chaque modèle est proposé avec deux configurations de
                    mouvement. Sélectionnez celle que vous souhaitez avant
                    d'envoyer votre réservation.
                </p>
            </header>

            <div class="mt-10 grid gap-5 md:grid-cols-2">
                <!-- JAPONAIS -->

                <div
                    class="rounded-3xl border border-white/10 bg-zinc-950/70 p-6 sm:p-7"
                >
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                    >
                        Mouvement
                    </p>

                    <h3 class="mt-2 text-2xl font-black">Japonais</h3>

                    <p class="mt-5 text-sm leading-7 text-zinc-500">
                        La configuration proposée au tarif le plus accessible
                        sur la fiche de la montre.
                    </p>
                </div>

                <!-- SUISSE -->

                <div
                    class="rounded-3xl border border-amber-300/20 bg-amber-300/[0.025] p-6 sm:p-7"
                >
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                    >
                        Mouvement
                    </p>

                    <h3 class="mt-2 text-2xl font-black">Suisse</h3>

                    <p class="mt-5 text-sm leading-7 text-zinc-500">
                        La seconde configuration disponible pour ce modèle,
                        proposée au tarif indiqué sur la fiche.
                    </p>
                </div>
            </div>

            <p
                class="mx-auto mt-5 max-w-3xl text-center text-xs leading-6 text-zinc-700"
            >
                Pour connaître les détails exacts d'un mouvement avant votre
                réservation, contactez directement VVS FLAWLESS.
            </p>

            <!-- PROCESSUS -->

            <div
                class="mt-14 rounded-3xl border border-white/10 bg-gradient-to-br from-zinc-950 to-black p-6 sm:p-8"
            >
                <div class="mb-8">
                    <p
                        class="text-[10px] font-black tracking-[0.3em] text-amber-300 uppercase"
                    >
                        Comment ça se passe ?
                    </p>

                    <h3 class="mt-3 text-2xl font-black">
                        De la sélection à la remise
                    </h3>
                </div>

                <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <p class="text-3xl font-black text-amber-300">01</p>

                        <p class="mt-3 font-black">Choisissez</p>

                        <p class="mt-2 text-xs leading-6 text-zinc-600">
                            Sélectionnez votre montre et votre mouvement.
                        </p>
                    </div>

                    <div>
                        <p class="text-3xl font-black text-amber-300">02</p>

                        <p class="mt-3 font-black">Réservez</p>

                        <p class="mt-2 text-xs leading-6 text-zinc-600">
                            Envoyez votre demande directement depuis le
                            formulaire.
                        </p>
                    </div>

                    <div>
                        <p class="text-3xl font-black text-amber-300">03</p>

                        <p class="mt-3 font-black">Confirmation</p>

                        <p class="mt-2 text-xs leading-6 text-zinc-600">
                            VVS FLAWLESS vous contacte pour confirmer les
                            détails de votre demande.
                        </p>
                    </div>

                    <div>
                        <p class="text-3xl font-black text-amber-300">04</p>

                        <p class="mt-3 font-black">Remise</p>

                        <p class="mt-2 text-xs leading-6 text-zinc-600">
                            Le point de rencontre est ensuite convenu pour la
                            remise en main propre.
                        </p>
                    </div>
                </div>

                <div
                    class="mt-8 grid gap-3 border-t border-white/10 pt-6 sm:grid-cols-2"
                >
                    <div
                        class="rounded-2xl border border-white/10 bg-black/50 p-4"
                    >
                        <p
                            class="text-[9px] font-black tracking-[0.18em] text-zinc-600 uppercase"
                        >
                            Disponibilité estimée
                        </p>

                        <p class="mt-2 font-bold text-zinc-200">
                            5–6 jours ouvrables
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-black/50 p-4"
                    >
                        <p
                            class="text-[9px] font-black tracking-[0.18em] text-zinc-600 uppercase"
                        >
                            Mode de remise
                        </p>

                        <p class="mt-2 font-bold text-zinc-200">
                            Remise en main propre uniquement
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
