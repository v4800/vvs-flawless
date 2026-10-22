<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
const page = usePage();
const translations = computed(() => page.props.translations);
</script>

<template>
    <section id="faq" class="vvs-faq-list">
        <h2>
            {{ translations.faq.title_before }}
            {{ translations.faq.title_highlight }}
        </h2>
        <div>
            <details v-for="faq in translations.faq.items" :key="faq.question">
                <summary>{{ faq.question }}</summary>
                <p>{{ faq.answer }}</p>
            </details>
        </div>
    </section>
</template>
