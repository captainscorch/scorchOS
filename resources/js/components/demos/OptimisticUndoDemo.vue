<script setup lang="ts">
import { onClickOutside } from '@vueuse/core';
import { onUnmounted, ref } from 'vue';

type Status = 'Backlog' | 'Todo' | 'In Progress' | 'Done';

const STATUSES: Status[] = ['Backlog', 'Todo', 'In Progress', 'Done'];

const STATUS_DOT: Record<Status, string> = {
    Backlog: 'border-neutral-400',
    Todo: 'border-neutral-500',
    'In Progress': 'border-amber-500',
    Done: 'border-emerald-500 bg-emerald-500',
};

interface Toast {
    status: Status | null;
    id: number;
    text: string;
    action: string;
    onAction: () => void;
    timer: ReturnType<typeof setTimeout> | null;
    // Time left before the toast dismisses itself; frozen while the stack is hovered.
    remaining: number;
    startedAt: number;
}

const optimistic = ref(true);
const failRequest = ref(false);
const latency = ref(800);

const status = ref<Status>('Todo');
const pending = ref(false);
const menuOpen = ref(false);
const menuRef = ref<HTMLElement | null>(null);
// A menu that only closes through a choice traps the user; outside clicks and Escape must close it too.
onClickOutside(menuRef, () => (menuOpen.value = false));
const toasts = ref<Toast[]>([]);
// Hovering the stack fans the toasts out so every action stays reachable.
const expanded = ref(false);

const MAX_VISIBLE = 3;
const TOAST_HEIGHT = 40;
const TOAST_GAP = 8;
// Five seconds is long enough to read and act, short enough that the stack never piles up.
const TOAST_LIFETIME = 5000;

let toastId = 0;
// Only the newest request may touch the UI: an older answer landing late would flip the pill back.
let requestSeq = 0;
const requestTimers = new Set<ReturnType<typeof setTimeout>>();

function fakeRequest(): Promise<void> {
    return new Promise((resolve, reject) => {
        const timer = setTimeout(() => {
            requestTimers.delete(timer);
            if (failRequest.value) {
                reject(new Error('Request failed'));
            } else {
                resolve();
            }
        }, latency.value);
        requestTimers.add(timer);
    });
}

function clearRequests() {
    requestTimers.forEach(clearTimeout);
    requestTimers.clear();
    requestSeq += 1;
}

function dismissToast(id: number) {
    const toast = toasts.value.find((entry) => entry.id === id);
    if (toast?.timer) {
        clearTimeout(toast.timer);
    }
    toasts.value = toasts.value.filter((entry) => entry.id !== id);
}

function startToastTimer(toast: Toast) {
    toast.startedAt = performance.now();
    toast.timer = setTimeout(() => dismissToast(toast.id), toast.remaining);
}

function pushToast(text: string, action: string, onAction: () => void, status: Status | null = null) {
    const toast: Toast = { id: ++toastId, status, text, action, onAction, timer: null, remaining: TOAST_LIFETIME, startedAt: 0 };
    if (!expanded.value) {
        startToastTimer(toast);
    }
    toasts.value = [...toasts.value, toast];
    // Older toasts fall off the back of the stack instead of piling up.
    while (toasts.value.length > MAX_VISIBLE) {
        dismissToast(toasts.value[0].id);
    }
}

// A toast must not vanish while the cursor is on its way to Undo, so hovering the stack freezes every timer.
function pauseToasts() {
    expanded.value = true;
    toasts.value.forEach((toast) => {
        if (toast.timer) {
            clearTimeout(toast.timer);
            toast.timer = null;
            toast.remaining = Math.max(0, toast.remaining - (performance.now() - toast.startedAt));
        }
    });
}

function resumeToasts() {
    expanded.value = false;
    toasts.value.forEach((toast) => {
        if (!toast.timer) {
            startToastTimer(toast);
        }
    });
}

async function changeStatus(next: Status, { silent = false } = {}) {
    menuOpen.value = false;
    if (next === status.value) {
        return;
    }
    const previous = status.value;
    const seq = ++requestSeq;

    if (optimistic.value) {
        // Show the result first, ask the server second. Rollback covers the rare failure.
        status.value = next;
    } else {
        pending.value = true;
    }

    try {
        await fakeRequest();
        if (seq !== requestSeq) {
            return;
        }
        status.value = next;
        if (!silent) {
            pushToast(`Moved to ${next}`, 'Undo', () => changeStatus(previous, { silent: true }), next);
        }
    } catch {
        if (seq !== requestSeq) {
            return;
        }
        status.value = previous;
        pushToast("Couldn't update status", 'Retry', () => changeStatus(next));
    } finally {
        if (seq === requestSeq) {
            pending.value = false;
        }
    }
}

// Index 0 is the newest toast, drawn in front at full size; older ones peek out behind it.
function toastStyle(index: number) {
    if (expanded.value) {
        return { transform: `translateY(-${index * (TOAST_HEIGHT + TOAST_GAP)}px)`, opacity: 1 };
    }
    return { transform: `translateY(-${index * 10}px) scale(${1 - index * 0.05})`, opacity: index === 0 ? 1 : 0.9 };
}

// Fanned out, the stack is taller than one toast; the container has to cover the gaps, or crossing one collapses it.
function stackHeight() {
    return Math.max(1, toasts.value.length) * (TOAST_HEIGHT + TOAST_GAP) - TOAST_GAP;
}

function runToastAction(toast: Toast) {
    dismissToast(toast.id);
    toast.onAction();
}

function clearToasts() {
    toasts.value.forEach((toast) => {
        if (toast.timer) {
            clearTimeout(toast.timer);
        }
    });
    toasts.value = [];
}

