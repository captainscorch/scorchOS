<script setup lang="ts">
import CommandTrigger from '@/components/CommandTrigger.vue';
import FooterArea from '@/components/FooterArea.vue';
import ProgressiveBlur from '@/components/ProgressiveBlur.vue';
import TextReveal from '@/components/TextReveal.vue';
import { useCommandMenu } from '@/composables/useCommandMenu';
import { useProjects } from '@/composables/useProjects';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faTimes } from '@fortawesome/sharp-light-svg-icons';
import { Head, Link } from '@inertiajs/vue3';
import gsap from 'gsap';
import { computed, nextTick, ref } from 'vue';
import { useI18n } from 'vue-i18n';

library.add(faTimes);

const { t, locale } = useI18n();
const { open: openCommandMenu } = useCommandMenu();
const { projects } = useProjects();

const pageTitle = 'Daniel Schmier - Portfolio';
const pageDescription = 'Case studies and portfolio of Daniel Schmier, showcasing work in product design, development, and branding.';
const ogTitle = 'Daniel Schmier – Portfolio';

const ogUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});

const viewMode = ref<'covers' | 'spines'>('covers');

const toggleView = (mode: 'covers' | 'spines') => {
    viewMode.value = mode;
};

const onEnter = (el: Element, done: () => void) => {
    if (viewMode.value === 'spines') {
        // Spines Enter Animation (Falling Books)
        const spines = Array.from((el as HTMLElement).children) as HTMLElement[];

        // Ensure layout is calculated
        nextTick(() => {
            spines.forEach((spine, index) => {
                const rect = spine.getBoundingClientRect();
                const startY = -rect.top - window.innerHeight * 1.2 - Math.random() * 200;

                gsap.fromTo(
                    spine,
                    {
                        y: startY,
                        opacity: 1, // Visible but off-screen
                        rotation: Math.random() * 30 - 15,
                        scale: 0.9,
                        // Disable CSS transitions during GSAP animation to prevent conflicts
                        transition: 'none',
                    },
                    {
                        y: 0,
                        opacity: 1,
                        rotation: 0,
                        scale: 1,
                        // Slower duration for a more "floating" feel, especially with large distances
                        duration: 2.0 + Math.random() * 0.5,
                        delay: index * 0.05,
                        ease: 'power3.out', // Smoother easing than bounce for long falls
                        onComplete: () => {
                            if (index === spines.length - 1) done();
                        },
                        // Restore CSS transitions after animation
                        clearProps: 'transform,rotation,scale,transition',
                    },
                );
            });
        });
    } else {
        // Covers Enter Animation (Fold Up)
        const items = Array.from(el.children);

        gsap.fromTo(
            items,
            {
                opacity: 0,
                rotationX: 90,
                transformOrigin: 'bottom center',
                y: 50,
            },
            {
                opacity: 1,
                rotationX: 0,
                y: 0,
                duration: 0.8,
                stagger: 0.05,
                ease: 'power2.out',
                onComplete: done,
                // Keep opacity to override the opacity-0 class
                clearProps: 'transform,rotationX,y',
            },
        );
    }
};

