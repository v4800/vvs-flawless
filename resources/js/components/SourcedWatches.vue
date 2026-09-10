<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted } from 'vue';

const props = defineProps({
    models: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);

/*
 * Un modèle déjà relié à une fiche produit existe déjà dans props.watches.
 * On ne le réaffiche donc pas dans le catalogue pour éviter les doublons
 * (notamment la montre bleue).
 */
const displayModels = computed(() =>
    props.models.filter((model) => !model.watchUrl),
);

const requestPriceLabel = computed(() => {
    return {
        fr_BE: 'Prix sur demande',
        nl_BE: 'Prijs op aanvraag',
        en_BE: 'Price on request',
    }[page.props.locale] ?? 'Prix sur demande';
});

const imageClickCleanups = [];

/*
 * Les cartes produit historiques restent dans Index.vue.
 * On rend leur zone image cliquable sans changer les liens Japonais/Suisse.
 */
onMounted(() => {
    document
        .querySelectorAll('#collection .watch-card:not([data-catalog-card])')
        .forEach((card) => {
            const image = card.querySelector(':scope > div > img');
            const imageArea = image?.parentElement;
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
            imageArea.setAttribute(
                'aria-label',
                productLink.textContent?.trim() || 'Voir la montre',
            );
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
    <!--
        Les modèles supplémentaires sont injectés directement dans la grille
        "Choisissez votre montre" : même carte, même grille et mêmes classes
        d'animation que les autres montres. `defer` permet de cibler la grille
        qui est rendue juste après ce composant dans Index.vue.
    -->
    <Teleport
        v-if="displayModels.length"
        defer
        to="#collection > div.mx-auto > div.grid"
    >
        <article
            v-for="model in displayModels"
            :key="model.reference"
            data-catalog-card
            class="watch-card reveal-on-scroll vvs-luxury-card vvs-luxury-card--interactive group relative flex min-w-0 flex-col overflow-hidden rounded-2xl border"
        >
            <div
                class="absolute top-0 left-1/2 z-20 h-px w-0 -translate-x-1/2 bg-gradient-to-r from-transparent via-amber-300 to-transparent transition-all duration-500 group-hover:w-[85%]"
            ></div>

            <a
                href="#contact"
                class="relative block h-[390px] overflow-hidden bg-[radial-gradient(circle_at_50%_35%,rgba(251,191,36,0.12),rgba(12,10,8,0.96)_45%,#050505_78%)] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-amber-300"
                :aria-label="`${translations.collection.source_request} — ${model.name}`"
            >
                <div
                    class="pointer-events-none absolute top-0 -left-1/2 z-10 h-full w-1/3 -skew-x-12 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 transition-all duration-700 group-hover:left-[120%] group-hover:opacity-100"
                ></div>

                <img
                    :src="model.cardImage"
                    :srcset="`${model.cardImage} 720w, ${model.image} 1448w`"
                    sizes="(min-width: 1280px) 32vw, (min-width: 640px) 48vw, 100vw"
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

                <div class="mt-6 text-center">
                    <p
                        class="text-[10px] font-bold tracking-[0.18em] text-zinc-500 uppercase"
                    >
                        {{ requestPriceLabel }}
                    </p>
                </div>

                <p
                    class="mt-5 line-clamp-2 text-center text-sm leading-6 text-zinc-500"
                >
                    {{ translations.collection.source_details }}
                </p>

                <div class="mt-auto w-full pt-6">
                    <a
                        href="#contact"
                        class="vvs-button-secondary flex min-h-12 w-full items-center justify-center gap-3 rounded-xl px-4 py-4 text-xs font-bold tracking-[0.12em] uppercase"
                    >
                        {{ translations.collection.source_request }}
                        <span>→</span>
                    </a>
                </div>
            </div>
        </article>
    </Teleport>
</template>
