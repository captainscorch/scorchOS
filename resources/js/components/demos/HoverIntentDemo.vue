<script setup lang="ts">
import { onUnmounted, ref } from 'vue';

interface Point {
    x: number;
    y: number;
}

interface NavItem {
    id: string;
    label: string;
    children: string[];
}

const NAV: NavItem[] = [
    { id: 'product', label: 'Product', children: ['Issues', 'Cycles', 'Roadmaps', 'Insights'] },
    { id: 'resources', label: 'Resources', children: ['Docs', 'Changelog', 'Community', 'Status'] },
];

const hoverIntent = ref(true);
const safeTriangle = ref(true);
const showTriangle = ref(true);

const openId = ref<string | null>(null);
const triangle = ref<[Point, Point, Point] | null>(null);
// Panel rect in stage coordinates, padded sideways and below: anywhere over the panel is safe by definition.
const safeRect = ref<{ left: number; top: number; right: number; bottom: number } | null>(null);
const closedEarly = ref(0);
const crossedSafely = ref(0);

const stage = ref<HTMLElement | null>(null);
const panels = ref<Record<string, HTMLElement | null>>({});

let intentTimer: ReturnType<typeof setTimeout> | null = null;
let graceTimer: ReturnType<typeof setTimeout> | null = null;
let leaveTimer: ReturnType<typeof setTimeout> | null = null;
// Widen the triangle base so a slightly wobbly path toward the panel still counts as progress.
const TRIANGLE_TOLERANCE = 24;
// Set while the cursor is inside the gap, so a later panel enter counts as a safe crossing.
let crossing = false;
// "Closed early" means the menu died while the cursor was already below the trigger, on its way to the panel.
// A cursor that wandered off along the nav bar closed it on purpose and must not count.
let gapTop = 0;
let lastY = 0;

function clearIntent() {
    if (intentTimer) {
        clearTimeout(intentTimer);
        intentTimer = null;
    }
}

function clearGrace() {
    if (graceTimer) {
        clearTimeout(graceTimer);
        graceTimer = null;
    }
}

function clearLeave() {
    if (leaveTimer) {
        clearTimeout(leaveTimer);
        leaveTimer = null;
    }
}

function close(early = false) {
    if (openId.value && early) {
        closedEarly.value += 1;
    }
    openId.value = null;
    // The drawing stays after the menu closes, so the reader can study the zone they just crossed.
    crossing = false;
    clearGrace();
    clearLeave();
}

function open(id: string) {
    clearGrace();
    clearLeave();
    crossing = false;
    openId.value = id;
}

function toStage(event: PointerEvent | MouseEvent): Point {
    const rect = stage.value?.getBoundingClientRect();
    return { x: event.clientX - (rect?.left ?? 0), y: event.clientY - (rect?.top ?? 0) };
}

// Touch has no hover: a tap sends enter, leave and click at once, and would open and close the menu in one go.
// Touch goes through the click handler only.
function isTouch(event: PointerEvent) {
    return event.pointerType === 'touch';
}

function onTriggerEnter(id: string, event: PointerEvent) {
    if (isTouch(event)) {
        return;
    }
    clearIntent();
    // Coming back onto the trigger of the open menu is not a reason to close it.
    if (openId.value === id) {
        clearGrace();
        clearLeave();
        crossing = false;
        return;
    }
    if (!hoverIntent.value) {
        open(id);
        return;
    }
    // A cursor passing through on its way somewhere else should not flip menus open.
    intentTimer = setTimeout(() => open(id), 150);
}

