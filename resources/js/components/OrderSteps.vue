<script setup>
import { animate, stagger } from 'animejs';
import { onMounted, ref } from 'vue';

const section = ref(null);

onMounted(() => {
    const prefersReducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    if (
        prefersReducedMotion
        || !section.value
    ) {
        return;
    }

    const observer = new IntersectionObserver(
        ([entry]) => {
            if (!entry.isIntersecting) {
                return;
            }

            const steps =
                section.value.querySelectorAll(
                    '.order-step'
                );

            const line =
                section.value.querySelector(
                    '.order-line-fill'
                );

            animate(steps, {
                opacity: [0, 1],
                y: [35, 0],
                scale: [0.96, 1],
                delay: stagger(130),
                duration: 850,
                ease: 'outExpo',
            });

            if (line) {
                animate(line, {
                    scaleX: [0, 1],
                    duration: 1600,
                    delay: 300,
                    ease: 'outExpo',
                });
            }

            observer.disconnect();
        },
        {
            threshold: 0.25,
        }
    );

    observer.observe(
        section.value
    );
});
</script>

<template>
    <section
        ref="section"
        id="commander"
        class="relative overflow-hidden px-5 py-24 sm:px-6 lg:px-10"
    >
        <div
            class="pointer-events-none absolute left-1/2 top-1/2 -z-10 h-[420px] w-[900px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.04] blur-[130px]"
        ></div>

        <div
            class="mx-auto max-w-[1500px]"
        >
            <header
                class="mb-16 text-center"
            >
                <div
                    class="mb-4 flex items-center justify-center gap-3"
                >
                    <span
                        class="h-px w-10 bg-gradient-to-r from-transparent to-amber-300"
                    ></span>

                    <span
                        class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-300"
                    >
                        Simple & rapide
                    </span>

                    <span
                        class="h-px w-10 bg-gradient-to-l from-transparent to-amber-300"
                    ></span>
                </div>

                <h2
                    class="text-4xl font-black uppercase tracking-[-0.04em] sm:text-5xl"
                >
                    Comment
                    <span class="text-amber-300">
                        commander ?
                    </span>
                </h2>

                <p
                    class="mx-auto mt-5 max-w-xl text-sm leading-6 text-zinc-500 sm:text-base"
                >
                    De la sélection de votre montre
                    jusqu'à sa remise en main propre.
                </p>
            </header>

            <div class="relative">

                <!-- LIGNE -->

                <div
                    class="absolute left-[10%] right-[10%] top-9 hidden h-px bg-white/10 lg:block"
                >
                    <div
                        class="order-line-fill h-full origin-left bg-gradient-to-r from-amber-500 via-amber-200 to-amber-500 shadow-[0_0_15px_rgba(251,191,36,0.5)]"
                    ></div>
                </div>

                <div
                    class="grid gap-5 md:grid-cols-2 lg:grid-cols-5"
                >

                    <!-- 01 -->

                    <div
                        class="order-step opacity-0"
                    >
                        <div
                            class="h-full rounded-2xl border border-white/10 bg-gradient-to-b from-zinc-950 to-black p-6 transition duration-300 hover:-translate-y-1 hover:border-amber-300/40"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-amber-300/40 bg-black text-sm font-black text-amber-200"
                            >
                                01
                            </div>

                            <p class="mt-7 text-lg font-black">
                                Choisis ta montre
                            </p>

                            <p
                                class="mt-3 text-sm leading-6 text-zinc-500"
                            >
                                Parcours la collection et sélectionne
                                la pièce qui correspond à ton style.
                            </p>
                        </div>
                    </div>

                    <!-- 02 -->

                    <div
                        class="order-step opacity-0"
                    >
                        <div
                            class="h-full rounded-2xl border border-white/10 bg-gradient-to-b from-zinc-950 to-black p-6 transition duration-300 hover:-translate-y-1 hover:border-amber-300/40"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-amber-300/40 bg-black text-sm font-black text-amber-200"
                            >
                                02
                            </div>

                            <p class="mt-7 text-lg font-black">
                                Choisis ton mouvement
                            </p>

                            <p
                                class="mt-3 text-sm leading-6 text-zinc-500"
                            >
                                Sélectionne le mouvement japonais
                                ou la version suisse premium.
                            </p>
                        </div>
                    </div>

                    <!-- 03 -->

                    <div
                        class="order-step opacity-0"
                    >
                        <div
                            class="h-full rounded-2xl border border-amber-300/20 bg-amber-300/[0.025] p-6 transition duration-300 hover:-translate-y-1 hover:border-amber-300/50"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-300 text-sm font-black text-black shadow-[0_0_30px_rgba(251,191,36,0.18)]"
                            >
                                03
                            </div>

                            <p class="mt-7 text-lg font-black">
                                Envoie ta réservation
                            </p>

                            <p
                                class="mt-3 text-sm leading-6 text-zinc-500"
                            >
                                Renseigne tes coordonnées et envoie
                                ta demande directement depuis le site.
                            </p>
                        </div>
                    </div>

                    <!-- 04 -->

                    <div
                        class="order-step opacity-0"
                    >
                        <div
                            class="h-full rounded-2xl border border-white/10 bg-gradient-to-b from-zinc-950 to-black p-6 transition duration-300 hover:-translate-y-1 hover:border-amber-300/40"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-amber-300/40 bg-black text-sm font-black text-amber-200"
                            >
                                04
                            </div>

                            <p class="mt-7 text-lg font-black">
                                On te contacte
                            </p>

                            <p
                                class="mt-3 text-sm leading-6 text-zinc-500"
                            >
                                VVS FLAWLESS te contacte afin
                                de confirmer la réservation et
                                organiser la remise.
                            </p>
                        </div>
                    </div>

                    <!-- 05 -->

                    <div
                        class="order-step opacity-0"
                    >
                        <div
                            class="h-full rounded-2xl border border-white/10 bg-gradient-to-b from-zinc-950 to-black p-6 transition duration-300 hover:-translate-y-1 hover:border-amber-300/40"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full border border-amber-300/40 bg-black text-sm font-black text-amber-200"
                            >
                                05
                            </div>

                            <p class="mt-7 text-lg font-black">
                                Récupère ta montre
                            </p>

                            <p
                                class="mt-3 text-sm leading-6 text-zinc-500"
                            >
                                Un point de rencontre est convenu
                                avec VVS FLAWLESS pour effectuer
                                la remise en main propre.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="mx-auto mt-10 max-w-3xl rounded-xl border border-amber-300/15 bg-amber-300/[0.025] px-5 py-4 text-center"
            >
                <p
                    class="text-xs leading-5 text-zinc-500"
                >
                    Une réservation confirme votre intérêt pour la pièce.
                    La commande est finalisée après confirmation avec
                    VVS FLAWLESS.
                </p>
            </div>
        </div>
    </section>
</template>