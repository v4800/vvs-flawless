<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
});

const page = usePage();
const currentUrl = computed(() => page.url);
const userName = computed(() => page.props.auth?.user?.name ?? 'Administration');

const links = [
    {
        label: 'Vue d’ensemble',
        href: '/admin',
        active: (url) => url === '/admin',
    },
    {
        label: 'Réservations',
        href: '/admin/reservations',
        active: (url) =>
            url.startsWith('/admin/reservations') &&
            !url.includes('status=Rendez-vous'),
    },
    {
        label: 'Rendez-vous',
        href: '/admin/reservations?status=Rendez-vous%20planifi%C3%A9&sort=appointment',
        active: (url) => url.includes('status=Rendez-vous'),
    },
    {
        label: 'Ventes terminées',
        href: '/admin/reservations?status=Remise%20effectu%C3%A9e%20et%20solde%20pay%C3%A9',
        active: (url) => url.includes('status=Remise'),
    },
];

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="min-h-screen bg-[#050505] text-white lg:flex">
        <aside
            class="border-b border-white/10 bg-black lg:sticky lg:top-0 lg:h-screen lg:w-64 lg:shrink-0 lg:border-r lg:border-b-0"
        >
            <div class="flex h-full flex-col">
                <div class="border-b border-white/10 px-5 py-6">
                    <p
                        class="text-[10px] font-black tracking-[0.35em] text-amber-300 uppercase"
                    >
                        VVS FLAWLESS
                    </p>
                    <p class="mt-2 text-sm font-bold text-zinc-300">
                        Administration
                    </p>
                </div>

                <nav
                    class="flex gap-2 overflow-x-auto p-3 lg:flex-1 lg:flex-col lg:overflow-visible lg:p-4"
                    aria-label="Administration"
                >
                    <Link
                        v-for="link in links"
                        :key="link.href"
                        :href="link.href"
                        :class="[
                            'shrink-0 rounded-xl border px-4 py-3 text-sm font-bold transition',
                            link.active(currentUrl)
                                ? 'border-amber-300/30 bg-amber-300/10 text-amber-200'
                                : 'border-transparent text-zinc-400 hover:border-white/10 hover:bg-white/5 hover:text-white',
                        ]"
                    >
                        {{ link.label }}
                    </Link>
                </nav>

                <div
                    class="hidden border-t border-white/10 p-4 lg:block"
                >
                    <p class="truncate px-3 text-xs text-zinc-500">
                        {{ userName }}
                    </p>
                    <button
                        type="button"
                        class="mt-3 w-full rounded-xl border border-white/10 px-4 py-3 text-left text-sm font-bold text-zinc-300 hover:border-white/30 hover:text-white"
                        @click="logout"
                    >
                        Déconnexion
                    </button>
                </div>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <header
                class="border-b border-white/10 bg-black/80 px-4 py-5 backdrop-blur-xl sm:px-6 lg:px-8"
            >
                <div
                    class="mx-auto flex max-w-[1500px] items-center justify-between gap-4"
                >
                    <div>
                        <h1 class="text-2xl font-black sm:text-3xl">
                            {{ title }}
                        </h1>
                        <p
                            v-if="subtitle"
                            class="mt-1 text-sm text-zinc-500"
                        >
                            {{ subtitle }}
                        </p>
                    </div>

                    <button
                        type="button"
                        class="rounded-xl border border-white/10 px-4 py-2 text-xs font-bold text-zinc-300 hover:border-white/30 lg:hidden"
                        @click="logout"
                    >
                        Déconnexion
                    </button>
                </div>
            </header>

            <main class="mx-auto max-w-[1500px] p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
