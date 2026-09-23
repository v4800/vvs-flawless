<script setup>
import PresentedWatchOffer from '@/components/PresentedWatchOffer.vue';
import CustomerConfidence from '@/components/CustomerConfidence.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, nextTick, ref, watch as vueWatch } from 'vue';
import MobileReservationBar from '@/components/MobileReservationBar.vue';
import MoissaniteQualitySection from '@/components/MoissaniteQualitySection.vue';
import ProductConfiguration from '@/components/ProductConfiguration.vue';
import ProductGallery from '@/components/ProductGallery.vue';
import ProductReviews from '@/components/ProductReviews.vue';
import ProductPurchaseExperience from '@/components/ProductPurchaseExperience.vue';
import ProductReservationSection from '@/components/ProductReservationSection.vue';
import ProductSpecs from '@/components/ProductSpecs.vue';
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
    reviews: {
        type: Array,
        default: () => [],
    },
    reviewRoutes: {
        type: Object,
        required: true,
    },
    seo: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const localizedRoutes = page.props.localizedRoutes;
const translations = computed(() => page.props.translations);

const reserveLabel = computed(() => translations.value.product.reserve_watch);

const singleOfferPrice = computed(() => {
    const price = Number(props.watch.price);

    if (!Number.isFinite(price) || price <= 0) {
        return '—';
    }

    const locale =
        {
            fr_BE: 'fr-BE',
            nl_BE: 'nl-BE',
            en_BE: 'en-BE',
            de_BE: 'de-BE',
        }[page.props.locale] ?? 'fr-BE';

    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: 'EUR',
        maximumFractionDigits: 0,
    }).format(price);
});
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
    props.watch.single_offer
        ? props.watch.presented_copy.model_label
        : form.movement === 'Suisse'
          ? translations.value.movements.suisse
          : translations.value.movements.japonais,
);

vueWatch(
    () => props.selectedMovement,
    (movement) => {
        form.movement = props.watch.single_offer
            ? 'Modele presente'
            : movement === 'Suisse'
              ? 'Suisse'
              : 'Japonais';
    },
    {
        immediate: true,
    },
);

vueWatch(
    () => props.watch.id,
    (watchId) => {
        form.watch_id = watchId;
        form.movement = props.watch.single_offer
            ? 'Modele presente'
            : props.selectedMovement === 'Suisse'
              ? 'Suisse'
              : 'Japonais';
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
    if (props.watch.single_offer) return Number(props.watch.price);
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
    if (props.watch.single_offer) return 0;
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

    <div
        class="vvs-storefront min-h-screen w-full max-w-full overflow-x-clip overscroll-x-none pb-24 text-white lg:pb-0"
    >
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

        <main
            id="main-content"
            tabindex="-1"
            class="w-full max-w-full min-w-0 overflow-x-clip"
        >
            <section
                id="model"
                class="relative scroll-mt-28 overflow-hidden px-4 pt-8 pb-16 sm:px-6 sm:pt-10 sm:pb-20 lg:px-10 lg:pt-14"
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
                    class="mx-auto grid w-full max-w-[1400px] min-w-0 grid-cols-[minmax(0,1fr)] gap-8 sm:gap-10 lg:grid-cols-[minmax(0,1.04fr)_minmax(0,0.96fr)] lg:gap-16"
                >
                    <ProductGallery
                        :watch="watch"
                        :gallery="gallery"
                        :active-image="activeImage"
                        :translations="translations"
                        @select-image="activeImage = $event"
                    />

                    <PresentedWatchOffer
                        v-if="watch.single_offer"
                        :watch="watch"
                    />
                    <ProductConfiguration
                        v-else
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

            <ProductSpecs
                v-if="!watch.single_offer"
                :watch="watch"
                :translations="translations"
            />

            <MoissaniteQualitySection />

            <ProductPurchaseExperience
                :watch="watch"
                :movement="localizedMovement"
                :selected-price="selectedPrice"
            />
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

            <ProductReviews
                :watch="watch"
                :reviews="reviews"
                :review-routes="reviewRoutes"
            />

            <RelatedWatches :watches="relatedWatches" />
            <CustomerConfidence />
        </main>

        <footer
            class="min-w-0 border-t border-white/10 px-4 py-8 sm:px-6 sm:py-9"
        >
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

        <a
            v-if="watch.single_offer"
            href="#reservation"
            class="fixed inset-x-3 bottom-3 z-40 min-w-0 rounded-xl bg-amber-300 p-3.5 text-center text-sm font-bold text-black sm:inset-x-4 sm:bottom-4 sm:p-4 lg:hidden"
        >
            {{ singleOfferPrice }} · {{ reserveLabel }}
        </a>
        <MobileReservationBar
            v-else
            :movement="form.movement"
            :price="selectedPrice"
        />
    </div>
</template>
