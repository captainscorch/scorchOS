<script setup lang="ts">
import { useModalWindow } from '@/composables/useModalWindow';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faCompress, faExpand, faTimes } from '@fortawesome/sharp-light-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import hljs from 'highlight.js/lib/core';
import javascript from 'highlight.js/lib/languages/javascript';
import 'highlight.js/styles/github-dark.css';
import { onMounted, onUnmounted, watch } from 'vue';

library.add(faTimes, faCompress, faExpand);

hljs.registerLanguage('javascript', javascript);

interface Props {
    isOpen: boolean;
}

interface Emits {
    (e: 'close'): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const {
    modalRef: _modalRef,
    handleRef: _handleRef,
    isDragging,
    isResizing,
    isFullscreen,
    modalPosition,
    modalWidth,
    modalHeight,
    modalMinWidth,
    windowWidth,
    startDrag,
    startResize,
    toggleFullscreen,
    resetPosition,
    setInitialSize,
} = useModalWindow(800, 612);

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        emit('close');
    }
};

const lockBodyScroll = () => {
    document.body.style.overflow = 'hidden';
};

const unlockBodyScroll = () => {
    document.body.style.overflow = '';
};

watch(
    () => props.isOpen,
    (isOpen) => {
        if (!isOpen) {
            resetPosition();
            unlockBodyScroll();
        } else {
            setInitialSize();
            lockBodyScroll();
        }
    },
);

onMounted(() => {
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
    unlockBodyScroll();
});

const skillsData = {
    languages: ['German (native)', 'Englisch (professional – C2)'],
    codeLanguages: ['HTML', 'CSS', 'JavaScript', 'PHP', 'Liquid'],
    technologies: {
        backend: {
            php: ['Laravel', 'Livewire', 'Filament', 'Nova', 'Jetstream', 'Cashier', 'Spark', 'Inertia'],
            js: ['Nuxt'],
            shopify: ['Liquid', 'Hydrogen', 'Oxygen'],
        },
        frontend: {
            js: ['Vue.js', 'Alpine.js'],
            css: ['Tailwind', 'Bootstrap'],
        },
        devOps: ['CLI', 'Git', 'CI/CD', 'AWS', 'Docker', 'S3', 'EC2', 'Nginx', 'Node.js', 'Envoyer', 'Forge'],
        databases: ['MySQL', 'SQLite', 'GraphQL'],
        misc: ['Homebrew', 'Prompt Engineering', 'Agents', 'MCP', 'Cursor', 'OpenAI API'],
    },
    designTools: ['Figma', 'Adobe CC', 'Photoshop', 'Illustrator', 'InDesign', 'After Effects', 'Premiere Pro'],
    analytics: [
        'Google Analytics',
        'Google Tag Manager',
        'Google Search Console',
        'Google Lighthouse',
        'PageSpeed Insights',
        'Conversions API',
        'Microsoft Clarity',
        'Meta Pixel',
        'Hotjar',
    ],
    openSource: [
        {
            project: 'Laravel Cashier',
        },
    ],
};

const formatSkillsAsCode = () => {
    return `// Skills & Technologies

// 01 LANGUAGES
const languages = [
${skillsData.languages.map((lang) => `  "${lang}"`).join(',\n')}
];

// 02 CODE LANGUAGES
const codeLanguages = [
${skillsData.codeLanguages.map((lang) => `  "${lang}"`).join(',\n')}
];

// 03 TECHNOLOGIES
const technologies = {
  // Backend
  backend: {
    php: [
${skillsData.technologies.backend.php.map((tech) => `      "${tech}"`).join(',\n')}
    ],
    js: [
${skillsData.technologies.backend.js.map((tech) => `      "${tech}"`).join(',\n')}
    ],
    shopify: [
${skillsData.technologies.backend.shopify.map((tech) => `      "${tech}"`).join(',\n')}
    ]
  },

  // Frontend
  frontend: {
    js: [
${skillsData.technologies.frontend.js.map((tech) => `      "${tech}"`).join(',\n')}
    ],
    css: [
${skillsData.technologies.frontend.css.map((tech) => `      "${tech}"`).join(',\n')}
    ]
  },

  // Dev Ops
  devOps: [
${skillsData.technologies.devOps.map((tech) => `    "${tech}"`).join(',\n')}
  ],

  // Databases
  databases: [
${skillsData.technologies.databases.map((db) => `    "${db}"`).join(',\n')}
  ],

  // Misc
  misc: [
${skillsData.technologies.misc.map((item) => `    "${item}"`).join(',\n')}
  ]
};

// 04 DESIGN TOOLS
const designTools = [
${skillsData.designTools.map((tool) => `  "${tool}"`).join(',\n')}
];

// 05 ANALYTICS
const analytics = [
${skillsData.analytics.map((item) => `  "${item}"`).join(',\n')}
];

// 05 OPEN SOURCE CONTRIBUTIONS
const openSourceContributions = [
${skillsData.openSource.map((contrib) => `  {\n    project: "${contrib.project}",\n  }`).join(',\n')}
];

// Export all skills
export { languages, codeLanguages, technologies, designTools, analytics, openSourceContributions };`;
};

