<script setup>
defineProps({
    movement: {
        type: String,
        required: true,
    },

    price: {
        type: Number,
        required: true,
    },
});

const formatPrice = (price) => {
    return `${Number(price).toFixed(0)} €`;
};
</script>

<template>
    <div
        class="fixed right-0 bottom-0 left-0 z-50 border-t border-white/10 bg-black/95 px-4 pt-3 pb-[calc(0.75rem+env(safe-area-inset-bottom))] backdrop-blur-xl lg:hidden"
    >
        <div class="mx-auto flex max-w-xl items-center justify-between gap-4">
            <div class="min-w-0">
                <p
                    class="truncate text-[9px] font-black tracking-[0.16em] text-zinc-600 uppercase"
                >
                    Mouvement {{ movement }}
                </p>

                <p class="mt-1 text-xl font-black text-amber-200">
                    {{ formatPrice(price) }}
                </p>
            </div>

            <a
                href="#reservation"
                class="flex shrink-0 items-center gap-3 rounded-xl bg-amber-300 px-5 py-3.5 text-xs font-black tracking-[0.08em] text-black uppercase transition active:scale-[0.98]"
            >
                Réserver

                <span>↓</span>
            </a>
        </div>
    </div>
</template>
