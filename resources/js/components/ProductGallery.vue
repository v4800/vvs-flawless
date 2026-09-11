<script setup>
import StockBadge from '@/components/StockBadge.vue';

defineProps({
    watch: {
        type: Object,
        required: true,
    },
    gallery: {
        type: Array,
        default: () => [],
    },
    activeImage: {
        type: String,
        required: true,
    },
    translations: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['select-image']);

const selectImage = (image) => {
    emit('select-image', image);
};
</script>

<template>
    <div>
        <div class="relative">
            <div class="absolute top-5 left-5 z-20">
                <StockBadge
                    :quantity="watch.stock_quantity"
                    :availability="watch.availability"
                />
            </div>

            <div
                class="relative aspect-square self-start overflow-hidden rounded-3xl border border-white/10 bg-[radial-gradient(circle_at_50%_38%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_48%,#050505_80%)]"
            >
                <img
                    :src="activeImage"
                    :alt="watch.name"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                    draggable="false"
                    class="block h-full w-full object-contain p-3 sm:p-5"
                />
            </div>

            <div
                class="absolute right-5 bottom-5 left-5 flex items-center justify-between rounded-2xl border border-white/10 bg-black/75 px-5 py-4 backdrop-blur-xl"
            >
                <div>
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-500 uppercase"
                    >
                        {{ translations.product.stone }}
                    </p>

                    <p class="mt-1 text-sm font-bold text-white">
                        Moissanite VVS
                    </p>
                </div>

                <div aria-hidden="true" class="h-8 w-px bg-white/10"></div>

                <div class="text-right">
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-500 uppercase"
                    >
                        {{ translations.product.color }}
                    </p>

                    <p class="mt-1 text-sm font-bold text-amber-200">D</p>
                </div>
            </div>
        </div>

        <div
            v-if="gallery.length > 1"
            class="mt-4 grid grid-cols-4 gap-3 sm:grid-cols-6"
        >
            <button
                v-for="(image, index) in gallery"
                :key="image"
                type="button"
                :aria-label="`${watch.name} — ${index + 1}`"
                :aria-pressed="activeImage === image"
                :class="[
                    'aspect-square overflow-hidden rounded-xl border bg-[radial-gradient(circle_at_50%_38%,rgba(251,191,36,0.08),#090909_70%)] p-1 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black',
                    activeImage === image
                        ? 'border-amber-300/70 ring-1 ring-amber-300/30'
                        : 'border-white/10 hover:border-white/30',
                ]"
                @click="selectImage(image)"
            >
                <img
                    :src="image"
                    alt=""
                    aria-hidden="true"
                    loading="lazy"
                    decoding="async"
                    draggable="false"
                    class="h-full w-full rounded-lg object-contain"
                />
            </button>
        </div>

        <div class="mt-4 grid grid-cols-3 gap-3">
            <div class="vvs-choice-card rounded-2xl border p-4">
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                >
                    {{ translations.product.purity }}
                </p>

                <p class="vvs-price mt-2 font-black">VVS</p>
            </div>

            <div class="vvs-choice-card rounded-2xl border p-4">
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                >
                    {{ translations.product.color }}
                </p>

                <p class="vvs-price mt-2 font-black">D</p>
            </div>

            <div class="vvs-choice-card rounded-2xl border p-4">
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-600 uppercase"
                >
                    {{ translations.product.reception }}
                </p>

                <p class="mt-2 text-xs font-black text-amber-200">
                    {{ translations.product.customer_choice }}
                </p>
            </div>
        </div>
    </div>
</template>
