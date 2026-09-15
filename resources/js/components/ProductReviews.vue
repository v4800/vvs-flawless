<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    watch: {
        type: Object,
        required: true,
    },
    reviews: {
        type: Array,
        default: () => [],
    },
    reviewRoutes: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const submitted = ref(false);

const locale = computed(() => {
    return (
        {
            fr_BE: 'fr-BE',
            nl_BE: 'nl-BE',
            en_BE: 'en-BE',
            de_BE: 'de-BE',
        }[page.props.locale] ?? 'fr-BE'
    );
});

const copy = computed(() => {
    return (
        {
            fr_BE: {
                eyebrow: 'Avis sur ce modèle',
                title: 'L’expérience des clients',
                empty: 'Aucun avis publié pour ce modèle pour le moment.',
                note: 'Les avis sont modérés avant publication. Une note négative n’est pas un motif de refus.',
                name: 'Prénom ou pseudonyme',
                rating: 'Note',
                review: 'Votre avis',
                placeholder: 'Décrivez votre expérience avec cette montre…',
                confirmation: 'Je décris une expérience personnelle réelle et j’accepte la publication de cet avis.',
                send: 'Envoyer mon avis',
                sending: 'Envoi…',
                received: 'Merci. Votre avis a été reçu et sera visible après modération.',
                all: 'Voir tous les avis',
                date: 'Publié le',
            },
            nl_BE: {
                eyebrow: 'Reviews van dit model',
                title: 'Ervaringen van klanten',
                empty: 'Er zijn nog geen gepubliceerde reviews voor dit model.',
                note: 'Reviews worden voor publicatie gemodereerd. Een negatieve score is geen reden voor weigering.',
                name: 'Voornaam of pseudoniem',
                rating: 'Score',
                review: 'Jouw review',
                placeholder: 'Beschrijf je ervaring met dit horloge…',
                confirmation: 'Ik beschrijf een echte persoonlijke ervaring en ga akkoord met de publicatie van deze review.',
                send: 'Review versturen',
                sending: 'Bezig met versturen…',
                received: 'Bedankt. Je review is ontvangen en verschijnt na moderatie.',
                all: 'Alle reviews bekijken',
                date: 'Gepubliceerd op',
            },
            en_BE: {
                eyebrow: 'Reviews for this model',
                title: 'Customer experiences',
                empty: 'No published review for this model yet.',
                note: 'Reviews are moderated before publication. A negative rating is not a reason for rejection.',
                name: 'First name or public nickname',
                rating: 'Rating',
                review: 'Your review',
                placeholder: 'Describe your experience with this watch…',
                confirmation: 'I am describing a genuine personal experience and agree to the publication of this review.',
                send: 'Submit my review',
                sending: 'Sending…',
                received: 'Thank you. Your review has been received and will appear after moderation.',
                all: 'View all reviews',
                date: 'Published on',
            },
            de_BE: {
                eyebrow: 'Bewertungen dieses Modells',
                title: 'Erfahrungen unserer Kunden',
                empty: 'Für dieses Modell wurde noch keine Bewertung veröffentlicht.',
                note: 'Bewertungen werden vor der Veröffentlichung moderiert. Eine negative Note ist kein Ablehnungsgrund.',
                name: 'Vorname oder öffentliches Pseudonym',
                rating: 'Bewertung',
                review: 'Ihre Bewertung',
                placeholder: 'Beschreiben Sie Ihre Erfahrung mit dieser Uhr…',
                confirmation: 'Ich beschreibe eine echte persönliche Erfahrung und stimme der Veröffentlichung dieser Bewertung zu.',
                send: 'Bewertung senden',
                sending: 'Wird gesendet…',
                received: 'Danke. Ihre Bewertung wurde empfangen und erscheint nach der Moderation.',
                all: 'Alle Bewertungen ansehen',
                date: 'Veröffentlicht am',
            },
        }[page.props.locale] ?? null
    ) ?? {
        eyebrow: 'Avis sur ce modèle',
        title: 'L’expérience des clients',
        empty: 'Aucun avis publié pour ce modèle pour le moment.',
        note: 'Les avis sont modérés avant publication. Une note négative n’est pas un motif de refus.',
        name: 'Prénom ou pseudonyme',
        rating: 'Note',
        review: 'Votre avis',
        placeholder: 'Décrivez votre expérience avec cette montre…',
        confirmation: 'Je décris une expérience personnelle réelle et j’accepte la publication de cet avis.',
        send: 'Envoyer mon avis',
        sending: 'Envoi…',
        received: 'Merci. Votre avis a été reçu et sera visible après modération.',
        all: 'Voir tous les avis',
        date: 'Publié le',
    };
});

const form = useForm({
    watch_id: props.watch.id,
    display_name: '',
    rating: 5,
    body: '',
    experience: false,
    website: '',
});

watch(
    () => props.watch.id,
    (id) => {
        form.watch_id = id;
        submitted.value = false;
    },
);

