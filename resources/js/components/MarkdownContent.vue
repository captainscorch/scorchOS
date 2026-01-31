<script setup lang="ts">
import { marked } from '@/utils/markdown';
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

const html = computed(() => {
    if (!props.content) return '';
    return marked.parse(props.content) as string;
});
</script>

<template>
    <div class="markdown-content prose" :class="props.class" v-html="html" />
</template>
