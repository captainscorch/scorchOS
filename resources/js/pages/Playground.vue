<script setup lang="ts">
import CodeBlock from '@/components/CodeBlock.vue';
import CommandTrigger from '@/components/CommandTrigger.vue';
import FooterArea from '@/components/FooterArea.vue';
import ProgressiveBlur from '@/components/ProgressiveBlur.vue';
import TextReveal from '@/components/TextReveal.vue';
import LabelText from '@/components/global/typography/LabelText.vue';
import { useCommandMenu } from '@/composables/useCommandMenu';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faChevronDown, faCode, faFlask, faMicroscope } from '@fortawesome/sharp-light-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, Link } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import { playgroundExperiments } from '@/components/demos/playground-experiments';

library.add(faChevronDown, faFlask, faMicroscope, faCode);

const { t } = useI18n();
const { open: openCommandMenu } = useCommandMenu();

const pageTitle = 'Daniel Schmier - Playground';
const pageDescription = 'My lab for design engineering experiments and interactive demos.';
const ogTitle = 'Daniel Schmier – Playground';

const ogUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});

const experiments = playgroundExperiments;

const pageContainer = ref<HTMLElement | null>(null);

const viewByExpId = ref<Record<string, 'result' | 'code'>>({});

function getView(id: string): 'result' | 'code' {
    return viewByExpId.value[id] ?? 'result';
}

function setView(id: string, view: 'result' | 'code') {
    viewByExpId.value = { ...viewByExpId.value, [id]: view };
}

const demosDropdownOpen = ref(false);
const demosDropdownRef = ref<HTMLElement | null>(null);

onClickOutside(demosDropdownRef, () => {
    demosDropdownOpen.value = false;
});

function selectDemoAndClose(expId: string) {
    const el = document.getElementById(expId);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    demosDropdownOpen.value = false;
}
</script>

