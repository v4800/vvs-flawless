<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    items: {
        type: Array,
        default: () => [],
    },

    hasFaq: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();

const copy = computed(() => page.props.seoIntentContent?.editorial ?? {});
</script>

<template>
    <nav
        v-if="items.length || hasFaq"
        class="border-b border-white/10 px-5 py-5 sm:px-6 lg:px-10"
        :aria-label="copy.contents_title ?? 'Guide'"
    >
        <div class="mx-auto max-w-4xl">
            <p
                class="text-[10px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
            >
                {{ copy.contents_title ?? 'Guide' }}
            </p>

            <div
                class="mt-3 flex [scrollbar-width:none] gap-2 overflow-x-auto pb-1 [&::-webkit-scrollbar]:hidden"
            >
                <a
                    v-for="item in items"
                    :key="item.id"
                    :href="`#${item.id}`"
                    class="shrink-0 rounded-full border border-white/10 px-4 py-2 text-xs font-semibold text-zinc-300 transition hover:border-amber-300/30 hover:text-amber-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
                >
                    {{ item.label }}
                </a>

                <a
                    v-if="hasFaq"
                    href="#guide-faq"
                    class="shrink-0 rounded-full border border-amber-300/20 bg-amber-300/[0.04] px-4 py-2 text-xs font-semibold text-amber-200 transition hover:border-amber-300/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
                >
                    {{ copy.faq_link ?? 'FAQ' }}
                </a>
            </div>
        </div>
    </nav>
</template>
