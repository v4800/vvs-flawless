<template>
    <div
        class="rounded-2xl border border-amber-300/15 bg-amber-300/[0.025] p-5"
    >
        <div class="flex items-start gap-4">
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-300 text-xs font-black text-black"
            >
                ✓
            </div>

            <div>
                <p class="font-black text-white">
                    Une demande de réservation, pas un paiement en ligne
                </p>

                <p class="mt-2 text-sm leading-6 text-zinc-500">
                    L'envoi du formulaire n'effectue aucun paiement. Votre
                    demande est d'abord transmise à VVS FLAWLESS afin de
                    confirmer la montre, le mouvement et les modalités avec
                    vous.
                </p>
            </div>
        </div>

        <div
            class="mt-5 grid gap-3 border-t border-white/10 pt-5 sm:grid-cols-3"
        >
            <div>
                <p
                    class="text-[9px] font-black tracking-[0.15em] text-zinc-600 uppercase"
                >
                    01
                </p>

                <p class="mt-1 text-xs font-bold text-zinc-300">
                    Demande envoyée
                </p>
            </div>

            <div>
                <p
                    class="text-[9px] font-black tracking-[0.15em] text-zinc-600 uppercase"
                >
                    02
                </p>

                <p class="mt-1 text-xs font-bold text-zinc-300">
                    Contact et confirmation
                </p>
            </div>

            <div>
                <p
                    class="text-[9px] font-black tracking-[0.15em] text-zinc-600 uppercase"
                >
                    03
                </p>

                <p class="mt-1 text-xs font-bold text-zinc-300">
                    Point de rencontre convenu
                </p>
            </div>
        </div>
    </div>
</template>
