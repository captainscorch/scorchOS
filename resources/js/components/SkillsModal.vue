<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';
import { faTimes } from '@fortawesome/sharp-light-svg-icons';
import hljs from 'highlight.js/lib/core';
import javascript from 'highlight.js/lib/languages/javascript';
import 'highlight.js/styles/github-dark.css';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

library.add(faTimes);

hljs.registerLanguage('javascript', javascript);

interface Props {
    isOpen: boolean;
}

interface Emits {
    (e: 'close'): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

// Dragging
const isDragging = ref(false);
const dragOffset = ref({ x: 0, y: 0 });
const modalPosition = ref<{ x: number | null; y: number | null }>({ x: null, y: null });
const modalRef = ref<HTMLElement | null>(null);

// Resizing
const isResizing = ref(false);
const resizeDirection = ref('');
const resizeStart = ref({ x: 0, y: 0, width: 0, height: 0 });
const modalSize = ref({ width: 800, height: 612 });
const isFullscreen = ref(false);
const windowWidth = ref(window.innerWidth);

const setInitialSize = () => {
    const isMobile = windowWidth.value < 768;
    if (isMobile) {
        modalSize.value = {
            width: Math.min(800, windowWidth.value - 32),
            height: Math.min(600, window.innerHeight - 32),
        };
    } else {
        // Reset to desktop defaults when transitioning from mobile to desktop
        modalSize.value = {
            width: 800,
            height: 612,
        };
    }
};

const modalWidth = computed(() => {
    if (isFullscreen.value) {
        return windowWidth.value < 768 ? '100dvw' : '100vw';
    }
    const isMobile = windowWidth.value < 768;
    return isMobile ? 'calc(100vw - 2rem)' : `${modalSize.value.width}px`;
});

const modalHeight = computed(() => {
    if (isFullscreen.value) {
        return windowWidth.value < 768 ? '100dvh' : '100vh';
    }
    return `${modalSize.value.height}px`;
});

const modalMinWidth = computed(() => {
    const isMobile = windowWidth.value < 768;
    return isMobile ? '290px' : '400px';
});

const startDrag = (event: MouseEvent) => {
    if (!modalRef.value || isFullscreen.value) return;

    isDragging.value = true;

    const rect = modalRef.value.getBoundingClientRect();

    if (modalPosition.value.x === null || modalPosition.value.y === null) {
        const centerX = window.innerWidth / 2;
        const centerY = window.innerHeight / 2;
        const modalWidth = rect.width;
        const modalHeight = rect.height;

        modalPosition.value = {
            x: centerX - modalWidth / 2,
            y: centerY - modalHeight / 2,
        };
    }

    dragOffset.value = {
        x: event.clientX - rect.left,
        y: event.clientY - rect.top,
    };

    document.addEventListener('mousemove', onDrag, { passive: false });
    document.addEventListener('mouseup', stopDrag, { passive: false });
    event.preventDefault();
};

const onDrag = (event: MouseEvent) => {
    if (!isDragging.value) return;

    let newX = event.clientX - dragOffset.value.x;
    let newY = event.clientY - dragOffset.value.y;

    // Keep modal within viewport bounds
    const modalWidth = modalRef.value?.offsetWidth || 800;
    const modalHeight = modalRef.value?.offsetHeight || 612;

    newX = Math.max(0, Math.min(newX, window.innerWidth - modalWidth));
    newY = Math.max(0, Math.min(newY, window.innerHeight - modalHeight));

    modalPosition.value = {
        x: newX,
        y: newY,
    };
};

const stopDrag = () => {
    isDragging.value = false;
    document.removeEventListener('mousemove', onDrag);
    document.removeEventListener('mouseup', stopDrag);
};

// Handle mouse leave to stop dragging
const handleMouseLeave = () => {
    if (isDragging.value) {
        stopDrag();
    }
};

// Resize
const startResize = (event: MouseEvent, direction: string) => {
    if (!modalRef.value) return;

    // Disable on mobile devices
    if (window.innerWidth < 768) return;

    // Prevent interactions
    event.preventDefault();
    event.stopPropagation();
    event.stopImmediatePropagation();

    isResizing.value = true;
    resizeDirection.value = direction;

    const rect = modalRef.value.getBoundingClientRect();
    resizeStart.value = {
        x: event.clientX,
        y: event.clientY,
        width: rect.width,
        height: rect.height,
    };

    document.addEventListener('mousemove', onResize);
    document.addEventListener('mouseup', (event) => stopResize(event));
};

const onResize = (event: MouseEvent) => {
    if (!isResizing.value) return;

    const deltaX = event.clientX - resizeStart.value.x;
    const deltaY = event.clientY - resizeStart.value.y;

    let newWidth = resizeStart.value.width;
    let newHeight = resizeStart.value.height;

    if (resizeDirection.value.includes('right')) {
        newWidth = Math.max(400, resizeStart.value.width + deltaX);
    }
    if (resizeDirection.value.includes('left')) {
        newWidth = Math.max(400, resizeStart.value.width - deltaX);
    }
    if (resizeDirection.value.includes('bottom')) {
        newHeight = Math.max(300, resizeStart.value.height + deltaY);
    }
    if (resizeDirection.value.includes('top')) {
        newHeight = Math.max(300, resizeStart.value.height - deltaY);
    }

    // Constrain to full viewport dimensions
    newWidth = Math.min(newWidth, window.innerWidth);
    newHeight = Math.min(newHeight, window.innerHeight);

    modalSize.value = {
        width: newWidth,
        height: newHeight,
    };
};

const stopResize = (event?: MouseEvent) => {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
    }

