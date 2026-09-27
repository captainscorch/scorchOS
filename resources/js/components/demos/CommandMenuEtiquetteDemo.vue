<script setup lang="ts">
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faGithub } from '@fortawesome/free-brands-svg-icons';
import { faBook, faDiagramProject, faGlobe, faHouse, faListCheck, faSliders, faSwatchbook } from '@fortawesome/sharp-light-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { onUnmounted, ref } from 'vue';

library.add(faHouse, faListCheck, faDiagramProject, faSliders, faSwatchbook, faBook, faGithub, faGlobe);

const ITEMS = [
    { value: 'home', label: 'Home', icon: 'fa-sharp fa-light fa-house' },
    { value: 'issues', label: 'Issues', icon: 'fa-sharp fa-light fa-list-check' },
    { value: 'projects', label: 'Projects', icon: 'fa-sharp fa-light fa-diagram-project' },
    { value: 'settings', label: 'Settings', icon: 'fa-sharp fa-light fa-sliders' },
    { value: 'themes', label: 'Themes', icon: 'fa-sharp fa-light fa-swatchbook' },
    { value: 'docs', label: 'Docs', icon: 'fa-sharp fa-light fa-book' },
    { value: 'github', label: 'GitHub', icon: 'fa-brands fa-github' },
    { value: 'language', label: 'Language', icon: 'fa-sharp fa-light fa-globe' },
];

const pickAfterMove = ref(true);
const selectOnClick = ref(true);
const showHints = ref(true);

const stage = ref<HTMLElement | null>(null);
const selected = ref('');
const lastEvent = ref('');
// Remounting the list puts fresh rows under a resting cursor, which is the case the first rule is about.
const listKey = ref(0);
// The button that triggers the re-render sits outside the list, so the reader needs time to park the cursor on a row.
const REMOUNT_DELAY = 2;
const countdown = ref(0);

let suppressNextSelect = false;
let countdownTimer: ReturnType<typeof setInterval> | null = null;

function onItemEnter(event: MouseEvent) {
    if (pickAfterMove.value) {
        return;
    }
    // The old way: a row lights up the moment it is under the cursor, even if the cursor never moved.
    // reka-ui only highlights on pointermove, so the bad behaviour has to be faked with a synthetic move.
    event.currentTarget?.dispatchEvent(new PointerEvent('pointermove', { bubbles: true }));
}

function onItemPointerDown(label: string) {
    if (selectOnClick.value) {
        return;
    }
    // Committing on press fires before the user can drag away or change their mind.
    suppressNextSelect = true;
    selected.value = label;
    lastEvent.value = 'selected on pointerdown';
}

function onItemSelect(label: string, event: Event) {
    if (suppressNextSelect) {
        suppressNextSelect = false;
        event.preventDefault();
        return;
    }
    selected.value = label;
    // reka-ui turns Enter into element.click(), which arrives as a click with detail 0; a real mouse click counts from 1.
    const original = event instanceof CustomEvent ? event.detail?.originalEvent : null;
    lastEvent.value = original instanceof MouseEvent && original.detail === 0 ? 'selected with Enter' : 'selected on click';
}

// A real command menu keeps focus in the search field, so the arrow keys keep working after a click on a row.
// reka-ui moves focus onto the clicked option, where arrow keys do nothing, so it is handed back here.
function focusInput(event: MouseEvent) {
    const input = stage.value?.querySelector('input');
    if (input && event.target !== input) {
        input.focus({ preventScroll: true });
    }
}

function onInputEscape(event: KeyboardEvent) {
    const input = event.target as HTMLInputElement;
    if (!input.value) {
        return;
    }
    // The filter is owned by the Command component, so clearing goes through the input event it listens to.
    input.value = '';
    input.dispatchEvent(new Event('input', { bubbles: true }));
    lastEvent.value = 'query cleared with Escape';
}

function stopCountdown() {
    if (countdownTimer) {
        clearInterval(countdownTimer);
        countdownTimer = null;
    }
    countdown.value = 0;
}

function scheduleRemount() {
    stopCountdown();
    countdown.value = REMOUNT_DELAY;
    lastEvent.value = 'park the cursor on a row and keep it still';
    countdownTimer = setInterval(() => {
        countdown.value -= 1;
        if (countdown.value > 0) {
            return;
        }
        stopCountdown();
        listKey.value += 1;
        lastEvent.value = 'list re-rendered under the cursor';
    }, 1000);
}

