<script setup lang="ts">
import { ArrowDown } from 'lucide-vue-next';
import { nextTick, onUnmounted, ref } from 'vue';

interface Message {
    role: 'user' | 'assistant';
    text: string;
}

const REPLY =
    'Here is the plan for the next four weeks. Week one keeps the volume you already handle and adds one set to every compound lift. ' +
    'Week two raises the bench press to four sets of six at the same weight, and the rows follow the same pattern. ' +
    'Week three is the heavy week: squats and deadlifts move up by five kilos, everything else stays put so you can recover. ' +
    'Week four is a deload at seventy percent of the working weights, fewer sets, same movements, so the pattern stays fresh. ' +
    'Sleep and food matter more than the exact numbers during that week. ' +
    'After the deload we test the bench and the squat for a new five-rep max and build the next block from those numbers. ' +
    'If a session feels off, drop the last set instead of dropping the whole day, and log how it felt so the next plan can react to it.';

const SEED: Message[] = [
    { role: 'user', text: 'Can you put together a four-week plan from my last block?' },
    {
        role: 'assistant',
        text:
            "Sure. I'll keep the same four lifts and progress the volume first, then the weight. " +
            'Your last block ended with bench press at three sets of eight, rows and squats at the same volume, and deadlifts once a week. ' +
            'The new block starts from those numbers, so nothing jumps on day one, and the first week doubles as a check that the weights still match.',
    },
    { role: 'user', text: 'Keep the deadlift once a week?' },
    {
        role: 'assistant',
        text: 'Yes, once a week on the heavy day. The extra volume goes to the squat instead, and the rows move to the lighter day so your lower back gets a full rest between pulls. Keep the belt for the top sets only.',
    },
];

// Each Send asks for the plan again, so the pane keeps growing and there is always something above to read.
const PROMPTS = ['Go ahead, write out the full plan.', 'Post the full plan again, please.', 'One more time, I lost my place.'];

const stickToBottom = ref(true);
const speed = ref(40);
const streaming = ref(false);
const messages = ref<Message[]>([...SEED]);
const pane = ref<HTMLElement | null>(null);

// Whether the reader was at the end before the latest token arrived. Only then is it safe to follow.
const anchored = ref(true);
const showPill = ref(false);
// A smooth scroll fires scroll events on the way down; those must not read as the user scrolling up.
let jumping = false;

let timer: ReturnType<typeof setTimeout> | null = null;

const THRESHOLD = 24;

function distanceFromBottom(): number {
    const el = pane.value;
    if (!el) {
        return 0;
    }
    return el.scrollHeight - el.scrollTop - el.clientHeight;
}

function onScroll() {
    const atEnd = distanceFromBottom() <= THRESHOLD;
    if (jumping) {
        if (atEnd) {
            jumping = false;
        }
        return;
    }
    // The reader decides where the anchor is: at the end it follows, anywhere above it releases.
    anchored.value = atEnd;
    if (atEnd) {
        showPill.value = false;
    }
}

