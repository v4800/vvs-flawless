<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const copy = computed(() => page.props.translations.order);
const steps = [3, 4, 5];
</script>

<template>
    <section
        class="vvs-product-journey"
        aria-labelledby="reservation-journey-title"
    >
        <header>
            <p class="vvs-eyebrow">{{ copy.eyebrow }}</p>
            <h2 id="reservation-journey-title" class="vvs-display-title">
                {{ copy.title_before }} {{ copy.title_highlight }}
            </h2>
            <p>{{ copy.note }}</p>
        </header>
        <ol>
            <li v-for="(step, index) in steps" :key="step">
                <span aria-hidden="true">0{{ index + 1 }}</span>
                <div>
                    <h3>{{ copy[`step_${step}_title`] }}</h3>
                    <p>{{ copy[`step_${step}_text`] }}</p>
                </div>
            </li>
        </ol>
    </section>
</template>
