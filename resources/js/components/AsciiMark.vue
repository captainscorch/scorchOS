<script setup lang="ts">
import AsciiObject from '@/components/canvasui/AsciiObject.vue';
import { onUnmounted, ref } from 'vue';

defineProps<{
    color: string;
}>();

const objectRef = ref<InstanceType<typeof AsciiObject> | null>(null);
let idleTimer: ReturnType<typeof setTimeout> | null = null;

const cancelReset = () => {
    if (idleTimer) {
        clearTimeout(idleTimer);
        idleTimer = null;
    }
};

// Spun into an odd angle and left alone, the mark eases back to where it started
const scheduleReset = () => {
    cancelReset();
    idleTimer = setTimeout(() => objectRef.value?.resetView(), 3000);
};

onUnmounted(cancelReset);
</script>

<template>
    <!-- touch-pan-y overrides the canvas' own touch-action so vertical swipes still scroll the page -->
    <AsciiObject
        ref="objectRef"
        src="/img/captainscorch_logo.svg"
        :ascii="true"
        :cell-size="6"
        :colored="false"
        :color="color"
        :scale="3"
        :contrast="1.2"
        :edge-contrast="2.4"
        :orbit="true"
        :auto-rotate="false"
        :float-intensity="1.2"
        :rotation-intensity="0.8"
        :float-speed="1.4"
        background=""
        class="h-full w-full cursor-grab active:cursor-grabbing [&_canvas]:touch-pan-y!"
        @pointerdown="cancelReset"
        @pointerup="scheduleReset"
        @pointercancel="scheduleReset"
        @pointerleave="scheduleReset"
    />
</template>
