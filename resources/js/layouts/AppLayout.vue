<script setup lang="ts">
import { defineAsyncComponent, onMounted, ref } from 'vue';

import VisualDisclosure from '@/components/VisualDisclosure.vue';

import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();

void breadcrumbs;

const showVvsCursor = ref(false);

const VvsCursor = defineAsyncComponent(
    () => import('@/components/VvsCursor.vue'),
);

onMounted(() => {
    const finePointer = window.matchMedia('(pointer: fine)').matches;
    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    ).matches;

    showVvsCursor.value = finePointer && !reducedMotion;

    window.requestAnimationFrame(() => {
        void import('@/lib/vvsSafeMotion');
    });
});
</script>

<template>
    <slot />

    <VisualDisclosure />

    <!-- CURSEUR GLOBAL VVS FLAWLESS -->
    <VvsCursor v-if="showVvsCursor" />
</template>
