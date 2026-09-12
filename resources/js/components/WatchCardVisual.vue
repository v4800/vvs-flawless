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
        <img
            :src="src"
            :srcset="srcset"
            :sizes="sizes"
            :alt="alt"
            :loading="loading"
            decoding="async"
            class="h-full w-full object-contain object-center p-2 transition-transform duration-500 motion-safe:group-hover:scale-[1.012] sm:p-3"
        />

        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/5 via-transparent to-black/20"
        ></div>
    </div>
</template>
