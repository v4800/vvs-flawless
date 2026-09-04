<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import VvsNavigation from '@/components/VvsNavigation.vue';

const props = defineProps({
    reservation: {
        type: Object,
        required: true,
    },

    watch: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const localizedRoutes = page.props.localizedRoutes;
const translations = computed(() => page.props.translations);
const locale = computed(() =>
    page.props.locale === 'nl_BE' ? 'nl-BE' : 'fr-BE',
);

const localizedMovement = computed(() =>
    props.reservation.movement === 'Suisse'
        ? translations.value.movements.suisse
        : translations.value.movements.japonais,
);

const localizedDeliveryMethod = computed(() =>
    props.reservation.delivery_method === 'Livraison'
        ? translations.value.product.delivery
        : translations.value.product.handover,
);

const formatPrice = (price) => {
    return `${Number(price).toLocaleString(locale.value)} €`;
};
</script>

<template>
    <VvsNavigation
        current="reservation"
        :back-href="`${localizedRoutes.watches}/${watch.id}`"
        :back-label="translations.confirmation.view_watch"
        :watch-href="`${localizedRoutes.watches}/${watch.id}`"
    />
    <Head :title="translations.confirmation.title" />

    <main
        class="relative min-h-screen overflow-hidden bg-black px-5 py-12 text-white sm:px-6"
    >
        <!-- HALO -->
        <div
            class="pointer-events-none absolute top-0 left-1/2 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-amber-400/[0.06] blur-[140px]"
        ></div>

        <div class="relative z-10 mx-auto max-w-4xl">
            <!-- CONFIRMATION -->
            <div class="mb-8 text-center">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-amber-300/40 bg-amber-300/[0.08] text-2xl font-black text-amber-200"
                >
                    ✓
                </div>

                <p
                    class="mt-6 text-[11px] font-black tracking-[0.35em] text-amber-300 uppercase"
                >
                    VVS FLAWLESS
                </p>

                <h1 class="mt-3 text-3xl font-black uppercase sm:text-4xl">
                    {{ translations.confirmation.recorded }}
                </h1>

                <p
                    class="mx-auto mt-3 max-w-xl text-sm leading-6 text-zinc-400"
                >
                    {{ translations.confirmation.intro }}
                </p>
            </div>

            <!-- BON -->
            <section
                class="overflow-hidden rounded-3xl border border-amber-300/20 bg-zinc-950 shadow-[0_30px_100px_rgba(0,0,0,0.6)]"
            >
                <!-- EN-TÊTE -->
                <div
                    class="flex flex-col gap-6 border-b border-white/10 bg-black px-7 py-7 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p
                            class="text-xl font-black tracking-[0.08em] uppercase"
                        >
                            VVS FLAWLESS
                        </p>

                        <p class="mt-1 text-xs text-amber-300">
                            {{ translations.footer.tagline }}
                        </p>
                    </div>

                    <div class="sm:text-right">
                        <p
                            class="text-[10px] font-bold tracking-[0.25em] text-zinc-500 uppercase"
                        >
                            {{ translations.confirmation.summary }}
                        </p>

                        <p class="mt-1 text-lg font-black text-amber-200">
                            {{ reservation.reservation_number }}
                        </p>

                        <p class="mt-1 text-xs text-zinc-600">
                            {{ reservation.date }}
                        </p>
                    </div>
                </div>

                <!-- MONTRE -->
                <div
                    class="grid border-b border-white/10 md:grid-cols-[280px_1fr]"
                >
                    <div class="flex items-center justify-center bg-zinc-900">
                        <img
                            :src="watch.image"
                            :alt="watch.name"
                            class="max-h-80 w-full object-contain"
                        />
                    </div>

                    <div class="p-7">
                        <p
                            class="text-[10px] font-black tracking-[0.25em] text-amber-300 uppercase"
                        >
                            {{ translations.confirmation.your_watch }}
                        </p>

                        <h2 class="mt-3 text-2xl leading-tight font-black">
                            {{ watch.name }}
                        </h2>

                        <div class="mt-7 grid gap-6 sm:grid-cols-2">
                            <div>
                                <p class="text-xs text-zinc-600">
                                    {{ translations.product.movement }}
                                </p>

                                <p class="mt-1 font-semibold">
                                    {{ localizedMovement }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-zinc-600">
                                    {{
                                        translations.confirmation.reserved_price
                                    }}
                                </p>

                                <p
                                    class="mt-1 text-3xl font-black text-amber-200"
                                >
                                    {{ formatPrice(reservation.price) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CLIENT -->
                <div class="p-7 sm:p-8">
                    <h3
                        class="text-xs font-black tracking-[0.25em] text-zinc-500 uppercase"
                    >
                        {{ translations.confirmation.customer_information }}
                    </h3>

                    <div class="mt-5 grid gap-x-12 gap-y-5 sm:grid-cols-2">
                        <div>
                            <p class="text-xs text-zinc-600">
                                {{ translations.product.full_name }}
                            </p>

                            <p class="mt-1 font-semibold">
                                {{ reservation.customer_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-zinc-600">
                                {{ translations.product.email }}
                            </p>

                            <p class="mt-1 font-semibold">
                                {{ reservation.email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-zinc-600">
                                {{ translations.product.phone }}
                            </p>

                            <p class="mt-1 font-semibold">
                                {{ reservation.phone }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-zinc-600">
                                {{ translations.product.city }}
                            </p>

                            <p class="mt-1 font-semibold">
                                {{
                                    reservation.city ||
                                    translations.confirmation.not_provided
                                }}
                            </p>
                        </div>
                    </div>

                    <div class="my-7 border-t border-white/10"></div>

                    <!-- LIVRAISON -->
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-xs text-zinc-600">
                                {{ translations.product.reception_method }}
                            </p>

                            <p class="mt-1 font-semibold">
                                {{ localizedDeliveryMethod }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-zinc-600">
                                {{ translations.confirmation.status }}
                            </p>

                            <span
                                class="mt-1 inline-flex rounded-full border border-amber-300/20 bg-amber-300/[0.05] px-3 py-1 text-xs font-bold text-amber-200"
                            >
                                {{ translations.confirmation.new_request }}
                            </span>
                        </div>
                    </div>

                    <!-- MESSAGE -->
                    <div
                        v-if="reservation.message"
                        class="mt-7 rounded-2xl border border-white/10 bg-black/40 p-5"
                    >
                        <p
                            class="text-xs font-bold tracking-[0.18em] text-zinc-500 uppercase"
                        >
                            {{ translations.product.message }}
                        </p>

                        <p class="mt-3 text-sm leading-6 text-zinc-300">
                            {{ reservation.message }}
                        </p>
                    </div>

                    <!-- SUITE -->
                    <div
                        class="mt-7 rounded-2xl border border-amber-300/20 bg-amber-300/[0.04] p-5"
                    >
                        <p class="font-bold text-amber-200">
                            {{ translations.confirmation.next_title }}
                        </p>

                        <p class="mt-2 text-sm leading-6 text-zinc-400">
                            {{ translations.confirmation.next_text }}
                        </p>

                        <p class="mt-3 text-xs text-zinc-600">
                            {{ translations.confirmation.legal_note }}
                        </p>
                    </div>

                    <!-- BOUTONS -->
                    <div class="mt-8 grid gap-3 sm:grid-cols-2">
                        <Link
                            :href="localizedRoutes.watches"
                            class="flex items-center justify-center rounded-xl bg-amber-300 px-5 py-4 text-xs font-black tracking-[0.12em] text-black uppercase transition hover:bg-amber-200"
                        >
                            {{ translations.confirmation.back_collection }}
                        </Link>

                        <Link
                            :href="`${localizedRoutes.watches}/${watch.id}?movement=${reservation.movement}`"
                            class="flex items-center justify-center rounded-xl border border-amber-300/30 px-5 py-4 text-xs font-black tracking-[0.12em] text-amber-200 uppercase transition hover:bg-amber-300/[0.05]"
                        >
                            {{ translations.confirmation.review_watch }}
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </main>
</template>
