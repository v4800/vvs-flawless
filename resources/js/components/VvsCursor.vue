<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const cursor = ref(null);
const dot = ref(null);

const interactiveSelector = [
    'a',
    'button',
    'input',
    'textarea',
    'select',
    '[role="button"]',
].join(',');

const handlePointerMove = (event) => {
    if (!cursor.value || !dot.value) {
        return;
    }

    const transform = `translate3d(${event.clientX}px, ${event.clientY}px, 0) translate(-50%, -50%)`;

    cursor.value.style.transform = transform;
    dot.value.style.transform = transform;

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
    | CURSEUR VVS
    |--------------------------------------------------------------------------
    */

    window.addEventListener('pointermove', handlePointerMove);

    document.addEventListener('mouseleave', handlePointerLeave);

    window.addEventListener('pointerdown', handlePointerDown);

    window.addEventListener('pointerup', handlePointerUp);
});

onBeforeUnmount(() => {
    window.removeEventListener('pointermove', handlePointerMove);

    document.removeEventListener('mouseleave', handlePointerLeave);

    window.removeEventListener('pointerdown', handlePointerDown);

    window.removeEventListener('pointerup', handlePointerUp);
});
</script>

<template>
    <!-- HALO PRINCIPAL -->

    <div
        ref="cursor"
        aria-hidden="true"
        class="vvs-cursor pointer-events-none fixed top-0 left-0 z-[9999] hidden h-8 w-8 rounded-full border border-amber-200/55 opacity-0 shadow-[0_0_18px_rgba(251,191,36,0.16)] transition-[width,height,border-color,background-color,opacity] duration-150 md:block"
    >
        <!-- DIAMANT -->

        <span
            class="vvs-spark absolute -top-1 -right-1 text-[8px] text-amber-200"
        >
            ✦
        </span>
    </div>

    <!-- POINT CENTRAL -->

    <div
        ref="dot"
        aria-hidden="true"
        class="pointer-events-none fixed top-0 left-0 z-[10000] hidden h-1.5 w-1.5 rounded-full bg-amber-200 opacity-0 shadow-[0_0_10px_rgba(253,230,138,1)] md:block"
    ></div>
</template>

<style scoped>
.vvs-cursor {
    will-change: transform;
    contain: layout paint style;
}

.vvs-cursor-active {
    width: 44px;
    height: 44px;

    border-color: rgba(252, 211, 77, 0.85);

    background: rgba(252, 211, 77, 0.055);

    box-shadow: 0 0 24px rgba(251, 191, 36, 0.18);
}

.vvs-cursor-click {
    width: 24px;
    height: 24px;
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