const formatSkillsWithSyntaxHighlighting = () => {
    const code = formatSkillsAsCode();

    try {
        const highlighted = hljs.highlight(code, { language: 'javascript' });
        return highlighted.value;
    } catch (error) {
        console.warn('Syntax highlighting failed:', error);
        return code;
    }
};
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="fixed inset-0 z-50 bg-white/60 backdrop-blur-xs dark:bg-teal-black/60" @click="!isResizing && $emit('close')">
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        ref="_modalRef"
                        v-if="isOpen"
                        class="scorch-theme absolute flex flex-col overflow-hidden rounded-xl border border-teal-800 bg-teal-black shadow-2xl"
                        :style="{
                            left: isFullscreen ? '0' : modalPosition.x === null ? '50%' : `${modalPosition.x}px`,
                            top: isFullscreen ? '0' : modalPosition.y === null ? '50%' : `${modalPosition.y}px`,
                            width: modalWidth,
                            height: modalHeight,
                            minWidth: isFullscreen ? (windowWidth < 768 ? '100dvw' : '100vw') : modalMinWidth,
                            minHeight: isFullscreen ? (windowWidth < 768 ? '100dvh' : '100vh') : '300px',
                            maxWidth: isFullscreen ? (windowWidth < 768 ? '100dvw' : '100vw') : '100vw',
                            maxHeight: isFullscreen ? (windowWidth < 768 ? '100dvh' : '100vh') : '100vh',
                            transform: isFullscreen ? 'none' : modalPosition.x === null ? 'translate(-50%, -50%)' : 'none',
                            transition: isDragging || isResizing ? 'none' : 'all 0.2s ease-out',
                        }"
                        @click.stop
                    >
                        <!-- Modal Header -->
                        <div
                            ref="_handleRef"
                            class="flex items-center justify-between rounded-t-lg border-b border-teal-800/40 bg-[#0a1a18] px-4 py-3"
                            :class="{ 'cursor-grab active:cursor-grabbing': !isFullscreen }"
                            @mousedown="startDrag"
                        >
                            <div class="flex items-center space-x-2">
                                <div class="group flex space-x-2">
                                    <button
                                        @click="emit('close')"
                                        class="flex h-3 w-3 items-center justify-center rounded-full bg-[#ff5f56] text-[8px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#ff5f56]/80"
                                        title="Close"
                                    >
                                        <span class="opacity-0 group-hover:opacity-100">✕</span>
                                    </button>
                                    <div
                                        class="flex h-3 w-3 items-center justify-center rounded-full bg-[#ffbd2e] text-[8px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#ffbd2e]/80"
                                        title="Minimize"
                                    >
                                        <span class="opacity-0 group-hover:opacity-100">−</span>
                                    </div>
                                    <button
                                        @click="toggleFullscreen"
                                        class="flex h-3 w-3 items-center justify-center rounded-full bg-[#27c93f] text-[6px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#27c93f]/80"
                                        :title="isFullscreen ? 'Exit Fullscreen' : 'Enter Fullscreen'"
                                    >
                                        <span class="opacity-0 group-hover:opacity-100">
                                            <FontAwesomeIcon :icon="isFullscreen ? 'fa-sharp fa-light fa-compress' : 'fa-sharp fa-light fa-expand'" />
                                        </span>
                                    </button>
                                </div>
                                <span class="ml-4 text-sm font-medium text-teal-100">skills.js</span>
                            </div>
                        </div>

                        <div class="relative flex-1 cursor-default overflow-hidden" @mousedown.stop>
                            <div
                                class="absolute top-0 left-0 h-full w-12 border-r border-teal-800/40 bg-[#0a1a18] text-right font-mono text-xs leading-6 text-teal-600/60 select-none"
                            >
                                <div v-for="(line, index) in formatSkillsAsCode().split('\n')" :key="index" class="px-2">
                                    {{ index + 1 }}
                                </div>
                            </div>

                            <div class="terminal-scrollbar ml-12 h-full cursor-text overflow-auto bg-[#020807]">
                                <pre
                                    class="p-4 font-mono text-sm leading-6 whitespace-pre-wrap text-teal-50 select-text"
                                ><code v-html="formatSkillsWithSyntaxHighlighting()"></code></pre>
                            </div>
                        </div>

                        <div class="relative z-10 rounded-b-lg border-t border-teal-800/40 bg-[#0a1a18] px-4 py-3" @mousedown.stop>
                            <div class="flex items-center justify-between text-xs text-teal-400/70">
                                <div class="flex items-center space-x-4">
                                    <span>JavaScript</span>
                                    <span>UTF-8</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span>{{ formatSkillsAsCode().split('\n').length }} lines</span>
                                    <span>•</span>
                                    <span>{{ formatSkillsAsCode().length }} characters</span>
                                </div>
                            </div>
                        </div>

                        <!-- Resize handles -->
                        <template v-if="!isFullscreen">
                            <!-- Top -->
                            <div
                                class="absolute top-0 right-0 left-0 hidden h-1 cursor-n-resize hover:bg-teal-500/20 md:block"
                                @mousedown.stop="startResize($event, 'top')"
                            ></div>
                            <!-- Right -->
                            <div
                                class="absolute top-0 right-0 bottom-0 hidden w-1 cursor-e-resize hover:bg-teal-500/20 md:block"
                                @mousedown.stop="startResize($event, 'right')"
                            ></div>
                            <!-- Bottom -->
                            <div
                                class="absolute right-0 bottom-0 left-0 hidden h-1 cursor-s-resize hover:bg-teal-500/20 md:block"
                                @mousedown.stop="startResize($event, 'bottom')"
                            ></div>
                            <!-- Left -->
                            <div
                                class="absolute top-0 bottom-0 left-0 z-20 hidden w-1 cursor-w-resize hover:bg-teal-500/20 md:block"
                                @mousedown.stop="startResize($event, 'left')"
                                @click.stop
                            ></div>
                            <!-- Corners -->
                            <div
                                class="absolute top-0 left-0 hidden h-4 w-4 cursor-nw-resize hover:bg-teal-500/20 md:block"
                                @mousedown.stop="startResize($event, 'top-left')"
                            ></div>
                            <div
                                class="absolute top-0 right-0 hidden h-4 w-4 cursor-ne-resize hover:bg-teal-500/20 md:block"
                                @mousedown.stop="startResize($event, 'top-right')"
                            ></div>
                            <div
                                class="absolute bottom-0 left-0 hidden h-4 w-4 cursor-sw-resize hover:bg-teal-500/20 md:block"
                                @mousedown.stop="startResize($event, 'bottom-left')"
                            ></div>
                            <div
                                class="absolute right-0 bottom-0 hidden h-4 w-4 cursor-se-resize hover:bg-teal-500/20 md:block"
                                @mousedown.stop="startResize($event, 'bottom-right')"
                            ></div>
                        </template>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
:deep(.hljs) {
    background: #020807 !important;
    color: #ccfbf1 !important;
}

:deep(.hljs-comment) {
    color: #64748b !important;
}

:deep(.hljs-keyword) {
    color: #60a5fa !important;
}

:deep(.hljs-string) {
    color: #2ab193 !important;
}

:deep(.hljs-number) {
    color: #fb923c !important;
}

:deep(.hljs-title) {
    color: #a78bfa !important;
}

:deep(.hljs-built_in) {
    color: #a78bfa !important;
}

:deep(pre) ::selection,
.dark :deep(pre) ::selection {
    background-color: #14b8a6;
    color: #ffffff;
}

:deep(pre) ::-moz-selection,
.dark :deep(pre) ::-moz-selection {
    background-color: #14b8a6;
    color: #ffffff;
}
</style>
