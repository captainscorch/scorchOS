<script setup lang="ts">
import CommandMenu from '@/components/CommandMenu.vue';
import GlobalContextMenu from '@/components/GlobalContextMenu.vue';
import { Toaster } from '@/components/ui/sonner';
import { onBeforeUnmount, onMounted } from 'vue';

// Safari extends the root background colour behind its own chrome, so that colour has
// to follow what the page paints at the viewport edges: the flat html colour elsewhere,
// and on the landing page the gradient, blending into the teal it ends on. theme-color
// gets the same value for the browsers that tint from it instead.
const THEME_COLORS = {
    light: { page: '#ffffff', landing: '#f2f0ef', bottom: '#39ab92' },
    dark: { page: '#000000', landing: '#040e0c', bottom: '#103f35' },
};

// The gradient only turns teal over the last fifth of the landing page.
const GRADIENT_TAIL = 0.2;
const BLEND_STEPS = 24;

const channels = (hex: string): number[] => [1, 3, 5].map((index) => parseInt(hex.slice(index, index + 2), 16));

const blend = (from: string, to: string, amount: number): string => {
    const target = channels(to);

    return (
        '#' +
        channels(from)
            .map((value, index) =>
                Math.round(value + (target[index] - value) * amount)
                    .toString(16)
                    .padStart(2, '0'),
            )
            .join('')
    );
};

const tailProgress = (): number => {
    const { scrollHeight } = document.documentElement;
    if (!scrollHeight) return 0;

    const reached = (window.scrollY + window.innerHeight) / scrollHeight;
    const progress = Math.min(1, Math.max(0, (reached - (1 - GRADIENT_TAIL)) / GRADIENT_TAIL));

    return Math.round(progress * BLEND_STEPS) / BLEND_STEPS;
};

let currentColor = '';

const applyEdgeColor = () => {
    const root = document.documentElement;
    const palette = root.classList.contains('dark') ? THEME_COLORS.dark : THEME_COLORS.light;
    const next = root.classList.contains('page-welcome') ? blend(palette.landing, palette.bottom, tailProgress()) : palette.page;
    if (next === currentColor) return;

    currentColor = next;
    root.style.backgroundColor = next;

    const meta = document.querySelector<HTMLMetaElement>('meta[name="theme-color"]');
    if (meta) {
        meta.content = next;
    }
};

let frame = 0;

const scheduleEdgeColor = () => {
    if (frame) return;

    frame = requestAnimationFrame(() => {
        frame = 0;
        applyEdgeColor();
    });
};

let themeObserver: MutationObserver | null = null;

onMounted(() => {
    applyEdgeColor();
    themeObserver = new MutationObserver(scheduleEdgeColor);
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    window.addEventListener('scroll', scheduleEdgeColor, { passive: true });
    window.addEventListener('resize', scheduleEdgeColor, { passive: true });
});

onBeforeUnmount(() => {
    themeObserver?.disconnect();
    window.removeEventListener('scroll', scheduleEdgeColor);
    window.removeEventListener('resize', scheduleEdgeColor);
    if (frame) cancelAnimationFrame(frame);
});
</script>

<template>
    <GlobalContextMenu>
        <slot />
        <CommandMenu />
        <Toaster />
    </GlobalContextMenu>
</template>
