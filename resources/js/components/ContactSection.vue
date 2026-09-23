<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    collectionHref: {
        type: String,
        default: '#collection',
    },
});

const page = usePage();
const translations = computed(() => page.props.translations);
</script>

<template>
    <section
        id="contact"
        class="relative scroll-mt-24 overflow-hidden px-5 py-24 sm:px-6 lg:px-10"
    >
        <!-- HALO -->

        <div
            class="pointer-events-none absolute top-1/2 left-1/2 -z-10 h-[500px] w-[800px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/[0.04] blur-[150px]"
        ></div>

        <div class="mx-auto max-w-5xl">
            <div
                class="vvs-luxury-card overflow-hidden rounded-3xl border p-7 text-center sm:p-10 lg:p-14"
            >
                <!-- CONTACT -->

                <div class="mb-5 flex items-center justify-center gap-3">
                    <span
                        class="h-px w-10 bg-gradient-to-r from-transparent to-amber-300"
                    ></span>

                    <span class="vvs-eyebrow">
                        {{ translations.contact.eyebrow }}
                    </span>

                    <span
                        class="h-px w-10 bg-gradient-to-l from-transparent to-amber-300"
                    ></span>
                </div>

                <!-- TITRE -->

                <h2 class="vvs-display-title text-[clamp(2.4rem,10vw,3.75rem)]">
                    {{ translations.contact.title_before }}

                    <span class="vvs-gradient-text">
                        {{ translations.contact.title_highlight }}
                    </span>
                </h2>

                <!-- TEXTE -->

                <p
                    class="mx-auto mt-6 max-w-2xl text-sm leading-7 text-zinc-300 sm:text-base"
                >
                    {{ translations.contact.description }}
                </p>

                <!-- PROFIL TIKTOK -->

                <a
                    href="https://www.tiktok.com/@vvsflawless43"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="vvs-choice-card group mx-auto mt-9 flex max-w-md items-center justify-between gap-4 rounded-2xl border p-4 text-left"
                >
                    <div class="flex min-w-0 items-center gap-4">
                        <!-- PHOTO DE PROFIL -->

                        <div class="relative shrink-0">
                            <div
                                class="absolute -inset-1 rounded-full bg-amber-300/10 blur-md transition duration-300 group-hover:bg-amber-300/20"
                            ></div>

                            <img
                                src="/images/vvs-flawless-profile.webp"
                                srcset="
                                    /images/vvs-flawless-profile-112.webp  112w,
                                    /images/vvs-flawless-profile-224.webp  224w,
                                    /images/vvs-flawless-profile.webp     1254w
                                "
                                sizes="(min-width: 640px) 72px, 64px"
                                alt="VVS FLAWLESS"
                                width="72"
                                height="72"
                                loading="lazy"
                                decoding="async"
                                class="relative h-16 w-16 rounded-full border border-white/15 object-cover shadow-[0_0_25px_rgba(255,255,255,0.06)] sm:h-[72px] sm:w-[72px]"
                            />
                        </div>

                        <!-- IDENTITÉ -->

                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p
                                    class="truncate text-base font-black text-white"
                                >
                                    VVS FLAWLESS
                                </p>

                                <span
                                    class="hidden rounded-full border border-white/10 px-2 py-0.5 text-[7px] font-black tracking-[0.15em] text-zinc-500 uppercase sm:inline-flex"
                                    >TikTok</span
                                >
                            </div>

                            <p class="mt-1 text-xs text-zinc-300">
                                @vvsflawless43
                            </p>

                            <p
                                class="mt-2 hidden text-[10px] tracking-[0.12em] text-zinc-400 uppercase sm:block"
                            >
                                {{ translations.contact.official_profile }}
                            </p>
                        </div>
                    </div>

                    <!-- FLÈCHE -->

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-amber-300/20 text-amber-300 transition duration-300 group-hover:border-amber-300/50 group-hover:bg-amber-300 group-hover:text-black"
                    >
                        →
                    </div>
                </a>

                <!-- BOUTONS -->

                <div
                    class="mt-8 flex flex-col justify-center gap-3 sm:flex-row"
                >
                    <a
                        href="https://www.tiktok.com/@vvsflawless43"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="vvs-button-secondary flex items-center justify-center rounded-xl px-7 py-4 text-xs font-bold tracking-[0.1em] uppercase"
                    >
                        {{ translations.contact.tiktok_cta }}
                    </a>

                    <a
                        :href="collectionHref"
                        class="vvs-button-secondary flex items-center justify-center rounded-xl px-7 py-4 text-xs font-bold tracking-[0.1em] uppercase"
                    >
                        {{ translations.contact.collection_cta }}
                    </a>
                </div>

                <a
                    href="https://www.youtube.com/@v48-g5v"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="vvs-contact-youtube vvs-button-secondary mx-auto mt-4 inline-flex min-h-11 items-center justify-center gap-3 rounded-xl px-6 py-3 text-sm font-semibold"
                    aria-label="YouTube VVS FLAWLESS"
                >
                    <svg
                        aria-hidden="true"
                        viewBox="0 0 24 24"
                        width="21"
                        height="21"
                        fill="currentColor"
                    >
                        <path
                            d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.2 3.6-6.2 3.6Z"
                        />
                    </svg>
                    <span>YouTube</span>
                </a>
                <!-- INFO -->

                <div
                    class="mx-auto mt-8 max-w-2xl border-t border-white/10 pt-6"
                >
                    <p class="text-xs leading-6 text-zinc-400">
                        {{ translations.contact.note }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
