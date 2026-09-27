<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next';
import { nextTick, onUnmounted, ref } from 'vue';

type ToolStatus = 'pending' | 'waiting' | 'running' | 'done' | 'failed' | 'rejected';

interface ToolCall {
    id: string;
    label: string;
    write: boolean;
    status: ToolStatus;
    result: string;
    details: string[];
    open: boolean;
}

const INTRO = 'Let me check your last sessions before I touch the plan.';
const OUTRO = 'Done. Bench press is now four sets of six. The rest of the week stays as it was.';
const OUTRO_REJECTED = 'Understood. The plan stays as it is. Tell me if you want a smaller step instead.';
const OUTRO_FAILED = 'The plan update did not go through. Nothing changed, so you can retry the step above.';

const requireApproval = ref(true);
const failTool = ref(false);
const speed = ref(40);

const intro = ref('');
const outro = ref('');
const streaming = ref(false);
const started = ref(false);
const tools = ref<ToolCall[]>([]);
const pane = ref<HTMLElement | null>(null);

// This demo is about the cards, not about scroll anchoring, so the pane simply follows the newest content.
function followContent() {
    nextTick(() => pane.value?.scrollTo({ top: pane.value.scrollHeight }));
}

const timers = new Set<ReturnType<typeof setTimeout>>();

function later(ms: number, fn: () => void) {
    const id = setTimeout(() => {
        timers.delete(id);
        fn();
    }, ms);
    timers.add(id);
}

function clearTimers() {
    for (const id of timers) {
        clearTimeout(id);
    }
    timers.clear();
}

function makeTools(): ToolCall[] {
    return [
        {
            id: 'history',
            label: 'Look up exercise history',
            write: false,
            status: 'pending',
            result: '',
            details: ['bench_press: 3×8 @ 80 kg, 3×8 @ 80 kg, 3×7 @ 82.5 kg', 'last_deload: 5 weeks ago', 'rpe_trend: rising'],
            open: false,
        },
        {
            id: 'plan',
            label: 'Adjust plan: Bench press 3×8 → 4×6',
            write: true,
            status: 'pending',
            result: '',
            details: ['exercise: bench_press', 'sets: 3 → 4', 'reps: 8 → 6', 'weight: unchanged'],
            open: false,
        },
    ];
}

function stream(target: { value: string }, text: string, done: () => void) {
    const tokens = text.split(' ');
    let index = 0;
    streaming.value = true;
    const step = () => {
        if (index >= tokens.length) {
            streaming.value = false;
            done();
            return;
        }
        target.value += (index === 0 ? '' : ' ') + tokens[index];
        index += 1;
        followContent();
        later(speed.value, step);
    };
    step();
}

function runTool(tool: ToolCall, done: () => void) {
    tool.status = 'running';
    followContent();
    later(1100, () => {
        if (failTool.value && tool.write) {
            tool.status = 'failed';
            tool.result = 'Plan service returned 503.';
        } else {
            tool.status = 'done';
            tool.result = tool.write ? 'Plan updated for the next 3 sessions.' : '3 sessions found, last one 2 days ago.';
        }
        done();
    });
}

function finish() {
    const plan = tools.value[1];
    const text = plan.status === 'rejected' ? OUTRO_REJECTED : plan.status === 'failed' ? OUTRO_FAILED : OUTRO;
    stream(outro, text, () => {});
}

// Writes wait for a human unless approval is switched off; reads never do.
function offerPlanTool() {
    const plan = tools.value[1];
    if (requireApproval.value) {
        plan.status = 'waiting';
        return;
    }
    runTool(plan, finish);
}

function approve() {
    runTool(tools.value[1], finish);
}

function reject() {
    tools.value[1].status = 'rejected';
    finish();
}

function retry() {
    const plan = tools.value[1];
    outro.value = '';
    plan.result = '';
    runTool(plan, finish);
}

function start() {
    if (started.value) {
        return;
    }
    started.value = true;
    tools.value = makeTools();
    stream(intro, INTRO, () => {
        runTool(tools.value[0], () => later(300, offerPlanTool));
    });
}

function reset() {
    clearTimers();
    started.value = false;
    streaming.value = false;
    intro.value = '';
    outro.value = '';
    tools.value = [];
    requireApproval.value = true;
    failTool.value = false;
    speed.value = 40;
}

const STATUS_STYLE: Record<ToolStatus, { dot: string; label: string }> = {
    pending: { dot: 'bg-neutral-400', label: 'Pending' },
    waiting: { dot: 'bg-amber-500', label: 'Needs approval' },
    running: { dot: 'bg-sky-500 animate-pulse', label: 'Running' },
    done: { dot: 'bg-emerald-500', label: 'Done' },
    failed: { dot: 'bg-red-500', label: 'Failed' },
    rejected: { dot: 'bg-neutral-500', label: 'Rejected' },
};

