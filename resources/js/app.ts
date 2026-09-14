import '../css/storefront-backdrop.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'VVS FLAWLESS';

const forceStorefrontBackdrop = () => {
    const styleId = 'vvs-storefront-backdrop-runtime';

    if (document.getElementById(styleId)) {
        return;
    }

    const style = document.createElement('style');
    style.id = styleId;
    style.textContent = `
        .vvs-storefront {
            background-color: #050506 !important;
            background-image:
                radial-gradient(circle 680px at 12% 7%, rgb(217 166 46 / 0.13), transparent 68%),
                radial-gradient(circle 520px at 88% 20%, rgb(255 255 255 / 0.07), transparent 70%),
                radial-gradient(circle 620px at 18% 53%, rgb(161 161 170 / 0.085), transparent 72%),
                radial-gradient(circle 560px at 82% 78%, rgb(217 166 46 / 0.075), transparent 72%),
                radial-gradient(circle 480px at 52% 96%, rgb(255 255 255 / 0.045), transparent 74%),
                linear-gradient(180deg, #050506 0%, #070707 46%, #050505 100%) !important;
            background-position: center !important;
            background-size: auto !important;
            background-repeat: no-repeat !important;
        }
    `;

    document.head.appendChild(style);
};

forceStorefrontBackdrop();

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
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
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
