<script setup lang="ts">
import { onUnmounted, ref } from 'vue';

const DELAY_MS = 200;
const MIN_VISIBLE_MS = 300;

const latency = ref(140);
const delayIndicator = ref(true);
const minVisible = ref(true);
const indicator = ref<'skeleton' | 'spinner'>('skeleton');

type Phase = 'pending' | 'skeleton' | 'content';
const phase = ref<Phase>('content');

// What actually happened on the last load, so the reader can compare the rules against the numbers.
const report = ref<{ response: number; shownAt: number | null; hiddenAt: number | null } | null>(null);

let timers: ReturnType<typeof setTimeout>[] = [];

function clearTimers() {
    timers.forEach(clearTimeout);
    timers = [];
}

function later(ms: number, fn: () => void) {
    timers.push(setTimeout(fn, ms));
}

function reload() {
    clearTimers();
    const started = performance.now();
    const response = latency.value;
    const delay = delayIndicator.value ? DELAY_MS : 0;
    let shownAt: number | null = null;

    // The previous readout stays until the new one is ready: during the quiet window nothing on screen may change.
    phase.value = 'pending';

    const showContent = () => {
        phase.value = 'content';
        report.value = {
            response,
            shownAt,
            hiddenAt: shownAt === null ? null : Math.round(performance.now() - started),
        };
    };

    // Skeleton only appears when the response has not arrived within the delay window.
    if (response > delay) {
        later(delay, () => {
            phase.value = 'skeleton';
            shownAt = Math.round(performance.now() - started);
        });
    }

    later(response, () => {
        if (phase.value !== 'skeleton') {
            showContent();
            return;
        }
        // Holding the skeleton a little longer costs less than a blink that reads as a glitch.
        const holdUntil = minVisible.value ? delay + MIN_VISIBLE_MS : 0;
        const remaining = Math.max(0, holdUntil - response);
        later(remaining, showContent);
    });
}

function reset() {
    clearTimers();
    latency.value = 140;
    delayIndicator.value = true;
    minVisible.value = true;
    indicator.value = 'skeleton';
    phase.value = 'content';
    report.value = null;
}

onUnmounted(clearTimers);
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area -->
        <div class="flex h-[300px] flex-col items-center justify-center gap-4 p-4 font-sans md:p-8">
            <!-- The card keeps one height in every phase, so the skeleton never pushes the button around. On phones the summary wraps to four lines, hence the taller box. -->
            <div
                class="h-[166px] w-full max-w-sm overflow-hidden rounded-xl border border-neutral-200 bg-white p-5 sm:h-[142px] dark:border-white/10 dark:bg-neutral-950"
            >
                <div v-if="phase === 'skeleton' && indicator === 'spinner'" class="flex h-full items-center justify-center">
                    <span
                        class="size-6 animate-spin rounded-full border-2 border-neutral-300 border-t-neutral-900 dark:border-white/20 dark:border-t-white"
                    />
                </div>
                <template v-else-if="phase === 'skeleton'">
                    <div class="mb-3 h-4 w-2/3 animate-pulse rounded bg-neutral-900/10 dark:bg-white/15"></div>
                    <div class="mb-2 h-3 w-full animate-pulse rounded bg-neutral-900/10 dark:bg-white/15"></div>
                    <div class="mb-2 h-3 w-11/12 animate-pulse rounded bg-neutral-900/10 dark:bg-white/15"></div>
                    <div class="h-3 w-1/2 animate-pulse rounded bg-neutral-900/10 dark:bg-white/15"></div>
                </template>
                <template v-else>
                    <!-- Pending renders the previous content untouched: nothing to show yet means nothing changes. -->
                    <!-- Not a heading: inside a blog post this would join the document outline. -->
                    <p class="mb-2 text-base font-semibold text-neutral-900 dark:text-white">Weekly summary</p>
                    <p class="text-sm leading-relaxed text-neutral-600 dark:text-neutral-400">
                        Four sessions, 12,400 kg moved, one new personal best on the deadlift. Recovery looked good all week.
                    </p>
                </template>
            </div>

            <button
                type="button"
                class="rounded-lg bg-neutral-900 px-4 py-2 text-xs font-medium text-white transition-colors hover:bg-neutral-700 disabled:opacity-50 dark:bg-white dark:text-black dark:hover:bg-neutral-200"
                :disabled="phase === 'skeleton'"
                @click="reload"
            >
                Reload
            </button>

            <div class="h-5 font-mono text-xs text-neutral-500 dark:text-neutral-400">
                <!-- A dimmed button or a "loading" line during the quiet window would be a loader of its own. -->
                <span v-if="phase === 'skeleton'">loading…</span>
                <span v-else-if="report && report.shownAt === null"> response {{ report.response }} ms · {{ indicator }} skipped </span>
                <span v-else-if="report">
                    response {{ report.response }} ms · {{ indicator }} shown {{ report.shownAt }}–{{ report.hiddenAt }} ms
                </span>
            </div>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <!-- Slider on its own row: three toggles plus a slider never fit one line at blog width. -->
            <div class="flex flex-col gap-5">
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Latency</label>
                        <span class="font-mono text-xs text-neutral-500 tabular-nums">{{ latency }} ms</span>
                    </div>
                    <input
                        v-model.number="latency"
                        type="range"
                        min="0"
                        max="2000"
                        step="10"
                        class="h-1.5 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-neutral-900 dark:bg-neutral-700 dark:accent-white"
                    />
                </div>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                    <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                        <input v-model="delayIndicator" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                        <span>Delay the indicator ({{ DELAY_MS }} ms)</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                        <input v-model="minVisible" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                        <span>Minimum visible time ({{ MIN_VISIBLE_MS }} ms)</span>
                    </label>
                    <div class="flex gap-1.5">
                        <button
                            v-for="kind in ['skeleton', 'spinner'] as const"
                            :key="kind"
                            type="button"
                            @click="indicator = kind"
                            class="cursor-pointer rounded-full border px-3 py-1 font-mono text-[11px] transition-colors"
                            :class="
                                indicator === kind
                                    ? 'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-black'
                                    : 'border-neutral-200 text-neutral-600 hover:border-neutral-400 dark:border-white/10 dark:text-neutral-400 dark:hover:border-white/30'
                            "
                        >
                            {{ kind }}
                        </button>
                    </div>

                    <button
                        @click="reset"
                        class="w-full shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 md:ml-auto md:w-auto dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <div class="mt-6 rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-900">
                <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-400">
                    <span>
                        show after <span class="text-emerald-600 dark:text-emerald-400">{{ delayIndicator ? DELAY_MS : 0 }} ms</span>, hold at least
                        <span class="text-emerald-600 dark:text-emerald-400">{{ minVisible ? MIN_VISIBLE_MS : 0 }} ms</span>
                    </span>
                    <span class="text-neutral-500">Fast responses never flash a loader, slow ones never blink one.</span>
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
