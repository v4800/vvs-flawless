<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage();
const translations = page.props.translations;
</script>

<template>
    <section id="commander" class="vvs-editorial-order">
        <header>
            <p class="vvs-editorial-label">
                <span aria-hidden="true">03 / </span
                >{{ translations.order.eyebrow }}
            </p>
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
            <li v-for="step in 5" :key="step" class="order-step">
                <span class="vvs-editorial-order__number" aria-hidden="true"
                    >0{{ step }}</span
                >
                <div>
                    <h3>{{ translations.order[`step_${step}_title`] }}</h3>
                    <p>{{ translations.order[`step_${step}_text`] }}</p>
                </div>
            </li>
        </ol>
    </section>
</template>