function onTriggerLeave(id: string, event: PointerEvent) {
    if (isTouch(event)) {
        return;
    }
    clearIntent();
    if (openId.value !== id) {
        return;
    }
    const trigger = (event.currentTarget as HTMLElement).getBoundingClientRect();
    const exitedDown = event.clientY >= trigger.bottom - 2;
    if (!safeTriangle.value) {
        // The naive path: the 8px gap below the trigger closes the panel before the cursor reaches it.
        close(exitedDown);
        return;
    }
    if (event.clientY <= trigger.top + 2) {
        // Leaving upward points away from the panel; there is nothing to bridge.
        close();
        return;
    }
    const panel = panels.value[id];
    const stageRect = stage.value?.getBoundingClientRect();
    if (!panel || !stageRect) {
        close(exitedDown);
        return;
    }
    gapTop = trigger.bottom - stageRect.top;
    lastY = event.clientY - stageRect.top;
    const rect = panel.getBoundingClientRect();
    const apex = toStage(event);
    // The base sits on the panel's top edge; everything below it is covered by the panel rect itself.
    const left = { x: rect.left - stageRect.left - TRIANGLE_TOLERANCE, y: rect.top - stageRect.top };
    const right = { x: rect.right - stageRect.left + TRIANGLE_TOLERANCE, y: rect.top - stageRect.top };
    triangle.value = [apex, left, right];
    // No padding upward: the gap above the panel belongs to the triangle, and the nav bar is not part of the zone.
    safeRect.value = {
        left: rect.left - stageRect.left - TRIANGLE_TOLERANCE,
        top: rect.top - stageRect.top,
        right: rect.right - stageRect.left + TRIANGLE_TOLERANCE,
        bottom: rect.bottom - stageRect.top + TRIANGLE_TOLERANCE,
    };
    crossing = true;
    armGrace();
}

// Progress toward the panel keeps the menu alive; a cursor that stalls in the gap gives up after 600 ms.
function armGrace() {
    clearGrace();
    graceTimer = setTimeout(() => close(lastY > gapTop), 600);
}

function sign(a: Point, b: Point, c: Point) {
    return (a.x - c.x) * (b.y - c.y) - (b.x - c.x) * (a.y - c.y);
}

function insideTriangle(p: Point, [a, b, c]: [Point, Point, Point]) {
    const d1 = sign(p, a, b);
    const d2 = sign(p, b, c);
    const d3 = sign(p, c, a);
    const hasNeg = d1 < 0 || d2 < 0 || d3 < 0;
    const hasPos = d1 > 0 || d2 > 0 || d3 > 0;
    return !(hasNeg && hasPos);
}

function onStageMove(event: PointerEvent) {
    // Only the gap crossing is judged. Over the panel or the trigger the menu simply stays open.
    if (!triangle.value || !crossing) {
        return;
    }
    const point = toStage(event);
    lastY = point.y;
    const zone = safeRect.value;
    const overPanel = zone && point.x >= zone.left && point.x <= zone.right && point.y >= zone.top && point.y <= zone.bottom;
    if (overPanel || insideTriangle(point, triangle.value)) {
        armGrace();
        clearLeave();
    } else if (!leaveTimer) {
        // One stray move outside the triangle is not a decision; a few of them are.
        leaveTimer = setTimeout(() => close(lastY > gapTop), 250);
    }
}

function onPanelEnter(event: PointerEvent) {
    if (isTouch(event)) {
        return;
    }
    clearLeave();
    if (crossing) {
        crossedSafely.value += 1;
    }
    crossing = false;
    // The drawing is kept on purpose; only the next crossing or Reset replaces it.
    clearGrace();
}

function onPanelLeave(event: PointerEvent) {
    if (isTouch(event)) {
        return;
    }
    // Leaving the panel edge for a moment (toward a nested item, or past the bottom) should not slam it shut.
    clearLeave();
    leaveTimer = setTimeout(() => close(), 200);
}

function setPanel(id: string, el: unknown) {
    panels.value[id] = (el as HTMLElement | null) ?? null;
}

function reset() {
    hoverIntent.value = true;
    safeTriangle.value = true;
    showTriangle.value = true;
    closedEarly.value = 0;
    crossedSafely.value = 0;
    clearIntent();
    close();
    triangle.value = null;
    safeRect.value = null;
}

