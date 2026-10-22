<script setup>
import { usePage } from '@inertiajs/vue3';

const emit = defineEmits(['scroll-to-collection']);
const page = usePage();
const translations = page.props.translations;
const hero = page.props.landingCopy.hero;
</script>

<template>
    <section class="vvs-editorial-hero" aria-labelledby="vvs-hero-title">
        <div class="vvs-editorial-hero__layout">
            <div class="vvs-editorial-hero__copy">
                <p class="vvs-editorial-label">
                    {{ hero.badge_style }} <span aria-hidden="true">/</span>
                    {{ page.props.landingCopy.brand_country }}
                </p>
                <h1
                    id="vvs-hero-title"
                    class="vvs-editorial-wordmark"
                    aria-label="VVS FLAWLESS"
                >
                    <span>VVS</span>
                    <span
                        >FLAWLESS<span class="vvs-editorial-wordmark__dot"
                            >.</span
                        ></span
                    >
                </h1>
                <p class="vvs-editorial-hero__tagline">
                    {{ translations.hero.tagline }}
                </p>
                <p class="vvs-editorial-hero__description">
                    {{ translations.hero.description }}
                </p>
                <div class="vvs-editorial-hero__actions">
                    <button
                        type="button"
                        class="vvs-editorial-button"
                        @click="emit('scroll-to-collection')"
                    >
                        {{ translations.concept.cta }}
                        <span aria-hidden="true">↗</span>
                    </button>
                    <a href="#about" class="vvs-editorial-text-link">
                        {{ translations.hero.concept }}
                    </a>
                </div>
            </div>
            <figure class="vvs-editorial-hero__visual">
                <picture>
                    <source
                        srcset="
                            /images/vvs-watch-hero-480.webp   480w,
                            /images/vvs-watch-hero-768.webp   768w,
                            /images/vvs-watch-hero-1024.webp 1024w,
                            /images/vvs-watch-hero.webp      1536w
                        "
                        sizes="(min-width: 1024px) 65vw, 100vw"
                        type="image/webp"
                    />
                    <img
                        src="/images/vvs-watch-hero-1024.webp"
                        :alt="hero.image_alt"
                        width="1536"
                        height="1024"
                        fetchpriority="high"
                        decoding="async"
                    />
                </picture>
                <figcaption>
                    <span>{{ hero.signature_title }}</span>
                    <span>{{ hero.signature_material }}</span>
                </figcaption>
            </figure>
        </div>
        <div class="vvs-editorial-hero__specs">
            <span>{{ hero.badge_stone }}</span>
            <span>{{ hero.color_title }}</span>
            <span>{{ hero.markets_title }}</span>
            <a href="#collection" :aria-label="translations.concept.cta">
                <span aria-hidden="true">↓</span>
            </a>
        </div>
    </section>
</template>
