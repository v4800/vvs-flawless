<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import VvsCursor from '@/components/VvsCursor.vue';

import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>

    <!-- CURSEUR GLOBAL VVS FLAWLESS -->
    <VvsCursor />
</template>