const formatDate = (value) => {
    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return '';
    }

    return new Intl.DateTimeFormat(locale.value, {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(date);
};

const submit = () => {
    submitted.value = false;

    form.post(props.reviewRoutes.store, {
        preserveScroll: true,
        onSuccess: () => {
            submitted.value = true;
            form.reset(
                'display_name',
                'body',
                'experience',
                'website',
            );
            form.rating = 5;
            form.watch_id = props.watch.id;
        },
    });
};
</script>

<template>
    <section
        id="avis-modele"
        class="mx-auto max-w-[1400px] scroll-mt-28 px-5 py-14"
    >
        <div class="mb-8 max-w-2xl">
            <p
                class="text-[10px] font-bold tracking-[0.24em] text-amber-300 uppercase"
            >
                {{ copy.eyebrow }}
            </p>

            <h2
                class="vvs-display-title mt-3 text-4xl text-white sm:text-5xl"
            >
                {{ copy.title }}
            </h2>

            <p class="mt-4 text-sm leading-6 text-zinc-400">
                {{ copy.note }}
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="space-y-4">
                <article
                    v-for="review in reviews"
                    :key="review.id"
                    class="rounded-2xl border border-white/10 bg-black/45 p-6"
                >
                    <div
                        class="flex flex-wrap items-center justify-between gap-3"
                    >
                        <p class="font-bold text-white">
                            {{ review.display_name }}
                        </p>

                        <p
                            class="text-sm tracking-[0.08em] text-amber-300"
                            :aria-label="`${review.rating} / 5`"
                        >
                            {{ '★'.repeat(review.rating) }}{{ '☆'.repeat(5 - review.rating) }}
                        </p>
                    </div>

                    <p class="mt-4 whitespace-pre-line leading-7 text-zinc-300">
                        {{ review.body }}
                    </p>

                    <p class="mt-4 text-xs text-zinc-600">
                        {{ copy.date }} {{ formatDate(review.created_at) }}
                    </p>
                </article>

                <div
                    v-if="reviews.length === 0"
                    class="rounded-2xl border border-dashed border-white/10 p-7 text-sm leading-6 text-zinc-500"
                >
                    {{ copy.empty }}
                </div>

                <a
                    :href="reviewRoutes.index"
                    class="inline-flex min-h-11 items-center rounded-lg text-sm font-semibold text-amber-200 underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-300"
                >
                    {{ copy.all }}
                </a>
            </div>

            <form
                class="vvs-form rounded-2xl border border-white/10 bg-black/45 p-6 sm:p-7"
                @submit.prevent="submit"
            >
                <div>
                    <label
                        for="product-review-name"
                        class="text-sm font-semibold text-zinc-200"
                    >
                        {{ copy.name }}
                    </label>

                    <input
                        id="product-review-name"
                        v-model="form.display_name"
                        type="text"
                        minlength="2"
                        maxlength="50"
                        required
                        autocomplete="name"
                        class="mt-2 min-h-11 w-full rounded-xl border px-4 py-3 text-white outline-none"
                    />

                    <p
                        v-if="form.errors.display_name"
                        class="mt-2 text-sm text-red-300"
                    >
                        {{ form.errors.display_name }}
                    </p>
                </div>

                <div class="mt-5">
                    <label
                        for="product-review-rating"
                        class="text-sm font-semibold text-zinc-200"
                    >
                        {{ copy.rating }}
                    </label>

                    <select
                        id="product-review-rating"
                        v-model.number="form.rating"
                        required
                        class="mt-2 min-h-11 w-full rounded-xl border px-4 py-3 text-white outline-none"
                    >
                        <option
                            v-for="rating in [5, 4, 3, 2, 1]"
                            :key="rating"
                            :value="rating"
                        >
                            {{ rating }} / 5
                        </option>
                    </select>

                    <p
                        v-if="form.errors.rating"
                        class="mt-2 text-sm text-red-300"
                    >
                        {{ form.errors.rating }}
                    </p>
                </div>

                <div class="mt-5">
                    <label
                        for="product-review-body"
                        class="text-sm font-semibold text-zinc-200"
                    >
                        {{ copy.review }}
                    </label>

                    <textarea
                        id="product-review-body"
                        v-model="form.body"
                        minlength="20"
                        maxlength="2000"
                        required
                        rows="5"
                        :placeholder="copy.placeholder"
                        class="mt-2 w-full rounded-xl border px-4 py-3 text-white outline-none"
                    ></textarea>

                    <p
                        v-if="form.errors.body"
                        class="mt-2 text-sm text-red-300"
                    >
                        {{ form.errors.body }}
                    </p>
                </div>

                <div
                    class="absolute -left-[10000px] h-px w-px overflow-hidden"
                    aria-hidden="true"
                >
                    <label for="product-review-website">Website</label>
                    <input
                        id="product-review-website"
                        v-model="form.website"
                        type="text"
                        tabindex="-1"
                        autocomplete="off"
                    />
                </div>

                <label
                    class="mt-5 flex cursor-pointer items-start gap-3 text-sm leading-6 text-zinc-400"
                >
                    <input
                        v-model="form.experience"
                        type="checkbox"
                        class="mt-1 h-4 w-4 shrink-0"
                        required
                    />
                    <span>{{ copy.confirmation }}</span>
                </label>

                <p
                    v-if="form.errors.experience"
                    class="mt-2 text-sm text-red-300"
                >
                    {{ form.errors.experience }}
                </p>

                <p
                    v-if="submitted"
                    role="status"
                    class="mt-5 rounded-xl border border-emerald-400/20 bg-emerald-400/5 p-4 text-sm text-emerald-200"
                >
                    {{ copy.received }}
                </p>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="vvs-button-primary mt-6 min-h-11 w-full rounded-xl px-5 py-3 text-sm font-bold disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {{ form.processing ? copy.sending : copy.send }}
                </button>
            </form>
        </div>
    </section>
</template>