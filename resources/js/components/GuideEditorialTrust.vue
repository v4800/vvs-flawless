<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const editorial = computed(() => page.props.seoIntentContent?.editorial ?? {});

const aboutHref = computed(() => page.props.localizedRoutes?.about ?? null);
</script>

<template>
    <section
        v-if="editorial.author_name"
        class="border-b border-white/10 px-5 py-8 sm:px-6 lg:px-10"
        aria-label="Editorial information"
    >
        <div
            class="mx-auto grid max-w-4xl gap-5 rounded-2xl border border-white/10 bg-white/[0.025] p-5 sm:grid-cols-[0.7fr_0.55fr_1.75fr] sm:p-6"
        >
            <div>
                <p
                    class="text-[10px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                >
                    {{ editorial.author_label }}
                </p>

                <p class="mt-2 text-sm font-semibold text-white">
                    {{ editorial.author_name }}
                </p>

                <Link
                    v-if="aboutHref"
                    :href="aboutHref"
                    class="mt-2 inline-flex text-xs font-semibold text-amber-300 transition hover:text-amber-200"
                >
                    {{ editorial.about_label }} →
                </Link>
            </div>

            <div>
                <p
                    class="text-[10px] font-bold tracking-[0.16em] text-zinc-500 uppercase"
                >
                    {{ editorial.updated_label }}
                </p>

                <time
                    :datetime="editorial.updated_iso"
                    class="mt-2 block text-sm font-semibold text-zinc-200"
                >
                    {{ editorial.updated_display }}
                </time>
            </div>

            <div class="sm:border-l sm:border-white/10 sm:pl-6">
                <h2 class="text-sm font-semibold text-white">
                    {{ editorial.method_title }}
                </h2>

                <p class="mt-2 text-xs leading-6 text-zinc-400">
                    {{ editorial.method_text }}
                </p>

                <p class="mt-3 text-xs leading-6 text-zinc-600">
                    {{ editorial.transparency }}
                </p>
            </div>
        </div>
    </section>
</template>