function scrollToEnd(smooth = false) {
    const el = pane.value;
    if (!el) {
        return;
    }
    el.scrollTo({ top: el.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
}

function afterToken() {
    if (!stickToBottom.value) {
        // The bad way: every token yanks the pane down, no matter where the reader is.
        scrollToEnd();
        return;
    }
    if (anchored.value) {
        scrollToEnd();
    } else {
        showPill.value = true;
    }
}

function jumpToReply() {
    anchored.value = true;
    showPill.value = false;
    jumping = true;
    scrollToEnd(true);
}

function stopStream() {
    if (timer) {
        clearTimeout(timer);
        timer = null;
    }
    streaming.value = false;
}

function send() {
    if (streaming.value) {
        return;
    }
    // Count only the turns added after the seed, so the first Send always asks for the plan.
    const turn = (messages.value.length - SEED.length) / 2;
    messages.value.push({ role: 'user', text: PROMPTS[turn % PROMPTS.length] });
    messages.value.push({ role: 'assistant', text: '' });
    // Take the reactive proxy back out of the array: mutating the raw object would not re-render.
    const reply = messages.value[messages.value.length - 1];
    streaming.value = true;
    anchored.value = true;
    showPill.value = false;
    jumping = false;
    nextTick(() => scrollToEnd());

    const tokens = REPLY.split(' ');
    let index = 0;
    const step = () => {
        if (index >= tokens.length) {
            stopStream();
            return;
        }
        // Measure before the token lands: the scroll event for a wheel tick may not have fired yet,
        // but scrollTop already tells the truth. A smooth jump back down counts as anchored.
        if (stickToBottom.value && !jumping) {
            anchored.value = distanceFromBottom() <= THRESHOLD;
        }
        reply.text += (index === 0 ? '' : ' ') + tokens[index];
        index += 1;
        nextTick(afterToken);
        timer = setTimeout(step, speed.value);
    };
    step();
}

function reset() {
    stopStream();
    messages.value = [...SEED];
    stickToBottom.value = true;
    speed.value = 40;
    anchored.value = true;
    showPill.value = false;
    jumping = false;
    nextTick(() => scrollToEnd());
}

onUnmounted(stopStream);
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area: fixed height so toggling and streaming never move the page -->
        <div class="p-4 md:p-6">
            <div class="relative mx-auto w-full max-w-2xl">
                <div
                    ref="pane"
                    class="chat-pane flex h-[380px] flex-col gap-4 overflow-y-auto rounded-xl border border-neutral-200 bg-white p-4 font-sans text-sm leading-relaxed dark:border-white/10 dark:bg-neutral-950"
                    @scroll.passive="onScroll"
                >
                    <div
                        v-for="(message, index) in messages"
                        :key="index"
                        class="flex"
                        :class="message.role === 'user' ? 'justify-end' : 'justify-start'"
                    >
                        <div
                            v-if="message.role === 'user'"
                            class="max-w-[85%] rounded-2xl bg-neutral-200/70 px-4 py-2.5 text-neutral-900 dark:bg-white/10 dark:text-white"
                        >
                            {{ message.text }}
                        </div>
                        <div v-else class="max-w-[65ch] text-neutral-800 dark:text-neutral-200">
                            {{ message.text }}<span v-if="streaming && index === messages.length - 1" class="caret" aria-hidden="true" />
                        </div>
                    </div>
                </div>

                <Transition
                    enter-active-class="transition duration-150 ease-out"
                    enter-from-class="opacity-0 translate-y-1"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 translate-y-1"
                >
                    <button
                        v-if="showPill"
                        type="button"
                        @click="jumpToReply"
                        class="absolute bottom-4 left-1/2 flex -translate-x-1/2 cursor-pointer items-center gap-1.5 rounded-full border border-neutral-200 bg-white py-1.5 pr-3 pl-2.5 font-sans text-xs font-medium text-neutral-900 shadow-lg transition-colors hover:bg-neutral-100 dark:border-white/10 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700"
                    >
                        <ArrowDown class="size-3.5" />
                        New reply
                    </button>
                </Transition>
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
                    <input v-model="stickToBottom" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Stick to bottom</span>
                </label>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="send"
                        :disabled="streaming"
                        class="shrink-0 rounded-lg bg-neutral-900 px-4 py-2 text-xs font-medium text-white transition-colors hover:bg-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-white dark:text-black dark:hover:bg-neutral-200"
                    >
                        Send
                    </button>
                    <button
                        type="button"
                        @click="reset"
                        class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <div class="mt-6 min-h-[4.75rem] rounded-lg bg-neutral-100 p-4 font-mono text-sm dark:bg-neutral-900">
                <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-400">
                    <span>
                        anchored =
                        <span :class="anchored ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'">{{
                            anchored
                        }}</span>
                    </span>
                    <!-- Both sentences share one grid cell and only one is visible, so the box keeps the height of the longer one. -->
                    <span class="grid text-neutral-500">
                        <span class="[grid-area:1/1]" :class="{ invisible: !stickToBottom }"
                            >Scroll up while it streams: the pane stays where you are and a pill offers the way back.</span
                        >
                        <span class="[grid-area:1/1]" :class="{ invisible: stickToBottom }"
                            >Every token forces the pane to the end. Try reading the first paragraph while it streams.</span
                        >
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.chat-pane {
    scrollbar-width: thin;
}

/* A block caret that blinks in steps, like a terminal, instead of fading. */
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
