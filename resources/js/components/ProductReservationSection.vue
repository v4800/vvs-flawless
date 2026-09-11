<script setup>
import ReservationTrust from '@/components/ReservationTrust.vue';

defineProps({
    watch: {
        type: Object,
        required: true,
    },
    activeImage: {
        type: String,
        required: true,
    },
    localizedMovement: {
        type: String,
        required: true,
    },
    selectedPrice: {
        type: Number,
        required: true,
    },
    form: {
        type: Object,
        required: true,
    },
    deliveryOptions: {
        type: Array,
        required: true,
    },
    translations: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['submit']);

const formatPrice = (price) => `${Number(price).toFixed(0)} €`;
</script>

<template>
    <section
        id="reservation"
        class="relative scroll-mt-24 px-5 py-20 sm:px-6 lg:px-10"
    >
        <div
            class="pointer-events-none absolute top-1/2 left-1/2 -z-10 h-[550px] w-[1200px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.035] blur-[150px]"
        ></div>

        <div
            class="mx-auto grid max-w-[1500px] gap-8 xl:grid-cols-[0.58fr_1.42fr] xl:gap-10"
        >
            <div class="xl:sticky xl:top-28 xl:self-start">
                <p class="vvs-eyebrow">
                    {{ translations.product.your_selection }}
                </p>

                <h2 class="vvs-display-title mt-4 text-4xl">
                    {{ translations.product.reserve_piece }}
                </h2>

                <p class="mt-4 text-sm leading-7 text-zinc-500">
                    {{ translations.product.reservation_intro }}
                </p>

                <div
                    class="vvs-luxury-card mt-7 overflow-hidden rounded-2xl border"
                >
                    <div class="grid grid-cols-[105px_1fr]">
                        <div class="bg-zinc-400">
                            <img
                                :src="activeImage"
                                :alt="watch.name"
                                loading="lazy"
                                decoding="async"
                                class="h-full w-full object-cover"
                            />
                        </div>

                        <div class="p-4">
                            <p
                                class="vvs-display-title line-clamp-2 text-xl leading-5"
                            >
                                {{ watch.name }}
                            </p>

                            <p
                                class="mt-2 text-[10px] tracking-[0.15em] text-zinc-500 uppercase"
                            >
                                {{ localizedMovement }}
                            </p>

                            <p class="vvs-price mt-3 text-xl font-black">
                                {{ formatPrice(selectedPrice) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="vvs-choice-card vvs-choice-card--featured mt-4 rounded-2xl border p-5"
                >
                    <p class="text-xs font-bold text-zinc-300">
                        ◆ {{ form.delivery_method }}
                    </p>

                    <p class="mt-2 text-xs leading-5 text-zinc-600">
                        {{ translations.product.reception_note }}
                    </p>
                </div>
            </div>

            <form
                class="vvs-form vvs-luxury-card rounded-3xl border p-6 sm:p-8 xl:p-9"
                @submit.prevent="emit('submit')"
            >
                <div
                    class="mb-6 flex items-center justify-between gap-5 border-b border-white/10 pb-5"
                >
                    <div>
                        <p
                            class="text-[9px] font-black tracking-[0.3em] text-zinc-600 uppercase"
                        >
                            {{ translations.product.reservation }}
                        </p>

                        <h3 class="vvs-display-title mt-2 text-3xl">
                            {{ translations.product.your_information }}
                        </h3>
                    </div>

                    <div
                        class="rounded-full border border-amber-300/20 bg-amber-300/[0.04] px-4 py-2 text-xs font-bold text-amber-200"
                    >
                        {{ localizedMovement }}
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label
                            for="reservation-customer-name"
                            class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                        >
                            {{ translations.product.full_name }}
                        </label>

                        <input
                            id="reservation-customer-name"
                            v-model="form.customer_name"
                            type="text"
                            required
                            autocomplete="name"
                            :aria-invalid="Boolean(form.errors.customer_name)"
                            :aria-describedby="
                                form.errors.customer_name
                                    ? 'reservation-customer-name-error'
                                    : undefined
                            "
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                            :placeholder="translations.product.name_placeholder"
                        />

                        <p
                            v-if="form.errors.customer_name"
                            id="reservation-customer-name-error"
                            role="alert"
                            class="mt-2 text-xs text-red-400"
                        >
                            {{ form.errors.customer_name }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="reservation-email"
                            class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                        >
                            {{ translations.product.email }}
                        </label>

                        <input
                            id="reservation-email"
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="email"
                            :aria-invalid="Boolean(form.errors.email)"
                            :aria-describedby="
                                form.errors.email
                                    ? 'reservation-email-error'
                                    : undefined
                            "
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                            :placeholder="translations.product.email_placeholder"
                        />

                        <p
                            v-if="form.errors.email"
                            id="reservation-email-error"
                            role="alert"
                            class="mt-2 text-xs text-red-400"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="reservation-phone"
                            class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                        >
                            {{ translations.product.phone }}
                        </label>

                        <input
                            id="reservation-phone"
                            v-model="form.phone"
                            type="tel"
                            required
                            autocomplete="tel"
                            :aria-invalid="Boolean(form.errors.phone)"
                            :aria-describedby="
                                form.errors.phone
                                    ? 'reservation-phone-error'
                                    : undefined
                            "
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                            placeholder="+32..."
                        />

                        <p
                            v-if="form.errors.phone"
                            id="reservation-phone-error"
                            role="alert"
                            class="mt-2 text-xs text-red-400"
                        >
                            {{ form.errors.phone }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="reservation-city"
                            class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                        >
                            {{ translations.product.city }}
                        </label>

                        <input
                            id="reservation-city"
                            v-model="form.city"
                            type="text"
                            autocomplete="address-level2"
                            :aria-invalid="Boolean(form.errors.city)"
                            :aria-describedby="
                                form.errors.city
                                    ? 'reservation-city-error'
                                    : undefined
                            "
                            class="w-full rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                            :placeholder="translations.product.city_placeholder"
                        />

                        <p
                            v-if="form.errors.city"
                            id="reservation-city-error"
                            role="alert"
                            class="mt-2 text-xs text-red-400"
                        >
                            {{ form.errors.city }}
                        </p>
                    </div>

                    <fieldset
                        class="md:col-span-2"
                        :aria-invalid="Boolean(form.errors.delivery_method)"
                        :aria-describedby="
                            form.errors.delivery_method
                                ? 'reservation-delivery-method-error'
                                : undefined
                        "
                    >
                        <legend
                            class="mb-2 text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                        >
                            {{ translations.product.reception_method }}
                        </legend>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <label
                                v-for="(option, index) in deliveryOptions"
                                :key="option.value"
                                :for="`reservation-delivery-method-${index}`"
                                :class="[
                                    'cursor-pointer rounded-xl border p-5 transition',
                                    form.delivery_method === option.value
                                        ? 'border-amber-300/50 bg-amber-300/[0.05]'
                                        : 'border-white/10 bg-black hover:border-white/20',
                                ]"
                            >
                                <div class="flex items-start gap-3">
                                    <input
                                        :id="`reservation-delivery-method-${index}`"
                                        v-model="form.delivery_method"
                                        type="radio"
                                        name="delivery_method"
                                        :value="option.value"
                                        required
                                        class="mt-1 h-4 w-4 accent-amber-300"
                                    />

                                    <span>
                                        <span class="block font-bold text-white">
                                            {{ option.label }}
                                        </span>

                                        <span
                                            class="mt-1 block text-sm leading-6 text-zinc-500"
                                        >
                                            {{ option.description }}
                                        </span>
                                    </span>
                                </div>
                            </label>
                        </div>

                        <p
                            v-if="form.errors.delivery_method"
                            id="reservation-delivery-method-error"
                            role="alert"
                            class="mt-2 text-xs text-red-400"
                        >
                            {{ form.errors.delivery_method }}
                        </p>
                    </fieldset>

                    <div class="md:col-span-2">
                        <label
                            for="reservation-message"
                            class="mb-2 block text-xs font-bold tracking-[0.1em] text-zinc-500 uppercase"
                        >
                            {{ translations.product.message }}

                            <span
                                class="tracking-normal text-zinc-700 normal-case"
                            >
                                {{ translations.product.optional }}
                            </span>
                        </label>

                        <textarea
                            id="reservation-message"
                            v-model="form.message"
                            rows="3"
                            :aria-invalid="Boolean(form.errors.message)"
                            :aria-describedby="
                                form.errors.message
                                    ? 'reservation-message-error'
                                    : undefined
                            "
                            class="w-full resize-none rounded-xl border border-white/10 bg-black px-4 py-4 text-sm text-white transition outline-none placeholder:text-zinc-700 focus:border-amber-300/50"
                            :placeholder="translations.product.message_placeholder"
                        ></textarea>

                        <p
                            v-if="form.errors.message"
                            id="reservation-message-error"
                            role="alert"
                            class="mt-2 text-xs text-red-400"
                        >
                            {{ form.errors.message }}
                        </p>
                    </div>

                    <ReservationTrust />

                    <div
                        class="vvs-choice-card flex flex-col gap-4 rounded-2xl border p-5 sm:flex-row sm:items-center sm:justify-between md:col-span-2"
                    >
                        <div>
                            <p class="font-black text-white">
                                {{ translations.product.contact_question }}
                            </p>

                            <p class="mt-1 text-xs leading-5 text-zinc-600">
                                {{ translations.product.contact_text }}
                            </p>
                        </div>

                        <a
                            href="https://www.tiktok.com/@vvsflawless43"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="vvs-button-secondary shrink-0 rounded-xl px-5 py-3 text-center text-xs font-bold tracking-[0.1em] uppercase"
                        >
                            @vvsflawless43 →
                        </a>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            for="reservation-confirmation"
                            class="flex h-full cursor-pointer items-start gap-4 rounded-xl border border-white/10 bg-black/60 p-4"
                        >
                            <input
                                id="reservation-confirmation"
                                v-model="form.confirmation"
                                type="checkbox"
                                required
                                :aria-invalid="Boolean(form.errors.confirmation)"
                                :aria-describedby="
                                    form.errors.confirmation
                                        ? 'reservation-confirmation-error'
                                        : undefined
                                "
                                class="mt-1 h-4 w-4 accent-amber-300"
                            />

                            <span class="text-xs leading-6 text-zinc-500">
                                {{ translations.product.confirmation_text }}
                            </span>
                        </label>

                        <p
                            v-if="form.errors.confirmation"
                            id="reservation-confirmation-error"
                            role="alert"
                            class="mt-2 text-xs text-red-400"
                        >
                            {{ translations.product.confirmation_error }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <div
                            class="flex h-full items-center justify-between gap-5 rounded-xl border border-white/10 bg-black/40 px-5 py-4"
                        >
                            <div>
                                <p
                                    class="text-[9px] font-black tracking-[0.25em] text-zinc-600 uppercase"
                                >
                                    {{ translations.product.piece_amount }}
                                </p>

                                <p class="mt-2 text-sm text-zinc-400">
                                    {{ translations.product.movement }}
                                    {{ localizedMovement }}
                                </p>
                            </div>

                            <p class="vvs-price text-3xl font-black">
                                {{ formatPrice(selectedPrice) }}
                            </p>
                        </div>
                    </div>

                    <div class="md:col-span-2 xl:col-span-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="vvs-button-primary group flex w-full items-center justify-between rounded-xl px-6 py-5 text-sm font-bold tracking-[0.1em] uppercase disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span>
                                {{
                                    form.processing
                                        ? translations.product.sending
                                        : translations.product.submit
                                }}
                            </span>

                            <span
                                class="text-xl transition duration-300 group-hover:translate-x-1"
                            >
                                →
                            </span>
                        </button>

                        <p
                            class="mt-4 text-center text-[11px] leading-5 text-zinc-700"
                        >
                            {{ translations.product.reservation_note }}
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </section>
</template>
