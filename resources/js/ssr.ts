import type { Page } from '@inertiajs/core';
import { createInertiaApp } from '@inertiajs/vue3';
import startInertiaServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, h, type DefineComponent } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'VVS FLAWLESS';

/*
|--------------------------------------------------------------------------
| SSR REQUEST ISOLATION
|--------------------------------------------------------------------------
|
| Inertia Vue 3.7 utilise plusieurs refs partagées au niveau du module.
| On sérialise donc les rendus SSR afin qu'une requête ne puisse pas
| contaminer la langue ou les props de la suivante.
|
*/

let renderQueue: Promise<void> = Promise.resolve();

const renderOnePage = (page: Page) =>
    createInertiaApp({
        page,
        render: renderToString,

        title: (title) => (title ? `${title} - ${appName}` : appName),

        resolve: (name) =>
            resolvePageComponent(
                `./pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./pages/**/*.vue'),
            ),

        layout: (name) => {
            switch (true) {
                case name === 'Welcome':
                    return null;

                case name.startsWith('auth/'):
                    return AuthLayout;

                case name.startsWith('settings/'):
                    return [AppLayout, SettingsLayout];

                default:
                    return AppLayout;
            }
        },

        setup({ App, props, plugin }) {
            return createSSRApp({
                render: () => h(App, props),
            }).use(plugin);
        },
    });

const renderPage = (page: Page) => {
    const job = renderQueue.then(() => renderOnePage(page));

    renderQueue = job.then(
        () => undefined,
        () => undefined,
    );

    return job;
};

/*
|--------------------------------------------------------------------------
| PRODUCTION SSR SERVER
|--------------------------------------------------------------------------
|
| En production le bundle Node démarre réellement le serveur HTTP.
| En mode Vite HOT, le renderer est simplement exporté à Vite.
|
*/

if (import.meta.env.PROD) {
    startInertiaServer(renderPage);
}

export default renderPage;