function reset() {
    stopCountdown();
    pickAfterMove.value = true;
    selectOnClick.value = true;
    showHints.value = true;
    selected.value = '';
    lastEvent.value = '';
    suppressNextSelect = false;
    listKey.value += 1;
}

onUnmounted(stopCountdown);
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area -->
        <div ref="stage" class="flex h-[440px] flex-col items-center justify-center p-4 md:p-8">
            <!-- highlight-on-hover lets reka move the highlight on pointermove; CSS :hover is switched off below so the
                 highlight is the only thing that lights a row, and it never fires for a cursor that did not move. -->
            <Command
                :key="listKey"
                :highlight-on-hover="true"
                @click="focusInput"
                class="h-auto w-full max-w-sm rounded-xl border border-neutral-200 bg-white shadow-lg dark:border-white/10 dark:bg-neutral-950"
            >
                <CommandInput placeholder="Where to?" @keydown.esc="onInputEscape" />
                <CommandList class="h-[264px] max-h-none">
                    <CommandEmpty>No results.</CommandEmpty>
                    <CommandGroup heading="Pages">
                        <CommandItem
                            v-for="item in ITEMS"
                            :key="item.value"
                            :value="item.value"
                            class="group hover:bg-transparent hover:text-inherit data-[highlighted]:bg-accent"
                            @mouseenter="onItemEnter"
                            @pointerdown="onItemPointerDown(item.label)"
                            @select="onItemSelect(item.label, $event)"
                        >
                            <FontAwesomeIcon :icon="item.icon" class="mr-1.5 ml-0.5 h-4 w-4 opacity-60 group-data-[highlighted]:opacity-100" />
                            <span>{{ item.label }}</span>
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
                <div
                    class="command-hints flex h-9 items-center justify-end gap-1 border-t border-neutral-200 px-3 dark:border-white/10"
                    :class="{ invisible: !showHints }"
                >
                    <kbd
                        v-for="hint in ['↑', '↓', '⏎', 'esc']"
                        :key="hint"
                        class="rounded border border-neutral-200 px-1.5 py-0.5 font-mono text-[10px] text-neutral-500 dark:border-white/10 dark:text-neutral-400"
                    >
                        {{ hint }}
                    </kbd>
                </div>
            </Command>

            <div class="mt-5 flex h-10 w-full max-w-sm flex-col gap-1 font-mono text-xs text-neutral-500 dark:text-neutral-400">
                <span>
                    Selected:
                    <span class="text-emerald-600 dark:text-emerald-400">{{ selected || '—' }}</span>
                </span>
                <span class="truncate">{{ lastEvent || 'Hover, press and click the rows.' }}</span>
            </div>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-4 md:flex-row md:flex-wrap md:items-center md:gap-8">
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="pickAfterMove" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Pointer picks only after it moved</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="selectOnClick" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Select on click, not on press</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="showHints" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Keyboard hints</span>
                </label>

                <div class="grid grid-cols-[1fr_auto] gap-2 md:ml-auto md:flex md:items-center">
                    <button
                        @click="scheduleRemount"
                        :disabled="countdown > 0"
                        class="shrink-0 rounded-lg border border-neutral-200 px-3 py-2 text-xs font-medium whitespace-nowrap text-neutral-600 tabular-nums transition-colors hover:bg-neutral-100 disabled:cursor-default disabled:hover:bg-transparent md:px-4 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                    >
                        <!-- Same label length while counting down, so the button never changes width. -->
                        Re-render in {{ countdown || REMOUNT_DELAY }} s
                    </button>
                    <button
                        @click="reset"
                        class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <div class="mt-6 rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-900">
                <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-400">
                    <span>
                        highlight on <span class="text-emerald-600 dark:text-emerald-400">{{ pickAfterMove ? 'pointermove' : 'mouseenter' }}</span> ·
                        select on
                        <span class="text-emerald-600 dark:text-emerald-400">{{ selectOnClick ? 'click' : 'pointerdown' }}</span>
                    </span>
                    <span class="text-neutral-500"
                        >Press “Re-render in {{ REMOUNT_DELAY }} s”, park the cursor on a row and watch whether that row lights up.</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Hints describe keys a touch device does not have. Visibility keeps the row's height, so the card stays put. */
@media (hover: none) {
    .command-hints {
        visibility: hidden;
    }
}
</style>
