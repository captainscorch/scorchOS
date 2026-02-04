<script setup lang="ts">
import { computed, ref } from 'vue';

// Interactive controls
const innerRadius = ref(12);
const padding = ref(16);
const useConcentricRadius = ref(true);

// Calculate outer radius
const outerRadius = computed(() => {
    if (useConcentricRadius.value) {
        return innerRadius.value + padding.value;
    }
    // Non-concentric: use same radius for both
    return innerRadius.value;
});

// Reset to defaults
const reset = () => {
    innerRadius.value = 12;
    padding.value = 16;
    useConcentricRadius.value = true;
};
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area -->
        <div class="flex min-h-[280px] items-center justify-center gap-4 p-8 md:gap-12 md:gap-16">
            <!-- Non-Concentric Example -->
            <div class="flex flex-col items-center gap-4">
                <div
                    class="flex items-center justify-center bg-red-500/20 transition-all duration-300"
                    :style="{
                        padding: `${padding}px`,
                        borderRadius: `${innerRadius}px`,
                    }"
                >
                    <div
                        class="flex h-20 w-20 items-center justify-center bg-red-500 text-xs font-medium text-white transition-all duration-300 md:h-24 md:w-24"
                        :style="{ borderRadius: `${innerRadius}px` }"
                    >
                        Same
                    </div>
                </div>
                <span class="text-xs text-neutral-500 dark:text-neutral-400">Non-concentric</span>
            </div>

            <!-- Concentric Example -->
            <div class="flex flex-col items-center gap-4">
                <div
                    class="flex items-center justify-center bg-emerald-500/20 transition-all duration-300"
                    :style="{
                        padding: `${padding}px`,
                        borderRadius: `${outerRadius}px`,
                    }"
                >
                    <div
                        class="flex h-20 w-20 items-center justify-center bg-emerald-500 text-xs font-medium text-white transition-all duration-300 md:h-24 md:w-24"
                        :style="{ borderRadius: `${innerRadius}px` }"
                    >
                        Concentric
                    </div>
                </div>
                <span class="text-xs text-neutral-500 dark:text-neutral-400">Concentric</span>
            </div>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:gap-8">
                <!-- Inner Radius Slider -->
                <div class="flex-1">
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Inner Radius</label>
                        <span class="font-mono text-xs text-neutral-500">{{ innerRadius }}px</span>
                    </div>
                    <input
                        v-model.number="innerRadius"
                        type="range"
                        min="0"
                        max="48"
                        class="h-1.5 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-neutral-900 dark:bg-neutral-700 dark:accent-white"
                    />
                </div>

                <!-- Padding Slider -->
                <div class="flex-1">
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Padding</label>
                        <span class="font-mono text-xs text-neutral-500">{{ padding }}px</span>
                    </div>
                    <input
                        v-model.number="padding"
                        type="range"
                        min="4"
                        max="48"
                        class="h-1.5 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-neutral-900 dark:bg-neutral-700 dark:accent-white"
                    />
                </div>

                <!-- Reset Button -->
                <button
                    @click="reset"
                    class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                >
                    Reset
                </button>
            </div>

            <!-- Formula Display -->
            <div class="mt-6 rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-900">
                <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-400">
                    <span> <span class="text-emerald-600 dark:text-emerald-400">outer</span> = inner + padding </span>
                    <span>
                        <span class="text-emerald-600 dark:text-emerald-400">{{ outerRadius }}px</span> = {{ innerRadius }}px + {{ padding }}px
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom range input styling */
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
