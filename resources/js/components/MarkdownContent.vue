<script setup lang="ts">
import { marked } from 'marked';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        content: string;
        class?: string;
    }>(),
    {
        content: '',
        class: '',
    },
);

// Configure marked for safe rendering
marked.setOptions({
    breaks: true,
    gfm: true,
});

const html = computed(() => {
    if (!props.content) return '';
    return marked.parse(props.content) as string;
});
</script>

<template>
    <div class="markdown-content" :class="props.class" v-html="html" />
</template>

<style scoped>
@reference "../../css/app.css";

.markdown-content {
    @apply space-y-6;
}

.markdown-content :deep(h1) {
    @apply font-work-sans text-3xl font-bold text-neutral-900 dark:text-white;
}

.markdown-content :deep(h2) {
    @apply mt-8 font-work-sans text-xl font-bold text-neutral-900 dark:text-white;
}

.markdown-content :deep(h3) {
    @apply mt-6 font-work-sans text-lg font-bold text-neutral-900 dark:text-white;
}

.markdown-content :deep(p) {
    @apply text-base leading-relaxed text-neutral-600 dark:text-neutral-300;
}

.markdown-content :deep(strong) {
    @apply font-bold text-neutral-900 dark:text-white;
}

.markdown-content :deep(a) {
    @apply text-neutral-900 underline underline-offset-2 transition-colors hover:text-neutral-600 dark:text-white dark:hover:text-neutral-300;
}

.markdown-content :deep(ul) {
    @apply my-4 list-inside list-disc space-y-2 text-neutral-600 dark:text-neutral-300;
}

.markdown-content :deep(ol) {
    @apply my-4 list-inside list-decimal space-y-2 text-neutral-600 dark:text-neutral-300;
}

.markdown-content :deep(li) {
    @apply text-base leading-relaxed;
}

.markdown-content :deep(blockquote) {
    @apply my-6 border-l-4 border-neutral-300 pl-4 text-neutral-500 italic dark:border-neutral-700 dark:text-neutral-400;
}

.markdown-content :deep(code) {
    @apply rounded bg-neutral-100 px-1.5 py-0.5 font-mono text-sm text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200;
}

.markdown-content :deep(pre) {
    @apply my-4 overflow-x-auto rounded-lg bg-neutral-100 p-4 dark:bg-neutral-800;
}

.markdown-content :deep(pre code) {
    @apply bg-transparent p-0;
}

.markdown-content :deep(hr) {
    @apply my-8 border-neutral-200 dark:border-neutral-800;
}
</style>