onUnmounted(clearTimers);
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area: fixed height, the pane scrolls when cards expand -->
        <div class="p-4 md:p-6">
            <div
                ref="pane"
                class="chat-pane mx-auto flex h-[380px] w-full max-w-2xl flex-col gap-4 overflow-y-auto rounded-xl border border-neutral-200 bg-white p-4 font-sans text-sm leading-relaxed dark:border-white/10 dark:bg-neutral-950"
            >
                <div class="flex justify-end">
                    <div class="max-w-[85%] rounded-2xl bg-neutral-200/70 px-4 py-2.5 text-neutral-900 dark:bg-white/10 dark:text-white">
                        Bench felt easy this week, bump it up.
                    </div>
                </div>

                <div v-if="!started" class="flex flex-1 items-center justify-center">
                    <button
                        type="button"
                        @click="start"
                        class="cursor-pointer rounded-lg bg-neutral-900 px-4 py-2 text-xs font-medium text-white transition-colors hover:bg-neutral-700 dark:bg-white dark:text-black dark:hover:bg-neutral-200"
                    >
                        Let the agent answer
                    </button>
                </div>

                <template v-else>
                    <p class="max-w-[65ch] text-neutral-800 dark:text-neutral-200">
                        {{ intro }}<span v-if="streaming && !outro && intro.length < INTRO.length" class="caret" aria-hidden="true" />
                    </p>

                    <div class="flex flex-col gap-2">
                        <div
                            v-for="tool in tools"
                            :key="tool.id"
                            class="max-w-[65ch] rounded-lg border border-neutral-200 bg-neutral-50 text-xs dark:border-white/10 dark:bg-neutral-900"
                        >
                            <!-- One row per call: dot, label, actions, status, chevron. The label wraps, nothing truncates. -->
                            <div class="flex items-center gap-2.5 px-3 py-2">
                                <span class="size-2 shrink-0 rounded-full" :class="STATUS_STYLE[tool.status].dot" />
                                <button
                                    type="button"
                                    class="min-w-0 flex-1 cursor-pointer text-left font-medium text-neutral-900 dark:text-white"
                                    @click="tool.open = !tool.open"
                                >
                                    {{ tool.label }}
                                </button>

                                <div v-if="tool.status === 'waiting'" class="flex shrink-0 items-center gap-1.5">
                                    <button
                                        type="button"
                                        @click="approve"
                                        class="cursor-pointer rounded-md bg-neutral-900 px-2.5 py-1 text-[11px] font-medium text-white transition-colors hover:bg-neutral-700 dark:bg-white dark:text-black dark:hover:bg-neutral-200"
                                    >
                                        Run
                                    </button>
                                    <button
                                        type="button"
                                        @click="reject"
                                        class="cursor-pointer rounded-md border border-neutral-200 px-2.5 py-1 text-[11px] font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                                    >
                                        Reject
                                    </button>
                                </div>
                                <button
                                    v-else-if="tool.status === 'failed'"
                                    type="button"
                                    @click="retry"
                                    class="shrink-0 cursor-pointer rounded-md border border-neutral-200 px-2.5 py-1 text-[11px] font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                                >
                                    Retry
                                </button>

                                <span class="hidden shrink-0 font-mono text-[10px] tracking-wider text-neutral-500 uppercase sm:inline">
                                    {{ STATUS_STYLE[tool.status].label }}
                                </span>
                                <button
                                    type="button"
                                    class="shrink-0 cursor-pointer text-neutral-400 transition-transform hover:text-neutral-700 dark:hover:text-neutral-200"
                                    :class="{ 'rotate-180': tool.open }"
                                    :aria-expanded="tool.open"
                                    @click="tool.open = !tool.open"
                                >
                                    <ChevronDown class="size-3.5" />
                                </button>
                            </div>

                            <div
                                v-if="tool.open"
                                class="border-t border-neutral-200 px-3 py-2 font-mono text-[11px] text-neutral-500 dark:border-white/10"
                            >
                                <div class="mb-1 tracking-wider uppercase sm:hidden">{{ STATUS_STYLE[tool.status].label }}</div>
                                <div v-for="line in tool.details" :key="line">{{ line }}</div>
                            </div>

                            <div
                                v-if="tool.result"
                                class="border-t border-neutral-200 px-3 py-2 text-neutral-600 dark:border-white/10 dark:text-neutral-400"
                            >
                                {{ tool.result }}
                            </div>
                        </div>
                    </div>

                    <p v-if="outro" class="max-w-[65ch] text-neutral-800 dark:text-neutral-200">
                        {{ outro }}<span v-if="streaming" class="caret" aria-hidden="true" />
                    </p>
                </template>
            </div>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-6 md:flex-row md:flex-wrap md:items-end md:gap-8">
                <div class="min-w-[14rem] flex-1">
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Token delay</label>
                        <span class="w-12 text-right font-mono text-xs text-neutral-500 tabular-nums">{{ speed }} ms</span>
                    </div>
                    <input
                        v-model.number="speed"
                        type="range"
                        min="10"
                        max="120"
                        class="h-1.5 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-neutral-900 dark:bg-neutral-700 dark:accent-white"
                    />
                </div>

                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium whitespace-nowrap text-neutral-600 dark:text-neutral-400">
                    <input v-model="requireApproval" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Require approval for writes</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium whitespace-nowrap text-neutral-600 dark:text-neutral-400">
                    <input v-model="failTool" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Fail tool</span>
                </label>

                <button
                    type="button"
                    @click="reset"
                    class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                >
                    Reset
                </button>
            </div>

            <div class="mt-6 min-h-[4.75rem] rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-900">
                <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-400">
                    <span>
                        reads run, writes
                        <span class="text-emerald-600 dark:text-emerald-400">{{ requireApproval ? 'wait for a human' : 'run on their own' }}</span>
                    </span>
                    <span class="text-neutral-500">The follow-up text only streams once every tool has settled.</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.chat-pane {
    scrollbar-width: thin;
}

.caret {
    display: inline-block;
    width: 0.5em;
    height: 1em;
    margin-left: 0.1em;
    vertical-align: -0.15em;
    background: currentColor;
    animation: caret-blink 1s steps(2, start) infinite;
}

@keyframes caret-blink {
    to {
        visibility: hidden;
    }
}

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
