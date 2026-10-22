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

const copies = {
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
};

const copy = computed(() => copies[page.props.locale] ?? copies.fr_BE);
const diameter = computed(
    () => props.watch.name?.match(/\b\d{2}\s*mm\b/i)?.[0] ?? null,
);
const availableMovements = computed(() => {
    const movements = [];

    if (
        props.watch.japanese_price !== null &&
        props.watch.japanese_price !== undefined
    ) {
        movements.push(props.translations.movements.japonais);
    }

    if (
        props.watch.swiss_price !== null &&
        props.watch.swiss_price !== undefined
    ) {
        movements.push(props.translations.movements.suisse);
    }

    return movements;
});
const specs = computed(() => {
    const items = [];

    if (diameter.value) {
        items.push({
            label: copy.value.diameter,
            value: diameter.value,
        });
    }

    items.push(
        {
            label: props.translations.product.stone,
            value: 'Moissanite VVS',
        },
        {
            label: props.translations.product.color,
            value: 'D',
        },
    );

    if (availableMovements.value.length > 0) {
        items.push({
            label: copy.value.movements,
            value: availableMovements.value.join(' / '),
        });
    }

    items.push(
        {
            label: props.translations.product.estimated_availability,
            value:
                props.watch.delivery_estimate ??
                props.translations.product.estimated_delay,
        },
        {
            label: props.translations.product.reception,
            value: props.translations.product.handover_or_delivery,
        },
    );

    return items;
});
</script>

<template>
    <section class="vvs-product-specifications" :aria-label="copy.eyebrow">
        <h2>{{ copy.eyebrow }}</h2>
        <dl>
            <div v-for="spec in specs" :key="spec.label">
                <dt>{{ spec.label }}</dt>
                <dd>{{ spec.value }}</dd>
            </div>
        </dl>
    </section>
</template>
