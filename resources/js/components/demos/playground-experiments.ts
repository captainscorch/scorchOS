import { demoRegistry } from './registry';

export interface PlaygroundExperimentConfig {
    componentName: string;
    id: string;
    title: string;
    description: string;
    category: string;
    tags: string[];
    code: string;
    language: string;
}

/**
 * Playground experiment metadata. Component is resolved from the auto-discovered demo registry.
 * Add new entry here when adding a demo to the Playground.
 */
const CONFIG: PlaygroundExperimentConfig[] = [
    {
        componentName: 'ConcentricDemo',
        id: 'concentric-radius',
        title: 'Concentric Border Radius',
        description: 'Maintaining geometric continuity in nested corners through mathematical offsets.',
        category: 'UI Logic',
        tags: ['CSS', 'Geometry'],
        code: `.card {
  --inner-radius: 12px;
  --gap: 16px;

  padding: var(--gap);
  border-radius: calc(var(--inner-radius) + var(--gap));
}

.card-content {
  border-radius: var(--inner-radius);
}`,
        language: 'css',
    },
    {
        componentName: 'TabularNumbersDemo',
        id: 'tabular-numbers',
        title: 'Tabular Numbers',
        description: 'Wherever digits line up or change under the eye, one declaration gives every figure the same width and stops the jitter.',
        category: 'Typography',
        tags: ['CSS', 'OpenType'],
        code: `/* Timers, stats, prices, table columns: anything that ticks or aligns. */
.stopwatch,
.ledger td,
.stat-value {
    font-variant-numeric: tabular-nums;
}

/* Tailwind: the same thing as a utility, <span class="tabular-nums">. */

/* Proportional figures are the default and look better in running text,
   so scope tabular figures to the places where numbers move or stack. */`,
        language: 'css',
    },
    {
        componentName: 'AsciiObjectDemo',
        id: 'ascii-object',
        title: 'ASCII Object',
        description:
            'A three.js scene rendered as characters that are matched by shape and edge, not just brightness, with your own logo, image or GLB dropped in.',
        category: 'Canvas',
        tags: ['three.js', 'WebGL', 'Canvas'],
        code: `<script setup lang="ts">
import AsciiObject from '@/components/canvasui/AsciiObject.vue';
import { ref } from 'vue';

// Any GLB, glTF, SVG, PNG, JPEG, WebP or GIF. The format is sniffed
// from the bytes, so object URLs from a file input work too.
const src = ref('/img/captainscorch_logo.svg');

function onFile(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) src.value = URL.createObjectURL(file);
}
</script>

<template>
    <AsciiObject
        :src="src"
        :cell-size="8"
        :colored="false"
        color="#ffffff"
        :edge-contrast="2.4"
        :orbit="true"
        :float-intensity="1.2"
        class="h-[400px] w-full"
    />
    <input type="file" accept=".glb,.svg,.png,.webp" @change="onFile" />
</template>`,
        language: 'html',
    },
    {
        componentName: 'CommandMenuEtiquetteDemo',
        id: 'command-menu-etiquette',
        title: 'Command Menu Etiquette',
        description:
            'Three rules a command menu needs: a resting cursor never steals the highlight, rows commit on click instead of press, and key hints vanish on touch.',
        category: 'UI Logic',
        tags: ['Pointer', 'Keyboard', 'A11y'],
        code: `// A resting cursor must not pick the row that appears under it.
const pointerMoved = ref(false);

list.addEventListener('pointermove', () => (pointerMoved.value = true));

function onRowEnter(index: number) {
    if (!pointerMoved.value) return;
    activeIndex.value = index;
}

// Commit on click, never on pointerdown: press can still become a scroll or a drag.
row.addEventListener('click', () => select(item));

// Hints describe keys a touch device does not have:
// hide them with @media (hover: none) { .command-hints { display: none } }.

// cmdk and reka-ui ship both rules; the demo fakes the bad versions to show the difference.`,
        language: 'typescript',
    },
    {
        componentName: 'OptimisticUndoDemo',
        id: 'optimistic-undo',
        title: 'Optimistic Update + Undo',
        description:
            'Show the result before the server answers, offer Undo in a toast instead of a confirm dialog, and roll back only when the request actually fails.',
        category: 'UI Logic',
        tags: ['State', 'Toast', 'Latency'],
        code: `async function changeStatus(next: Status) {
    const previous = status.value;

    // Result first, server second.
    status.value = next;

    try {
        await api.updateStatus(issue.id, next);
        toast(\`Moved to \${next}\`, {
            action: 'Undo',
            onAction: () => changeStatus(previous),
        });
    } catch {
        // The rare failure is the only moment the user sees a rollback.
        status.value = previous;
        toast("Couldn't update status", {
            action: 'Retry',
            onAction: () => changeStatus(next),
        });
    }
}`,
        language: 'typescript',
    },
    {
        componentName: 'HoverIntentDemo',
        id: 'hover-intent',
        title: 'Hover Intent + Safe Triangle',
        description:
            'Menus open only after the cursor rests for a moment, and stay open while it travels diagonally across the gap toward the panel.',
        category: 'UI Logic',
        tags: ['Pointer', 'Menus', 'Geometry'],
        code: `// Open only after the cursor rests: a pass-through should not flip menus.
trigger.addEventListener('mouseenter', () => {
    intentTimer = setTimeout(open, 150);
});

trigger.addEventListener('mouseleave', (event) => {
    clearTimeout(intentTimer);
    if (!isOpen) return;

    // Safe zone: a triangle from the exit point to the panel's top corners,
    // widened a little, plus the panel itself.
    const rect = panel.getBoundingClientRect();
    triangle = [
        { x: event.clientX, y: event.clientY },
        { x: rect.left - 24, y: rect.top },
        { x: rect.right + 24, y: rect.top },
    ];
    stallTimer = setTimeout(close, 600); // stalled in the gap
});

// Progress inside the zone keeps the menu alive; straying out of it closes after a short grace.
document.addEventListener('pointermove', (event) => {
    if (!triangle) return;
    if (insideTriangle(event, triangle) || insideRect(event, panel)) {
        clearTimeout(stallTimer);
        clearTimeout(strayTimer);
        stallTimer = setTimeout(close, 600);
    } else {
        strayTimer ??= setTimeout(close, 250);
    }
});

// Leaving the panel itself gets the same grace, so a slip past its edge does not slam it shut.
panel.addEventListener('mouseleave', () => (leaveTimer = setTimeout(close, 200)));
panel.addEventListener('mouseenter', () => clearTimeout(leaveTimer));`,
        language: 'typescript',
    },
    {
        componentName: 'InlineEditDemo',
        id: 'inline-edit',
        title: 'Inline Edit',
        description: 'The cell becomes the input in place, so editing never pulls the eye away from the row.',
        category: 'UI Logic',
        tags: ['Tables', 'Keyboard'],
        code: `<!-- Text and input share one box: same height, same padding, no layout shift. -->
<td class="h-10 px-1">
    <input
        v-if="editing"
        v-model="draft"
        class="h-8 w-full rounded-md px-2"
        @keydown.enter.prevent="commit"
        @keydown.esc.prevent="cancel"
        @keydown.tab="moveToNextCell"
        @blur="commit"
    />
    <button v-else class="h-8 w-full px-2 text-left" @click="open">
        {{ value }}
    </button>
</td>

<!-- On open: focus and select, so the first keystroke replaces the value. -->
input.focus();
input.select();`,
        language: 'html',
    },
    {
        componentName: 'LoadingThresholdsDemo',
        id: 'loading-thresholds',
        title: 'Loading Thresholds',
        description: 'Show a loader only after 200 ms and keep it for at least 300 ms, so fast responses never flash and slow ones never blink.',
        category: 'UI Logic',
        tags: ['Loading', 'Perceived Performance'],
        code: `const DELAY_MS = 200;
const MIN_VISIBLE_MS = 300;

async function load(fetcher: () => Promise<void>) {
    let shownAt: number | null = null;

    // Nothing for the first 200 ms: a fast response never shows a loader.
    const showTimer = setTimeout(() => {
        skeleton.value = true;
        shownAt = performance.now();
    }, DELAY_MS);

    await fetcher();
    clearTimeout(showTimer);

    // Once visible, hold the skeleton so it never blinks.
    if (shownAt !== null) {
        const elapsed = performance.now() - shownAt;
        await new Promise((r) => setTimeout(r, Math.max(0, MIN_VISIBLE_MS - elapsed)));
    }
    skeleton.value = false;
}`,
        language: 'typescript',
    },
    {
        componentName: 'HoverLiftDemo',
        id: 'hover-lift',
        title: 'Hover Lift Without Flicker',
        description: 'The link keeps the hit area, an inner layer carries the lift, so the card never moves out from under the pointer.',
        category: 'UI Logic',
        tags: ['CSS', 'Hover'],
        code: `/* Broken: the element that owns :hover moves, slides out from under
   the pointer at its bottom edge and retriggers itself in a loop. */
a.card:hover {
    transform: translateY(-6px);
}

/* Fixed: the link keeps its geometry, only the inner layer moves. */
a.card > .surface {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

a.card:hover > .surface {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px -8px rgb(0 0 0 / 0.25);
}`,
        language: 'css',
    },
    {
        componentName: 'StickToBottomDemo',
        id: 'stick-to-bottom',
        title: 'Stick to Bottom',
        description: 'A streaming reply follows the reader only while they are at the end, and a pill offers the way back once they scroll up.',
        category: 'Agent Chat',
        tags: ['Scroll', 'Streaming', 'UX'],
        code: `const THRESHOLD = 24;
let anchored = true;

// The reader decides: at the end we follow, anywhere above we release.
pane.addEventListener('scroll', () => {
    const distance = pane.scrollHeight - pane.scrollTop - pane.clientHeight;
    anchored = distance <= THRESHOLD;
    if (anchored) hidePill();
});

// Measured right before each token lands: scrollTop is already true even if
// the scroll event has not fired yet, so a wheel tick never loses the race.
function beforeToken() {
    if (jumping) return;
    anchored = pane.scrollHeight - pane.scrollTop - pane.clientHeight <= THRESHOLD;
}

function afterToken() {
    if (anchored) {
        pane.scrollTo({ top: pane.scrollHeight }); // instant while streaming
    } else {
        showPill(); // "↓ New reply": click scrolls smoothly, sets jumping, re-anchors
    }
}`,
        language: 'typescript',
    },
    {
        componentName: 'ToolCallCardsDemo',
        id: 'tool-call-cards',
        title: 'Tool Calls as Status Cards',
        description:
            'Agent actions render inline with a status, reads run on their own, writes wait for a human, and the reply only continues once every tool has settled.',
        category: 'Agent Chat',
        tags: ['Agents', 'Human in the Loop', 'UX'],
        code: `type Status = 'pending' | 'waiting' | 'running' | 'done' | 'failed' | 'rejected';

interface ToolCall {
    label: string;
    write: boolean; // writes need approval, reads never do
    status: Status;
}

async function handle(tool: ToolCall) {
    if (tool.write && requireApproval) {
        tool.status = 'waiting';
        return; // the card shows Run / Reject and the reply pauses here
    }
    tool.status = 'running';
    try {
        await run(tool);
        tool.status = 'done';
    } catch {
        tool.status = 'failed'; // card offers Retry, nothing changed
    }
}`,
        language: 'typescript',
    },
    {
        componentName: 'ComposerRulesDemo',
        id: 'composer-rules',
        title: 'Composer Rules',
        description: 'The field grows with the text, Enter sends, Shift+Enter breaks the line, and Send turns into Stop while the model is talking.',
        category: 'Agent Chat',
        tags: ['Forms', 'Keyboard', 'UX'],
        code: `function onKeydown(event: KeyboardEvent) {
    if (event.key === 'Escape' && generating) return stop();
    if (event.key !== 'Enter') return;
    // Shift keeps the newline, IME composition must never send half a word.
    if (event.shiftKey || event.isComposing) return;
    event.preventDefault();
    send();
}

// Collapse, measure, clamp: a textarea does not know its content height until asked.
function resize(el: HTMLTextAreaElement, maxRows = 6) {
    el.style.height = 'auto';
    const line = parseFloat(getComputedStyle(el).lineHeight);
    const padding = parseFloat(getComputedStyle(el).paddingTop) + parseFloat(getComputedStyle(el).paddingBottom);
    const max = line * maxRows + padding;
    el.style.height = \`\${Math.min(el.scrollHeight, max)}px\`;
    el.style.overflowY = el.scrollHeight > max ? 'auto' : 'hidden';
}`,
        language: 'typescript',
    },
];

export interface PlaygroundExperiment extends PlaygroundExperimentConfig {
    component: (typeof demoRegistry)[string];
}

export const playgroundExperiments: PlaygroundExperiment[] = CONFIG.map((entry) => ({
    ...entry,
    component: demoRegistry[entry.componentName],
})).filter((exp): exp is PlaygroundExperiment => !!exp.component);
