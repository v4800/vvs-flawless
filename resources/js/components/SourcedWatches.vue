<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted } from 'vue';

defineProps({
    models: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);

const formatPrice = (price) => `${Number(price).toFixed(0)} €`;

const matchedWatch = (model) => {
    return (page.props.watches ?? []).find((watch) => watch.image === model.image);
};

const startingPrice = (model) => {
    const watch = matchedWatch(model);

    if (!watch) {
        return null;
    }

    const prices = [
        watch.japanese_promo_price ?? watch.japanese_price,
        watch.swiss_promo_price ?? watch.swiss_price,
    ]
        .map((price) => Number(price))
        .filter((price) => Number.isFinite(price) && price > 0);

    return prices.length ? Math.min(...prices) : null;
};

const startingAtLabel = computed(() => {
    return {
        fr_BE: 'À partir de',
        nl_BE: 'Vanaf',
        en_BE: 'From',
    }[page.props.locale] ?? 'À partir de';
});

const imageClickCleanups = [];

onMounted(() => {
    document.querySelectorAll('#collection .watch-card').forEach((card) => {
        const imageArea = card.querySelector(':scope > div:nth-of-type(2)');
        const productLink = card.querySelector('a.vvs-button-secondary');

        if (!imageArea || !productLink) {
            return;
        }

        const visitProduct = () => {
            const href = productLink.getAttribute('href');

            if (href) {
                router.visit(href);
            }
        };

        const handleClick = (event) => {
            if (event.target.closest('a, button')) {
                return;
            }

            visitProduct();
        };

        const handleKeydown = (event) => {
            if (event.key !== 'Enter' && event.key !== ' ') {
                return;
            }

            event.preventDefault();
            visitProduct();
        };

        imageArea.classList.add('cursor-pointer');
        imageArea.setAttribute('role', 'link');
        imageArea.setAttribute('tabindex', '0');
        imageArea.setAttribute('aria-label', productLink.textContent?.trim() || 'Voir la montre');
        imageArea.addEventListener('click', handleClick);
        imageArea.addEventListener('keydown', handleKeydown);

        imageClickCleanups.push(() => {
            imageArea.removeEventListener('click', handleClick);
            imageArea.removeEventListener('keydown', handleKeydown);
        });
    });
});

onBeforeUnmount(() => {
    imageClickCleanups.splice(0).forEach((cleanup) => cleanup());
});
</script>

<template>
    <section
        v-if="models.length"
        id="catalog-models"
        class="scroll-mt-24 border-y border-white/10 bg-black px-5 py-16 sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1500px]">
            <header class="reveal-on-scroll mx-auto mb-10 max-w-2xl text-center">
                <p class="vvs-eyebrow">
                    {{ translations.collection.source_eyebrow }}
                </p>
                <h2 class="vvs-display-title mt-3 text-4xl sm:text-5xl">
                    {{ translations.collection.source_title }}
                </h2>
                <p class="mt-4 text-sm leading-7 text-zinc-400">
                    {{ translations.collection.source_description }}
                </p>
            </header>

            <div class="grid gap-6 md:grid-cols-2">
                <article
                    v-for="model in models"
                    :key="model.reference"
                    class="watch-card reveal-on-scroll vvs-luxury-card vvs-luxury-card--interactive group relative flex min-w-0 flex-col overflow-hidden rounded-2xl border"
                >
                    <div
                        class="absolute top-0 left-1/2 z-20 h-px w-0 -translate-x-1/2 bg-gradient-to-r from-transparent via-amber-300 to-transparent transition-all duration-500 group-hover:w-[85%]"
                    ></div>

                    <Link
                        v-if="model.watchUrl"
                        :href="model.watchUrl"
                        class="relative block h-[390px] overflow-hidden bg-[radial-gradient(circle_at_50%_35%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_45%,#050505_78%)] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-amber-300"
                        :aria-label="`${translations.collection.source_view} — ${model.name}`"
                    >
                        <img
                            :src="model.cardImage"
                            :srcset="`${model.cardImage} 720w, ${model.image} 1448w`"
                            sizes="(min-width: 768px) 48vw, 100vw"
                            :alt="model.name"
                            width="1448"
                            height="1086"
                            loading="lazy"
                            decoding="async"
                            class="h-full w-full object-contain transition duration-700 group-hover:scale-[1.04]"
                        />
                    </Link>

                    <a
                        v-else
                        href="#contact"
                        class="relative block h-[390px] overflow-hidden bg-[radial-gradient(circle_at_50%_35%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_45%,#050505_78%)] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-amber-300"
                        :aria-label="`${translations.collection.source_request} — ${model.name}`"
                    >
                        <img
                            :src="model.cardImage"
                            :srcset="`${model.cardImage} 720w, ${model.image} 1448w`"
                            sizes="(min-width: 768px) 48vw, 100vw"
                            :alt="model.name"
                            width="1448"
                            height="1086"
                            loading="lazy"
                            decoding="async"
                            class="h-full w-full object-contain transition duration-700 group-hover:scale-[1.04]"
                        />
                    </a>

                    <div class="relative flex flex-1 flex-col p-6">
                        <p
                            class="text-center text-[10px] font-bold tracking-[0.18em] text-amber-200/80 uppercase"
                        >
                            {{ model.reference }}
                        </p>

                        <h3
                            class="vvs-display-title mt-2 min-h-[58px] text-center text-2xl leading-[1.05] text-white"
                        >
                            {{ model.name }}
                        </h3>

                        <p
                            class="mt-2 text-center text-[11px] font-bold tracking-[0.18em] text-amber-300 uppercase"
                        >
                            Moissanite VVS
                        </p>

                        <div class="mt-5 text-center">
                            <template v-if="startingPrice(model)">
                                <p
                                    class="text-[10px] font-bold tracking-[0.18em] text-zinc-500 uppercase"
                                >
                                    {{ startingAtLabel }}
                                </p>
                                <p class="vvs-price mt-1 text-2xl font-black">
                                    {{ formatPrice(startingPrice(model)) }}
                                </p>
                            </template>

                            <p
                                v-else
                                class="text-sm leading-6 text-zinc-400"
                            >
                                {{ translations.collection.source_details }}
                            </p>
                        </div>

                        <div class="mt-auto w-full pt-6">
                            <Link
                                v-if="model.watchUrl"
                                :href="model.watchUrl"
                                class="vvs-button-secondary flex min-h-12 w-full items-center justify-center gap-3 rounded-xl px-4 py-4 text-xs font-bold tracking-[0.12em] uppercase"
                            >
                                {{ translations.collection.view_watch }}
                                <span>→</span>
                            </Link>

                            <a
                                v-else
                                href="#contact"
                                class="vvs-button-secondary flex min-h-12 w-full items-center justify-center gap-3 rounded-xl px-4 py-4 text-xs font-bold tracking-[0.12em] uppercase"
                            >
                                {{ translations.collection.source_request }}
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
