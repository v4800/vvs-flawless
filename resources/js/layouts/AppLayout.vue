<script setup lang="ts">
import { onMounted } from 'vue';

import VisualDisclosure from '@/components/VisualDisclosure.vue';
import VvsCursor from '@/components/VvsCursor.vue';

import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();

void breadcrumbs;

onMounted(() => {
    window.requestAnimationFrame(() => {
        void import('@/lib/vvsSafeMotion');
    });
});
</script>

<template>
    <slot />

    <VisualDisclosure />

    <!-- CURSEUR GLOBAL VVS FLAWLESS -->
    <VvsCursor />
</template>
