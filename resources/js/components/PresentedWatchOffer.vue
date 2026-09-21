<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({ watch: { type: Object, required: true } });

const page = usePage();
const translations = computed(() => page.props.translations);
const htmlLang = computed(() => page.props.locale.replace('_', '-'));
</script>

<template>
    <div
        class="self-center rounded-3xl border border-amber-300/20 bg-black/70 p-6 sm:p-9"
        :lang="htmlLang"
    >
        <p class="text-xs font-bold tracking-[0.3em] text-amber-300">
            VVS FLAWLESS
        </p>
        <h1 class="vvs-watch-title mt-6 text-4xl sm:text-5xl">
            {{ watch.name }}
        </h1>
        <p class="mt-6 leading-7 text-zinc-300">
            {{ watch.description }}
        </p>
        <p class="mt-8 text-xs tracking-widest text-zinc-400 uppercase">
            {{ watch.presented_copy.price_label }}
        </p>
        <p class="vvs-price mt-2 inline-block max-w-full text-[clamp(2.25rem,9vw,3rem)] font-bold">
            {{ Number(watch.price).toFixed(0) }} €
        </p>
        <dl class="mt-8 space-y-4 border-t border-white/10 pt-6 text-sm">
            <div>
                <dt class="text-zinc-400">
                    {{ translations.product.stone }}
                </dt>
                <dd class="mt-1">
                    Moissanite · {{ watch.presented_copy.stone_weight_value }}
                </dd>
            </div>
            <div>
                <dt class="text-zinc-400">
                    {{ watch.presented_copy.confirm_title }}
                </dt>
                <dd class="mt-1">
                    {{ watch.presented_copy.confirm_details }}
                </dd>
            </div>
        </dl>
        <p class="mt-5 text-xs leading-6 text-zinc-400">
            {{ watch.presented_copy.visual_note }}
        </p>
        <a
            href="#reservation"
            class="vvs-button-primary mt-8 block rounded-xl px-5 py-4 text-center font-bold focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-200"
        >
            {{ translations.product.reserve_watch }}
        </a>
    </div>
</template>
