<script setup>
import { usePage } from '@inertiajs/vue3';
import { Gem, Settings2, MapPin, Clock3 } from '@lucide/vue';

const emit = defineEmits(['scroll-to-collection']);

const page = usePage();
const translations = page.props.translations;
</script>

<template>
    <section id="concept" class="scroll-mt-24 px-5 pb-8 sm:px-6 lg:px-10">
        <div
            class="reveal-on-scroll relative mx-auto max-w-[1500px] overflow-hidden rounded-[1.75rem] border border-amber-200/15 bg-[radial-gradient(ellipse_at_85%_10%,rgba(205,160,89,0.13),transparent_45%),linear-gradient(115deg,#151310_0%,#0c0c0d_50%,#11100f_100%)] shadow-[0_24px_70px_rgba(0,0,0,0.22)]"
        >
            <div
                aria-hidden="true"
                class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-amber-200/40 to-transparent"
            ></div>

            <div class="relative grid gap-10 px-7 py-10 sm:px-10 sm:py-12 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end lg:gap-16 lg:px-16 lg:py-14">
                <div class="min-w-0">
                    <div class="flex items-center gap-4">
                        <span aria-hidden="true" class="h-px w-8 bg-amber-300/70"></span>
                        <p class="text-[0.67rem] font-bold tracking-[0.25em] text-amber-200 uppercase sm:text-xs">
                            {{ translations.concept.eyebrow }}
                        </p>
                    </div>

                    <h2 class="vvs-display-title mt-6 max-w-[16ch] text-[clamp(2.25rem,4vw,4rem)] leading-[1.08] text-white">
                        {{ translations.concept.title }}
                    </h2>

                    <p class="mt-6 max-w-2xl border-l border-amber-200/35 pl-5 text-sm leading-7 text-zinc-300 sm:text-base">
                        {{ translations.concept.description }}
                    </p>
                </div>

                <button
                    type="button"
                    class="group inline-flex min-h-14 w-full items-center justify-between gap-8 rounded-full border border-amber-200/50 bg-amber-200 px-6 py-4 text-left text-xs font-bold tracking-[0.13em] text-zinc-950 uppercase transition-colors hover:border-amber-100 hover:bg-amber-100 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-200 sm:w-fit lg:mb-1"
                    @click="emit('scroll-to-collection')"
                >
                    <span>{{ translations.concept.cta }}</span>
                    <span aria-hidden="true" class="text-xl leading-none transition-transform group-hover:translate-x-1">↗</span>
                </button>
            </div>
        </div>
    </section>

    <section id="services" class="scroll-mt-24 px-5 py-12 sm:px-6 lg:px-10">
        <div
            class="reveal-on-scroll vvs-luxury-card mx-auto grid max-w-[1500px] overflow-hidden rounded-2xl border sm:grid-cols-2 lg:grid-cols-4"
        >
            <div class="border-b border-white/10 p-7 sm:border-r lg:border-b-0">
                <Gem
                    :size="21"
                    :stroke-width="1.5"
                    class="text-amber-200"
                    aria-hidden="true"
                />
                <h3 class="mt-4 text-sm font-semibold tracking-wide">
                    {{ translations.services.quality_title }}
                </h3>
                <p class="mt-2 text-xs leading-5 text-zinc-500">
                    {{ translations.services.quality_text }}
                </p>
            </div>
            <div class="border-b border-white/10 p-7 lg:border-r lg:border-b-0">
                <Settings2
                    :size="21"
                    :stroke-width="1.5"
                    class="text-amber-200"
                    aria-hidden="true"
                />
                <h3 class="mt-4 text-sm font-semibold tracking-wide">
                    {{ translations.services.movements_title }}
                </h3>
                <p class="mt-2 text-xs leading-5 text-zinc-500">
                    {{ translations.services.movements_text }}
                </p>
            </div>
            <div class="border-b border-white/10 p-7 sm:border-r sm:border-b-0">
                <MapPin
                    :size="21"
                    :stroke-width="1.5"
                    class="text-amber-200"
                    aria-hidden="true"
                />
                <h3 class="mt-4 text-sm font-semibold tracking-wide">
                    {{ translations.services.delivery_title }}
                </h3>
                <p class="mt-2 text-xs leading-5 text-zinc-500">
                    {{ translations.services.delivery_text }}
                </p>
            </div>
            <div class="p-7">
                <Clock3
                    :size="21"
                    :stroke-width="1.5"
                    class="text-amber-200"
                    aria-hidden="true"
                />
                <h3 class="mt-4 text-sm font-semibold tracking-wide">
                    {{ translations.services.delay_title }}
                </h3>
                <p class="mt-2 text-xs leading-5 text-zinc-500">
                    {{ translations.services.delay_text }}
                </p>
            </div>
        </div>
    </section>
</template>
