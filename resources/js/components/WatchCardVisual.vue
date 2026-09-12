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
            alt=""
            aria-hidden="true"
            loading="lazy"
            decoding="async"
            class="absolute inset-0 h-full w-full scale-110 object-cover opacity-40 blur-2xl saturate-75"
        />

        <div
            aria-hidden="true"
            class="absolute inset-0 bg-gradient-to-b from-black/15 via-transparent to-black/35"
        ></div>

        <img
            :src="src"
            :srcset="srcset"
            :sizes="sizes"
            :alt="alt"
            :loading="loading"
            decoding="async"
            class="relative h-full w-full object-contain transition-transform duration-500 motion-safe:group-hover:scale-[1.015]"
        />
    </div>
</template>
