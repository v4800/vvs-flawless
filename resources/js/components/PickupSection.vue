<script setup>
import { usePage } from '@inertiajs/vue3';
import { MapPin } from '@lucide/vue';
import { computed } from 'vue';

const page = usePage();
const translations = computed(() => page.props.translations);

const zoneCopy = computed(() => {
    return (
        {
            fr_BE: {
                title: 'Zones de remise',
                be: 'Belgique',
                beDetail: 'Liège et environs',
                fr: 'Nord de la France',
                frDetail: 'Sur rendez-vous',
                nl: 'Pays-Bas',
                nlDetail: 'Maastricht • Gulpen',
            },
            nl_BE: {
                title: 'Afhaalzones',
                be: 'België',
                beDetail: 'Luik en omgeving',
                fr: 'Noord-Frankrijk',
                frDetail: 'Op afspraak',
                nl: 'Nederland',
                nlDetail: 'Maastricht • Gulpen',
            },
            en_BE: {
                title: 'Handover zones',
                be: 'Belgium',
                beDetail: 'Liège and nearby',
                fr: 'Northern France',
                frDetail: 'By appointment',
                nl: 'Netherlands',
                nlDetail: 'Maastricht • Gulpen',
            },
            de_BE: {
                title: 'Übergabezonen',
                be: 'Belgien',
                beDetail: 'Lüttich und Umgebung',
                fr: 'Nordfrankreich',
                frDetail: 'Nach Vereinbarung',
                nl: 'Niederlande',
                nlDetail: 'Maastricht • Gulpen',
            },
        }[page.props.locale] ?? {
            title: 'Zones de remise',
            be: 'Belgique',
            beDetail: 'Liège et environs',
            fr: 'Nord de la France',
            frDetail: 'Sur rendez-vous',
            nl: 'Pays-Bas',
            nlDetail: 'Maastricht • Gulpen',
        }
    );
});
</script>

<template>
    <section
        id="pickup"
        class="relative scroll-mt-24 px-5 py-20 sm:px-6 lg:px-10"
    >
        <div
            class="vvs-luxury-card mx-auto max-w-[1350px] overflow-hidden rounded-3xl border"
        >
            <div
                class="grid gap-10 p-7 sm:p-10 lg:grid-cols-[1.05fr_0.95fr] lg:p-14"
            >
                <div>
                    <p class="vvs-eyebrow">
                        {{ translations.pickup.eyebrow }}
                    </p>

                    <h2
                        class="vvs-display-title mt-5 max-w-3xl text-[clamp(2.4rem,9vw,3.75rem)]"
                    >
                        {{ translations.pickup.title_before }}
                        <span class="vvs-gradient-text">
                            {{ translations.pickup.title_highlight }}
                        </span>
                    </h2>

                    <p class="mt-7 max-w-2xl text-base leading-7 text-zinc-300">
                        {{ translations.pickup.description_1 }}
                    </p>

                    <p class="mt-4 max-w-2xl text-sm leading-6 text-zinc-400">
                        {{ translations.pickup.description_2 }}
                    </p>

                    <div class="mt-8">
                        <p
                            class="mb-3 text-[10px] font-bold tracking-[0.18em] text-zinc-300 uppercase"
                        >
                            {{ zoneCopy.title }}
                        </p>

                        <div class="pickup-coverage-grid relative grid gap-3 rounded-2xl border border-white/10 p-3 sm:grid-cols-3">
                            <div
                                class="relative min-w-0 rounded-xl border border-white/10 bg-[#111]/95 p-4"
                            >
                                <div class="flex items-center gap-3">
                                    <MapPin :size="16" :stroke-width="1.5" class="text-amber-200" aria-hidden="true" />
                                    <span
                                        class="text-[10px] font-bold tracking-[0.18em] text-zinc-300"
                                        >BE</span
                                    >
                                </div>
                                <p class="mt-3 text-sm font-bold text-white">
                                    {{ zoneCopy.be }}
                                </p>
                                <p class="mt-1 text-xs text-zinc-400">
                                    {{ zoneCopy.beDetail }}
                                </p>
                            </div>

                            <div
                                class="relative min-w-0 rounded-xl border border-white/10 bg-[#111]/95 p-4"
                            >
                                <div class="flex items-center gap-3">
                                    <MapPin :size="16" :stroke-width="1.5" class="text-amber-200" aria-hidden="true" />
                                    <span
                                        class="text-[10px] font-bold tracking-[0.18em] text-zinc-300"
                                        >FR</span
                                    >
                                </div>
                                <p class="mt-3 text-sm font-bold text-white">
                                    {{ zoneCopy.fr }}
                                </p>
                                <p class="mt-1 text-xs text-zinc-400">
                                    {{ zoneCopy.frDetail }}
                                </p>
                            </div>

                            <div
                                class="relative min-w-0 rounded-xl border border-white/10 bg-[#111]/95 p-4"
                            >
                                <div class="flex items-center gap-3">
                                    <MapPin :size="16" :stroke-width="1.5" class="text-amber-200" aria-hidden="true" />
                                    <span
                                        class="text-[10px] font-bold tracking-[0.18em] text-zinc-300"
                                        >NL</span
                                    >
                                </div>
                                <p class="mt-3 text-sm font-bold text-white">
                                    {{ zoneCopy.nl }}
                                </p>
                                <p class="mt-1 text-xs text-zinc-400">
                                    {{ zoneCopy.nlDetail }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4">
                    <div
                        class="vvs-choice-card flex gap-5 rounded-2xl border p-5"
                    >
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-amber-300/30 font-black text-amber-200"
                        >
                            01
                        </div>
                        <div>
                            <p class="font-bold">
                                {{ translations.pickup.step_1_title }}
                            </p>
                            <p class="mt-1 text-sm leading-6 text-zinc-300">
                                {{ translations.pickup.step_1_text }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="vvs-choice-card flex gap-5 rounded-2xl border p-5"
                    >
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-amber-300/30 font-black text-amber-200"
                        >
                            02
                        </div>
                        <div>
                            <p class="font-bold">
                                {{ translations.pickup.step_2_title }}
                            </p>
                            <p class="mt-1 text-sm leading-6 text-zinc-300">
                                {{ translations.pickup.step_2_text }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="vvs-choice-card vvs-choice-card--featured flex gap-5 rounded-2xl border p-5"
                    >
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-amber-300 font-black text-black"
                        >
                            03
                        </div>
                        <div>
                            <p class="font-bold">
                                {{ translations.pickup.step_3_title }}
                            </p>
                            <p class="mt-1 text-sm leading-6 text-zinc-300">
                                {{ translations.pickup.step_3_text }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between rounded-xl border border-white/10 px-5 py-4"
                    >
                        <span class="text-sm text-zinc-300">
                            {{ translations.pickup.delay_label }}
                        </span>
                        <span class="text-sm font-bold text-zinc-200">
                            {{ translations.pickup.delay }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
