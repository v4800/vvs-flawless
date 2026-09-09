<script setup lang="ts">
import VisualDisclosure from '@/components/VisualDisclosure.vue';
import VvsCursor from '@/components/VvsCursor.vue';

import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();

void breadcrumbs;
</script>

<template>
    <slot />

    <VisualDisclosure />

    <!-- CURSEUR GLOBAL VVS FLAWLESS -->
    <VvsCursor />
</template>
