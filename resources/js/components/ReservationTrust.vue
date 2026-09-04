<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const translations = computed(() => page.props.translations);
</script>

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
                    {{ translations.reservation_trust.title }}
                </p>

                <p class="mt-2 text-sm leading-6 text-zinc-500">
                    {{ translations.reservation_trust.description }}
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
                    {{ translations.reservation_trust.sent }}
                </p>
            </div>

            <div>
                <p
                    class="text-[9px] font-black tracking-[0.15em] text-zinc-600 uppercase"
                >
                    02
                </p>

                <p class="mt-1 text-xs font-bold text-zinc-300">
                    {{ translations.reservation_trust.confirmed }}
                </p>
            </div>

            <div>
                <p
                    class="text-[9px] font-black tracking-[0.15em] text-zinc-600 uppercase"
                >
                    03
                </p>

                <p class="mt-1 text-xs font-bold text-zinc-300">
                    {{ translations.reservation_trust.organized }}
                </p>
            </div>
        </div>
    </div>
</template>
