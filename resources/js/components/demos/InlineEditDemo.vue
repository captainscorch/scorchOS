<script setup lang="ts">
import { nextTick, onUnmounted, ref } from 'vue';

type Field = 'company' | 'owner' | 'arr';

interface Row {
    company: string;
    owner: string;
    arr: string;
}

const FIELDS: Field[] = ['company', 'owner', 'arr'];

const DEFAULT_ROWS: Row[] = [
    { company: 'Northwind Traders', owner: 'Mara Lindqvist', arr: '184,000' },
    { company: 'Blue Harbor Labs', owner: 'Jonas Weber', arr: '92,500' },
    { company: 'Kestrel Analytics', owner: 'Priya Natarajan', arr: '310,200' },
    { company: 'Fernwood & Co.', owner: 'Elliot Grange', arr: '47,800' },
];

const inlineEdit = ref(true);
const rows = ref<Row[]>(DEFAULT_ROWS.map((row) => ({ ...row })));

// Which cell is open, and what it held before editing so Escape can restore it.
const editing = ref<{ row: number; field: Field } | null>(null);
const draft = ref('');
const savedFlash = ref<{ row: number; field: Field } | null>(null);
const inputRef = ref<HTMLInputElement | null>(null);

// A plain ref inside v-for collects an array, so the input registers itself instead.
function setInput(el: unknown) {
    if (el) {
        inputRef.value = el as HTMLInputElement;
    }
}

let flashTimer: ReturnType<typeof setTimeout> | null = null;
let ignoreBlur = false;

// Cell buttons by position, so a keyboard user lands back on the cell they just edited instead of on the page.
const cellButtons = new Map<string, HTMLButtonElement>();

function setCellButton(row: number, field: Field, el: unknown) {
    const key = `${row}:${field}`;
    if (el) {
        cellButtons.set(key, el as HTMLButtonElement);
    } else {
        cellButtons.delete(key);
    }
}

async function refocus(row: number, field: Field) {
    await nextTick();
    cellButtons.get(`${row}:${field}`)?.focus();
}

function isEditing(row: number, field: Field): boolean {
    return editing.value?.row === row && editing.value?.field === field;
}

function isFlashing(row: number, field: Field): boolean {
    return savedFlash.value?.row === row && savedFlash.value?.field === field;
}

async function open(row: number, field: Field) {
    editing.value = { row, field };
    draft.value = rows.value[row][field];
    await nextTick();
    // Selecting the text means the first keystroke replaces the value, which is what a spreadsheet user expects.
    inputRef.value?.focus();
    inputRef.value?.select();
    // The previous input's blur fires while Vue swaps the DOM, after onTab returned, so the guard drops only now.
    ignoreBlur = false;
}

function flash(row: number, field: Field) {
    savedFlash.value = { row, field };
    if (flashTimer) {
        clearTimeout(flashTimer);
    }
    flashTimer = setTimeout(() => (savedFlash.value = null), 900);
}

function commit() {
    if (!editing.value) {
        return;
    }
    const { row, field } = editing.value;
    const value = draft.value.trim();
    if (value && value !== rows.value[row][field]) {
        rows.value[row][field] = value;
        flash(row, field);
    }
    editing.value = null;
}

function cancel() {
    editing.value = null;
}

function commitFromKeyboard() {
    const cell = editing.value;
    commit();
    if (cell) {
        refocus(cell.row, cell.field);
    }
}

function cancelFromKeyboard() {
    const cell = editing.value;
    cancel();
    if (cell) {
        refocus(cell.row, cell.field);
    }
}

function onBlur() {
    // Tab moves focus deliberately; the blur it causes must not double-commit.
    if (ignoreBlur) {
        return;
    }
    commit();
}

function onTab(event: KeyboardEvent) {
    if (!editing.value) {
        return;
    }
    event.preventDefault();
    const { row, field } = editing.value;
    const flat = row * FIELDS.length + FIELDS.indexOf(field);
    const total = rows.value.length * FIELDS.length;
    const next = (flat + (event.shiftKey ? -1 : 1) + total) % total;
    ignoreBlur = true;
    commit();
    open(Math.floor(next / FIELDS.length), FIELDS[next % FIELDS.length]);
}

function reset() {
    inlineEdit.value = true;
    rows.value = DEFAULT_ROWS.map((row) => ({ ...row }));
    editing.value = null;
    savedFlash.value = null;
    ignoreBlur = false;
    if (flashTimer) {
        clearTimeout(flashTimer);
        flashTimer = null;
    }
}

onUnmounted(() => {
    if (flashTimer) {
        clearTimeout(flashTimer);
    }
});

