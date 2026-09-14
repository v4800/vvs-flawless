<script setup>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    reservation: {
        type: Object,
        required: true,
    },
    reviewInvitation: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

const reviewUrl = window.location.origin + '/#avis-clients';

const form = reactive({
    subject: props.reviewInvitation
        ? 'Votre avis sur votre expérience VVS FLAWLESS'
        : 'Votre réservation ' +
          props.reservation.reservation_number +
          ' — VVS FLAWLESS',
    message: props.reviewInvitation
        ? 'Merci pour votre confiance. Vous pouvez partager votre expérience VVS FLAWLESS ici :\n' +
          reviewUrl +
          '\n\nVotre avis ne sera publié qu’après validation.'
        : 'Je vous contacte au sujet de votre réservation ' +
          props.reservation.reservation_number +
          '.',
    sending: false,
    errors: {},
});

const close = () => {
    if (!form.sending) {
        emit('close');
    }
};

const send = () => {
    form.sending = true;
    form.errors = {};

    router.post(
        '/admin/reservations/' + props.reservation.id + '/email',
        {
            subject: form.subject,
            message: form.message,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                form.sending = false;
                emit('close');
            },
            onError: (errors) => {
                form.errors = errors;
            },
            onFinish: () => {
                form.sending = false;
            },
        },
    );
};
</script>

<template>
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        @click.self="close"
    >
        <section
            role="dialog"
            aria-modal="true"
            aria-labelledby="reservation-email-title"
            class="w-full max-w-2xl rounded-3xl border border-amber-300/20 bg-zinc-950 p-6 shadow-2xl"
        >
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p
                        class="text-xs font-black tracking-[0.25em] text-amber-300 uppercase"
                    >
                        VVS FLAWLESS
                    </p>
                    <h2
                        id="reservation-email-title"
                        class="mt-2 text-2xl font-black"
                    >
                        Envoyer un email
                    </h2>
                    <p class="mt-2 text-sm text-zinc-400">
                        À {{ reservation.email }}
                    </p>
                </div>

                <button
                    type="button"
                    class="rounded-lg border border-white/10 px-3 py-2 text-sm text-zinc-300 hover:border-white/30"
                    :disabled="form.sending"
                    @click="close"
                >
                    Fermer
                </button>
            </div>

            <form class="mt-6 space-y-4" @submit.prevent="send">
                <label class="block">
                    <span class="text-xs font-bold text-zinc-400">
                        Sujet
                    </span>
                    <input
                        v-model.trim="form.subject"
                        type="text"
                        maxlength="150"
                        required
                        class="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm outline-none focus:border-amber-300/60"
                    />
                    <span
                        v-if="form.errors.subject"
                        class="mt-1 block text-xs text-red-300"
                    >
                        {{ form.errors.subject }}
                    </span>
                </label>

                <label class="block">
                    <span class="text-xs font-bold text-zinc-400">
                        Message
                    </span>
                    <textarea
                        v-model.trim="form.message"
                        rows="8"
                        maxlength="5000"
                        required
                        class="mt-2 w-full resize-y rounded-xl border border-white/10 bg-black px-4 py-3 text-sm leading-6 outline-none focus:border-amber-300/60"
                    ></textarea>
                    <span
                        v-if="form.errors.message"
                        class="mt-1 block text-xs text-red-300"
                    >
                        {{ form.errors.message }}
                    </span>
                </label>

                <p
                    v-if="form.errors.email_message"
                    class="rounded-xl border border-red-400/20 bg-red-400/10 px-4 py-3 text-sm text-red-200"
                >
                    {{ form.errors.email_message }}
                </p>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        class="rounded-xl border border-white/10 px-5 py-3 text-sm font-bold hover:border-white/30"
                        :disabled="form.sending"
                        @click="close"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        class="rounded-xl bg-amber-300 px-5 py-3 text-sm font-black text-black hover:bg-amber-200 disabled:cursor-wait disabled:opacity-60"
                        :disabled="form.sending"
                    >
                        {{
                            form.sending
                                ? 'Envoi en cours…'
                                : 'Envoyer depuis VVS FLAWLESS'
                        }}
                    </button>
                </div>
            </form>
        </section>
    </div>
</template>
