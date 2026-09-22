<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    movement: {
        type: String,
        required: true,
    },

    price: {
        type: Number,
        required: true,
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);
const localizedMovement = computed(() =>
    props.movement === 'Suisse'
        ? translations.value.collection.swiss
        : translations.value.collection.japanese,
);

const formatPrice = (price) => {
    const numericPrice = Number(price);

    return Number.isFinite(numericPrice) && numericPrice > 0
        ? `${numericPrice.toFixed(0)} €`
        : '—';
};
</script>

<template>
    <div
        class="fixed right-0 bottom-0 left-0 z-50 border-t border-white/10 bg-black/95 px-4 pt-3 pb-[calc(0.75rem+env(safe-area-inset-bottom))] backdrop-blur-xl lg:hidden"
    >
        <div class="mx-auto flex max-w-xl items-center justify-between gap-4">
            <div class="min-w-0">
                <p
                    class="truncate text-[9px] font-black tracking-[0.16em] text-zinc-400 uppercase"
                >
                    {{
                        translations.mobile_reservation.movement.replace(
                            ':movement',
                            localizedMovement,
                        )
                    }}
                </p>

                <p class="vvs-price mt-1 inline-block text-xl font-black">
                    {{ formatPrice(price) }}
                </p>
            </div>

            <a
                href="#reservation"
                class="vvs-button-primary flex shrink-0 items-center gap-3 rounded-xl px-5 py-3.5 text-xs font-black tracking-[0.08em] uppercase focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none active:scale-[0.98]"
            >
                {{ translations.mobile_reservation.cta }}

                <span aria-hidden="true">↓</span>
            </a>
        </div>
    </div>
</template>