function reset() {
    clearRequests();
    clearToasts();
    optimistic.value = true;
    failRequest.value = false;
    latency.value = 800;
    status.value = 'Todo';
    pending.value = false;
    menuOpen.value = false;
    expanded.value = false;
}

onUnmounted(() => {
    clearRequests();
    clearToasts();
});
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area -->
        <!-- The row sits high so a fanned-out stack of three toasts never covers it. -->
        <div class="relative flex h-[300px] items-start justify-center p-6 pt-10 md:p-8 md:pt-12">
            <!-- Issue row -->
            <div
                class="flex w-full max-w-md items-center gap-3 rounded-xl border border-neutral-200 bg-white px-4 py-3 font-sans text-sm shadow-sm dark:border-white/10 dark:bg-neutral-950"
            >
                <span class="font-mono text-xs text-neutral-400">SCO-42</span>
                <span class="flex-1 truncate text-neutral-900 dark:text-white">Ship the playground</span>

                <div ref="menuRef" class="relative" @keydown.esc="menuOpen = false">
                    <button
                        type="button"
                        @click="menuOpen = !menuOpen"
                        class="flex cursor-pointer items-center gap-2 rounded-full border border-neutral-200 px-3 py-1 text-xs text-neutral-700 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-300 dark:hover:bg-white/5"
                    >
                        <span
                            v-if="pending"
                            class="size-3 animate-spin rounded-full border-2 border-neutral-300 border-t-neutral-900 dark:border-neutral-600 dark:border-t-white"
                        />
                        <span v-else class="size-3 rounded-full border-2" :class="STATUS_DOT[status]" />
                        <span>{{ status }}</span>
                    </button>

                    <ul
                        v-if="menuOpen"
                        class="absolute top-full right-0 z-10 mt-1 w-40 rounded-lg border border-neutral-200 bg-white py-1 shadow-lg dark:border-white/10 dark:bg-neutral-900"
                    >
                        <li v-for="option in STATUSES" :key="option">
                            <button
                                type="button"
                                class="flex w-full cursor-pointer items-center gap-2 px-3 py-1.5 text-left text-xs text-neutral-700 hover:bg-neutral-100 focus-visible:bg-neutral-100 focus-visible:outline-none dark:text-neutral-300 dark:hover:bg-white/10 dark:focus-visible:bg-white/10"
                                @click="changeStatus(option)"
                            >
                                <span class="size-3 rounded-full border-2" :class="STATUS_DOT[option]" />
                                <span>{{ option }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Toasts stack inside the preview so the demo never touches the page's own layout -->
            <TransitionGroup
                tag="div"
                class="absolute right-4 bottom-4 w-[calc(100%-2rem)] max-w-xs"
                :class="toasts.length ? 'pointer-events-auto' : 'pointer-events-none'"
                :style="{ height: `${expanded ? stackHeight() : TOAST_HEIGHT}px` }"
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="translate-y-2! opacity-0!"
                leave-active-class="transition duration-150 ease-in"
                leave-to-class="translate-y-2! opacity-0!"
                @mouseenter="pauseToasts"
                @mouseleave="resumeToasts"
            >
                <div
                    v-for="(toast, index) in [...toasts].reverse()"
                    :key="toast.id"
                    class="absolute inset-x-0 bottom-0 flex h-10 origin-bottom items-center justify-between gap-3 rounded-lg border border-neutral-200 bg-white px-3 font-sans text-xs text-neutral-900 shadow-lg transition-[transform,opacity] duration-300 ease-out dark:border-white/10 dark:bg-neutral-950 dark:text-white"
                    :style="{ ...toastStyle(index), zIndex: toasts.length - index }"
                >
                    <span class="flex min-w-0 items-center gap-2">
                        <!-- The same dot as the pill, so the toast reads as the status it just set; red marks a rollback. -->
                        <span v-if="toast.status" class="size-3 shrink-0 rounded-full border-2" :class="STATUS_DOT[toast.status]" />
                        <span v-else class="size-3 shrink-0 rounded-full border-2 border-red-500 bg-red-500/20" />
                        <span class="truncate">{{ toast.text }}</span>
                    </span>
                    <button
                        type="button"
                        @click="runToastAction(toast)"
                        class="shrink-0 cursor-pointer font-medium text-emerald-600 hover:underline dark:text-emerald-400"
                    >
                        {{ toast.action }}
                    </button>
                </div>
            </TransitionGroup>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:gap-8">
                <div class="flex-1">
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Latency</label>
                        <span class="font-mono text-xs text-neutral-500 tabular-nums">{{ latency }} ms</span>
                    </div>
                    <input
                        v-model.number="latency"
                        type="range"
                        min="200"
                        max="2000"
                        step="100"
                        class="h-1.5 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-neutral-900 dark:bg-neutral-700 dark:accent-white"
                    />
                </div>

                <label class="flex cursor-pointer items-center gap-3 pb-1 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="optimistic" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Optimistic</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 pb-1 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="failRequest" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Fail request</span>
                </label>

                <button
                    @click="reset"
                    class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                >
                    Reset
                </button>
            </div>

            <div class="mt-6 rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-900">
                <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-400">
                    <span>
                        status changes
                        <span class="text-emerald-600 dark:text-emerald-400">{{ optimistic ? 'before the request' : 'after the response' }}</span>
                    </span>
                    <span class="text-neutral-500">{{
                        optimistic
                            ? 'Success gets an Undo, failure rolls back with a Retry.'
                            : 'Every change waits for the server, even the ones that never fail.'
                    }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
input[type='range']::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: currentColor;
    cursor: pointer;
}

input[type='range']::-moz-range-thumb {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: currentColor;
    cursor: pointer;
    border: none;
}
</style>
