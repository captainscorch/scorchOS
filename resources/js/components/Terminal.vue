```vue
<script setup lang="ts">
import { useModalWindow } from '@/composables/useModalWindow';
import { useProjects } from '@/composables/useProjects';
import { useTerminal } from '@/composables/useTerminal';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

const { isOpen: isTerminalOpen } = useTerminal();

const {
    modalRef: _terminalWindow,
    handleRef: _terminalHeader,
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
} = useModalWindow(800, window.innerWidth < 768 ? 600 : 575);

const { projects } = useProjects();

const routes = computed(() => {
    const baseRoutes: Record<string, string> = {
        '/': '/',
        '/home': '/',
        '/about': '/about',
        '/portfolio': '/portfolio',
        '/blog': '/blog',
        '/playground': '/playground',
    };

    projects.value.forEach((project) => {
        baseRoutes[`/case-study/${project.slug}`] = `/case-study/${project.slug}`;
    });

    return baseRoutes;
});

watch(isTerminalOpen, (isOpen) => {
    if (isOpen) {
        setInitialSize();
    } else {
        resetPosition();
    }
});

interface Props {
    user?: string;
    host?: string;
    initialCommand?: string;
}

const props = withDefaults(defineProps<Props>(), {
    user: 'user',
    host: 'scorchOS',
    initialCommand: 'help',
});

const terminalInput = ref<HTMLInputElement | null>(null);
const terminalBody = ref<HTMLElement | null>(null);

const history = ref<string[]>([]);
const historyIndex = ref<number>(-1);
const currentDir = ref<string>('~/scorchOS');
const inputValue = ref('');

const outputLines = ref<Array<{ type: 'command' | 'output'; content: string; dir?: string }>>([]);

const fileSystem = computed<Record<string, string[]>>(() => ({
    '~': ['scorchOS', 'Documents', 'Downloads', 'Projects'],
    '~/scorchOS': [
        'app',
        'bootstrap',
        'config',
        'database',
        'public',
        'resources',
        'routes',
        'storage',
        'tests',
        'vendor',
        '.env',
        'artisan',
        'composer.json',
        'package.json',
        'phpunit.xml',
        'README.md',
        'vite.config.js',
    ],
    '~/Projects': projects.value.map((p) => p.slug),
}));

const isStartupComplete = ref(false);
const showInitialCommand = ref(false);
const showOutput = ref(false);
const showInput = ref(false);

const wait = (ms: number) => new Promise((resolve) => setTimeout(resolve, ms));

onMounted(async () => {
    await wait(500);
    showInitialCommand.value = true;

    await wait(300);
    showOutput.value = true;

    await wait(100);
    showInput.value = true;
    isStartupComplete.value = true;

    nextTick(() => {
        focusInput();
    });
});

const focusInput = () => {
    if (window.getSelection()?.toString()) return;
    terminalInput.value?.focus();
};

const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Enter') {
        handleCommand(inputValue.value);
        inputValue.value = '';
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        navigateHistory('up');
    } else if (e.key === 'ArrowDown') {
        e.preventDefault();
        navigateHistory('down');
    }
};

const handleCommand = (cmd: string) => {
    const command = cmd.trim();
    if (!command) return;

    history.value.push(command);
    historyIndex.value = history.value.length;

    outputLines.value.push({
        type: 'command',
        content: command,
        dir: currentDir.value,
    });

    const parts = command.split(' ');
    const baseCmd = parts[0].toLowerCase();
    const args = parts.slice(1);

    let output = '';

    switch (baseCmd) {
        case 'help':
            output = `
