<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const translations = computed(() => page.props.translations?.visuals ?? null);
const shouldShow = computed(
    () => page.component.startsWith('Watches/') && Boolean(translations.value),
);
</script>

<template>
    <section
        v-if="shouldShow"
        class="border-t border-white/10 bg-zinc-950/80 px-5 py-8 text-white sm:px-6 lg:px-10"
        aria-labelledby="visual-disclosure-title"
    >
        <div
            class="mx-auto max-w-[1400px] rounded-2xl border border-amber-300/15 bg-amber-300/[0.025] px-5 py-5 sm:px-6"
        >
            <div class="flex items-start gap-4">
                <div
                    class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-amber-300/25 bg-amber-300/[0.06] text-sm text-amber-200"
                    aria-hidden="true"
                >
                    i
                </div>

                <div class="min-w-0">
                    <p
                        class="text-[9px] font-black tracking-[0.28em] text-amber-300/80 uppercase"
                    >
                        {{ translations.eyebrow }}
                    </p>

                    <h2
                        id="visual-disclosure-title"
                        class="mt-2 text-base font-black tracking-[-0.01em] text-white"
                    >
                        {{ translations.title }}
                    </h2>

                    <p class="mt-2 max-w-5xl text-xs leading-6 text-zinc-400 sm:text-sm">
                        {{ translations.text }}
                    </p>

                    <p class="mt-2 max-w-5xl text-xs leading-6 text-zinc-500">
                        {{ translations.note }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
