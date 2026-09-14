<script setup lang="ts">
import DecryptReveal, { supportsHtmlInCanvas } from '@/components/canvasui/DecryptReveal.vue';
import Peel from '@/components/canvasui/Peel.vue';
import FooterBottomBar from '@/components/FooterBottomBar.vue';
import BodyText from '@/components/global/typography/BodyText.vue';
import { useSocials } from '@/composables/useSocials';
import { setLocale } from '@/i18n';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faClockEight, faLocationArrow } from '@fortawesome/sharp-light-svg-icons';
import { faMoon, faSunBright } from '@fortawesome/sharp-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useDark, useIntersectionObserver, useToggle } from '@vueuse/core';
import { computed, defineAsyncComponent, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
library.add(faLocationArrow, faClockEight, faMoon, faSunBright);

// Pulls three.js with it, so it stays a separate chunk until someone opens the mark
const AsciiMark = defineAsyncComponent(() => import('@/components/AsciiMark.vue'));

const { t } = useI18n();
const socials = useSocials();

defineProps<{
    isLandingPage?: boolean;
}>();

const year = new Date().getFullYear();
const lastCommitDate = ref<string>('');
const totalCommitCount = ref<number | null>(null);

const formatCommitDate = (dateString: string): string => {
    const date = new Date(dateString);
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};

const isDark = useDark({
    selector: 'html',
    attribute: 'class',
    valueDark: 'dark',
    valueLight: '',
    initialValue: 'dark',
});
const toggleTheme = useToggle(isDark);

// The cipher needs the colour actually painted behind the notes card to tell content from empty space
const notesBackground = computed(() => (isDark.value ? '#000a08' : '#fafffd'));

// Falls back to the plain card wherever the browser cannot rasterize HTML into a canvas.
// The reveal follows the cursor, so it is pointless without one.
const canDecrypt = ref(false);

const markColor = computed(() => (isDark.value ? '#2bb193' : '#19735e'));

// The mark stays folded away until someone goes looking for it
const markPanelRef = ref<HTMLElement | null>(null);
const markShellRef = ref<HTMLElement | null>(null);
const peelRef = ref<HTMLElement | null>(null);
const notesRef = ref<HTMLElement | null>(null);
const notesHeight = ref<number | null>(null);
let notesObserver: ResizeObserver | null = null;
const sheetRef = ref<HTMLElement | null>(null);
const sheetHeight = ref<number | null>(null);
let sheetObserver: ResizeObserver | null = null;

// Same trick for the notes card, whose padding now lives inside the captured content
watch(notesRef, (element) => {
    notesObserver?.disconnect();
    notesObserver = null;
    if (!element) {
        return;
    }
    notesObserver = new ResizeObserver(([entry]) => {
        const height = Math.ceil(entry.borderBoxSize?.[0]?.blockSize ?? (entry.target as HTMLElement).offsetHeight);
        if (height > 0) {
            notesHeight.value = height;
        }
    });
    notesObserver.observe(element);
});

// Peel swaps its content element when it switches into canvas mode, so follow the ref
watch(sheetRef, (element) => {
    sheetObserver?.disconnect();
    sheetObserver = null;
    if (!element) {
        return;
    }
    sheetObserver = new ResizeObserver(([entry]) => {
        // contentRect excludes padding, and the sheet's padding is what gives the mark its room
        const height = Math.ceil(entry.borderBoxSize?.[0]?.blockSize ?? (entry.target as HTMLElement).offsetHeight);
        if (height > 0) {
            sheetHeight.value = height;
        }
    });
    sheetObserver.observe(element);
});
const isMarkOpen = ref(false);
const isMarkLoaded = ref(false);
const canPeel = ref(false);

const supportsWebgl2 = (): boolean => {
    try {
        return Boolean(document.createElement('canvas').getContext('webgl2'));
    } catch {
        return false;
    }
};

const syncScrollbarWidth = () => {
    const width = window.innerWidth - document.documentElement.clientWidth;
    document.documentElement.style.setProperty('--sbw', `${Math.max(width, 0)}px`);
};

// The peel sheet paints the page gradient at the document's own scale, so it needs the
// document height the gradient is sized to
const syncPageHeight = () => {
    const height = document.documentElement.getBoundingClientRect().height;
    document.documentElement.style.setProperty('--page-height', `${Math.round(height)}px`);
};

let pageObserver: ResizeObserver | null = null;

const preloadMark = () => {
    isMarkLoaded.value = true;
};

// Resolves once the panel has finished growing, or right away when transitions are off
const panelSettled = (panel: HTMLElement): Promise<void> =>
    new Promise((resolve) => {
        let done = false;
        const finish = () => {
            if (done) {
                return;
            }
            done = true;
            panel.removeEventListener('transitionend', finish);
            resolve();
        };
        panel.addEventListener('transitionend', finish);
        setTimeout(finish, 800);
    });

const { stop: stopPeelObserver } = useIntersectionObserver(
    peelRef,
    ([entry]) => {
        if (entry?.isIntersecting) {
            preloadMark();
            stopPeelObserver();
        }
    },
    { rootMargin: '300px' },
);

const toggleMark = async () => {
    isMarkOpen.value = !isMarkOpen.value;
    if (!isMarkOpen.value) {
        return;
    }
    preloadMark();
    await nextTick();
    const panel = markPanelRef.value;
    if (!panel) {
        return;
    }
    if (markShellRef.value) {
        await panelSettled(markShellRef.value);
    }
    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

const emit = defineEmits<{
    languageSwitched: [];
}>();

const switchLanguage = (lang: string) => {
    setLocale(lang);
    setTimeout(() => {
        emit('languageSwitched');
    }, 100);
};

// Clock functionality
const currentTime = ref('');
const timezone = ref('');
let clockInterval: ReturnType<typeof setInterval> | null = null;

const updateClock = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString('en-US', {
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
    timezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    calculateTimeDifference();
};

const timeDifference = ref('');

const calculateTimeDifference = () => {
    const now = new Date();
    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    const targetTimezone = 'Europe/Berlin';

    const userDate = new Date(now.toLocaleString('en-US', { timeZone: userTimezone }));
    const targetDate = new Date(now.toLocaleString('en-US', { timeZone: targetTimezone }));

    const diffInHours = (targetDate.getTime() - userDate.getTime()) / (1000 * 60 * 60);
    const roundedDiff = Math.round(diffInHours);

    if (roundedDiff === 0) {
        timeDifference.value = t('home.clock.same');
    } else if (roundedDiff > 0) {
        timeDifference.value = t('home.clock.ahead', { hours: roundedDiff });
    } else {
        timeDifference.value = t('home.clock.behind', { hours: Math.abs(roundedDiff) });
    }
};

onMounted(async () => {
    syncScrollbarWidth();
    syncPageHeight();
    window.addEventListener('resize', syncScrollbarWidth, { passive: true });
    pageObserver = new ResizeObserver(syncPageHeight);
    pageObserver.observe(document.documentElement);

    // Both effects need the canvas to actually render; without WebGL2 the components
    // fall back to plain DOM and would drop their revealed layer, so hand over to the arrow.
    const hasPointer = window.matchMedia('(pointer: fine)').matches;
    const canRenderCanvas = supportsHtmlInCanvas() && supportsWebgl2();
    canPeel.value = canRenderCanvas && hasPointer;
    canDecrypt.value = canRenderCanvas && hasPointer;
    updateClock();
    clockInterval = setInterval(updateClock, 1000);

    try {
        const response = await fetch('https://api.github.com/repos/captainscorch/scorchOS/commits?per_page=1');
        if (!response.ok) throw new Error('Failed to fetch commits');

        const linkHeader = response.headers.get('Link');
        if (linkHeader) {
            const lastBlock = linkHeader.match(/<[^>]+>;\s*rel="last"/);
            if (lastBlock) {
                const pageMatch = lastBlock[0].match(/[?&]page=(\d+)/);
                if (pageMatch) {
                    totalCommitCount.value = parseInt(pageMatch[1], 10);
                }
            }
        }

        const commits = await response.json();
        if (commits && commits.length > 0 && commits[0].commit?.author?.date) {
            lastCommitDate.value = formatCommitDate(commits[0].commit.author.date);
        }
    } catch (error) {
        console.error('Error fetching latest commit date:', error);
        // Fallback to current date if API fails
        lastCommitDate.value = formatCommitDate(new Date().toISOString());
    }
});

onUnmounted(() => {
    notesObserver?.disconnect();
    sheetObserver?.disconnect();
    pageObserver?.disconnect();
    window.removeEventListener('resize', syncScrollbarWidth);
    if (clockInterval) {
        clearInterval(clockInterval);
    }
});
</script>

<template>
    <footer class="mx-auto mt-16 w-full max-w-7xl pt-16 text-neutral-900 md:pt-16 dark:text-white">
        <div class="mx-auto">
            <h2 class="scramble-trigger relative mb-12 w-fit font-sans text-3xl font-medium text-neutral-900 md:text-4xl dark:text-white">
                {{ t('footer.contact') }}
            </h2>

            <div class="flex flex-col gap-8 lg:flex-row lg:gap-16">
                <div
                    class="flex min-h-[320px] flex-1 flex-col overflow-hidden rounded-3xl border border-brand-200/40 bg-brand-50/10 dark:border-brand-800/60 dark:bg-brand-950/50"
                >
                    <component
                        :is="canDecrypt ? DecryptReveal : 'div'"
                        v-bind="
                            canDecrypt
                                ? {
                                      radius: 400,
                                      softness: 0.45,
                                      cell: 9,
                                      scramble: 0.08,
                                      edgeGlow: 1.6,
                                      aberration: 6,
                                      // Monochrome: letting glyphs inherit the UI colour muddies
                                      // them, because legibility amplifies the card's own tint
                                      colored: 0,
                                      legibility: 0.45,
                                      brightness: 1.15,
                                      passthrough: 0.04,
                                      color: '#2bb193',
                                      background: notesBackground,
                                  }
                                : {}
                        "
                        class="flex flex-1 flex-col"
                        :style="canDecrypt && notesHeight ? { minHeight: `${notesHeight}px` } : undefined"
                    >
                        <!-- The cipher only hides what it covers, so the captured content must be opaque -->
                        <div
                            ref="notesRef"
                            class="flex flex-col justify-between p-6 md:p-12"
                            :style="canDecrypt ? { backgroundColor: notesBackground } : undefined"
                        >
                            <div>
                                <h3 class="mb-6 text-base font-medium text-neutral-900 dark:text-white">{{ t('footer.notes') }}</h3>
                                <p class="mb-4 max-w-lg text-sm leading-relaxed text-neutral-600 dark:text-neutral-400">
                                    {{ t('footer.description') }}
                                    <a
                                        class="border-b border-b-neutral-300/50 leading-[135%] text-neutral-900 transition-all group-hover:opacity-50 hover:cursor-ne-resize hover:border-b-brand-400 hover:opacity-100! dark:border-b-neutral-600/50 dark:text-white dark:hover:border-b-brand-400"
                                        href="https://github.com/captainscorch/scorchOS/commits/main/"
                                        >{{ lastCommitDate || t('footer.loading') }}</a
                                    >
                                    <span v-if="totalCommitCount !== null" class="text-neutral-500 dark:text-neutral-400">
                                        ({{ totalCommitCount.toLocaleString() }} {{ t('footer.commits') }})</span
                                    >
                                </p>
                                <div class="flex flex-col items-start gap-x-6 gap-y-2 md:flex-row">
                                    <span class="min-w-fit [@media(max-height:680px)]:min-w-fit"
                                        ><FontAwesomeIcon icon="fa-sharp fa-light fa-location-arrow" class="mr-2" /><BodyText size="sm" as="span">{{
                                            t('home.about.lastSeen')
                                        }}</BodyText></span
                                    >
                                    <span class="min-w-fit [@media(max-height:680px)]:min-w-fit"
                                        ><FontAwesomeIcon icon="fa-sharp fa-light fa-clock-eight" class="mr-2" /><BodyText size="sm" as="span"
                                            >{{ currentTime }} {{ timezone }} <span class="opacity-50">({{ timeDifference }})</span></BodyText
                                        ></span
                                    >
                                </div>
                            </div>
                            <div class="mt-10 text-sm text-neutral-500 dark:text-neutral-500">
                                <span class="italic">{{ t('footer.quote') }}</span>
                                <span class="mt-2 block text-xs text-neutral-400 dark:text-neutral-600">{{ t('footer.quoteAttribution') }}</span>
                            </div>
                        </div>
                    </component>
                </div>

                <div class="p-8 md:p-12 lg:w-1/3 lg:px-0">
                    <h3 class="text-center text-base font-medium text-neutral-900 md:text-left dark:text-white">
                        {{ t('footer.socials') }}
                    </h3>
                    <div
                        class="group mx-auto flex w-full max-w-[200px] flex-row flex-wrap justify-center gap-x-8 gap-y-4 py-6 md:mx-0 md:max-w-none md:justify-between"
                    >
                        <a
                            v-for="social in socials"
                            :key="social.label"
                            :href="social.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="border-b border-b-teal-black/0 leading-[135%] text-neutral-900 transition-all group-hover:opacity-50 hover:cursor-ne-resize hover:border-b-brand-400 hover:opacity-100! dark:text-neutral-400 dark:hover:text-white"
                            v-html="social.label"
                        ></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Only the page floor peels; the notes card stays out of the capture so its cipher survives -->
        <div v-if="canPeel" ref="peelRef" class="peel-bleed">
            <Peel
                side="bottom"
                mode="cursor"
                :reveal="240"
                :zone="196"
                :curl="320"
                :bow="30"
                :shade="0.12"
                :shine="2"
                :shine-distance="1200"
                :bulge="5"
                :perspective="4000"
                class="w-full"
                :style="sheetHeight ? { height: `${sheetHeight}px` } : undefined"
            >
                <div class="peel-sheet h-full w-full bg-white dark:bg-black">
                    <div ref="sheetRef" class="mx-auto w-full max-w-7xl px-6 pt-10 pb-32 lg:px-8" :class="{ 'pb-28': isLandingPage }">
                        <FooterBottomBar :is-dark="isDark" :year="year" @switch-language="switchLanguage" @toggle-theme="toggleTheme" />
                    </div>
                </div>
                <template #under>
                    <div class="flex h-full w-full items-end justify-center pb-1">
                        <div class="h-40 w-full max-w-[280px]">
                            <AsciiMark v-if="isMarkLoaded" :color="markColor" />
                        </div>
                    </div>
                </template>
            </Peel>
        </div>

        <div v-else class="mx-auto md:pb-16">
            <FooterBottomBar class="mb-0!" :is-dark="isDark" :year="year" @switch-language="switchLanguage" @toggle-theme="toggleTheme" />

            <!-- Everywhere the peel cannot run, the arrow is how the mark gets found -->
            <!-- The floating page nav is fixed 24px off the bottom, so the arrow has to clear
                 it; subpages already carry 80px of their own padding below the footer -->
            <div class="flex flex-col items-center pt-16 md:pt-20 md:pb-16" :class="isLandingPage ? 'pb-32' : 'pb-12'">
                <button
                    type="button"
                    class="mark-toggle group/mark relative flex size-11 cursor-pointer items-center justify-center rounded-full border border-neutral-200/80 text-neutral-400 transition-colors duration-300 hover:border-brand-400/60 hover:text-brand-400 dark:border-white/25 dark:text-neutral-400 dark:hover:border-brand-400/60 dark:hover:text-brand-400"
                    :class="{ 'is-open': isMarkOpen }"
                    :aria-expanded="isMarkOpen"
                    aria-controls="footer-mark"
                    :aria-label="t(isMarkOpen ? 'footer.markHide' : 'footer.markShow')"
                    @pointerenter="preloadMark"
                    @focus="preloadMark"
                    @click="toggleMark"
                >
                    <span class="mark-sonar pointer-events-none absolute inset-0 rounded-full border border-brand-400/40" aria-hidden="true" />
                    <svg class="mark-chevron size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                        <path d="M3 6l5 5 5-5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div
                    id="footer-mark"
                    ref="markShellRef"
                    class="grid w-full transition-[grid-template-rows] duration-700 ease-out motion-reduce:transition-none"
                    :class="isMarkOpen ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
                >
                    <div class="overflow-hidden">
                        <div ref="markPanelRef" class="flex justify-center pt-20">
                            <div class="h-40 w-full max-w-[280px]">
                                <AsciiMark v-if="isMarkLoaded" :color="markColor" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
/* Break out of the footer's max-width so the sheet spans the viewport.
   The footer clips on x, so the vw margins cannot produce a scrollbar. */
.peel-bleed {
    width: calc(100vw - var(--sbw, 0px));
    margin-left: calc(50% - (100vw - var(--sbw, 0px)) / 2);
}

.mark-chevron {
    animation: mark-nudge 2.4s ease-in-out infinite;
}

.mark-toggle:hover .mark-chevron {
    animation-duration: 1.1s;
}

.mark-toggle.is-open .mark-chevron {
    rotate: 180deg;
    animation: none;
}

.mark-sonar {
    animation: mark-sonar 2.4s ease-out infinite;
}

.mark-toggle.is-open .mark-sonar {
    animation: none;
    opacity: 0;
}

@keyframes mark-nudge {
    0%,
    100% {
        transform: translateY(-1px);
    }
    50% {
        transform: translateY(2px);
    }
}

@keyframes mark-sonar {
    0% {
        opacity: 0.5;
        transform: scale(1);
    }
    70%,
    100% {
        opacity: 0;
        transform: scale(1.6);
    }
}

@media (prefers-reduced-motion: reduce) {
    .mark-chevron,
    .mark-sonar {
        animation: none;
    }

    .mark-sonar {
        opacity: 0;
    }
}
</style>