<span class="text-[#2ab193]">Available commands:</span>
  <span class="text-[#60a5fa]">cd</span>       Change directory
  <span class="text-[#60a5fa]">ls</span>       List directory contents
  <span class="text-[#60a5fa]">clear</span>    Clear the terminal screen
  <span class="text-[#60a5fa]">pwd</span>      Print working directory
  <span class="text-[#60a5fa]">whoami</span>   Print current user
  <span class="text-[#60a5fa]">cat</span>      Concatenate and print files
  <span class="text-[#60a5fa]">sudo</span>     Execute a command as another user`;
            break;
        case 'clear':
            outputLines.value = [];
            showInitialCommand.value = false;
            showOutput.value = false;
            return;
        case 'ls':
            const files = fileSystem.value[currentDir.value] || [];
            output = files
                .map((f) => {
                    const isDir = !f.includes('.') && !currentDir.value.includes('Projects');
                    if (isDir) return `<span class="text-[#60a5fa] font-bold">${f}/</span>`;
                    return `<span class="text-[#ccfbf1]">${f}</span>`;
                })
                .join('  ');
            break;
        case 'pwd':
            output = currentDir.value;
            break;
        case 'whoami':
            output = props.user;
            break;
        case 'cd':
            handleCd(args[0]);
            return;
        case 'sudo':
            output = `<span class="text-[#ff5f56]">Password required for sudo. Access denied.</span>`;
            break;
        case 'exit':
            window.location.href = '/';
            return;
        default:
            output = `<span class="text-[#ff5f56]">zsh: command not found: ${baseCmd}</span>`;
    }

    if (output) {
        outputLines.value.push({
            type: 'output',
            content: output,
        });
    }

    nextTick(() => {
        if (terminalBody.value) {
            terminalBody.value.scrollTop = terminalBody.value.scrollHeight;
        }
    });
};

const handleCd = (path: string) => {
    if (!path || path === '~') {
        currentDir.value = '~';
        outputLines.value.push({ type: 'output', content: '' });
        return;
    }

    if (path === '..') {
        if (currentDir.value === '~/scorchOS' || currentDir.value === '~/Projects') currentDir.value = '~';
        else if (currentDir.value === '~') outputLines.value.push({ type: 'output', content: 'Already at root level for this session.' });
        return;
    }

    if (path.startsWith('/')) {
        const targetRoute = routes.value[path];
        if (targetRoute) {
            window.location.href = targetRoute;
        } else {
            outputLines.value.push({ type: 'output', content: `<span class="text-[#ff5f56]">cd: no such file or directory: ${path}</span>` });
        }
        return;
    }

    const normalizedPath = path.replace('/', '');
    const currentFiles = fileSystem.value[currentDir.value] || [];

    if (currentFiles.includes(normalizedPath)) {
        if (currentDir.value === '~' && (normalizedPath === 'scorchOS' || normalizedPath === 'Projects')) {
            currentDir.value = `~/${normalizedPath}`;
            return;
        }

        if (currentDir.value === '~/Projects') {
            window.location.href = `/case-study/${normalizedPath}`;
            return;
        }

        outputLines.value.push({ type: 'output', content: `<span class="text-[#64748b]">cd: ${path}: permission denied (simulated)</span>` });
    } else {
        outputLines.value.push({ type: 'output', content: `<span class="text-[#ff5f56]">cd: no such file or directory: ${path}</span>` });
    }
};

const navigateHistory = (direction: 'up' | 'down') => {
    if (history.value.length === 0) return;

    if (direction === 'up') {
        historyIndex.value = Math.max(0, historyIndex.value - 1);
    } else {
        historyIndex.value = Math.min(history.value.length, historyIndex.value + 1);
    }

    if (historyIndex.value < history.value.length) {
        inputValue.value = history.value[historyIndex.value];
    } else {
        inputValue.value = '';
    }

    nextTick(() => {
        if (terminalInput.value) {
            terminalInput.value.selectionStart = terminalInput.value.selectionEnd = terminalInput.value.value.length;
        }
    });
};
</script>

<template>
    <div
        v-if="isTerminalOpen"
        class="fixed inset-0 z-200 bg-off-white/75 backdrop-blur-xs dark:bg-teal-black/75"
        @click="!isResizing && $emit('close')"
    ></div>
    <div
        ref="_terminalWindow"
        class="scorch-theme fixed top-1/2 left-1/2 z-200 flex h-auto max-h-[85vh] min-h-[330px] w-full max-w-4xl flex-col overflow-hidden rounded-xl border border-[#115e59] shadow-2xl transition-shadow duration-300 hover:shadow-[0_0_60px_-15px_rgba(42,177,147,0.3)]"
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
    >
        <!-- Window Header -->
        <div
            ref="_terminalHeader"
            class="relative flex items-center justify-end border-b border-[#115e59]/40 bg-[#0a1a18] px-4 py-3 select-none md:justify-center"
            :class="{ 'cursor-grab active:cursor-grabbing': !isFullscreen }"
            @mousedown="startDrag"
        >
            <!-- Window Controls -->
            <div class="group absolute left-4 flex items-center space-x-2">
                <button
                    @click="$emit('close')"
                    class="flex h-3 w-3 items-center justify-center rounded-full bg-[#ff5f56] text-[12px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#ff5f56]/80"
                    title="Close"
                >
                    <span class="opacity-0 group-hover:opacity-100">✕</span>
                </button>
                <div
                    class="flex h-3 w-3 items-center justify-center rounded-full bg-[#ffbd2e] text-[12px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#ffbd2e]/80"
                    title="Minimize"
                >
                    <span class="opacity-0 group-hover:opacity-100">−</span>
                </div>
                <button
                    @click="toggleFullscreen"
                    class="flex h-3 w-3 items-center justify-center rounded-full bg-[#27c93f] text-[6px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#27c93f]/80"
                    title="Toggle Fullscreen"
                >
                    <span class="opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="h-2 w-2 fill-current">
                            <path
                                d="M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32S0 334.3 0 352v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z"
                            />
                        </svg>
                    </span>
                </button>
            </div>

            <!-- Title -->
            <div class="flex items-center gap-2 pl-2 opacity-60 md:pl-0">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="12"
                    height="12"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-folder-open text-[#ccfbf1]"
                >
                    <path
                        d="m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2"
                    />
                </svg>
                <span class="text-sm font-medium text-[#ccfbf1]"
                    ><span class="hidden md:inline">{{ user }} &mdash; </span>user@scorchOS &mdash; -zsh &mdash; 80x24</span
                >
            </div>
        </div>

        <!-- Terminal Content -->
        <div
            ref="terminalBody"
            id="terminal-body"
            class="terminal-scrollbar relative flex-1 overflow-y-auto p-6 font-mono text-sm leading-relaxed md:p-8 md:text-base"
            @click="focusInput"
        >
            <div class="scanline"></div>

            <!-- Last Login -->
            <div class="mb-6 text-[#64748b]">Last login: {{ new Date().toDateString() }} on ttys008</div>

            <!-- Initial Command Block -->
            <div v-if="showInitialCommand" class="mb-4">
                <div class="mb-2 flex flex-wrap gap-x-3 gap-y-1">
                    <span class="font-bold text-[#2ab193]">➜</span>
                    <span class="font-bold text-[#2ab193]">~/{{ host }}</span>
                    <span class="text-[#64748b]">$</span>
                    <span class="font-bold text-[#a78bfa]">{{ initialCommand }}</span>
                </div>
            </div>

            <!-- Initial Output -->
            <div v-if="showOutput" class="mb-8 ml-1 space-y-4 border-l-2 border-[#115e59]/50 pl-2">
                <slot name="initial-output"></slot>
            </div>

            <!-- Dynamic Output -->
            <div v-for="(line, index) in outputLines" :key="index">
                <div v-if="line.type === 'command'" class="mb-1 flex flex-wrap gap-x-3 gap-y-1">
                    <span class="font-bold text-[#2ab193]">➜</span>
                    <span class="font-bold text-[#2ab193]">{{ line.dir }}</span>
                    <span class="text-[#64748b]">$</span>
                    <span class="text-[#ccfbf1]">{{ line.content }}</span>
                </div>
                <div v-else class="mb-4 whitespace-pre-wrap text-[#ccfbf1] opacity-90" v-html="line.content"></div>
            </div>

            <!-- Input Line -->
            <div v-show="showInput" class="mt-2 flex items-center gap-2">
                <span class="font-bold text-[#2ab193]">➜</span>
                <span class="font-bold text-[#2ab193]">{{ currentDir }}</span>
                <span class="text-[#64748b]">$</span>
                <input
                    ref="terminalInput"
                    v-model="inputValue"
                    type="text"
                    class="m-0 flex-1 border-none bg-transparent p-0 font-mono text-[#ccfbf1] placeholder-[#64748b]/50 outline-none focus:ring-0"
                    autocomplete="off"
                    spellcheck="false"
                    @keydown="handleKeydown"
                />
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="flex items-center justify-between border-t border-[#115e59]/30 bg-[#0a1a18] px-4 py-2 text-[10px] text-[#64748b] select-none">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="10"
                        height="10"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-git-branch"
                    >
                        <line x1="6" x2="6" y1="3" y2="15" />
                        <circle cx="18" cy="6" r="3" />
                        <circle cx="6" cy="18" r="3" />
                        <path d="M18 9a9 9 0 0 1-9 9" />
                    </svg>
                    <span>main</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="10"
                        height="10"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-hash"
                    >
                        <line x1="4" x2="20" y1="9" y2="9" />
                        <line x1="4" x2="20" y1="15" y2="15" />
                        <line x1="10" x2="8" y1="3" y2="21" />
                        <line x1="16" x2="14" y1="3" y2="21" />
                    </svg>
                    <span>31ab968</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <div class="h-1.5 w-1.5 rounded-full bg-[#27c93f]"></div>
                    <span>Online</span>
                </div>
                <div>UTF-8</div>
                <div>Vue</div>
            </div>
        </div>

        <!-- Resize handles -->
        <template v-if="!isFullscreen">
            <!-- Top -->
            <div
                class="absolute top-0 right-0 left-0 hidden h-1 cursor-n-resize hover:bg-[#2ab193]/20 md:block"
                @mousedown.stop="startResize($event, 'top')"
            ></div>
            <!-- Right -->
            <div
                class="absolute top-0 right-0 bottom-0 hidden w-1 cursor-e-resize hover:bg-[#2ab193]/20 md:block"
                @mousedown.stop="startResize($event, 'right')"
            ></div>
            <!-- Bottom -->
            <div
                class="absolute right-0 bottom-0 left-0 hidden h-1 cursor-s-resize hover:bg-[#2ab193]/20 md:block"
                @mousedown.stop="startResize($event, 'bottom')"
            ></div>
            <!-- Left -->
            <div
                class="absolute top-0 bottom-0 left-0 z-20 hidden w-1 cursor-w-resize hover:bg-[#2ab193]/20 md:block"
                @mousedown.stop="startResize($event, 'left')"
                @click.stop
            ></div>
            <!-- Corners -->
            <div
                class="absolute top-0 left-0 hidden h-4 w-4 cursor-nw-resize hover:bg-[#2ab193]/20 md:block"
                @mousedown.stop="startResize($event, 'top-left')"
            ></div>
            <div
                class="absolute top-0 right-0 hidden h-4 w-4 cursor-ne-resize hover:bg-[#2ab193]/20 md:block"
                @mousedown.stop="startResize($event, 'top-right')"
            ></div>
            <div
                class="absolute bottom-0 left-0 hidden h-4 w-4 cursor-sw-resize hover:bg-[#2ab193]/20 md:block"
                @mousedown.stop="startResize($event, 'bottom-left')"
            ></div>
            <div
                class="absolute right-0 bottom-0 hidden h-4 w-4 cursor-se-resize hover:bg-[#2ab193]/20 md:block"
                @mousedown.stop="startResize($event, 'bottom-right')"
            ></div>
        </template>
    </div>
</template>
