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
    <div class="absolute inset-0 overflow-hidden bg-[#070707]">
        <img
            :src="src"
            :srcset="srcset"
            :sizes="sizes"
            :alt="alt"
            :loading="loading"
            decoding="async"
            class="h-full w-full object-cover object-center transition-transform duration-500 motion-safe:group-hover:scale-[1.015]"
        />

        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/5 via-transparent to-black/20"
        ></div>
    </div>
</template>
