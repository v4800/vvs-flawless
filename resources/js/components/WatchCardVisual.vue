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
        class="relative aspect-[3/4] w-full overflow-hidden bg-[radial-gradient(circle_at_50%_42%,rgba(251,191,36,0.07),rgba(7,7,7,0.98)_62%,#050505_100%)]"
    >
        <img
            :src="src"
            :srcset="srcset"
            :sizes="sizes"
            :alt="alt"
            :loading="loading"
            decoding="async"
            class="absolute inset-0 block h-full w-full object-contain object-center p-1.5 sm:p-2"
        />
    </div>
</template>