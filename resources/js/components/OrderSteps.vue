<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage();
const translations = page.props.translations;
const steps = [3, 4, 5];
</script>

<template>
    <section id="commander" class="vvs-editorial-order">
        <header>
            <h2>
                {{ translations.order.title_before }}
                {{ translations.order.title_highlight }}
            </h2>
            <p>{{ translations.order.description }}</p>
            <Link
                :href="page.props.localizedRoutes.diamondGuide"
                class="vvs-editorial-text-link"
                >{{ page.props.guideLinks.cta }}
                <span aria-hidden="true">↗</span></Link
            >
        </header>
        <ol>
            <li v-for="(step, index) in steps" :key="step" class="order-step">
                <span class="vvs-editorial-order__number" aria-hidden="true"
                    >0{{ index + 1 }}</span
                >
                <div>
                    <h3>{{ translations.order[`step_${step}_title`] }}</h3>
                    <p>{{ translations.order[`step_${step}_text`] }}</p>
                </div>
            </li>
        </ol>
    </section>
</template>
