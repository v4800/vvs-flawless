<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const viewerHost = ref(null);

const loading = ref(true);
const error = ref(false);
const progress = ref(0);

let viewer = null;

onMounted(async () => {
    try {
        await import('@google/model-viewer');

        if (!viewerHost.value) {
            return;
        }

        viewer = document.createElement('model-viewer');

        /*
        |--------------------------------------------------------------------------
        | MODELE 3D
        |--------------------------------------------------------------------------
        */

        viewer.setAttribute('src', '/models/vvs-watch.glb');
        viewer.setAttribute('alt', 'Montre VVS FLAWLESS 3D');

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | PAS de rotation auto
        | PAS de camera-controls
        | => montre totalement statique
        */

        viewer.removeAttribute('auto-rotate');
        viewer.removeAttribute('camera-controls');

        /*
        |--------------------------------------------------------------------------
        | ORIENTATION
        |--------------------------------------------------------------------------
        | Avant elle etait verticale.
        | On la remet horizontale.
        */

        viewer.setAttribute('orientation', '0deg 0deg -90deg');

        /*
        |--------------------------------------------------------------------------
        | CAMERA
        |--------------------------------------------------------------------------
        */

        viewer.setAttribute('camera-orbit', '0deg 78deg 92%');
        viewer.setAttribute('field-of-view', '22deg');

        /*
        |--------------------------------------------------------------------------
        | LOOK / BRILLANCE
        |--------------------------------------------------------------------------
        */

        viewer.setAttribute('environment-image', 'neutral');
        viewer.setAttribute('shadow-intensity', '1.45');
        viewer.setAttribute('shadow-softness', '0.65');
        viewer.setAttribute('exposure', '1.45');
        viewer.setAttribute('tone-mapping', 'commerce');

        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT
        |--------------------------------------------------------------------------
        */

        viewer.setAttribute('loading', 'eager');
        viewer.setAttribute('reveal', 'auto');

        /*
        |--------------------------------------------------------------------------
        | STYLE
        |--------------------------------------------------------------------------
        */

        viewer.style.width = '100%';
        viewer.style.height = '100%';
        viewer.style.display = 'block';
        viewer.style.background = 'transparent';
        viewer.style.setProperty('--poster-color', 'transparent');

        /*
        |--------------------------------------------------------------------------
        | EVENTS
        |--------------------------------------------------------------------------
        */

        viewer.addEventListener('progress', (event) => {
            const total = event.detail?.totalProgress ?? 0;
            progress.value = Math.round(total * 100);
        });

        viewer.addEventListener('load', () => {
            progress.value = 100;
            loading.value = false;
            error.value = false;
        });

        viewer.addEventListener('error', (event) => {
            console.error('Erreur GLB VVS :', event);
            loading.value = false;
            error.value = true;
        });

        viewerHost.value.replaceChildren(viewer);
    } catch (err) {
        console.error('Impossible de charger model-viewer :', err);
        loading.value = false;
        error.value = true;
    }
});

onBeforeUnmount(() => {
    viewer?.remove();
    viewer = null;
});
</script>

