<script setup lang="ts">
import AsciiObject from '@/components/canvasui/AsciiObject.vue';
import { onUnmounted, ref } from 'vue';

const DEFAULT_SRC = '/img/captainscorch_logo.svg';

const CHARSETS: Record<string, string> = {
    ascii: Array.from({ length: 95 }, (_, i) => String.fromCharCode(32 + i)).join(''),
    dots: ' .:-=+*#%@',
    blocks: ' ░▒▓█',
    binary: ' 01',
};

const src = ref(DEFAULT_SRC);
const fileName = ref('');
const cellSize = ref(8);
const charsetKey = ref('ascii');
const colored = ref(false);
const invert = ref(false);
const edgeContrast = ref(2.4);
const autoRotate = ref(false);
const highlight = ref('#ffffff');
const tint = ref('#ffffff');
const dragging = ref(false);
const error = ref('');

const objectRef = ref<InstanceType<typeof AsciiObject> | null>(null);
let objectUrl: string | null = null;

// The renderer sniffs the format from the bytes, so any of these can go straight in.
const ACCEPT = '.glb,.gltf,.svg,.png,.jpg,.jpeg,.webp,.gif';

function releaseObjectUrl() {
    if (objectUrl) {
        URL.revokeObjectURL(objectUrl);
        objectUrl = null;
    }
}

function loadFile(file: File | undefined) {
    if (!file) {
        return;
    }
    releaseObjectUrl();
    objectUrl = URL.createObjectURL(file);
    fileName.value = file.name;
    error.value = '';
    src.value = objectUrl;
}

function onFileInput(event: Event) {
    loadFile((event.target as HTMLInputElement).files?.[0]);
}

// dragleave also fires when the pointer crosses into a child, so only the stage's own edge counts.
function onDragLeave(event: DragEvent) {
    const stage = event.currentTarget as HTMLElement | null;
    if (!stage || !stage.contains(event.relatedTarget as Node | null)) {
        dragging.value = false;
    }
}

function onDrop(event: DragEvent) {
    dragging.value = false;
    loadFile(event.dataTransfer?.files?.[0]);
}

function onError() {
    error.value = fileName.value ? `Could not read ${fileName.value}.` : 'Could not load the asset.';
}

function reset() {
    releaseObjectUrl();
    src.value = DEFAULT_SRC;
    fileName.value = '';
    error.value = '';
    cellSize.value = 8;
    charsetKey.value = 'ascii';
    colored.value = false;
    invert.value = false;
    edgeContrast.value = 2.4;
    autoRotate.value = false;
    highlight.value = '#ffffff';
    tint.value = '#ffffff';
    objectRef.value?.resetView();
}

onUnmounted(releaseObjectUrl);
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Stage: dark on purpose, the glyphs are drawn in white like the footer mark -->
        <div
            class="relative bg-neutral-950 transition-shadow"
            :class="{ 'shadow-[inset_0_0_0_2px_var(--color-brand-400)]': dragging }"
            @dragover.prevent="dragging = true"
            @dragleave="onDragLeave"
            @drop.prevent="onDrop"
        >
            <AsciiObject
                ref="objectRef"
                :src="src"
                :ascii="true"
                :cell-size="cellSize"
                :charset="CHARSETS[charsetKey]"
                :colored="colored"
                color="#ffffff"
                :invert="invert"
                :scale="3"
                :contrast="1.2"
                :edge-contrast="edgeContrast"
                :orbit="true"
                :auto-rotate="autoRotate"
                :highlight="highlight"
                :tint="tint"
                :float-intensity="1.2"
                :rotation-intensity="0.8"
                :float-speed="1.4"
                background=""
                :on-error="onError"
                class="h-[320px] w-full cursor-grab active:cursor-grabbing md:h-[400px] [&_canvas]:touch-pan-y!"
            />
            <div
                class="pointer-events-none absolute inset-x-0 bottom-0 flex items-end justify-between p-4 text-[10px] tracking-widest text-white/40 uppercase"
            >
                <span>{{ fileName || 'captainscorch_logo.svg' }}</span>
                <span class="hidden md:inline">Drag to orbit · Drop a file to swap</span>
            </div>
            <div v-if="error" class="absolute inset-x-0 top-0 p-4 text-center text-xs text-red-400">{{ error }}</div>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3 md:gap-8">
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Cell size</label>
                        <span class="font-mono text-xs text-neutral-500 tabular-nums">{{ cellSize }} px</span>
                    </div>
                    <input
                        v-model.number="cellSize"
                        type="range"
                        min="4"
                        max="20"
                        class="h-1.5 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-neutral-900 dark:bg-neutral-700 dark:accent-white"
                    />
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Edge contrast</label>
                        <span class="font-mono text-xs text-neutral-500">{{ edgeContrast.toFixed(1) }}</span>
                    </div>
                    <input
                        v-model.number="edgeContrast"
                        type="range"
                        min="1"
                        max="5"
                        step="0.1"
                        class="h-1.5 w-full cursor-pointer appearance-none rounded-full bg-neutral-200 accent-neutral-900 dark:bg-neutral-700 dark:accent-white"
                    />
                </div>

                <div>
                    <label class="mb-2 block text-xs font-medium text-neutral-600 dark:text-neutral-400">Charset</label>
                    <div class="flex flex-wrap gap-1.5">
                        <button
                            v-for="(_chars, key) in CHARSETS"
                            :key="key"
                            type="button"
                            @click="charsetKey = key"
                            class="cursor-pointer rounded-full border px-3 py-1 font-mono text-[11px] transition-colors"
                            :class="
                                charsetKey === key
                                    ? 'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-black'
                                    : 'border-neutral-200 text-neutral-600 hover:border-neutral-400 dark:border-white/10 dark:text-neutral-400 dark:hover:border-white/30'
                            "
                        >
                            {{ key }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-4 md:flex-row md:flex-wrap md:items-center md:gap-x-8 md:gap-y-4">
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="colored" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Colored</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="invert" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Invert</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="autoRotate" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Turntable</span>
                </label>
                <!-- The ring light tints the bevel and extrusion sides, so this is what Colored picks up on a single-color asset. -->
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input
                        v-model="highlight"
                        type="color"
                        class="size-6 cursor-pointer rounded-full border border-neutral-300 bg-transparent p-0 dark:border-white/10 [&::-webkit-color-swatch]:rounded-full [&::-webkit-color-swatch]:border-0 [&::-webkit-color-swatch-wrapper]:p-0"
                    />
                    <span>Light</span>
                    <span class="font-mono text-neutral-500">{{ highlight }}</span>
                </label>
                <!-- Multiplied into the asset's materials, so it colors the faces themselves. -->
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input
                        v-model="tint"
                        type="color"
                        class="size-6 cursor-pointer rounded-full border border-neutral-300 bg-transparent p-0 dark:border-white/10 [&::-webkit-color-swatch]:rounded-full [&::-webkit-color-swatch]:border-0 [&::-webkit-color-swatch-wrapper]:p-0"
                    />
                    <span>Tint</span>
                    <span class="font-mono text-neutral-500">{{ tint }}</span>
                </label>

                <div class="flex items-center gap-2 md:ml-auto">
                    <label
                        class="cursor-pointer rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                    >
                        Load file
                        <input type="file" :accept="ACCEPT" class="sr-only" @change="onFileInput" />
                    </label>
                    <button
                        @click="reset"
                        class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                    >
                        Reset
                    </button>
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
