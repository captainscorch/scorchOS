<script setup lang="ts">
import { ref } from 'vue';

const fixLeft = ref(false);
const leftEnters = ref(0);
const rightEnters = ref(0);

function reset() {
    fixLeft.value = false;
    leftEnters.value = 0;
    rightEnters.value = 0;
}
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area -->
        <div class="flex h-[440px] flex-col items-center justify-center gap-6 p-6 font-sans sm:h-[300px] md:p-8">
            <div class="grid w-full max-w-lg grid-cols-1 gap-8 sm:grid-cols-2">
                <!-- Left card: the transform on the link itself pulls the hit area away from the cursor. -->
                <div class="flex flex-col items-center gap-3">
                    <a
                        v-if="!fixLeft"
                        href="#"
                        class="lift block w-full rounded-xl border border-neutral-200 bg-white p-5 text-left dark:border-white/10 dark:bg-neutral-950"
                        @click.prevent
                        @mouseenter="leftEnters++"
                    >
                        <span class="block text-sm font-semibold text-neutral-900 dark:text-white">Broken</span>
                        <span class="mt-1 block text-xs text-neutral-500 dark:text-neutral-400">Transform on the link</span>
                    </a>
                    <a v-else href="#" class="block w-full text-left" @click.prevent @mouseenter="leftEnters++">
                        <span class="lift block rounded-xl border border-neutral-200 bg-white p-5 dark:border-white/10 dark:bg-neutral-950">
                            <span class="block text-sm font-semibold text-neutral-900 dark:text-white">Fixed now</span>
                            <span class="mt-1 block text-xs text-neutral-500 dark:text-neutral-400">Transform on an inner layer</span>
                        </span>
                    </a>
                    <span class="font-mono text-xs text-neutral-500">mouseenter × {{ leftEnters }}</span>
                </div>

                <!-- Right card: the link keeps its geometry, only the inner layer moves, so the hit area never leaves the cursor. -->
                <div class="flex flex-col items-center gap-3">
                    <a href="#" class="block w-full text-left" @click.prevent @mouseenter="rightEnters++">
                        <span class="lift block rounded-xl border border-neutral-200 bg-white p-5 dark:border-white/10 dark:bg-neutral-950">
                            <span class="block text-sm font-semibold text-neutral-900 dark:text-white">Fixed</span>
                            <span class="mt-1 block text-xs text-neutral-500 dark:text-neutral-400">Transform on an inner layer</span>
                        </span>
                    </a>
                    <span class="font-mono text-xs text-neutral-500">mouseenter × {{ rightEnters }}</span>
                </div>
            </div>
            <p class="text-center text-xs text-neutral-500 dark:text-neutral-400">
                Slide the cursor slowly along the bottom edge of each card and watch the counters.
            </p>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-8">
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="fixLeft" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Apply fix to left card</span>
                </label>

                <button
                    @click="reset"
                    class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 md:ml-auto dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                >
                    Reset
                </button>
            </div>

            <div class="mt-6 rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-900">
                <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-400">
                    <span>
                        left card · hit area:
                        <span :class="fixLeft ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400'">{{
                            fixLeft ? 'static' : 'moves'
                        }}</span>
                        · visual layer: <span class="text-emerald-600 dark:text-emerald-400">moves</span>
                    </span>
                    <span class="text-neutral-500"
                        >When the element that owns :hover moves, it can move out from under the pointer and retrigger itself.</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.lift {
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

a:hover > .lift,
a.lift:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px -8px rgb(0 0 0 / 0.25);
}
</style>
