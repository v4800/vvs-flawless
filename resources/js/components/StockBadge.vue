<script setup>
import { usePage } from '@inertiajs/vue3';
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

const page = usePage();

const translations = computed(() => page.props.translations);

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

const normalizedAvailability = computed(() =>
    availabilityLabel.value
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase(),
);

const isMadeToOrder = computed(() =>
    ['sur commande', 'sur reservation', 'precommande'].some((label) =>
        normalizedAvailability.value.includes(label),
    ),
);

const isUnavailable = computed(() =>
    ['indisponible', 'rupture', 'epuise'].some((label) =>
        normalizedAvailability.value.includes(label),
    ),
);

const badge = computed(() => {
    if (stock.value === null) {
        if (isMadeToOrder.value) {
            return {
                label: translations.value.stock.made_to_order,
                icon: 'Ã¢â€”â€¹',
                classes: 'border-white/15 bg-black/85 text-zinc-400',
                iconClasses: 'text-zinc-500',
            };
        }

        if (isUnavailable.value) {
            return {
                label: translations.value.stock.unavailable,
                icon: 'Ã¢â€”â€¹',
                classes: 'border-white/15 bg-black/85 text-zinc-400',
                iconClasses: 'text-zinc-500',
            };
        }

        return {
            label: translations.value.stock.available,
            icon: 'Ã¢â€”Â',
            classes: 'border-amber-300/30 bg-black/85 text-amber-200',
            iconClasses: 'text-amber-300',
        };
    }

    if (stock.value <= 0) {
        return {
            label: isMadeToOrder.value
                ? translations.value.stock.made_to_order
                : translations.value.stock.unavailable,
            icon: 'Ã¢â€”â€¹',
            classes: 'border-white/15 bg-black/85 text-zinc-400',
            iconClasses: 'text-zinc-500',
        };
    }

    if (stock.value === 1) {
        return {
            label: translations.value.stock.last_piece,
            icon: 'Ã¢â€”Â',
            classes:
                'border-red-500/50 bg-red-950/80 text-red-300 shadow-[0_0_22px_rgba(239,68,68,0.18)]',
            iconClasses: 'animate-pulse text-red-400',
        };
    }

    if (stock.value === 2) {
        return {
            label: translations.value.stock.two_left,
            icon: 'Ã¢â€”Â',
            classes:
                'border-orange-400/50 bg-orange-950/70 text-orange-200 shadow-[0_0_22px_rgba(251,146,60,0.14)]',
            iconClasses: 'text-orange-400',
        };
    }

    return {
        label: translations.value.stock.available,
        icon: 'Ã¢â€”Â',
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