const LABELS: Record<Field, string> = { company: 'Company', owner: 'Owner', arr: 'ARR' };
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area -->
        <div class="relative h-[340px] p-4 font-sans md:p-8">
            <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white dark:border-white/10 dark:bg-neutral-950">
                <!-- Fixed layout with explicit widths: a cell turning into an input must not re-flow its column. -->
                <table class="w-full table-fixed text-sm text-neutral-900 dark:text-white">
                    <colgroup>
                        <col class="w-[36%]" />
                        <col class="w-[32%]" />
                        <col class="w-[32%]" />
                    </colgroup>
                    <thead>
                        <tr
                            class="border-b border-neutral-200 text-[10px] tracking-widest text-neutral-500 uppercase dark:border-white/10 dark:text-neutral-400"
                        >
                            <th
                                v-for="field in FIELDS"
                                :key="field"
                                class="px-3 py-2 text-left font-medium"
                                :class="{ 'text-right': field === 'arr' }"
                            >
                                {{ LABELS[field] }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, rowIndex) in rows" :key="rowIndex" class="border-b border-neutral-100 last:border-0 dark:border-white/5">
                            <td
                                v-for="field in FIELDS"
                                :key="field"
                                class="relative h-10 px-1 py-0"
                                :class="{ 'text-right': field === 'arr', 'tabular-nums': field === 'arr' }"
                            >
                                <!-- Input and text share the same box, so opening a cell moves nothing around it. -->
                                <input
                                    v-if="inlineEdit && isEditing(rowIndex, field)"
                                    :ref="setInput"
                                    v-model="draft"
                                    type="text"
                                    class="box-border h-8 w-full min-w-0 rounded-md bg-neutral-100 px-2 text-left font-sans text-sm leading-8 text-neutral-900 ring-2 ring-neutral-900 outline-none ring-inset dark:bg-neutral-800 dark:text-white dark:ring-white"
                                    :class="{ 'text-right': field === 'arr' }"
                                    @keydown.enter.prevent="commitFromKeyboard"
                                    @keydown.esc.prevent="cancelFromKeyboard"
                                    @keydown.tab="onTab"
                                    @blur="onBlur"
                                />
                                <button
                                    v-else
                                    :ref="(el) => setCellButton(rowIndex, field, el)"
                                    type="button"
                                    class="box-border block h-8 w-full min-w-0 cursor-text truncate rounded-md px-2 text-left leading-8 transition-colors hover:bg-neutral-100 focus-visible:ring-2 focus-visible:ring-neutral-400/60 focus-visible:outline-none focus-visible:ring-inset dark:hover:bg-white/5"
                                    :class="{
                                        'text-right': field === 'arr',
                                        'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300': isFlashing(rowIndex, field),
                                        // Room for the Saved label, so it never sits on top of truncated text.
                                        'pr-12': isFlashing(rowIndex, field) && field !== 'arr',
                                        'pl-12': isFlashing(rowIndex, field) && field === 'arr',
                                    }"
                                    @click="open(rowIndex, field)"
                                >
                                    <span v-if="field === 'arr'" class="mr-0.5 text-neutral-400">€</span>{{ row[field] }}
                                </button>
                                <span
                                    v-if="isFlashing(rowIndex, field)"
                                    class="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-[10px] font-medium text-emerald-600 dark:text-emerald-400"
                                    :class="{ 'right-auto left-3': field === 'arr' }"
                                >
                                    Saved
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- The old way: a separate edit panel that pulls the eye away from the row. -->
            <div
                v-if="!inlineEdit && editing"
                class="absolute inset-x-4 bottom-4 rounded-xl border border-neutral-200 bg-white p-4 shadow-lg md:inset-x-8 md:bottom-8 dark:border-white/10 dark:bg-neutral-950"
            >
                <label class="mb-2 block text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    Edit {{ LABELS[editing.field] }} · row {{ editing.row + 1 }}
                </label>
                <input
                    :ref="setInput"
                    v-model="draft"
                    type="text"
                    class="mb-3 h-9 w-full rounded-md border border-neutral-200 bg-neutral-50 px-3 text-sm outline-none focus:ring-2 focus:ring-neutral-400/30 dark:border-white/10 dark:bg-neutral-900"
                    @keydown.enter.prevent="commitFromKeyboard"
                    @keydown.esc.prevent="cancelFromKeyboard"
                />
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        class="rounded-lg border border-neutral-200 px-3 py-1.5 text-xs font-medium text-neutral-600 hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                        @click="cancel"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        class="rounded-lg bg-neutral-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-neutral-700 dark:bg-white dark:text-black dark:hover:bg-neutral-200"
                        @click="commit"
                    >
                        Save
                    </button>
                </div>
            </div>

            <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">
                Click a cell. Enter or blur saves, Escape restores, Tab and Shift+Tab walk the cells.
            </p>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:gap-8">
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="inlineEdit" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" @change="editing = null" />
                    <span>Inline edit</span>
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
                        mode:
                        <span class="text-emerald-600 dark:text-emerald-400">{{ inlineEdit ? 'in place' : 'edit panel' }}</span>
                    </span>
                    <!-- Both sentences share one grid cell and only one is visible, so the box keeps the height of the longer one. -->
                    <span class="grid text-neutral-500">
                        <span class="[grid-area:1/1]" :class="{ invisible: !inlineEdit }">The cell becomes the input. Nothing else moves.</span>
                        <span class="[grid-area:1/1]" :class="{ invisible: inlineEdit }"
                            >Editing happens somewhere else, so the eye has to travel and come back.</span
                        >
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
