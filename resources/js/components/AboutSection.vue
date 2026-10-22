<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
const page = usePage();
const translations = computed(() => page.props.translations);
</script>

<template>
    <section id="about" class="vvs-about-story">
        <figure>
            <img
                src="/images/editorial/vvs-lifestyle-watch.webp"
                alt=""
                width="1448"
                height="1086"
                loading="lazy"
                decoding="async"
            />
        </figure>
        <div>
            <h2>
                {{ translations.about.title_before }}
                {{ translations.about.title_highlight }}
                {{ translations.about.title_after }}
            </h2>
            <p>{{ translations.about.paragraph_1 }}</p>
            <p>{{ translations.about.paragraph_2 }}</p>
        </div>
    </section>
</template>