onUnmounted(() => {
    clearIntent();
    clearGrace();
    clearLeave();
});
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area -->
        <div ref="stage" class="relative h-[320px] p-6 md:p-8" @pointermove="onStageMove">
            <!-- Nav bar -->
            <!-- Keyboard and touch use the click path: a trigger toggles its panel, Escape closes it. -->
            <nav
                class="mx-auto flex max-w-md items-center gap-1 rounded-xl border border-neutral-200 bg-white px-2 py-1.5 font-sans text-sm shadow-sm dark:border-white/10 dark:bg-neutral-950"
                @keydown.esc="close()"
            >
                <span class="px-2 font-semibold text-neutral-900 dark:text-white">Acme</span>
                <div v-for="(item, index) in NAV" :key="item.id" class="relative">
                    <button
                        type="button"
                        class="cursor-default rounded-lg px-2 py-1.5 text-neutral-600 transition-colors sm:px-3 dark:text-neutral-400"
                        :class="{ 'bg-neutral-100 text-neutral-900 dark:bg-white/10 dark:text-white': openId === item.id }"
                        @pointerenter="onTriggerEnter(item.id, $event)"
                        @pointerleave="onTriggerLeave(item.id, $event)"
                        @click="openId === item.id ? close() : open(item.id)"
                    >
                        {{ item.label }}
                    </button>
                    <!-- The 8px gap (mt-2) is deliberate: it is the space every naive hover menu dies in.
                         On phones the last panel opens to the left, or the card would cut it off. -->
                    <ul
                        v-if="openId === item.id"
                        :ref="(el) => setPanel(item.id, el)"
                        :class="{ 'max-sm:right-0 max-sm:left-auto': index === NAV.length - 1 }"
                        class="absolute top-full left-0 z-10 mt-2 w-44 rounded-lg border border-neutral-200 bg-white py-1 shadow-lg dark:border-white/10 dark:bg-neutral-900"
                        @pointerenter="onPanelEnter"
                        @pointerleave="onPanelLeave"
                    >
                        <li v-for="child in item.children" :key="child">
                            <button
                                type="button"
                                class="block w-full cursor-pointer px-3 py-1.5 text-left text-xs text-neutral-700 hover:bg-neutral-100 focus-visible:bg-neutral-100 focus-visible:outline-none dark:text-neutral-300 dark:hover:bg-white/10 dark:focus-visible:bg-white/10"
                                @click="close()"
                            >
                                {{ child }}
                            </button>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Triangle overlay in stage coordinates -->
            <Transition
                enter-active-class="transition duration-150"
                enter-from-class="opacity-0"
                leave-active-class="transition duration-300"
                leave-to-class="opacity-0"
            >
                <svg
                    v-if="showTriangle && triangle"
                    class="pointer-events-none absolute inset-0 h-full w-full transition-opacity duration-300"
                    :class="openId ? 'opacity-100' : 'opacity-50'"
                    aria-hidden="true"
                >
                    <!-- The whole safe zone: the padded panel rect plus the triangle that bridges the gap. -->
                    <rect
                        v-if="safeRect"
                        :x="safeRect.left"
                        :y="safeRect.top"
                        :width="safeRect.right - safeRect.left"
                        :height="safeRect.bottom - safeRect.top"
                        rx="8"
                        class="fill-emerald-500/5 stroke-emerald-500/60"
                        stroke-width="1"
                        stroke-dasharray="4 3"
                    />
                    <polygon
                        :points="triangle.map((p) => `${p.x},${p.y}`).join(' ')"
                        class="fill-emerald-500/20 stroke-emerald-500"
                        stroke-width="1"
                        stroke-dasharray="4 3"
                    />
                </svg>
            </Transition>

            <div
                class="absolute right-6 bottom-6 left-6 flex flex-col gap-1 font-mono text-xs text-neutral-500 md:right-8 md:bottom-8 md:left-8 dark:text-neutral-400"
            >
                <span>
                    Crossed safely:
                    <span class="text-emerald-600 dark:text-emerald-400">{{ crossedSafely }}</span>
                </span>
                <span>
                    Closed early:
                    <span class="text-red-500 dark:text-red-400">{{ closedEarly }}</span>
                </span>
            </div>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:gap-8">
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="hoverIntent" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Hover intent (150 ms)</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="safeTriangle" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Safe triangle</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    <input v-model="showTriangle" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Show triangle</span>
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
                        open after <span class="text-emerald-600 dark:text-emerald-400">{{ hoverIntent ? '150 ms' : '0 ms' }}</span> · gap
                        <span class="text-emerald-600 dark:text-emerald-400">{{ safeTriangle ? 'bridged' : 'closes the menu' }}</span>
                    </span>
                    <span class="text-neutral-500">Hover a menu, then move diagonally into the panel.</span>
                </div>
            </div>
        </div>
    </div>
</template>
