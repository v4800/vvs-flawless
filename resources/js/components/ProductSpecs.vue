<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    watch: {
        type: Object,
        required: true,
    },
    translations: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const copy = computed(() => {
    return (
        {
            fr_BE: {
                eyebrow: 'Détails du modèle',
                title: 'Caractéristiques vérifiées',
                diameter: 'Diamètre',
                movements: 'Mouvements disponibles',
                note: 'Nous affichons uniquement les caractéristiques connues pour ce modèle.',
            },
            nl_BE: {
                eyebrow: 'Modeldetails',
                title: 'Geverifieerde kenmerken',
                diameter: 'Diameter',
                movements: 'Beschikbare uurwerken',
                note: 'We tonen alleen kenmerken die voor dit model bekend zijn.',
            },
            en_BE: {
                eyebrow: 'Model details',
                title: 'Verified specifications',
                diameter: 'Case diameter',
                movements: 'Available movements',
                note: 'Only specifications known for this model are displayed.',
            },
            de_BE: {
                eyebrow: 'Modelldetails',
                title: 'Geprüfte Merkmale',
                diameter: 'Gehäusedurchmesser',
                movements: 'Verfügbare Uhrwerke',
                note: 'Es werden nur bekannte Merkmale dieses Modells angezeigt.',
            },
        }[page.props.locale] ?? {
            eyebrow: 'Détails du modèle',
            title: 'Caractéristiques vérifiées',
            diameter: 'Diamètre',
            movements: 'Mouvements disponibles',
            note: 'Nous affichons uniquement les caractéristiques connues pour ce modèle.',
        }
    );
});

const diameter = computed(() => {
    return props.watch.name?.match(/\b\d{2}\s*mm\b/i)?.[0] ?? null;
});

const specs = computed(() => {
    const items = [
        diameter.value
            ? {
                  label: copy.value.diameter,
                  value: diameter.value,
              }
            : null,
        {
            label: props.translations.product.stone,
            value: 'Moissanite VVS',
        },
        {
            label: props.translations.product.color,
            value: 'D',
        },
        {
            label: copy.value.movements,
            value: `${props.translations.movements.japonais} / ${props.translations.movements.suisse}`,
        },
        {
            label: props.translations.product.estimated_availability,
            value: props.translations.product.estimated_delay,
        },
        {
            label: props.translations.product.reception,
            value: props.translations.product.handover_or_delivery,
        },
    ];

    return items.filter(Boolean);
});
</script>

<template>
    <section class="border-y border-white/10 bg-zinc-950/60 px-5 py-10 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-[1400px]">
            <header class="mb-7 max-w-3xl">
                <p class="vvs-eyebrow">{{ copy.eyebrow }}</p>
                <h2 class="vvs-display-title mt-3 text-3xl sm:text-4xl">
                    {{ copy.title }}
                </h2>
                <p class="mt-3 text-xs leading-6 text-zinc-500">
                    {{ copy.note }}
                </p>
            </header>

            <dl class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="spec in specs"
                    :key="spec.label"
                    class="vvs-choice-card rounded-2xl border p-5"
                >
                    <dt
                        class="text-[9px] font-black tracking-[0.2em] text-zinc-500 uppercase"
                    >
                        {{ spec.label }}
                    </dt>
                    <dd class="mt-2 text-sm font-bold text-zinc-100">
                        {{ spec.value }}
                    </dd>
                </div>
            </dl>
        </div>
    </section>
</template>
