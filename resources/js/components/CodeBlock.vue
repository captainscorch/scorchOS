<script setup lang="ts">
import hljs from 'highlight.js/lib/core';
import css from 'highlight.js/lib/languages/css';
import javascript from 'highlight.js/lib/languages/javascript';
import json from 'highlight.js/lib/languages/json';
import markdown from 'highlight.js/lib/languages/markdown';
import php from 'highlight.js/lib/languages/php';
import sql from 'highlight.js/lib/languages/sql';
import typescript from 'highlight.js/lib/languages/typescript';
import xml from 'highlight.js/lib/languages/xml';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

// Register languages
hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('typescript', typescript);
hljs.registerLanguage('css', css);
hljs.registerLanguage('html', xml);
hljs.registerLanguage('xml', xml);
hljs.registerLanguage('json', json);
hljs.registerLanguage('php', php);
hljs.registerLanguage('sql', sql);
hljs.registerLanguage('mysql', sql);
hljs.registerLanguage('markdown', markdown);

const props = withDefaults(
    defineProps<{
        code: string;
        language?: string;
        showLineNumbers?: boolean;
        filename?: string;
        isPlayground?: boolean;
    }>(),
    {
        language: 'plaintext',
        showLineNumbers: false,
        filename: '',
        isPlayground: false,
    },
);

const { t } = useI18n();
const copied = ref(false);

const highlightedCode = computed(() => {
    const lang = props.language.toLowerCase();

    // Check if language is registered
    if (hljs.getLanguage(lang)) {
        try {
            return hljs.highlight(props.code, { language: lang }).value;
        } catch {
            return escapeHtml(props.code);
        }
    }

    // Fallback to auto-detection or plain text
    try {
        return hljs.highlightAuto(props.code).value;
    } catch {
        return escapeHtml(props.code);
    }
});

const lines = computed(() => {
    return props.code.split('\n');
});

function escapeHtml(text: string): string {
    return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

async function copyCode() {
    try {
        await navigator.clipboard.writeText(props.code);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2000);
    } catch (err) {
        console.error('Failed to copy code:', err);
    }
}

const displayLanguage = computed(() => {
    const langMap: Record<string, string> = {
        javascript: 'JS',
        typescript: 'TS',
        css: 'CSS',
        html: 'HTML',
        json: 'JSON',
        php: 'PHP',
        sql: 'SQL',
        mysql: 'MySQL',
        markdown: 'Markdown',
        plaintext: 'Text',
    };
    return langMap[props.language.toLowerCase()] || props.language.toUpperCase();
});
</script>

<template>
    <div
        class="code-block group relative overflow-hidden border border-neutral-200 bg-neutral-50 p-[var(--code-inset)] dark:border-white/10 dark:bg-neutral-900"
        :class="{ '!rounded-tl-none !rounded-tr-none border-0 ': isPlayground }"
    >
        <div class="code-block__inner overflow-hidden">
            <!-- Header -->
            <div
                class="code-block__header flex items-center justify-between bg-neutral-50 dark:border-white/10 dark:bg-neutral-900"
            >
                <div class="flex items-center gap-2">
                    <!-- Language Badge -->
                    <span class="rounded bg-neutral-200 px-2 py-0.5 text-[10px] font-medium text-neutral-600 dark:bg-white/10 dark:text-neutral-400">
                        {{ displayLanguage }}
                    </span>
                    <!-- Filename (optional) -->
                    <span v-if="filename" class="text-xs text-neutral-500 dark:text-neutral-500">
                        {{ filename }}
                    </span>
                </div>

                <!-- Copy Button -->
                <button
                    @click="copyCode"
                    class="flex cursor-pointer items-center gap-1.5 rounded-md px-2 py-1 text-xs text-neutral-500 transition-colors hover:bg-neutral-200 hover:text-neutral-700 dark:text-neutral-500 dark:hover:bg-white/10 dark:hover:text-neutral-300"
                    :class="{ 'text-green-600 dark:text-green-400': copied }"
                >
                    <svg
                        v-if="!copied"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3.5 w-3.5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                        />
                    </svg>
                    <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3.5 w-3.5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ copied ? t('blog.copied') : t('blog.copyCode') }}</span>
                </button>
            </div>

            <!-- Code Content -->
            <div class="overflow-x-auto">
                <pre
                    class="code-block__pre w-fit min-w-full bg-neutral-50 text-sm leading-relaxed dark:bg-neutral-900"
                    :class="{ '!rounded-xl': isPlayground }"
                ><code v-if="showLineNumbers" class="grid"><span v-for="(line, index) in lines" :key="index" class="table-row"><span class="table-cell select-none pr-4 text-right text-neutral-400 dark:text-neutral-600">{{ index + 1 }}</span><span class="table-cell" v-html="hljs.highlight(line || ' ', { language: language }).value"></span></span></code><code v-else class="block" v-html="highlightedCode"></code></pre>
            </div>
        </div>
    </div>
</template>

<style scoped>
@reference "../../css/app.css";

.code-block pre {
    @apply m-0 rounded-lg bg-neutral-100 font-mono dark:bg-neutral-800;
}

.code-block code {
    @apply rounded bg-neutral-100 font-mono text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200;
}

.code-block {
    --code-inset: 2px;
    --code-inner-radius: 10px;
    --code-outer-radius: calc(var(--code-inner-radius) + var(--code-inset));
    border-radius: var(--code-outer-radius);
}

.code-block__inner {
    border-radius: var(--code-inner-radius);
}

.code-block__header {
    padding: 10px 16px 12px 16px;
}

.code-block__pre {
    padding: 16px;
}

/* Syntax highlighting colors */
.code-block :deep(.hljs-keyword),
.code-block :deep(.hljs-selector-tag) {
    @apply text-purple-600 dark:text-purple-400;
}

.code-block :deep(.hljs-string),
.code-block :deep(.hljs-attr) {
    @apply text-green-600 dark:text-green-400;
}

.code-block :deep(.hljs-number),
.code-block :deep(.hljs-literal) {
    @apply text-orange-600 dark:text-orange-400;
}

.code-block :deep(.hljs-comment) {
    @apply text-neutral-500 italic dark:text-neutral-500;
}

.code-block :deep(.hljs-function),
.code-block :deep(.hljs-title) {
    @apply text-blue-600 dark:text-blue-400;
}

.code-block :deep(.hljs-property),
.code-block :deep(.hljs-attribute) {
    @apply text-cyan-600 dark:text-cyan-400;
}

.code-block :deep(.hljs-variable),
.code-block :deep(.hljs-template-variable) {
    @apply text-red-600 dark:text-red-400;
}

.code-block :deep(.hljs-selector-class),
.code-block :deep(.hljs-selector-id) {
    @apply text-yellow-600 dark:text-yellow-400;
}
</style>