const onLeave = (el: Element, done: () => void) => {
    gsap.to(el, {
        opacity: 0,
        duration: 0.3,
        ease: 'power2.in',
        onComplete: done,
    });
};
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
    <div class="min-h-screen bg-white pt-32 pb-20 text-neutral-900 md:pt-40 md:pb-0 dark:bg-black dark:text-white">
        <!-- Header / Navigation -->
        <header class="pointer-events-none fixed top-0 right-0 left-0 z-50 mx-auto">
            <ProgressiveBlur class="absolute inset-0 -z-10 h-full w-full" />
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between p-6 px-6 sm:px-6 md:p-6 lg:px-8">
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
                    <button
                        @click="toggleView('covers')"
                        class="covers-btn group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium transition-all duration-300"
                        :class="
                            viewMode === 'covers'
                                ? 'bg-neutral-900 text-white shadow-lg dark:bg-white dark:text-black'
                                : 'text-neutral-500 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white'
                        "
                    >
                        <lord-icon
                            src="/icons/system-solid-165-view-carousel-hover-carousel.json"
                            trigger="hover"
                            target=".covers-btn"
                            :class="
                                viewMode === 'covers'
                                    ? 'size-4 fill-white dark:fill-black'
                                    : 'size-4 fill-neutral-400 group-hover:fill-neutral-900 dark:fill-white/40 dark:group-hover:fill-white'
                            "
                        >
                        </lord-icon>
                        <span>{{ t('portfolio.views.covers') }}</span>
                    </button>
                    <button
                        @click="toggleView('spines')"
                        class="spines-btn group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium transition-all duration-300"
                        :class="
                            viewMode === 'spines'
                                ? 'bg-neutral-900 text-white shadow-lg dark:bg-white dark:text-black'
                                : 'text-neutral-500 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white'
                        "
                    >
                        <lord-icon
                            src="/icons/system-solid-77-pause-hover-pause.json"
                            trigger="hover"
                            target=".spines-btn"
                            style="transform: rotate(90deg)"
                            :class="
                                viewMode === 'spines'
                                    ? 'size-4 fill-white dark:fill-black'
                                    : 'size-4 fill-neutral-400 group-hover:fill-neutral-900 dark:fill-white/40 dark:group-hover:fill-white'
                            "
                        >
                        </lord-icon>
                        <span>{{ t('portfolio.views.spines') }}</span>
                    </button>
                </div>

                <!-- Hamburger Menu (Top Right) -->
                <div class="pointer-events-auto flex items-center gap-4">
                    <CommandTrigger @click="openCommandMenu" />
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-[1920px] px-6 lg:px-8">
            <!-- Hero Text -->
            <div class="mx-auto mb-22 max-w-[calc(1280px-64px)] md:mb-32">
                <h2 class="mb-6 text-sm tracking-widest text-neutral-500 uppercase dark:text-white/30">{{ t('portfolio.title') }}</h2>
                <h1
                    class="font-work-sans text-3xl font-bold text-neutral-900 dark:text-white"
                    :class="locale === 'en' ? 'max-w-56 sm:max-w-none' : locale === 'de' ? 'max-w-80 sm:max-w-none' : ''"
                >
                    <TextReveal :text="t('portfolio.hero.line1')" />
                    <br />
                    <TextReveal :text="t('portfolio.hero.line2')" class="text-neutral-400 dark:text-white/30" :delay="1.5" />
                </h1>
            </div>

            <!-- Views -->
            <transition mode="out-in" @enter="onEnter" @leave="onLeave" :css="false" appear>
                <!-- Covers View -->
                <div v-if="viewMode === 'covers'" key="covers" class="flex flex-wrap items-end justify-center gap-4 perspective-[1000px] md:gap-6">
                    <Link
                        v-for="project in projects"
                        :key="project.id"
                        :href="`/case-study/${project.slug}`"
                        class="group relative w-[calc(50%-0.5rem)] transform cursor-pointer overflow-hidden rounded-2xl bg-neutral-500 opacity-0 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:shadow-neutral-900/5 md:w-[calc(100%/3-1rem)] lg:w-[calc(25%-1.125rem)] xl:w-[calc(20%-1.2rem)] dark:bg-neutral-900 dark:hover:shadow-neutral-100/5"
                        :class="project.height"
                    >
                        <img
                            :src="project.image"
                            :alt="project.title"
                            class="h-full w-full object-cover opacity-80 transition-transform duration-700 group-hover:scale-110 group-hover:opacity-100"
                        />

                        <!-- Category Badge(s) (Top Left) -->
                        <div
                            class="absolute top-4 left-4 z-20 flex flex-wrap gap-2 pr-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                        >
                            <span
                                v-for="(category, index) in Array.isArray(project.category) ? project.category : [project.category]"
                                :key="index"
                                class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-3 py-1.5 text-[10px] text-neutral-600 backdrop-blur-xs transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-white/10 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                            >
                                {{ category }}
                            </span>
                        </div>

                        <div
                            class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-transparent to-transparent p-6 opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        >
                            <div class="translate-y-4 transform transition-transform duration-500 group-hover:translate-y-0">
                                <h3 class="mb-1 font-work-sans text-xl font-bold text-white md:text-2xl">{{ project.client }}</h3>
                                <p class="line-clamp-2 font-work-sans text-xs text-white/80 md:text-sm">{{ project.title }}</p>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Spines View -->
                <div v-else key="spines" class="flex w-full flex-col items-center gap-2 perspective-[1000px]">
                    <Link
                        v-for="project in projects"
                        :key="project.id"
                        :href="`/case-study/${project.slug}`"
                        class="group relative w-full origin-center cursor-pointer overflow-hidden rounded-lg opacity-0 transition-all duration-500 hover:z-10 hover:scale-[1.02] hover:shadow-2xl hover:shadow-neutral-900/50 dark:hover:shadow-white/15"
                        :class="project.spineHeight"
                        :style="{ width: project.width, backgroundColor: project.color }"
                    >
                        <div
                            class="absolute inset-0 flex items-center justify-between px-6 opacity-80 transition-opacity group-hover:opacity-100 md:px-12"
                        >
                            <div class="flex flex-col">
                                <span class="mb-1 font-work-sans text-xs font-medium text-white opacity-60 md:text-sm">{{ project.title }}</span>
                                <span class="font-work-sans text-lg font-bold text-white md:text-3xl">{{ project.client }}</span>
                            </div>

                            <!-- Logo Representation -->
                            <div class="hidden md:block">
                                <span
                                    class="font-sans text-4xl font-black tracking-tighter text-white/20 uppercase italic transition-colors group-hover:text-white/30 md:text-6xl"
                                >
                                    {{ project.logo }}
                                </span>
                            </div>

                            <!-- Mobile Icon -->
                            <div class="md:hidden">
                                <span
                                    class="rounded border border-white/20 px-2 py-1 font-mono text-lg font-bold tracking-widest text-white uppercase"
                                    >{{ project.logo.substring(0, 2) }}</span
                                >
                            </div>
                        </div>
                    </Link>
                </div>
            </transition>
        </main>

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <FooterArea />
        </div>
    </div>
</template>
