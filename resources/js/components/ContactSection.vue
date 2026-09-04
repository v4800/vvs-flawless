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
                class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-zinc-950 via-black to-zinc-950 p-7 text-center shadow-[0_30px_100px_rgba(0,0,0,0.35)] sm:p-10 lg:p-14"
            >
                <!-- CONTACT -->

                <div class="mb-5 flex items-center justify-center gap-3">
                    <span
                        class="h-px w-10 bg-gradient-to-r from-transparent to-amber-300"
                    ></span>

                    <span
                        class="text-[10px] font-black tracking-[0.4em] text-amber-300 uppercase"
                    >
                        {{ translations.contact.eyebrow }}
                    </span>

                    <span
                        class="h-px w-10 bg-gradient-to-l from-transparent to-amber-300"
                    ></span>
                </div>

                <!-- TITRE -->

                <h2
                    class="text-4xl font-black tracking-[-0.04em] uppercase sm:text-5xl"
                >
                    {{ translations.contact.title_before }}

                    <span class="text-amber-300">
                        {{ translations.contact.title_highlight }}
                    </span>
                </h2>

                <!-- TEXTE -->

                <p
                    class="mx-auto mt-6 max-w-2xl text-sm leading-7 text-zinc-500 sm:text-base"
                >
                    {{ translations.contact.description }}
                </p>

                <!-- PROFIL INSTAGRAM -->

                <a
                    href="https://www.instagram.com/vvsflawless43/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group mx-auto mt-9 flex max-w-md items-center justify-between gap-4 rounded-2xl border border-white/10 bg-black/70 p-4 text-left transition duration-300 hover:-translate-y-1 hover:border-amber-300/30 hover:bg-white/[0.025]"
                >
                    <div class="flex min-w-0 items-center gap-4">
                        <!-- PHOTO DE PROFIL -->

                        <div class="relative shrink-0">
                            <div
                                class="absolute -inset-1 rounded-full bg-amber-300/10 blur-md transition duration-300 group-hover:bg-amber-300/20"
                            ></div>

                            <img
                                src="/images/vvs-flawless-profile.webp"
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
                                >
                                    Instagram
                                </span>
                            </div>

                            <p class="mt-1 text-xs text-zinc-500">
                                @vvsflawless43
                            </p>

                            <p
                                class="mt-2 hidden text-[10px] tracking-[0.12em] text-zinc-700 uppercase sm:block"
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
                        href="https://www.instagram.com/vvsflawless43/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group flex items-center justify-center gap-3 rounded-xl bg-amber-300 px-7 py-4 text-xs font-black tracking-[0.1em] text-black uppercase transition duration-300 hover:-translate-y-1 hover:bg-amber-200"
                    >
                        {{ translations.contact.instagram_cta }}

                        <span
                            class="transition duration-300 group-hover:translate-x-1"
                        >
                            →
                        </span>
                    </a>

                    <a
                        href="https://www.tiktok.com/@vvsflawless43"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center justify-center rounded-xl border border-white/15 bg-white/[0.025] px-7 py-4 text-xs font-black tracking-[0.1em] text-white uppercase transition duration-300 hover:-translate-y-1 hover:border-amber-300/50 hover:text-amber-200"
                    >
                        {{ translations.contact.tiktok_cta }}
                    </a>

                    <a
                        :href="collectionHref"
                        class="flex items-center justify-center rounded-xl border border-white/15 bg-white/[0.025] px-7 py-4 text-xs font-black tracking-[0.1em] text-white uppercase transition duration-300 hover:-translate-y-1 hover:border-amber-300/50 hover:text-amber-200"
                    >
                        {{ translations.contact.collection_cta }}
                    </a>
                </div>

                <!-- INFO -->

                <div
                    class="mx-auto mt-8 max-w-2xl border-t border-white/10 pt-6"
                >
                    <p class="text-xs leading-6 text-zinc-600">
                        {{ translations.contact.note }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
