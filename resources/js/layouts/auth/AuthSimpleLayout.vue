<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { home } from '@/routes';

const props = defineProps<{
    title?: string;
    description?: string;
}>();

const page = usePage();
const copy = computed(() => page.props.translations.auth);
const displayTitle = computed(() =>
    props.title === 'Connexion administrateur' ? copy.value.login_title : props.title,
);
const displayDescription = computed(() =>
    props.description === 'Accès réservé à VVS FLAWLESS'
        ? copy.value.login_description
        : props.description,
);
</script>

<template>
    <div
        class="relative isolate flex min-h-svh flex-col overflow-hidden bg-[#070707] font-sans text-[#f6f2e9]"
    >
        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_18%_32%,rgba(196,161,100,0.12),transparent_34rem),radial-gradient(ellipse_at_86%_8%,rgba(255,255,255,0.045),transparent_26rem),linear-gradient(180deg,#0b0b0a_0%,#070707_58%,#090908_100%)]"
        />
        <div
            aria-hidden="true"
            class="pointer-events-none absolute inset-0 -z-10 opacity-[0.16] [background-image:linear-gradient(rgba(255,255,255,0.018)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.018)_1px,transparent_1px)] [background-size:56px_56px] [mask-image:linear-gradient(to_bottom,black,transparent_82%)]"
        />

        <header
            class="relative z-10 mx-auto flex w-full max-w-7xl items-center justify-between gap-5 px-5 py-5 sm:px-8 lg:px-12 lg:py-7"
        >
            <Link
                :href="home()"
                class="group flex min-w-0 items-center gap-3 rounded-lg focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#e5d2a9]"
                :aria-label="copy.home_aria"
            >
                <img
                    src="/images/branding/vvs-flawless-logo.svg"
                    alt="VVS FLAWLESS"
                    class="h-12 w-[4.25rem] shrink-0 object-contain sm:h-[3.75rem] sm:w-[5rem]"
                    width="1280"
                    height="1024"
                    fetchpriority="high"
                />
                <span class="hidden border-l border-white/10 pl-3 text-[0.62rem] font-semibold uppercase tracking-[0.24em] text-white/45 sm:block">
                    {{ copy.private_space }}
                </span>
            </Link>

            <Link
                :href="home()"
                class="group inline-flex min-h-10 items-center gap-2 rounded-full border border-white/10 bg-white/[0.025] px-3.5 text-xs font-medium text-white/65 transition-colors duration-200 hover:border-[#c8ad78]/45 hover:text-[#f2dfb8] focus-visible:outline-2 focus-visible:outline-offset-3 focus-visible:outline-[#e5d2a9] motion-reduce:transition-none sm:px-4 sm:text-sm"
            >
                <span>{{ copy.return_store }}</span>
                <svg
                    aria-hidden="true"
                    viewBox="0 0 20 20"
                    fill="none"
                    class="size-4 transition-transform duration-200 group-hover:translate-x-0.5 motion-reduce:transition-none"
                >
                    <path
                        d="M4.5 10h10m-4-4 4 4-4 4"
                        stroke="currentColor"
                        stroke-width="1.4"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </Link>
        </header>

        <main
            class="relative z-10 mx-auto grid w-full max-w-7xl flex-1 items-center gap-10 px-5 pb-10 pt-5 sm:px-8 sm:pb-14 lg:grid-cols-[1fr_0.88fr] lg:gap-20 lg:px-12 lg:py-12"
        >
            <section class="mx-auto w-full max-w-xl lg:mx-0 lg:py-10">
                <p class="flex items-center gap-3 text-[0.65rem] font-semibold uppercase tracking-[0.27em] text-[#d8bd88] sm:text-xs">
                    <span class="h-px w-8 bg-[#c8ad78]/80" />
                    {{ copy.brand_eyebrow }}
                </p>

                <h2 class="mt-6 max-w-lg font-display text-4xl leading-[1.04] tracking-[-0.025em] text-[#f6f0e4] sm:text-5xl lg:text-6xl">
                    {{ copy.headline }}
                </h2>

                <div class="mt-7 h-px w-24 bg-gradient-to-r from-[#d7bc88]/80 to-transparent" />

                <p class="mt-6 max-w-md text-sm leading-7 text-white/58 sm:text-base sm:leading-8">
                    {{ copy.intro }}
                </p>

                <div class="mt-10 hidden items-center gap-4 border-t border-white/10 pt-5 text-[0.65rem] font-medium uppercase tracking-[0.18em] text-white/38 sm:flex">
                    <svg
                        aria-hidden="true"
                        viewBox="0 0 24 24"
                        fill="none"
                        class="size-5 text-[#d5bd8e]"
                    >
                        <path
                            d="m12 2.8 2.5 6.7 6.7 2.5-6.7 2.5-2.5 6.7-2.5-6.7-6.7-2.5 6.7-2.5L12 2.8Z"
                            stroke="currentColor"
                            stroke-width="1.1"
                            stroke-linejoin="round"
                        />
                        <path
                            d="m18.7 3.2.9 2.2 2.2.9-2.2.9-.9 2.2-.9-2.2-2.2-.9 2.2-.9.9-2.2Z"
                            fill="currentColor"
                        />
                    </svg>
                    <span>{{ copy.location }}</span>
                </div>
            </section>

            <section class="mx-auto w-full max-w-lg lg:mx-0 lg:justify-self-end">
                <div class="rounded-[1.65rem] border border-[#c8ad78]/25 bg-[linear-gradient(145deg,rgba(255,255,255,0.055),rgba(255,255,255,0.012)_38%,rgba(196,161,100,0.035))] p-px shadow-[0_28px_90px_rgba(0,0,0,0.48)]">
                    <div class="rounded-[calc(1.65rem-1px)] border border-white/[0.045] bg-[#0b0b0a]/95 px-5 py-7 backdrop-blur-xl sm:px-8 sm:py-9 lg:px-10 lg:py-10">
                        <div class="mb-7 flex items-center gap-3 border-b border-white/[0.08] pb-5">
                            <span class="flex size-9 items-center justify-center rounded-full border border-[#c8ad78]/30 bg-[#c8ad78]/[0.08] text-[#e3cca0]">
                                <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" class="size-4">
                                    <path d="M6.5 8V6a3.5 3.5 0 1 1 7 0v2m-8.2 0h9.4c.7 0 1.3.6 1.3 1.3v6.4c0 .7-.6 1.3-1.3 1.3H5.3c-.7 0-1.3-.6-1.3-1.3V9.3C4 8.6 4.6 8 5.3 8Z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                                    <path d="M10 11v2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-[0.62rem] font-semibold uppercase tracking-[0.2em] text-[#d8bd88]">
                                    {{ copy.admin }}
                                </p>
                                <p class="mt-1 text-xs text-white/40">
                                    {{ copy.region }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <h1 class="font-display text-3xl leading-tight tracking-[-0.015em] text-[#fbf7ee] sm:text-[2.15rem]">
                                {{ displayTitle }}
                            </h1>
                            <p class="text-sm leading-6 text-white/52">
                                {{ displayDescription }}
                            </p>
                        </div>

                        <div class="mt-7">
                            <slot />
                        </div>
                    </div>
                </div>

                <p class="mt-5 px-2 text-center text-[0.68rem] leading-5 text-white/35">
                    {{ copy.restricted }}
                </p>
            </section>
        </main>

        <footer class="relative z-10 mx-auto flex w-full max-w-7xl items-center justify-between gap-4 border-t border-white/[0.07] px-5 py-4 text-[0.62rem] font-medium uppercase tracking-[0.16em] text-white/32 sm:px-8 lg:px-12">
            <span>VVS FLAWLESS</span>
        </footer>
    </div>
</template>
