<script setup lang="ts">
import { useIntersectionObserver } from '@vueuse/core';
import hljs from 'highlight.js/lib/core';
import css from 'highlight.js/lib/languages/css';
import { computed, onUnmounted, ref } from 'vue';

hljs.registerLanguage('css', css);

const rootRef = ref<HTMLElement | null>(null);
const code = ref('');

const fullCode = `.card {
  --radius: 12px;
  --gap: 16px;
  padding: var(--gap);
  border-radius: calc(var(--radius) + var(--gap));
}`;

const isTyping = ref(false);
const hasStarted = ref(false);
let timeoutId: ReturnType<typeof setTimeout> | null = null;

const highlightedCode = computed(() => {
    if (!code.value) return '';
    try {
        return hljs.highlight(code.value, { language: 'css' }).value;
    } catch {
        return code.value.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }
});

function startTyping() {
    if (hasStarted.value) return;
    hasStarted.value = true;
    code.value = '';
    isTyping.value = true;

    let index = 0;
    const typeCode = () => {
        if (index < fullCode.length) {
            code.value += fullCode.charAt(index);
            index++;
            timeoutId = setTimeout(typeCode, Math.random() * 40 + 35);
        } else {
            isTyping.value = false;
        }
    };
    timeoutId = setTimeout(typeCode, 80);
}

useIntersectionObserver(
    rootRef,
    ([entry]) => {
        if (entry?.isIntersecting) startTyping();
    },
    { threshold: 0.25, rootMargin: '0px' },
);

onUnmounted(() => {
    if (timeoutId) clearTimeout(timeoutId);
});
</script>

<template>
    <div
        ref="rootRef"
        class="ide-preview relative min-h-[174px] overflow-hidden rounded-xl border border-neutral-200 bg-neutral-50 font-mono text-xs shadow-lg dark:border-white/10 dark:bg-neutral-900"
    >
        <div class="flex items-center justify-between border-b border-neutral-200 bg-neutral-100 px-3 py-2 dark:border-white/10 dark:bg-neutral-800">
            <div class="flex gap-1.5">
                <div class="h-1.5 w-1.5 rounded-full bg-neutral-400 dark:bg-neutral-500"></div>
                <div class="h-1.5 w-1.5 rounded-full bg-neutral-400 dark:bg-neutral-500"></div>
                <div class="h-1.5 w-1.5 rounded-full bg-neutral-400 dark:bg-neutral-500"></div>
            </div>
            <span class="text-[10px] text-neutral-500 dark:text-neutral-400">playground.css</span>
        </div>

        <div class="relative p-3 leading-relaxed">
            <pre
                class="ide-preview__pre m-0 font-mono whitespace-pre-wrap text-neutral-800 dark:text-neutral-200"
            ><code class="ide-preview__code inline" v-html="highlightedCode"></code><span v-if="isTyping" class="ide-preview__cursor" aria-hidden="true"></span></pre>
        </div>

        <div
            class="absolute -right-12 -bottom-12 h-64 w-64 rounded-full bg-brand/10 blur-3xl transition-all duration-700 group-hover:bg-brand/20"
        ></div>
    </div>
</template>

<style scoped>
@reference "../../../css/app.css";

.ide-preview__pre {
    min-height: 1.5em;
}

.ide-preview__cursor {
    display: inline-block;
    width: 2px;
    height: 1em;
    margin-left: 1px;
    vertical-align: text-bottom;
    background-color: var(--color-brand, #2ab193);
    animation: blink 0.9s step-end infinite;
}

@keyframes blink {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0;
    }
}

.ide-preview__code :deep(.hljs-selector-tag),
.ide-preview__code :deep(.hljs-selector-class) {
    @apply text-purple-600 dark:text-purple-400;
}

.ide-preview__code :deep(.hljs-attribute),
.ide-preview__code :deep(.hljs-attr) {
    @apply text-cyan-600 dark:text-cyan-400;
}

.ide-preview__code :deep(.hljs-number),
.ide-preview__code :deep(.hljs-literal) {
    @apply text-orange-600 dark:text-orange-400;
}

.ide-preview__code :deep(.hljs-built_in),
.ide-preview__code :deep(.hljs-function) {
    @apply text-blue-600 dark:text-blue-400;
}

.ide-preview__code :deep(.hljs-variable) {
    @apply text-green-600 dark:text-green-400;
}
</style>
