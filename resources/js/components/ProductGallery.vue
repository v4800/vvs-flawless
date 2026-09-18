<script setup>
import {
    computed,
    nextTick,
    onBeforeUnmount,
    ref,
} from 'vue';

import StockBadge from '@/components/StockBadge.vue';

const props = defineProps({
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

const thumbnailButtons = ref([]);
const lightboxOpen = ref(false);
const lightboxDialog = ref(null);

const pointerStart = ref(null);
const ignoreNextClick = ref(false);

let previousBodyOverflow = '';
let previousFocusedElement = null;
let swipeResetTimer = null;

const activeIndex = computed(() => {
    const index = props.gallery.indexOf(props.activeImage);

    return index >= 0 ? index : 0;
});

const imagePosition = computed(() => {
    return `${activeIndex.value + 1}/${Math.max(props.gallery.length, 1)}`;
});

const selectImage = (image) => {
    emit('select-image', image);
};

const setThumbnailButton = (element, index) => {
    if (element) {
        thumbnailButtons.value[index] = element;
    }
};

const moveToImage = (index, focusThumbnail = true) => {
    if (!props.gallery.length) {
        return;
    }

    const normalizedIndex =
        (index + props.gallery.length) % props.gallery.length;

    selectImage(props.gallery[normalizedIndex]);

    if (!focusThumbnail) {
        return;
    }

    nextTick(() => {
        thumbnailButtons.value[normalizedIndex]?.focus();
    });
};

const handleThumbnailKeydown = (event, index) => {
    if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
        event.preventDefault();
        moveToImage(index + 1);

        return;
    }

    if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
        event.preventDefault();
        moveToImage(index - 1);

        return;
    }

    if (event.key === 'Home') {
        event.preventDefault();
        moveToImage(0);

        return;
    }

    if (event.key === 'End') {
        event.preventDefault();
        moveToImage(props.gallery.length - 1);
    }
};

const handlePointerDown = (event) => {
    if (event.pointerType === 'mouse' && event.button !== 0) {
        return;
    }

    pointerStart.value = {
        id: event.pointerId,
        x: event.clientX,
        y: event.clientY,
    };
};

const handlePointerCancel = () => {
    pointerStart.value = null;
};

const handlePointerUp = (event) => {
    const start = pointerStart.value;

    pointerStart.value = null;

    if (!start || start.id !== event.pointerId) {
        return;
    }

    const deltaX = event.clientX - start.x;
    const deltaY = event.clientY - start.y;

    const horizontalDistance = Math.abs(deltaX);
    const verticalDistance = Math.abs(deltaY);

    if (
        horizontalDistance < 50 ||
        horizontalDistance <= verticalDistance * 1.15
    ) {
        return;
    }

    ignoreNextClick.value = true;

    if (swipeResetTimer) {
        window.clearTimeout(swipeResetTimer);
    }

    swipeResetTimer = window.setTimeout(() => {
        ignoreNextClick.value = false;
    }, 350);

    moveToImage(
        deltaX < 0 ? activeIndex.value + 1 : activeIndex.value - 1,
        false,
    );
};

const openLightbox = () => {
    if (!props.activeImage || lightboxOpen.value) {
        return;
    }

    if (typeof document !== 'undefined') {
        previousFocusedElement = document.activeElement;
        previousBodyOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
    }

    lightboxOpen.value = true;

    nextTick(() => {
        lightboxDialog.value?.focus();
    });
};

const closeLightbox = () => {
    if (!lightboxOpen.value) {
        return;
    }

    lightboxOpen.value = false;

    if (typeof document !== 'undefined') {
        document.body.style.overflow = previousBodyOverflow;
    }

    nextTick(() => {
        previousFocusedElement?.focus?.();
    });
};

const handleMainImageClick = () => {
    if (ignoreNextClick.value) {
        ignoreNextClick.value = false;

        return;
    }

    openLightbox();
};

onBeforeUnmount(() => {
    if (swipeResetTimer) {
        window.clearTimeout(swipeResetTimer);
    }

    if (typeof document !== 'undefined' && lightboxOpen.value) {
        document.body.style.overflow = previousBodyOverflow;
    }
});
</script>

