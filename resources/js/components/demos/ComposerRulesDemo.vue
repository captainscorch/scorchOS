<script setup lang="ts">
import { ArrowUp, Square } from 'lucide-vue-next';
import { nextTick, onMounted, onUnmounted, ref } from 'vue';

interface Message {
    role: 'user' | 'assistant';
    text: string;
    stopped?: boolean;
}

const REPLY =
    'Good call. I moved the bench press to four sets of six and kept the weight, so the first session will feel about the same and the last set is where the work happens.';

const autoGrow = ref(true);
const enterSends = ref(true);
const stopReplacesSend = ref(true);
const speed = ref(40);

const draft = ref('');
const messages = ref<Message[]>([]);
const generating = ref(false);
const field = ref<HTMLTextAreaElement | null>(null);
const pane = ref<HTMLElement | null>(null);

const MAX_ROWS = 6;
let timer: ReturnType<typeof setTimeout> | null = null;

// The textarea has no idea how tall its content is until we ask it: collapse, read, then clamp to six rows.
function resize() {
    const el = field.value;
    if (!el) {
        return;
    }
    if (!autoGrow.value) {
        // The old way: one fixed box, the text scrolls inside it no matter how much or little there is.
        el.style.height = '9.5rem';
        el.style.overflowY = 'auto';
        return;
    }
    el.style.height = 'auto';
    const style = getComputedStyle(el);
    const lineHeight = parseFloat(style.lineHeight) || 20;
    const padding = parseFloat(style.paddingTop) + parseFloat(style.paddingBottom);
    const max = lineHeight * MAX_ROWS + padding;
    el.style.height = `${Math.min(el.scrollHeight, max)}px`;
    el.style.overflowY = el.scrollHeight > max ? 'auto' : 'hidden';
}

function scrollPane() {
    nextTick(() => pane.value?.scrollTo({ top: pane.value.scrollHeight }));
}

function stop() {
    if (timer) {
        clearTimeout(timer);
        timer = null;
    }
    if (generating.value) {
        const last = messages.value[messages.value.length - 1];
        if (last?.role === 'assistant') {
            last.stopped = true;
        }
    }
    generating.value = false;
}

function send() {
    const text = draft.value.trim();
    if (!text || generating.value) {
        return;
    }
    messages.value.push({ role: 'user', text });
    draft.value = '';
    nextTick(resize);

    messages.value.push({ role: 'assistant', text: '' });
    // Take the reactive proxy back out of the array: mutating the raw object would not re-render.
    const reply = messages.value[messages.value.length - 1];
    generating.value = true;
    scrollPane();

    const tokens = REPLY.split(' ');
    let index = 0;
    const step = () => {
        if (index >= tokens.length) {
            generating.value = false;
            return;
        }
        reply.text += (index === 0 ? '' : ' ') + tokens[index];
        index += 1;
        scrollPane();
        timer = setTimeout(step, speed.value);
    };
    step();
}

function onKeydown(event: KeyboardEvent) {
    if (event.key === 'Escape' && stopReplacesSend.value && generating.value) {
        event.preventDefault();
        stop();
        return;
    }
    if (event.key !== 'Enter' || !enterSends.value) {
        return;
    }
    // Shift+Enter keeps the newline, bare Enter sends. IME composition must never send half a word;
    // Safari reports the committing Enter with keyCode 229 instead of isComposing.
    if (event.shiftKey || event.isComposing || event.keyCode === 229) {
        return;
    }
    event.preventDefault();
    send();
}

function onPrimary() {
    if (generating.value && stopReplacesSend.value) {
        stop();
        return;
    }
    send();
}

function reset() {
    stop();
    messages.value = [];
    draft.value = '';
    autoGrow.value = true;
    enterSends.value = true;
    stopReplacesSend.value = true;
    speed.value = 40;
    nextTick(resize);
}

onMounted(resize);

