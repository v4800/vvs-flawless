<script setup>
import { usePage } from '@inertiajs/vue3';
const emit = defineEmits(['scroll-to-collection']);
const translations = usePage().props.translations;
const services = ['quality', 'movements', 'delivery', 'delay'];
</script>

<template>
    <section id="concept" class="vvs-editorial-concept">
        <div class="vvs-editorial-concept__heading">
            <p class="vvs-editorial-label">
                <span aria-hidden="true">02 / </span
                >{{ translations.concept.eyebrow }}
            </p>
            <h2>{{ translations.concept.title }}</h2>
        </div>
        <div class="vvs-editorial-concept__copy">
            <p>{{ translations.concept.description }}</p>
            <button
                type="button"
                class="vvs-editorial-text-link"
                @click="emit('scroll-to-collection')"
            >
                {{ translations.concept.cta }} <span aria-hidden="true">↗</span>
            </button>
        </div>
    </section>
    <section id="services" class="vvs-editorial-services">
        <div v-for="(service, index) in services" :key="service">
            <span class="vvs-editorial-services__number" aria-hidden="true"
                >0{{ index + 1 }}</span
            >
            <h3>{{ translations.services[`${service}_title`] }}</h3>
            <p>{{ translations.services[`${service}_text`] }}</p>
        </div>
    </section>
</template>
