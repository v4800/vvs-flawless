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
            class="reveal-on-scroll vvs-luxury-card mx-auto max-w-[1500px] overflow-hidden rounded-2xl border"
        >
            <div
                class="grid items-center gap-8 p-8 md:grid-cols-[auto_1fr_auto] md:p-10"
            >
                <div
                    aria-hidden="true"
                    class="flex h-20 w-28 items-center justify-center rounded-xl border border-amber-200/20 bg-[radial-gradient(circle_at_50%_40%,rgba(242,213,138,0.12),transparent_72%)] shadow-[inset_0_1px_0_rgba(255,255,255,0.08)]"
                >
                    <span class="font-serif text-4xl tracking-[-0.12em] text-amber-100">VVS</span>
                </div>

                <div>
                    <p
                        class="text-xs font-black tracking-[0.3em] text-amber-300 uppercase"
                    >
                        {{ translations.concept.eyebrow }}
                    </p>
                    <h2 class="vvs-display-title mt-3 text-3xl sm:text-4xl">
                        {{ translations.concept.title }}
                    </h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400">
                        {{ translations.concept.description }}
                    </p>
                </div>

                <button
                    type="button"
                    class="vvs-button-primary rounded-xl px-6 py-4 text-xs font-bold tracking-[0.15em] uppercase"
                    @click="emit('scroll-to-collection')"
                >
                    {{ translations.concept.cta }}
                </button>
            </div>
        </div>
    </section>

    <section id="services" class="scroll-mt-24 px-5 py-12 sm:px-6 lg:px-10">
        <div
            class="reveal-on-scroll vvs-luxury-card mx-auto grid max-w-[1500px] overflow-hidden rounded-2xl border sm:grid-cols-2 lg:grid-cols-4"
        >
            <div class="border-b border-white/10 p-7 sm:border-r lg:border-b-0">
                <Gem :size="21" :stroke-width="1.5" class="text-amber-200" aria-hidden="true" />
                <h3 class="mt-4 text-sm font-semibold tracking-wide">
                    {{ translations.services.quality_title }}
                </h3>
                <p class="mt-2 text-xs leading-5 text-zinc-500">
                    {{ translations.services.quality_text }}
                </p>
            </div>
            <div class="border-b border-white/10 p-7 lg:border-r lg:border-b-0">
                <Settings2 :size="21" :stroke-width="1.5" class="text-amber-200" aria-hidden="true" />
                <h3 class="mt-4 text-sm font-semibold tracking-wide">
                    {{ translations.services.movements_title }}
                </h3>
                <p class="mt-2 text-xs leading-5 text-zinc-500">
                    {{ translations.services.movements_text }}
                </p>
            </div>
            <div class="border-b border-white/10 p-7 sm:border-r sm:border-b-0">
                <MapPin :size="21" :stroke-width="1.5" class="text-amber-200" aria-hidden="true" />
                <h3 class="mt-4 text-sm font-semibold tracking-wide">
                    {{ translations.services.delivery_title }}
                </h3>
                <p class="mt-2 text-xs leading-5 text-zinc-500">
                    {{ translations.services.delivery_text }}
                </p>
            </div>
            <div class="p-7">
                <Clock3 :size="21" :stroke-width="1.5" class="text-amber-200" aria-hidden="true" />
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
