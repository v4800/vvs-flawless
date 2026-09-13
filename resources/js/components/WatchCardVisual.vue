<script setup>
defineProps({
    src: {
        type: String,
        required: true,
    },
    srcset: {
        type: String,
        default: undefined,
    },
    sizes: {
        type: String,
        default: undefined,
    },
    alt: {
        type: String,
        default: '',
    },
    loading: {
        type: String,
        default: 'lazy',
    },
});
</script>

<template>
    <div
        class="absolute inset-0 overflow-hidden bg-[radial-gradient(circle_at_50%_42%,rgba(251,191,36,0.08),#080706_54%,#050505_100%)]"
    >
        <!--
            The enlarged background fills wide source images without forcing the
            watch itself to be cropped. The dark overlays keep every card in the
            same black/gold visual system, even when the source has coloured bars.
        -->
        <img
            :src="src"
            alt=""
            aria-hidden="true"
            decoding="async"
            class="pointer-events-none absolute inset-0 h-full w-full scale-110 object-cover object-center opacity-30 blur-xl saturate-75"
        />
        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 bg-black/55"
        ></div>
        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_50%_45%,transparent_0%,rgba(0,0,0,0.18)_58%,rgba(0,0,0,0.72)_100%)]"
        ></div>

        <!--
            Keep the complete watch visible and at a consistent distance from the
            edges. The soft side mask blends embedded 16:9 bars into the card.
        -->
        <img
            :src="src"
            :srcset="srcset"
            :sizes="sizes"
            :alt="alt"
            :loading="loading"
            decoding="async"
            class="watch-card-visual__product absolute inset-0 z-[1] h-full w-full object-contain object-center p-3 transition-transform duration-500 motion-safe:group-hover:scale-[1.012] sm:p-4"
        />

        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 z-[2] bg-gradient-to-b from-black/5 via-transparent to-black/25"
        ></div>
    </div>
</template>

<style scoped>
.watch-card-visual__product {
    -webkit-mask-image: linear-gradient(
        to right,
        transparent 0,
        #000 4%,
        #000 96%,
        transparent 100%
    );
    mask-image: linear-gradient(
        to right,
        transparent 0,
        #000 4%,
        #000 96%,
        transparent 100%
    );
}
</style>
