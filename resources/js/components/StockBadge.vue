<script setup>
import { computed } from 'vue';

const props = defineProps({
    quantity: {
        type: [Number, String],
        default: null,
    },
    availability: {
        type: String,
        default: '',
    },
});

const stock = computed(() => {
    if (
        props.quantity === null ||
        props.quantity === undefined ||
        props.quantity === ''
    ) {
        return null;
    }

    return Number(props.quantity);
});

const availabilityLabel = computed(() => props.availability.trim());

const isMadeToOrder = computed(() => {
    const availability = availabilityLabel.value
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();

    return ['sur commande', 'sur reservation', 'precommande'].some((label) =>
        availability.includes(label),
    );
});

const badge = computed(() => {
    /*
    |--------------------------------------------------------------------------
    | STOCK NON RENSEIGNÉ
    |--------------------------------------------------------------------------
    */

    if (stock.value === null) {
        if (isMadeToOrder.value) {
            return {
                label: 'Sur commande',
                icon: '○',
                classes: 'border-white/15 bg-black/85 text-zinc-400',
                iconClasses: 'text-zinc-500',
            };
        }

        return {
            label: availabilityLabel.value || 'Disponible',
            icon: '●',
            classes: 'border-amber-300/30 bg-black/85 text-amber-200',
            iconClasses: 'text-amber-300',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | SUR COMMANDE
    |--------------------------------------------------------------------------
    */

    if (stock.value <= 0) {
        return {
            label: isMadeToOrder.value ? 'Sur commande' : 'Indisponible',
            icon: '○',
            classes: 'border-white/15 bg-black/85 text-zinc-400',
            iconClasses: 'text-zinc-500',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | DERNIÈRE PIÈCE
    |--------------------------------------------------------------------------
    */

    if (stock.value === 1) {
        return {
            label: 'Dernière pièce',
            icon: '●',
            classes:
                'border-red-500/50 bg-red-950/80 text-red-300 shadow-[0_0_22px_rgba(239,68,68,0.18)]',
            iconClasses: 'animate-pulse text-red-400',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | PLUS QUE 2 PIÈCES
    |--------------------------------------------------------------------------
    */

    if (stock.value === 2) {
        return {
            label: 'Plus que 2 pièces',
            icon: '●',
            classes:
                'border-orange-400/50 bg-orange-950/70 text-orange-200 shadow-[0_0_22px_rgba(251,146,60,0.14)]',
            iconClasses: 'text-orange-400',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | DISPONIBLE
    |--------------------------------------------------------------------------
    */

    return {
        label: 'Disponible',
        icon: '●',
        classes: 'border-amber-300/30 bg-black/85 text-amber-200',
        iconClasses: 'text-amber-300',
    };
});
</script>

<template>
    <div
        :class="[
            'inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-[10px] font-black tracking-[0.12em] uppercase backdrop-blur-xl',
            badge.classes,
        ]"
    >
        <span :class="badge.iconClasses">
            {{ badge.icon }}
        </span>

        <span>
            {{ badge.label }}
        </span>
    </div>
</template>