<template>
    <div
        class="relative h-[520px] w-full overflow-hidden rounded-[32px] border border-white/[0.08] bg-black sm:h-[620px] lg:h-[670px]"
    >
        <!-- fond -->
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(251,191,36,0.07),transparent_60%)]"
        ></div>

        <div
            class="hero-orb pointer-events-none absolute left-1/2 top-1/2 h-[420px] w-[420px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-300/[0.09] blur-[100px]"
        ></div>

        <div
            class="pointer-events-none absolute left-1/2 top-1/2 h-[72%] w-[72%] -translate-x-1/2 -translate-y-1/2 rounded-full border border-amber-300/[0.08]"
        ></div>

        <div
            class="pointer-events-none absolute left-1/2 top-1/2 h-[90%] w-[90%] -translate-x-1/2 -translate-y-1/2 rounded-full border border-white/[0.035]"
        ></div>

        <!-- viewer -->
        <div
            ref="viewerHost"
            class="absolute inset-0 z-10"
        ></div>

        <!-- reflet animé pour donner plus de brillance -->
        <div
            v-if="!loading && !error"
            class="hero-shine pointer-events-none absolute inset-y-[16%] left-[-22%] z-20 w-[24%] -skew-x-12 bg-gradient-to-r from-transparent via-white/25 to-transparent blur-[10px]"
        ></div>

        <!-- petites etincelles -->
        <span
            v-if="!loading && !error"
            class="sparkle sparkle-1 pointer-events-none absolute right-[19%] top-[24%] z-20 text-4xl text-white"
        >
            ✦
        </span>

        <span
            v-if="!loading && !error"
            class="sparkle sparkle-2 pointer-events-none absolute right-[26%] top-[58%] z-20 text-3xl text-amber-200"
        >
            ✦
        </span>

        <span
            v-if="!loading && !error"
            class="sparkle sparkle-3 pointer-events-none absolute left-[18%] bottom-[20%] z-20 text-3xl text-white"
        >
            ✦
        </span>

        <!-- chargement -->
        <div
            v-if="loading && !error"
            class="pointer-events-none absolute inset-0 z-30 flex items-center justify-center"
        >
            <div
                class="w-[220px] rounded-2xl border border-white/10 bg-black/75 px-6 py-5 text-center shadow-2xl backdrop-blur-xl"
            >
                <p
                    class="text-[9px] font-black uppercase tracking-[0.28em] text-amber-300"
                >
                    VVS FLAWLESS
                </p>

                <p
                    class="mt-3 text-2xl font-black text-white"
                >
                    {{ progress }}%
                </p>

                <div
                    class="mt-4 h-[2px] overflow-hidden rounded-full bg-white/10"
                >
                    <div
                        class="h-full bg-amber-300 transition-[width] duration-300"
                        :style="{ width: `${progress}%` }"
                    ></div>
                </div>

                <p
                    class="mt-3 text-[8px] font-semibold uppercase tracking-[0.15em] text-zinc-600"
                >
                    Chargement de la montre 3D
                </p>
            </div>
        </div>

        <!-- erreur -->
        <div
            v-if="error"
            class="absolute inset-0 z-30 flex items-center justify-center p-6"
        >
            <div
                class="rounded-2xl border border-red-500/20 bg-black/80 px-7 py-6 text-center"
            >
                <p
                    class="text-xs font-black uppercase tracking-[0.15em] text-red-300"
                >
                    Modèle 3D introuvable
                </p>

                <p
                    class="mt-3 text-[10px] text-zinc-500"
                >
                    public/models/vvs-watch.glb
                </p>
            </div>
        </div>

        <!-- badges -->
        <div
            class="absolute left-5 top-5 z-20 rounded-2xl border border-white/10 bg-black/55 px-5 py-4 backdrop-blur-xl"
        >
            <p class="text-xs font-black text-white">
                Moissanite VVS
            </p>

            <p class="mt-1 text-xs text-zinc-500">
                Finition iced-out
            </p>
        </div>

        <div
            class="absolute right-5 top-5 z-20 rounded-2xl border border-white/10 bg-black/55 px-5 py-4 backdrop-blur-xl"
        >
            <p class="text-xs font-black text-white">
                Modèle 3D
            </p>

            <p class="mt-1 text-xs text-zinc-500">
                Statique
            </p>
        </div>

        <div
            class="absolute bottom-5 left-5 z-20 rounded-2xl border border-white/10 bg-black/55 px-5 py-4 backdrop-blur-xl"
        >
            <p class="text-xs font-black text-white">
                Couleur D
            </p>

            <p class="mt-1 text-xs text-zinc-500">
                Éclat clair et lumineux
            </p>
        </div>

        <div
            class="absolute bottom-5 right-5 z-20 rounded-2xl border border-white/10 bg-black/55 px-5 py-4 backdrop-blur-xl"
        >
            <p class="text-xs font-black text-white">
                AP look alike
            </p>

            <p class="mt-1 text-xs text-zinc-500">
                Sans marquage sur la montre
            </p>
        </div>
    </div>
</template>

<style scoped>
.hero-orb {
    animation: glowPulse 3.8s ease-in-out infinite;
}

.hero-shine {
    animation: shineSweep 4.8s ease-in-out infinite;
}

.sparkle {
    animation: sparklePulse 2.1s ease-in-out infinite;
    text-shadow:
        0 0 10px rgba(255, 255, 255, 0.45),
        0 0 20px rgba(251, 191, 36, 0.28);
}

.sparkle-2 {
    animation-delay: 0.4s;
}

.sparkle-3 {
    animation-delay: 0.9s;
}

@keyframes glowPulse {
    0%,
    100% {
        opacity: 0.70;
        transform: translate(-50%, -50%) scale(0.98);
    }
    50% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.04);
    }
}

@keyframes shineSweep {
    0% {
        transform: translateX(0) skewX(-12deg);
        opacity: 0;
    }
    12% {
        opacity: 0.75;
    }
    45% {
        transform: translateX(440%) skewX(-12deg);
        opacity: 0.45;
    }
    60%,
    100% {
        transform: translateX(520%) skewX(-12deg);
        opacity: 0;
    }
}

@keyframes sparklePulse {
    0%,
    100% {
        opacity: 0.25;
        transform: scale(0.72) rotate(0deg);
    }
    50% {
        opacity: 1;
        transform: scale(1.18) rotate(18deg);
    }
}
</style>