onUnmounted(() => {
    if (timer) {
        clearTimeout(timer);
    }
});
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <!-- Preview Area: the frame has a fixed height, the composer grows into the pane above it -->
        <div class="p-4 md:p-6">
            <div
                class="mx-auto flex h-[380px] w-full max-w-2xl flex-col rounded-xl border border-neutral-200 bg-white font-sans text-sm leading-relaxed dark:border-white/10 dark:bg-neutral-950"
            >
                <div ref="pane" class="chat-pane flex min-h-0 flex-1 flex-col gap-4 overflow-y-auto p-4">
                    <p v-if="!messages.length" class="m-auto text-xs text-neutral-400 dark:text-neutral-600">Type something below.</p>
                    <div
                        v-for="(message, index) in messages"
                        :key="index"
                        class="flex"
                        :class="message.role === 'user' ? 'justify-end' : 'justify-start'"
                    >
                        <div
                            v-if="message.role === 'user'"
                            class="max-w-[85%] rounded-2xl bg-neutral-200/70 px-4 py-2.5 whitespace-pre-wrap text-neutral-900 dark:bg-white/10 dark:text-white"
                        >
                            {{ message.text }}
                        </div>
                        <div v-else class="max-w-[65ch] text-neutral-800 dark:text-neutral-200">
                            {{ message.text }}<span v-if="generating && index === messages.length - 1" class="caret" aria-hidden="true" />
                            <span v-if="message.stopped" class="ml-1 font-mono text-[10px] tracking-wider text-neutral-400 uppercase">stopped</span>
                        </div>
                    </div>
                </div>

                <!-- Composer -->
                <div class="shrink-0 px-3 pb-3">
                    <div
                        class="relative rounded-[22px] border border-neutral-200 bg-neutral-50 transition-colors focus-within:border-neutral-400 dark:border-white/10 dark:bg-neutral-900 dark:focus-within:border-white/30"
                    >
                        <textarea
                            ref="field"
                            v-model="draft"
                            rows="1"
                            placeholder="Message the coach"
                            class="block w-full resize-none bg-transparent py-3 pr-12 pl-4.5 text-sm leading-5 text-neutral-900 outline-none placeholder:text-neutral-400 dark:text-white dark:placeholder:text-neutral-500"
                            @input="resize"
                            @keydown="onKeydown"
                        />
                        <button
                            type="button"
                            @click="onPrimary"
                            :disabled="generating ? !stopReplacesSend : !draft.trim()"
                            :aria-label="generating && stopReplacesSend ? 'Stop' : 'Send'"
                            class="absolute right-1.5 bottom-1.5 flex size-8 cursor-pointer items-center justify-center rounded-full transition-colors disabled:cursor-not-allowed disabled:opacity-30"
                            :class="
                                generating && stopReplacesSend
                                    ? 'bg-neutral-200 text-neutral-900 hover:bg-neutral-300 dark:bg-white/15 dark:text-white dark:hover:bg-white/25'
                                    : 'bg-neutral-900 text-white hover:bg-neutral-700 dark:bg-white dark:text-black dark:hover:bg-neutral-200'
                            "
                        >
                            <Square v-if="generating && stopReplacesSend" class="size-3 fill-current" />
                            <ArrowUp v-else class="size-4" />
                        </button>
                    </div>
                    <p class="mt-2 h-4 text-[11px] text-neutral-500">
                        <template v-if="generating && stopReplacesSend">Esc to stop</template>
                        <template v-else-if="enterSends">Enter to send · Shift+Enter for a new line</template>
                        <template v-else>Enter for a new line · click the arrow to send</template>
                    </p>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="border-t border-neutral-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-950">
            <div class="flex flex-col gap-6 md:flex-row md:flex-wrap md:items-end md:gap-x-6 md:gap-y-4">
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
                    <input v-model="autoGrow" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" @change="nextTick(resize)" />
                    <span>Auto-grow</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium whitespace-nowrap text-neutral-600 dark:text-neutral-400">
                    <input v-model="enterSends" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Enter sends</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-xs font-medium whitespace-nowrap text-neutral-600 dark:text-neutral-400">
                    <input v-model="stopReplacesSend" type="checkbox" class="size-4 accent-neutral-900 dark:accent-white" />
                    <span>Stop replaces Send</span>
                </label>

                <button
                    type="button"
                    @click="reset"
                    class="shrink-0 rounded-lg border border-neutral-200 px-4 py-2 text-xs font-medium text-neutral-600 transition-colors hover:bg-neutral-100 md:ml-auto dark:border-white/10 dark:text-neutral-400 dark:hover:bg-white/5"
                >
                    Reset
                </button>
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
