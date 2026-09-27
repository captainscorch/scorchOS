<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

const tabular = ref(true);
const running = ref(true);

// Values that change under the eye: a stopwatch, a weight readout and a small ledger.
const elapsed = ref(0);
const weight = ref(82.4);
const ledger = ref([1180, 3720, 940, 2260]);

let frame = 0;
let lastTick = 0;

function tick(now: number) {
    if (running.value) {
        if (lastTick) {
            elapsed.value += now - lastTick;
        }
        // Nudge the readouts every few frames so digits keep flipping without turning into noise.
        if (Math.floor(now / 160) !== Math.floor(lastTick / 160)) {
            weight.value = Math.round((80 + Math.random() * 9.9) * 10) / 10;
            const index = Math.floor(Math.random() * ledger.value.length);
            ledger.value[index] = Math.round(Math.random() * 9000) + 100;
        }
    }
    lastTick = now;
    frame = requestAnimationFrame(tick);
}

function formatElapsed(ms: number): string {
    const minutes = Math.floor(ms / 60000);
    const seconds = Math.floor((ms % 60000) / 1000);
    const millis = Math.floor(ms % 1000);
    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}.${String(millis).padStart(3, '0')}`;
}

function formatCurrency(value: number): string {
    return value.toLocaleString('en-US', { style: 'currency', currency: 'EUR' });
}

function reset() {
    tabular.value = true;
    running.value = true;
    elapsed.value = 0;
}

onMounted(() => {
    frame = requestAnimationFrame(tick);
});

onUnmounted(() => cancelAnimationFrame(frame));
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area -->
        <div class="grid min-h-[280px] grid-cols-1 gap-8 p-6 font-sans md:grid-cols-2 md:p-8" :class="{ 'tabular-nums': tabular }">
            <!-- Live readouts -->
            <div class="flex flex-col gap-5">
                <div>
                    <span class="mb-1 block text-[10px] tracking-widest text-neutral-500 uppercase dark:text-neutral-400">Stopwatch</span>
                    <span class="text-4xl font-semibold text-neutral-900 md:text-5xl dark:text-white">{{ formatElapsed(elapsed) }}</span>
                </div>
                <div>
                    <span class="mb-1 block text-[10px] tracking-widest text-neutral-500 uppercase dark:text-neutral-400">Body weight</span>
                    <span class="text-3xl font-medium text-neutral-900 dark:text-white">{{ weight.toFixed(1) }}</span>
                    <span class="ml-1 text-sm text-neutral-500 dark:text-neutral-400">kg</span>
                </div>
            </div>

            <!-- Ledger: right-aligned amounts only line up when every digit shares one width -->
            <div class="flex flex-col justify-center">
                <span class="mb-2 block text-[10px] tracking-widest text-neutral-500 uppercase dark:text-neutral-400">Ledger</span>
                <table class="w-full text-sm text-neutral-900 dark:text-white">
                    <tbody>
                        <tr v-for="(amount, index) in ledger" :key="index" class="border-b border-neutral-200 last:border-0 dark:border-white/10">
                            <td class="py-1.5 text-neutral-500 dark:text-neutral-400">Position {{ index + 1 }}</td>
                            <td class="py-1.5 text-right">{{ formatCurrency(amount) }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Same digit count, different glyph widths: each marker sits where its own text ends, so the gap is the whole point. -->
                <div class="mt-4 flex flex-col items-start text-2xl leading-tight text-neutral-400 dark:text-neutral-500">
                    <span class="border-r-2 border-emerald-500 pr-2">11111111</span>
                    <span class="border-r-2 border-emerald-500 pr-2">88888888</span>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-8">
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="tabular" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>tabular-nums</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="running" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Running</span>
                </label>

                <button
                    @click="reset"
                    class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 md:ml-auto dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                >
                    Reset
                </button>
            </div>

            <div class="mt-6 rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-900">
                <!-- Both states are rendered on top of each other and only one is visible, so the box keeps the height of the longer one. -->
                <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-400">
                    <span>
                        <span class="whitespace-nowrap">font-variant-numeric:</span>{{ ' ' }}
                        <span class="inline-grid text-emerald-600 dark:text-emerald-400">
                            <span class="[grid-area:1/1]" :class="{ invisible: !tabular }">tabular-nums;</span>
                            <span class="[grid-area:1/1]" :class="{ invisible: tabular }">normal;</span>
                        </span>
                    </span>
                    <span class="grid text-neutral-500">
                        <span class="[grid-area:1/1]" :class="{ invisible: !tabular }">Every digit takes the same advance width.</span>
                        <span class="[grid-area:1/1]" :class="{ invisible: tabular }"
                            >Digits keep their natural width, so a 1 is narrower than an 8.</span
                        >
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
