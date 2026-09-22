<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    reservation: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['compose-email']);

const copiedPhone = ref(false);
const copiedReference = ref(false);
const sendingRecap = ref(false);
const recapStatus = ref('');

const phoneHref = (phone) => {
    const value = String(phone ?? '')
        .trim()
        .replace(/[^\d+]/g, '');

    return value ? 'tel:' + value : '#';
};

const sendRecap = (reservation) => {
    sendingRecap.value = true;
    recapStatus.value = '';

    router.post(
        '/admin/reservations/' + reservation.id + '/recap/email',
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                recapStatus.value = 'Récapitulatif PDF envoyé';
            },
            onError: (errors) => {
                recapStatus.value =
                    errors.email_message || 'Échec de l’envoi du PDF';
            },
            onFinish: () => {
                sendingRecap.value = false;
            },
        },
    );
};

const copyValue = async (value, state) => {
    await navigator.clipboard.writeText(String(value ?? ''));
    state.value = true;

    window.setTimeout(() => {
        state.value = false;
    }, 1800);
};
</script>

<template>
    <div class="flex flex-wrap items-center gap-2">
        <a
            :href="phoneHref(reservation.phone)"
            class="rounded-lg bg-white/5 px-3 py-2 text-xs font-bold hover:bg-white/10"
        >
            Appeler
        </a>

        <button
            type="button"
            class="rounded-lg bg-white/5 px-3 py-2 text-xs font-bold hover:bg-white/10"
            @click="copyValue(reservation.phone, copiedPhone)"
        >
            {{ copiedPhone ? 'Téléphone copié' : 'Copier le téléphone' }}
        </button>

        <button
            type="button"
            class="rounded-lg bg-white/5 px-3 py-2 text-xs font-bold hover:bg-white/10"
            @click="emit('compose-email', false)"
        >
            Envoyer un email
        </button>

        <a
            :href="'/admin/reservations/' + reservation.id + '/recap'"
            class="rounded-lg bg-white/5 px-3 py-2 text-xs font-bold hover:bg-white/10"
        >
            Télécharger le récapitulatif PDF
        </a>

        <button
            type="button"
            class="rounded-lg bg-amber-300 px-3 py-2 text-xs font-black text-black hover:bg-amber-200 disabled:cursor-wait disabled:opacity-60"
            :disabled="sendingRecap"
            @click="sendRecap(reservation)"
        >
            {{ sendingRecap ? 'Envoi…' : 'Envoyer le récapitulatif PDF' }}
        </button>

        <span
            v-if="recapStatus"
            class="text-xs font-bold text-emerald-300"
        >
            {{ recapStatus }}
        </span>

        <button
            type="button"
            class="rounded-lg bg-white/5 px-3 py-2 text-xs font-bold hover:bg-white/10"
            @click="copyValue(reservation.reservation_number, copiedReference)"
        >
            {{ copiedReference ? 'Référence copiée' : 'Copier la référence' }}
        </button>

        <button
            v-if="reservation.review_invitation_allowed"
            type="button"
            class="rounded-lg bg-amber-300 px-3 py-2 text-xs font-black text-black hover:bg-amber-200"
            @click="emit('compose-email', true)"
        >
            Inviter à laisser un avis
        </button>
    </div>
</template>