    setTimeout(() => {
        isResizing.value = false;
        resizeDirection.value = '';
    }, 50);

    document.removeEventListener('mousemove', onResize);
    document.removeEventListener('mouseup', stopResize);
};

// Handle esc key
const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        emit('close');
    }
};

// Toggle fullscreen mode
const toggleFullscreen = () => {
    isFullscreen.value = !isFullscreen.value;
    if (isFullscreen.value) {
        // Store current position and size when exiting fullscreen
        modalPosition.value = { x: null, y: null };
    }
};

// Reset modal position and size when closed
const resetPosition = () => {
    modalPosition.value = { x: null, y: null };
    modalSize.value = { width: 800, height: 612 };
    isFullscreen.value = false;
};

// Handle window resize
const handleWindowResize = () => {
    windowWidth.value = window.innerWidth;
    if (props.isOpen) {
        setInitialSize();
    }
};

const lockBodyScroll = () => {
    document.body.style.overflow = 'hidden';
};

const unlockBodyScroll = () => {
    document.body.style.overflow = '';
};

// Watch for modal close to reset position
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
    document.addEventListener('mouseleave', handleMouseLeave);
    window.addEventListener('resize', handleWindowResize);
    document.addEventListener('mouseup', () => {
        if (isDragging.value) {
            stopDrag();
        }
    });
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
    document.removeEventListener('mouseleave', handleMouseLeave);
    window.removeEventListener('resize', handleWindowResize);
    document.removeEventListener('mousemove', onDrag);
    document.removeEventListener('mouseup', stopDrag);
    document.removeEventListener('mousemove', onResize);
    document.removeEventListener('mouseup', stopResize);
    unlockBodyScroll();
});

// Data
const skillsData = {
    languages: ['German (native)', 'Englisch (professional – C2)'],
    codeLanguages: ['HTML', 'CSS', 'JavaScript', 'PHP', 'Liquid'],
    technologies: {
        backend: {
            php: ['Laravel', 'Livewire', 'Filament', 'Nova', 'Jetstream', 'Cashier', 'Spark', 'Inertia'],
            js: ['Nuxt'],
            shopify: ['Liquid', 'Hydrogen'],
        },
        frontend: {
            js: ['Vue.js', 'Alpine.js'],
            css: ['Tailwind', 'Bootstrap', 'shadcn/ui'],
        },
        devOps: ['CLI', 'Git', 'CI/CD', 'AWS', 'Docker', 'S3', 'EC2', 'Nginx', 'Node.js', 'Envoyer', 'Forge'],
        databases: ['MySQL', 'SQLite', 'GraphQL'],
        misc: ['Homebrew', 'Prompt Engineering', 'Agents', 'MCP', 'Cursor', 'OpenAI API'],
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
    },
    designTools: ['Figma', 'Adobe CC', 'Photoshop', 'Illustrator', 'InDesign', 'After Effects', 'Premiere Pro'],
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
  ],

  // Analytics
  analytics: [
${skillsData.technologies.analytics.map((item) => `    "${item}"`).join(',\n')}
  ]
};

// 04 DESIGN TOOLS
const designTools = [
${skillsData.designTools.map((tool) => `  "${tool}"`).join(',\n')}
];

// 05 OPEN SOURCE CONTRIBUTIONS
const openSourceContributions = [
${skillsData.openSource.map((contrib) => `  {\n    project: "${contrib.project}",\n  }`).join(',\n')}
];

// Export all skills
export { languages, codeLanguages, technologies, designTools, openSourceContributions };`;
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
            <div v-if="isOpen" class="fixed inset-0 z-50 bg-teal-black/50 backdrop-blur-sm" @click="!isResizing && $emit('close')">
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        ref="modalRef"
                        v-if="isOpen"
                        class="absolute flex flex-col overflow-hidden rounded-lg border border-teal-800 bg-teal-black shadow-2xl"
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
                            class="flex items-center justify-between rounded-t-lg border-b border-teal-800/40 bg-[#0a1a18] px-4 py-3"
                            :class="{ 'cursor-move': !isFullscreen }"
                            @mousedown="startDrag"
                        >
                            <div class="flex items-center space-x-2">
                                <div class="flex space-x-1">
                                    <button
                                        @click="emit('close')"
                                        class="h-3 w-3 cursor-pointer rounded-full bg-red-500 transition-colors hover:bg-red-600"
                                        title="Close"
                                    ></button>
                                    <div
                                        class="h-3 w-3 cursor-default rounded-full bg-yellow-500 transition-colors hover:bg-yellow-600"
                                        title="Minimize"
                                    ></div>
                                    <button
                                        @click="toggleFullscreen"
                                        class="h-3 w-3 cursor-pointer rounded-full bg-green-500 transition-colors hover:bg-green-600"
                                        :title="isFullscreen ? 'Exit Fullscreen' : 'Enter Fullscreen'"
                                    ></button>
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

                            <div class="ml-12 h-full cursor-text overflow-auto bg-[#020807]">
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
.overflow-auto::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.overflow-auto::-webkit-scrollbar-track {
    background: #020807;
}

.overflow-auto::-webkit-scrollbar-thumb {
    background: #145c4a;
    border-radius: 4px;
    cursor: pointer;
}

.overflow-auto::-webkit-scrollbar-thumb:hover {
    background: #2ab193;
}

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
