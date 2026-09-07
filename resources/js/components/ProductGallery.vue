<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch as vueWatch } from 'vue';
import StockBadge from '@/components/StockBadge.vue';

const props = defineProps({
    watch: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);

const images = computed(() => {
    const gallery = Array.isArray(props.watch.gallery_images)
        ? props.watch.gallery_images.filter(Boolean)
        : [];

    if (gallery.length > 0) {
        return gallery;
    }

    return props.watch.image ? [props.watch.image] : [];
});

const activeIndex = ref(0);

vueWatch(
    () => props.watch.id,
    () => {
        activeIndex.value = 0;
    },
);

vueWatch(images, (nextImages) => {
    if (activeIndex.value >= nextImages.length) {
        activeIndex.value = 0;
    }
});

const activeImage = computed(() => images.value[activeIndex.value] ?? null);
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
                class="group relative aspect-square self-start overflow-hidden rounded-3xl border border-white/10 bg-zinc-950"
            >
                <img
                    v-if="activeImage"
                    :src="activeImage"
                    :alt="`${watch.name} — vue ${activeIndex + 1}`"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                    class="block h-full w-full object-contain transition duration-500 group-hover:scale-[1.015]"
                />

                <div
                    v-else
                    class="flex h-full items-center justify-center text-sm text-zinc-700"
                >
                    {{ watch.name }}
                </div>

                <div
                    class="pointer-events-none absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-black/75 via-black/20 to-transparent"
                ></div>
            </div>

            <div
                class="absolute right-5 bottom-5 left-5 z-10 flex items-center justify-between rounded-2xl border border-white/10 bg-black/75 px-5 py-4 backdrop-blur-xl"
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

                <div class="h-8 w-px bg-white/10"></div>

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
            v-if="images.length > 1"
            class="mt-4 grid grid-cols-4 gap-2 sm:gap-3"
            :aria-label="`${watch.name} — vues produit`"
        >
            <button
                v-for="(image, index) in images"
                :key="image"
                type="button"
                :aria-label="`${watch.name} — vue ${index + 1}`"
                :aria-pressed="activeIndex === index"
                :class="[
                    'relative aspect-square overflow-hidden rounded-xl border bg-zinc-950 transition',
                    activeIndex === index
                        ? 'border-amber-300/80 shadow-[0_0_24px_rgba(251,191,36,0.10)]'
                        : 'border-white/10 hover:border-white/25',
                ]"
                @click="activeIndex = index"
            >
                <img
                    :src="image"
                    :alt="`${watch.name} — miniature ${index + 1}`"
                    loading="lazy"
                    decoding="async"
                    class="h-full w-full object-cover"
                />

                <span
                    class="absolute right-2 bottom-2 rounded-full border border-white/10 bg-black/70 px-2 py-1 text-[9px] font-black text-white/80 backdrop-blur"
                >
                    {{ index + 1 }}/{{ images.length }}
                </span>
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
