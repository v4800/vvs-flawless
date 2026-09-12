<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, nextTick, ref, watch as vueWatch } from 'vue';
import MobileReservationBar from '@/components/MobileReservationBar.vue';
import ProductConfiguration from '@/components/ProductConfiguration.vue';
import ProductGallery from '@/components/ProductGallery.vue';
import ProductReservationSection from '@/components/ProductReservationSection.vue';
import PurchaseGuide from '@/components/PurchaseGuide.vue';
import RelatedWatches from '@/components/RelatedWatches.vue';
import VvsNavigation from '@/components/VvsNavigation.vue';

const props = defineProps({
    watch: {
        type: Object,
        required: true,
    },
    gallery: {
        type: Array,
        default: () => [],
    },
    selectedMovement: {
        type: String,
        default: 'Japonais',
    },
    relatedWatches: {
        type: Array,
        default: () => [],
    },
    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const localizedRoutes = page.props.localizedRoutes;
const translations = computed(() => page.props.translations);
const activeImage = ref(props.gallery[0] ?? props.watch.image);

const form = useForm({
    watch_id: props.watch.id,
    movement: props.selectedMovement === 'Suisse' ? 'Suisse' : 'Japonais',
    customer_name: '',
    email: '',
    phone: '',
    city: '',
    delivery_method: 'Remise en main propre',
    message: '',
    confirmation: false,
});

const deliveryOptions = computed(() => [
    {
        value: 'Remise en main propre',
        label: translations.value.product.handover,
        description: translations.value.product.handover_description,
    },
    {
        value: 'Livraison',
        label: translations.value.product.delivery,
        description: translations.value.product.delivery_description,
    },
]);

const localizedMovement = computed(() =>
    form.movement === 'Suisse'
        ? translations.value.movements.suisse
        : translations.value.movements.japonais,
);

vueWatch(
    () => props.selectedMovement,
    (movement) => {
        form.movement = movement === 'Suisse' ? 'Suisse' : 'Japonais';
    },
    {
        immediate: true,
    },
);

vueWatch(
    () => props.watch.id,
    (watchId) => {
        form.watch_id = watchId;
        activeImage.value = props.gallery[0] ?? props.watch.image;
    },
);

vueWatch(
    () => props.gallery,
    (gallery) => {
        activeImage.value = gallery[0] ?? props.watch.image;
    },
);

const selectedPrice = computed(() => {
    if (form.movement === 'Suisse') {
        return Number(
            props.watch.swiss_promo_price ?? props.watch.swiss_price ?? 0,
        );
    }

    return Number(
        props.watch.japanese_promo_price ?? props.watch.japanese_price ?? 0,
    );
});

const selectedOldPrice = computed(() => {
    if (form.movement === 'Suisse') {
        return Number(props.watch.swiss_price ?? 0);
    }

    return Number(props.watch.japanese_price ?? 0);
});

const focusFirstInvalidField = async () => {
    await nextTick();

    const invalidField = document.querySelector(
        'input[aria-invalid="true"], textarea[aria-invalid="true"], select[aria-invalid="true"], [aria-invalid="true"] input, [aria-invalid="true"] textarea, [aria-invalid="true"] select',
    );

    if (!(invalidField instanceof HTMLElement)) {
        return;
    }

    invalidField.focus({ preventScroll: true });
    invalidField.scrollIntoView({
        block: 'center',
        behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches
            ? 'auto'
            : 'smooth',
    });
};

const submit = () => {
    form.post(localizedRoutes.reservationStore, {
        preserveScroll: false,
        onError: focusFirstInvalidField,
    });
};
</script>

<template>
    <Head :title="seo.title" />

    <div class="vvs-storefront min-h-screen pb-24 text-white lg:pb-0">
        <a
            href="#main-content"
            class="sr-only z-[100] rounded-lg bg-amber-300 px-4 py-3 font-bold text-black focus:not-sr-only focus:fixed focus:top-4 focus:left-4"
        >
            {{ translations.accessibility.skip_content }}
        </a>

        <VvsNavigation
            current="watch"
            :back-label="translations.vvs_navigation.collection"
            :watch-href="`${localizedRoutes.watches}/${watch.slug}`"
        />

        <main id="main-content" tabindex="-1">
            <section
                id="model"
                class="relative scroll-mt-28 overflow-hidden px-5 pt-10 pb-20 sm:px-6 lg:px-10 lg:pt-14"
            >
                <div
                    aria-hidden="true"
                    class="pointer-events-none absolute top-20 -left-40 -z-10 h-[500px] w-[500px] rounded-full bg-amber-400/[0.05] blur-[150px]"
                ></div>

                <div
                    aria-hidden="true"
                    class="pointer-events-none absolute top-0 right-0 -z-10 h-[650px] w-[650px] rounded-full bg-white/[0.025] blur-[160px]"
                ></div>

                <div
                    class="mx-auto grid max-w-[1400px] gap-10 lg:grid-cols-[1.04fr_0.96fr] lg:gap-16"
                >
                    <ProductGallery
                        :watch="watch"
                        :gallery="gallery"
                        :active-image="activeImage"
                        :translations="translations"
                        @select-image="activeImage = $event"
                    />

                    <ProductConfiguration
                        :watch="watch"
                        :translations="translations"
                        :localized-routes="localizedRoutes"
                        :movement="form.movement"
                        :localized-movement="localizedMovement"
                        :selected-price="selectedPrice"
                        :selected-old-price="selectedOldPrice"
                    />
                </div>
            </section>

            <section
                class="border-y border-white/10 bg-zinc-950/60 px-5 py-6 sm:px-6 lg:px-10"
            >
                <div
                    class="mx-auto grid max-w-[1400px] gap-6 text-center sm:grid-cols-3"
                >
                    <div>
                        <p
                            class="text-xs font-black tracking-[0.18em] text-amber-200 uppercase"
                        >
                            Moissanite VVS
                        </p>

                        <p class="mt-2 text-xs text-zinc-400">
                            {{ translations.product.sparkle_text }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs font-black tracking-[0.18em] text-amber-200 uppercase"
                        >
                            {{ translations.product.color }} D
                        </p>

                        <p class="mt-2 text-xs text-zinc-400">
                            {{ translations.product.color_render }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs font-black tracking-[0.18em] text-amber-200 uppercase"
                        >
                            {{ translations.product.reception_choice }}
                        </p>

                        <p class="mt-2 text-xs text-zinc-400">
                            {{ translations.product.handover_or_delivery }}
                        </p>
                    </div>
                </div>
            </section>

            <PurchaseGuide />

            <ProductReservationSection
                :watch="watch"
                :active-image="activeImage"
                :localized-movement="localizedMovement"
                :selected-price="selectedPrice"
                :form="form"
                :delivery-options="deliveryOptions"
                :translations="translations"
                @submit="submit"
            />

            <RelatedWatches :watches="relatedWatches" />
        </main>

        <footer class="border-t border-white/10 px-6 py-9">
            <div
                class="mx-auto flex max-w-[1400px] flex-col gap-5 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left"
            >
                <div>
                    <p class="text-sm font-black tracking-[0.12em] uppercase">
                        VVS FLAWLESS
                    </p>

                    <p class="mt-1 text-xs text-zinc-400">
                        {{ translations.product.footer_material }}
                    </p>
                </div>

                <div
                    class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 sm:justify-end"
                >
                    <Link
                        :href="localizedRoutes.privacy"
                        class="rounded text-[10px] font-bold tracking-[0.1em] text-zinc-400 uppercase transition hover:text-amber-300 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ translations.footer.privacy }}
                    </Link>

                    <Link
                        :href="localizedRoutes.reservationTerms"
                        class="rounded text-[10px] font-bold tracking-[0.1em] text-zinc-400 uppercase transition hover:text-amber-300 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ translations.footer.terms }}
                    </Link>

                    <Link
                        :href="localizedRoutes.about"
                        class="rounded text-[10px] font-bold tracking-[0.1em] text-zinc-400 uppercase transition hover:text-amber-300 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ translations.navigation.about }}
                    </Link>

                    <Link
                        :href="localizedRoutes.diamondGuide"
                        class="rounded text-[10px] font-bold tracking-[0.1em] text-zinc-400 uppercase transition hover:text-amber-300 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ page.props.guideLinks.eyebrow }}
                    </Link>

                    <Link
                        :href="localizedRoutes.watches"
                        class="rounded text-[10px] font-bold tracking-[0.1em] text-zinc-400 uppercase transition hover:text-amber-300 focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:outline-none"
                    >
                        {{ translations.footer.collection }}
                    </Link>
                </div>
            </div>
        </footer>

        <MobileReservationBar
            :movement="form.movement"
            :price="selectedPrice"
        />
    </div>
</template>
