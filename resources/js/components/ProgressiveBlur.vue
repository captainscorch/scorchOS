<script setup lang="ts">
defineProps<{
    position?: 'top' | 'bottom';
}>();
</script>

<template>
    <div class="progressive-blur-container pointer-events-none" :class="position || 'top'">
        <div class="blur-filter"></div>
        <div class="blur-filter"></div>
        <div class="blur-filter"></div>
        <div class="blur-filter"></div>
        <div class="blur-filter"></div>
        <div class="blur-filter"></div>
        <div class="blur-filter"></div>
        <div class="gradient"></div>
    </div>
</template>

<style scoped>
.blur-filter {
    position: absolute;
    inset: 0;
}

/* Top Position - Masks fade from opaque at top (content hidden/blurred) to transparent at bottom (content visible) */
.progressive-blur-container.top {
    transform: rotate(180deg);
}

/* Bottom Position (Default) - Masks fade from opaque at bottom to transparent at top */
.progressive-blur-container .blur-filter:nth-child(1) {
    backdrop-filter: blur(1px);
    -webkit-backdrop-filter: blur(1px);
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 10%, rgba(0, 0, 0, 1) 30%, rgba(0, 0, 0, 0) 40%);
    -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 10%, rgba(0, 0, 0, 1) 30%, rgba(0, 0, 0, 0) 40%);
}

.progressive-blur-container .blur-filter:nth-child(2) {
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 10%, rgba(0, 0, 0, 1) 20%, rgba(0, 0, 0, 1) 40%, rgba(0, 0, 0, 0) 50%);
    -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 10%, rgba(0, 0, 0, 1) 20%, rgba(0, 0, 0, 1) 40%, rgba(0, 0, 0, 0) 50%);
}

.progressive-blur-container .blur-filter:nth-child(3) {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 15%, rgba(0, 0, 0, 1) 30%, rgba(0, 0, 0, 1) 50%, rgba(0, 0, 0, 0) 60%);
    -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 15%, rgba(0, 0, 0, 1) 30%, rgba(0, 0, 0, 1) 50%, rgba(0, 0, 0, 0) 60%);
}

.progressive-blur-container .blur-filter:nth-child(4) {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 20%, rgba(0, 0, 0, 1) 40%, rgba(0, 0, 0, 1) 60%, rgba(0, 0, 0, 0) 70%);
    -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 20%, rgba(0, 0, 0, 1) 40%, rgba(0, 0, 0, 1) 60%, rgba(0, 0, 0, 0) 70%);
}

.progressive-blur-container .blur-filter:nth-child(5) {
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, 1) 60%, rgba(0, 0, 0, 1) 80%, rgba(0, 0, 0, 0) 90%);
    -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, 1) 60%, rgba(0, 0, 0, 1) 80%, rgba(0, 0, 0, 0) 90%);
}

.progressive-blur-container .blur-filter:nth-child(6) {
    backdrop-filter: blur(32px);
    -webkit-backdrop-filter: blur(32px);
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 60%, rgba(0, 0, 0, 1) 80%);
    -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 60%, rgba(0, 0, 0, 1) 80%);
}

.progressive-blur-container .blur-filter:nth-child(7) {
    backdrop-filter: blur(64px);
    -webkit-backdrop-filter: blur(64px);
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 70%, rgba(0, 0, 0, 1) 100%);
    -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 70%, rgba(0, 0, 0, 1) 100%);
}

.progressive-blur-container .gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(transparent, #ffffff);
}

.dark .progressive-blur-container .gradient {
    background: linear-gradient(to top, rgba(0, 0, 0, 0.6), transparent);
}
</style>