<template>
    <div
        class="mx-auto w-full min-w-0 max-w-[520px] lg:max-w-none"
    >
        <div class="relative">
            <div class="absolute top-3 left-3 z-20 sm:top-5 sm:left-5">
                <StockBadge
                    :quantity="watch.stock_quantity"
                    :availability="watch.availability"
                />
            </div>

            <div
                id="product-gallery-panel"
                role="tabpanel"
                tabindex="0"
                :aria-labelledby="
                    gallery.length > 1
                        ? `product-gallery-tab-${activeIndex}`
                        : undefined
                "
                :aria-label="`${watch.name} — agrandir l’image`"
                aria-live="polite"
                aria-atomic="true"
                class="relative aspect-[3/4] w-full max-w-full touch-pan-y self-start overflow-hidden rounded-2xl border border-white/10 bg-[radial-gradient(circle_at_50%_38%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_48%,#050505_80%)] select-none sm:rounded-3xl lg:max-h-none"
                @pointerdown="handlePointerDown"
                @pointerup="handlePointerUp"
                @pointercancel="handlePointerCancel"
                @click="handleMainImageClick"
                @keydown.enter.prevent="openLightbox"
                @keydown.space.prevent="openLightbox"
            >
                <Transition
                    enter-active-class="transition-[opacity,transform] duration-300 ease-out motion-reduce:transition-none"
                    enter-from-class="opacity-0 scale-[1.01]"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-[opacity,transform] duration-200 ease-in motion-reduce:transition-none"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-[0.995]"
                >
                    <img
                    :key="activeImage"
                    :src="activeImage"
                    :alt="`${watch.name} — ${imagePosition}`"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                    draggable="false"
                    class="absolute inset-0 block h-full w-full max-w-full cursor-zoom-in object-contain object-center p-3 sm:p-4"
                />
                </Transition>

                <button
                    v-if="gallery.length > 1"
                    type="button"
                    :aria-label="
                        translations.product.previous_image ??
                        'Image précédente'
                    "
                    class="absolute top-1/2 left-3 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-black/65 text-2xl text-white backdrop-blur-sm transition hover:border-amber-300/40 hover:bg-black/90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300 sm:left-4"
                    @pointerdown.stop
                    @click.stop="moveToImage(activeIndex - 1, false)"
                >
                    <span aria-hidden="true">←</span>
                </button>

                <button
                    v-if="gallery.length > 1"
                    type="button"
                    :aria-label="
                        translations.product.next_image ??
                        'Image suivante'
                    "
                    class="absolute top-1/2 right-3 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-black/65 text-2xl text-white backdrop-blur-sm transition hover:border-amber-300/40 hover:bg-black/90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300 sm:right-4"
                    @pointerdown.stop
                    @click.stop="moveToImage(activeIndex + 1, false)"
                >
                    <span aria-hidden="true">→</span>
                </button>
            </div>

            <div
                class="mt-3 flex min-w-0 items-center justify-between rounded-2xl border border-white/10 bg-black/75 px-4 py-3 sm:px-5 sm:py-4"
            >
                <div class="min-w-0">
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-400 uppercase"
                    >
                        {{ translations.product.stone }}
                    </p>

                    <p class="mt-1 text-sm font-bold text-white">
                        Moissanite VVS
                    </p>
                </div>

                <div
                    aria-hidden="true"
                    class="mx-3 h-8 w-px shrink-0 bg-white/10"
                ></div>

                <div class="min-w-0 text-right">
                    <p
                        class="text-[9px] font-black tracking-[0.25em] text-zinc-400 uppercase"
                    >
                        {{ translations.product.color }}
                    </p>

                    <p class="mt-1 text-sm font-bold text-amber-200">D</p>
                </div>
            </div>
        </div>

        <div
            v-if="gallery.length > 1"
            role="tablist"
            :aria-label="watch.name"
            class="mt-3 grid min-w-0 grid-cols-4 gap-2 sm:mt-4 sm:grid-cols-6 sm:gap-3"
        >
            <button
                v-for="(image, index) in gallery"
                :id="`product-gallery-tab-${index}`"
                :key="image"
                :ref="(element) => setThumbnailButton(element, index)"
                type="button"
                role="tab"
                :aria-label="`${watch.name} — ${index + 1}/${gallery.length}`"
                :aria-selected="activeImage === image"
                aria-controls="product-gallery-panel"
                :tabindex="activeImage === image ? 0 : -1"
                :class="[
                    'aspect-square min-w-0 overflow-hidden rounded-xl border bg-[radial-gradient(circle_at_50%_38%,rgba(251,191,36,0.08),#090909_70%)] transition focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none',
                    activeImage === image
                        ? 'border-amber-300/70 ring-1 ring-amber-300/30'
                        : 'border-white/10 hover:border-white/30',
                ]"
                @click="selectImage(image)"
                @keydown="handleThumbnailKeydown($event, index)"
            >
                <img
                    :src="image"
                    alt=""
                    aria-hidden="true"
                    loading="lazy"
                    decoding="async"
                    draggable="false"
                    class="block h-full w-full object-contain object-center p-1"
                />
            </button>
        </div>

        <div
            class="mt-3 grid min-w-0 grid-cols-1 gap-2 min-[360px]:grid-cols-2 sm:mt-4 sm:grid-cols-3 sm:gap-3"
        >
            <div
                class="vvs-choice-card min-w-0 rounded-2xl border p-3 sm:p-4"
            >
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-400 uppercase"
                >
                    {{ translations.product.purity }}
                </p>

                <p class="vvs-price mt-2 font-black">VVS</p>
            </div>

            <div
                class="vvs-choice-card min-w-0 rounded-2xl border p-3 sm:p-4"
            >
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-400 uppercase"
                >
                    {{ translations.product.color }}
                </p>

                <p class="vvs-price mt-2 font-black">D</p>
            </div>

            <div
                class="vvs-choice-card min-w-0 rounded-2xl border p-3 min-[360px]:col-span-2 sm:col-span-1 sm:p-4"
            >
                <p
                    class="text-[9px] font-black tracking-[0.2em] text-zinc-400 uppercase"
                >
                    {{ translations.product.reception }}
                </p>

                <p class="mt-2 text-xs font-black text-amber-200">
                    {{ translations.product.customer_choice }}
                </p>
            </div>
        </div>

        <Teleport to="body">
            <div
                v-if="lightboxOpen"
                ref="lightboxDialog"
                role="dialog"
                aria-modal="true"
                tabindex="-1"
                :aria-label="`${watch.name} — image agrandie`"
                class="fixed inset-0 z-[100] flex min-w-0 items-center justify-center overflow-hidden bg-black/95 p-2 outline-none sm:p-6"
                @click.self="closeLightbox"
                @keydown.esc.stop.prevent="closeLightbox"
            >
                <button
                    type="button"
                    aria-label="Fermer l’image agrandie"
                    class="absolute top-4 right-4 z-20 flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-black/75 text-2xl text-white transition hover:border-amber-300/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
                    @click="closeLightbox"
                >
                    <span aria-hidden="true">×</span>
                </button>

                <button
                    v-if="gallery.length > 1"
                    type="button"
                    :aria-label="
                        translations.product.previous_image ??
                        'Image précédente'
                    "
                    class="absolute top-1/2 left-3 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-black/75 text-2xl text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300 sm:left-6"
                    @click.stop="moveToImage(activeIndex - 1, false)"
                >
                    <span aria-hidden="true">←</span>
                </button>

                <img
                    :src="activeImage"
                    :alt="`${watch.name} — ${imagePosition}`"
                    draggable="false"
                    class="block max-h-[calc(100dvh-1rem)] max-w-[calc(100vw-1rem)] object-contain sm:max-h-[calc(100dvh-4rem)] sm:max-w-[calc(100vw-6rem)]"
                    @click.stop
                />

                <button
                    v-if="gallery.length > 1"
                    type="button"
                    :aria-label="
                        translations.product.next_image ??
                        'Image suivante'
                    "
                    class="absolute top-1/2 right-3 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-black/75 text-2xl text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300 sm:right-6"
                    @click.stop="moveToImage(activeIndex + 1, false)"
                >
                    <span aria-hidden="true">→</span>
                </button>
            </div>
        </Teleport>
    </div>
</template>
