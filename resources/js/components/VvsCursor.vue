<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { Trail, Ripple } from 'mouse-animations';

const cursor = ref(null);
const dot = ref(null);

let mouseX = 0;
let mouseY = 0;

let cursorX = 0;
let cursorY = 0;

let animationFrame = null;

let trailEffect = null;
let rippleEffect = null;

const interactiveSelector = [
    'a',
    'button',
    'input',
    'textarea',
    'select',
    '[role="button"]',
].join(',');

const animateCursor = () => {
    cursorX += (mouseX - cursorX) * 0.13;

    cursorY += (mouseY - cursorY) * 0.13;

    if (cursor.value) {
        cursor.value.style.transform = `translate3d(${cursorX}px, ${cursorY}px, 0) translate(-50%, -50%)`;
    }

    if (dot.value) {
        dot.value.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0) translate(-50%, -50%)`;
    }

    animationFrame = requestAnimationFrame(animateCursor);
};

const handlePointerMove = (event) => {
    mouseX = event.clientX;
    mouseY = event.clientY;

    if (!cursor.value || !dot.value) {
        return;
    }

    cursor.value.style.opacity = '1';
    dot.value.style.opacity = '1';

    const target = event.target instanceof Element ? event.target : null;

    const interactive = target?.closest(interactiveSelector);

    if (interactive) {
        cursor.value.classList.add('vvs-cursor-active');
    } else {
        cursor.value.classList.remove('vvs-cursor-active');
    }
};

const handlePointerLeave = () => {
    if (cursor.value) {
        cursor.value.style.opacity = '0';
    }

    if (dot.value) {
        dot.value.style.opacity = '0';
    }
};

const handlePointerDown = () => {
    cursor.value?.classList.add('vvs-cursor-click');
};

const handlePointerUp = () => {
    cursor.value?.classList.remove('vvs-cursor-click');
};

onMounted(() => {
    const finePointer = window.matchMedia('(pointer: fine)').matches;

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    ).matches;

    if (!finePointer || reducedMotion) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | TRAÎNÉE DORÉE DERRIÈRE LA SOURIS
    |--------------------------------------------------------------------------
    */

    trailEffect = new Trail({
        color: '#fcd34d',
        size: 6,
        length: 18,
        decay: 0.055,
        blur: 1.2,
    });

    /*
    |--------------------------------------------------------------------------
    | PETIT EFFET AU CLIC
    |--------------------------------------------------------------------------
    */

    rippleEffect = new Ripple({
        color: 'rgba(252, 211, 77, 0.20)',
        duration: 480,
        maxSize: 70,
    });

    /*
    |--------------------------------------------------------------------------
    | CURSEUR VVS
    |--------------------------------------------------------------------------
    */

    window.addEventListener('pointermove', handlePointerMove);

    document.addEventListener('mouseleave', handlePointerLeave);

    window.addEventListener('pointerdown', handlePointerDown);

    window.addEventListener('pointerup', handlePointerUp);

    animationFrame = requestAnimationFrame(animateCursor);
});

onBeforeUnmount(() => {
    window.removeEventListener('pointermove', handlePointerMove);

    document.removeEventListener('mouseleave', handlePointerLeave);

    window.removeEventListener('pointerdown', handlePointerDown);

    window.removeEventListener('pointerup', handlePointerUp);

    if (animationFrame) {
        cancelAnimationFrame(animationFrame);
    }

    trailEffect?.destroy();
    rippleEffect?.destroy();
});
</script>

<template>
    <!-- HALO PRINCIPAL -->

    <div
        ref="cursor"
        class="vvs-cursor pointer-events-none fixed top-0 left-0 z-[9999] hidden h-10 w-10 rounded-full border border-amber-300/50 opacity-0 shadow-[0_0_25px_rgba(251,191,36,0.18)] backdrop-blur-[1px] transition-[width,height,border-color,background-color,opacity] duration-200 md:block"
    >
        <!-- DIAMANT -->

        <span
            class="vvs-spark absolute -top-1.5 -right-1.5 text-[9px] text-amber-200"
        >
            ✦
        </span>
    </div>

    <!-- POINT CENTRAL -->

    <div
        ref="dot"
        class="pointer-events-none fixed top-0 left-0 z-[10000] hidden h-1.5 w-1.5 rounded-full bg-amber-200 opacity-0 shadow-[0_0_10px_rgba(253,230,138,1)] md:block"
    ></div>
</template>

<style scoped>
.vvs-cursor {
    will-change: transform;
}

.vvs-cursor-active {
    width: 58px;
    height: 58px;

    border-color: rgba(252, 211, 77, 0.85);

    background: rgba(252, 211, 77, 0.055);

    box-shadow: 0 0 35px rgba(251, 191, 36, 0.2);
}

.vvs-cursor-click {
    width: 28px;
    height: 28px;
}

.vvs-spark {
    animation: vvsSparkle 1.8s ease-in-out infinite;
}

@keyframes vvsSparkle {
    0%,
    100% {
        opacity: 0.35;
        transform: scale(0.7) rotate(0deg);
    }

    50% {
        opacity: 1;
        transform: scale(1.25) rotate(45deg);
    }
}
</style>