<template>
    <Head :title="pageTitle">
        <!-- Standard meta tags -->
        <meta name="description" :content="pageDescription" />

        <!-- Open Graph / Facebook (Meta) -->
        <meta property="og:url" :content="ogUrl" />
        <meta property="og:title" :content="ogTitle" />
        <meta property="og:description" :content="pageDescription" />

        <!-- Twitter (X) -->
        <meta property="twitter:url" :content="ogUrl" />
        <meta property="twitter:title" :content="ogTitle" />
        <meta property="twitter:description" :content="pageDescription" />
    </Head>
    <div ref="pageContainer" class="min-h-screen bg-white pt-32 pb-20 text-neutral-900 md:pt-40 md:pb-0 dark:bg-black dark:text-white">
        <!-- Header / Navigation -->
        <header class="pointer-events-none fixed top-0 right-0 left-0 z-100 mx-auto">
            <ProgressiveBlur class="absolute inset-0 -z-10 h-full w-full" />
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between p-6 lg:px-8">
                <!-- Name (Top Left) -->
                <div class="pointer-events-auto">
                    <Link href="/" class="scramble-trigger text-sm font-bold tracking-wider text-neutral-900 uppercase dark:text-white"
                        >Daniel Schmier</Link
                    >
                </div>

                <!-- Centered Toggle -->
                <div
                    class="pointer-events-auto fixed bottom-6 left-1/2 flex -translate-x-1/2 transform items-center gap-2 rounded-full border border-neutral-200 bg-white/80 p-1.5 shadow-2xl backdrop-blur-xs md:absolute md:bottom-auto dark:border-white/10 dark:bg-neutral-900/80"
                >
                    <Link href="/">
                        <button
                            class="group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium text-neutral-500 transition-all duration-300 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 640"
                                class="inline size-4 fill-neutral-500 transition-all duration-300 ease-out group-hover:-translate-x-1 group-hover:fill-neutral-900 dark:fill-white/50 dark:group-hover:fill-white"
                            >
                                <path
                                    d="M36.8 308.7L25.5 320L36.8 331.3L180.8 475.3L192.1 486.6L214.7 464L203.4 452.7L86.7 336L608.1 336L608.1 304L86.7 304L203.4 187.3L214.7 176L192.1 153.4L36.8 308.7z"
                                />
                            </svg>
                            <span> {{ t('playground.backToHome') }} </span>
                        </button>
                    </Link>
                </div>

                <!-- Command Menu (Top Right) -->
                <div class="pointer-events-auto flex items-center gap-4">
                    <CommandTrigger @click="openCommandMenu" />
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto mb-22 max-w-[calc(1280px-64px)] md:mb-32">
                <h2 class="mb-6 text-sm tracking-widest text-neutral-500 uppercase dark:text-white/30">
                    {{ t('playground.title') }}
                </h2>
                <h1
                    class="font-work-sans text-3xl font-bold text-neutral-900 dark:text-white"
                >
                    <TextReveal :text="t('playground.hero.line1')" />
                    <br />
                    <TextReveal :text="t('playground.hero.line2')" class="text-neutral-400 dark:text-white/30" :delay="1.5" />
                </h1>
            </div>

            <div ref="demosDropdownRef" class="relative mb-8 w-full md:max-w-xs">
                <button
                    type="button"
                    @click="demosDropdownOpen = !demosDropdownOpen"
                    class="flex w-full md:max-w-xs items-center justify-between gap-3 rounded-xl border border-neutral-200 bg-white px-4 py-3 text-left font-mono text-sm shadow-sm transition-colors hover:border-neutral-300 hover:bg-neutral-50 focus:ring-2 focus:ring-neutral-400/20 focus:outline-none dark:border-white/10 dark:bg-neutral-900/50 dark:hover:border-white/20 dark:hover:bg-neutral-800/50 dark:focus:ring-white/20"
                    :aria-expanded="demosDropdownOpen"
                    :aria-haspopup="true"
                >
                    <span class="truncate text-neutral-700 dark:text-neutral-300">
                        {{ t('playground.demosDropdown') }}
                    </span>
                    <FontAwesomeIcon
                        icon="fa-sharp fa-light fa-chevron-down"
                        class="size-4 shrink-0 text-neutral-500 transition-transform duration-200 dark:text-neutral-400"
                        :class="{ 'rotate-180': demosDropdownOpen }"
                    />
                </button>
                <Transition
                    enter-active-class="transition duration-150 ease-out"
                    enter-from-class="opacity-0 -translate-y-1"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-1"
                >
                    <div
                        v-show="demosDropdownOpen"
                        class="absolute top-full right-0 left-0 z-10 mt-1 max-h-60 w-full md:max-w-xs overflow-auto rounded-xl border border-neutral-200 bg-white py-1 shadow-lg dark:border-white/10 dark:bg-neutral-900 dark:shadow-neutral-950/50"
                    >
                        <button
                            v-for="exp in experiments"
                            :key="exp.id"
                            type="button"
                            @click="selectDemoAndClose(exp.id)"
                            class="block w-full md:max-w-xs px-4 py-2.5 text-left font-mono text-[13px] transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-800"
                        >
                            {{ exp.title }}
                        </button>
                    </div>
                </Transition>
            </div>

            <div class="grid grid-cols-1 gap-12">
                <div
                    v-for="exp in experiments"
                    :key="exp.id"
                    :id="exp.id"
                    class="group relative scroll-mt-32 overflow-hidden rounded-3xl border border-neutral-200 bg-white/50 p-4 md:p-6 backdrop-blur-sm transition-all hover:border-brand/30 dark:border-white/10 dark:bg-neutral-900/50"
                >
                    <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row md:items-start">
                        <div class="max-w-xl">
                            <div class="mb-3 flex items-center gap-3">
                                <div class="flex size-8 items-center justify-center rounded-lg bg-brand/10 text-brand">
                                    <FontAwesomeIcon icon="fa-sharp fa-light fa-flask" class="size-4" />
                                </div>
                                <LabelText as="span" size="xs" weight="semibold" class="!text-brand">
                                    {{ exp.category }}
                                </LabelText>
                            </div>
                            <h2 class="font-work-sans text-2xl font-bold text-neutral-900 dark:text-white">{{ exp.title }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-neutral-600 dark:text-white/60">
                                {{ exp.description }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="tag in exp.tags"
                                :key="tag"
                                class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-3 py-1 text-[10px] text-neutral-600 transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-neutral-800 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                            >
                                {{ tag }}
                            </span>
                        </div>
                    </div>

                    <!-- Result / Code toggle -->
                    <div class="relative overflow-hidden rounded-2xl border border-neutral-200 dark:border-white/10">
                        <div class="flex bg-white dark:bg-black/20">
                            <button
                                type="button"
                                class="cursor-pointer px-4 py-2.5 text-xs font-medium transition-colors"
                                :class="
                                    getView(exp.id) === 'result'
                                        ? 'border-b-2 border-neutral-900 bg-white text-neutral-900 dark:border-white dark:bg-neutral-900 dark:text-white'
                                        : 'text-neutral-500 hover:text-neutral-700 dark:text-white/50 dark:hover:text-white'
                                "
                                @click="setView(exp.id, 'result')"
                            >
                                {{ t('playground.result') }}
                            </button>
                            <button
                                type="button"
                                class="cursor-pointer px-4 py-2.5 text-xs font-medium transition-colors"
                                :class="
                                    getView(exp.id) === 'code'
                                        ? 'border-b-2 border-neutral-900 bg-white text-neutral-900 dark:border-white dark:bg-neutral-900 dark:text-white'
                                        : 'text-neutral-500 hover:text-neutral-700 dark:text-white/50 dark:hover:text-white'
                                "
                                @click="setView(exp.id, 'code')"
                            >
                                {{ t('playground.code') }}
                            </button>
                        </div>

                        <!-- Live Demo -->
                        <div
                            v-show="getView(exp.id) === 'result'"
                            class="relative rounded-b-2xl border-t border-neutral-200 bg-neutral-50 p-4 md:p-8 dark:border-white/10 dark:bg-black/20"
                        >
                            <component :is="exp.component" />
                        </div>

                        <div v-show="getView(exp.id) === 'code'" class="playground-code-wrap rounded-b-2xl p-[var(--code-inset)]">
                            <CodeBlock :code="exp.code" :language="exp.language" :is-playground="true" />
                        </div>
                    </div>
                </div>
            </div>

            <FooterArea />
        </main>
    </div>
</template>

<style scoped>
.playground-code-wrap {
    --code-inset: 0px;
    --code-inner-radius: 10px;
    --code-outer-radius: calc(var(--code-inner-radius) + var(--code-inset));
}
</style>
