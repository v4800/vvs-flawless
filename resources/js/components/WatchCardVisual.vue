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
    <div class="relative w-full overflow-hidden bg-[#070707]">
        <img
            :src="src"
            :srcset="srcset"
            :sizes="sizes"
            :alt="alt"
            :loading="loading"
            decoding="async"
            class="block h-auto w-full"
        />
    </div>
</template>
