<script setup lang="ts">
import CodeBlock from '@/components/CodeBlock.vue';
import CommandTrigger from '@/components/CommandTrigger.vue';
import { COMPONENT_TO_PLAYGROUND_ID, demoRegistry, type DemoComponent } from '@/components/demos/registry';
import FooterArea from '@/components/FooterArea.vue';
import PageTitle from '@/components/global/typography/PageTitle.vue';
import MarkdownImage from '@/components/MarkdownImage.vue';
import ProgressiveBlur from '@/components/ProgressiveBlur.vue';
import { useCommandMenu } from '@/composables/useCommandMenu';
import { usePosts } from '@/composables/usePosts';
import { marked, slugify } from '@/utils/markdown';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faArrowUpRight, faArrowUpToLine, faBarsSort, faChevronDown } from '@fortawesome/sharp-light-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, Link } from '@inertiajs/vue3';
import { onClickOutside, useClipboard, useShare, useWindowScroll } from '@vueuse/core';
import { computed, onMounted, onUnmounted, ref, shallowRef, watch } from 'vue';
import { useI18n } from 'vue-i18n';

library.add(faBarsSort, faArrowUpToLine, faChevronDown, faArrowUpRight);

const { y: scrollY } = useWindowScroll();
const documentHeight = ref(0);
const windowHeight = ref(0);

const scrollProgress = computed(() => {
    const maxScroll = documentHeight.value - windowHeight.value;
    if (maxScroll <= 0) return 0;
    return Math.min(100, Math.max(0, (scrollY.value / maxScroll) * 100));
});

const updateHeights = () => {
    documentHeight.value = document.documentElement.scrollHeight;
    windowHeight.value = window.innerHeight;
};

const props = defineProps<{
    slug: string;
}>();

const { t, locale } = useI18n();
const { open: openCommandMenu } = useCommandMenu();
const { getPost } = usePosts();

const post = computed(() => getPost(props.slug));

const pageTitle = computed(() => post.value?.title ?? 'Blog Post');
const pageDescription = computed(() => post.value?.excerpt ?? '');

const ogUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});

const jsonLd = computed(() => {
    if (!post.value) return {};

    const baseUrl = typeof window !== 'undefined' ? window.location.origin : 'https://captainscor.ch';
    const postUrl = `${baseUrl}/blog/${post.value.category[0].toLowerCase()}/${post.value.slug}`;
    const siteMetaImage = `${baseUrl}/img/captainscorch_meta.jpg`;

    return {
        '@context': 'https://schema.org',
        '@type': 'BlogPosting',
        headline: post.value.title,
        description: post.value.excerpt,
        image: [siteMetaImage],
        datePublished: post.value.date,
        author: [
            {
                '@type': 'Person',
                name: 'Daniel Schmier',
                url: baseUrl,
            },
        ],
        publisher: {
            '@type': 'Organization',
            name: 'captainscorch',
            logo: {
                '@type': 'ImageObject',
                url: `${baseUrl}/img/captainscorch_logo.svg`,
            },
        },
        mainEntityOfPage: {
            '@type': 'WebPage',
            '@id': postUrl,
        },
    };
});

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString(locale.value === 'de' ? 'de-DE' : 'en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const { share, isSupported: isShareSupported } = useShare();
const { copy, copied } = useClipboard();

const sharePost = async () => {
    const url = window.location.href;
    const isTouchDevice = typeof navigator !== 'undefined' && navigator.maxTouchPoints > 0;

    if (isShareSupported.value && isTouchDevice) {
        await share({
            title: pageTitle.value,
            text: pageDescription.value,
            url: url,
        });
    } else {
        copy(url);
    }
};

const loadedComponents = shallowRef<Record<string, DemoComponent>>({});

watch(
    post,
    (newPost) => {
        if (newPost?.components) {
            const components: Record<string, DemoComponent> = {};
            for (const name of newPost.components) {
                if (demoRegistry[name]) {
                    components[name] = demoRegistry[name];
                }
            }
            loadedComponents.value = components;
        }
    },
    { immediate: true },
);

interface ContentBlock {
    type: 'markdown' | 'code' | 'interactive' | 'image';
    content: string;
    language?: string;
    component?: string;
    image?: {
        src: string;
        alt: string;
        caption: string;
    };
}

const contentBlocks = computed((): ContentBlock[] => {
    if (!post.value?.content) return [];

    const content = post.value.content;
    const blocks: ContentBlock[] = [];

    const codeBlockRegex = /```(\w+)?\n([\s\S]*?)```/g;
    const interactiveRegex = /::interactive\[(\w+)\]/g;
    const imageRegex = /::image\[(.*?)\|(.*?)\|(.*?)\]/g;

    let lastIndex = 0;
    let match;

    type MatchItem = {
        index: number;
        length: number;
        type: 'code' | 'interactive' | 'image';
        content: string;
        language?: string;
        component?: string;
        image?: { src: string; alt: string; caption: string };
    };
    const allMatches: MatchItem[] = [];

    while ((match = codeBlockRegex.exec(content)) !== null) {
        allMatches.push({
            index: match.index,
            length: match[0].length,
            type: 'code',
            content: match[2].trim(),
            language: match[1] || 'plaintext',
        });
    }

    while ((match = interactiveRegex.exec(content)) !== null) {
        allMatches.push({
            index: match.index,
            length: match[0].length,
            type: 'interactive',
            content: '',
            component: match[1],
        });
    }

    while ((match = imageRegex.exec(content)) !== null) {
        allMatches.push({
            index: match.index,
            length: match[0].length,
            type: 'image',
            content: '',
            image: {
                src: match[1].trim(),
                alt: match[2].trim(),
                caption: match[3].trim(),
            },
        });
    }

    allMatches.sort((a, b) => a.index - b.index);

    for (const m of allMatches) {
        if (m.index > lastIndex) {
            const markdownContent = content.slice(lastIndex, m.index).trim();
            if (markdownContent) {
                blocks.push({ type: 'markdown', content: markdownContent });
            }
        }

        if (m.type === 'code') {
            blocks.push({ type: 'code', content: m.content, language: m.language });
        } else if (m.type === 'interactive') {
            blocks.push({ type: 'interactive', content: '', component: m.component });
        } else if (m.type === 'image' && m.image) {
            blocks.push({ type: 'image', content: '', image: m.image });
        }

        lastIndex = m.index + m.length;
    }

    if (lastIndex < content.length) {
        const remainingContent = content.slice(lastIndex).trim();
        if (remainingContent) {
            blocks.push({ type: 'markdown', content: remainingContent });
        }
    }

    return blocks;
});

const renderMarkdown = (content: string): string => {
    return marked.parse(content) as string;
};

interface Heading {
    id: string;
    text: string;
    level: number;
}

const headings = ref<Heading[]>([]);
const activeHeading = ref<string>('');
const mobileTocOpen = ref(false);
const mobileTocRef = ref<HTMLElement | null>(null);

onClickOutside(mobileTocRef, () => {
    mobileTocOpen.value = false;
});

watch(
    post,
    (newPost) => {
        headings.value = [];
        if (!newPost?.content) return;

        const regex = /^(#{2,3})\s+(.*)$/gm;
        let match;
        const foundHeadings: Heading[] = [];

        while ((match = regex.exec(newPost.content)) !== null) {
            const level = match[1].length;
            const text = match[2].trim();
            const id = slugify(text);
            foundHeadings.push({ id, text, level });
        }

        headings.value = foundHeadings;
    },
    { immediate: true },
);

const isNavigating = ref(false);
let navigationTimeout: ReturnType<typeof setTimeout> | null = null;

const scrollToHeading = (id: string) => {
    const element = document.getElementById(id);
    if (element) {
        activeHeading.value = id;
        isNavigating.value = true;
        mobileTocOpen.value = false;

        if (navigationTimeout) {
            clearTimeout(navigationTimeout);
        }

        const offset = 100;
        const bodyRect = document.body.getBoundingClientRect().top;
        const elementRect = element.getBoundingClientRect().top;
        const elementPosition = elementRect - bodyRect;
        const offsetPosition = elementPosition - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth',
        });

        navigationTimeout = setTimeout(() => {
            isNavigating.value = false;
        }, 800);
    }
};

const selectTocAndClose = (id: string) => {
    scrollToHeading(id);
};

const scrollToTop = () => {
    isNavigating.value = true;
    if (navigationTimeout) {
        clearTimeout(navigationTimeout);
    }
    if (headings.value.length > 0) {
        activeHeading.value = headings.value[0].id;
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
    navigationTimeout = setTimeout(() => {
        isNavigating.value = false;
    }, 800);
};

const updateActiveHeading = () => {
    if (isNavigating.value) return;

    const headingElements = headings.value.map((h) => document.getElementById(h.id)).filter(Boolean) as HTMLElement[];

    if (headingElements.length === 0) return;

    const scrollY = window.scrollY + 150;

    for (let i = headingElements.length - 1; i >= 0; i--) {
        if (headingElements[i].offsetTop <= scrollY) {
            activeHeading.value = headingElements[i].id;
            break;
        }
    }
};

onMounted(() => {
    updateHeights();
    window.addEventListener('resize', updateHeights);
    window.addEventListener('scroll', updateActiveHeading);
});

onUnmounted(() => {
    window.removeEventListener('resize', updateHeights);
    window.removeEventListener('scroll', updateActiveHeading);
});

const hasComponent = (name: string): boolean => {
    return !!loadedComponents.value[name];
};

const getComponent = (name: string) => {
    return loadedComponents.value[name];
};

const ITEM_HEIGHT = 28;
const X_LEVEL_2 = 14;
const X_LEVEL_3 = 26;
const START_Y = 16;

const approxQuadraticLength = (x1: number, y1: number, cx: number, cy: number, x2: number, y2: number): number => {
    const chordLength = Math.sqrt((x2 - x1) ** 2 + (y2 - y1) ** 2);
    const controlLength = Math.sqrt((cx - x1) ** 2 + (cy - y1) ** 2) + Math.sqrt((x2 - cx) ** 2 + (y2 - cy) ** 2);
    return (chordLength + controlLength) / 2;
};

const tocPathData = computed(() => {
    if (headings.value.length === 0) return { path: '', cumulativeLengths: [0] };

    const segments: string[] = [];
    const cumulativeLengths: number[] = [0];
    let totalLength = 0;

    let currentX = headings.value[0].level === 2 ? X_LEVEL_2 : X_LEVEL_3;
    let currentY = START_Y;

    segments.push(`M ${currentX} ${currentY}`);

    for (let i = 1; i < headings.value.length; i++) {
        const prevLevel = headings.value[i - 1].level;
        const currLevel = headings.value[i].level;
        const targetX = currLevel === 2 ? X_LEVEL_2 : X_LEVEL_3;
        const targetY = START_Y + i * ITEM_HEIGHT;

        if (prevLevel !== currLevel) {
            const midY = currentY + ITEM_HEIGHT / 2;
            const midX = (X_LEVEL_2 + X_LEVEL_3) / 2;

            const lineEndY = midY - 8;
            segments.push(`L ${currentX} ${lineEndY}`);
            totalLength += Math.abs(lineEndY - currentY);

            segments.push(`Q ${currentX} ${midY} ${midX} ${midY}`);
            totalLength += approxQuadraticLength(currentX, lineEndY, currentX, midY, midX, midY);

            segments.push(`Q ${targetX} ${midY} ${targetX} ${midY + 8}`);
            totalLength += approxQuadraticLength(midX, midY, targetX, midY, targetX, midY + 8);

            currentX = targetX;
            currentY = midY + 8;

            segments.push(`L ${targetX} ${targetY}`);
            totalLength += Math.abs(targetY - currentY);
        } else {
            segments.push(`L ${currentX} ${targetY}`);
            totalLength += Math.abs(targetY - currentY);
        }

        currentY = targetY;
        cumulativeLengths.push(totalLength);
    }

    return { path: segments.join(' '), cumulativeLengths, totalLength };
});

const tocPath = computed(() => tocPathData.value.path);

const activeHeadingIndex = computed(() => {
    if (!activeHeading.value) return 0;
    const index = headings.value.findIndex((h) => h.id === activeHeading.value);
    return index >= 0 ? index : 0;
});

const tocProgress = computed(() => {
    const data = tocPathData.value;
    const total = data.totalLength ?? 0;
    if (headings.value.length <= 1 || total === 0) return 1;

    const activeIndex = activeHeadingIndex.value;
    const lengthAtActive = data.cumulativeLengths[activeIndex] ?? 0;

    return lengthAtActive / total;
});

const svgHeight = computed(() => {
    return START_Y + (headings.value.length - 1) * ITEM_HEIGHT + 20;
});

const activeCirclePosition = computed(() => {
    const index = activeHeadingIndex.value;
    const heading = headings.value[index];
    if (!heading) return { x: X_LEVEL_2, y: START_Y };

    return {
        x: heading.level === 2 ? X_LEVEL_2 : X_LEVEL_3,
        y: START_Y + index * ITEM_HEIGHT,
    };
});
</script>

<template>
    <Head :title="pageTitle">
        <!-- Standard meta tags -->
        <meta name="description" :content="pageDescription" />

        <!-- Open Graph / Facebook (Meta) -->
        <meta property="og:url" :content="ogUrl" />
        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="pageDescription" />

        <!-- Twitter (X) -->
        <meta property="twitter:url" :content="ogUrl" />
        <meta property="twitter:title" :content="pageTitle" />
        <meta property="twitter:description" :content="pageDescription" />

        <!-- JSON-LD -->
        <component :is="'script'" type="application/ld+json">
            {{ JSON.stringify(jsonLd) }}
        </component>
    </Head>

    <div
        class="min-h-screen bg-white pt-32 pb-20 text-neutral-900 transition-all duration-500 ease-out md:pt-40 md:pb-0 dark:bg-black dark:text-white"
    >
        <!-- Scroll Progress Bar -->
        <div class="fixed top-0 right-0 left-0 z-[60] h-0.5 bg-neutral-200/50 dark:bg-white/10">
            <div class="h-full bg-brand transition-[width] duration-100 ease-out" :style="{ width: `${scrollProgress}%` }" />
        </div>

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
                    <Link href="/blog">
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
                            <span> {{ t('blog.backToBlog') }} </span>
                        </button>
                    </Link>
                </div>

                <!-- Command Menu (Top Right) -->
                <div class="pointer-events-auto flex items-center gap-4">
                    <CommandTrigger @click="openCommandMenu" />
                </div>
            </div>
        </header>

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col gap-12 lg:grid lg:grid-cols-12">
                <!-- Main Content -->
                <main v-if="post" class="min-w-0 lg:col-span-9 lg:pr-20">
                    <!-- Article Header -->
                    <header class="mb-12">
                        <!-- Categories -->
                        <div class="mb-4 flex flex-wrap gap-2">
                            <span
                                v-for="category in post.category"
                                :key="category"
                                class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-4 py-2 text-xs text-neutral-600 transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-neutral-800 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                            >
                                {{ category }}
                            </span>
                        </div>

                        <!-- Title -->
                        <PageTitle>
                            {{ post.title }}
                        </PageTitle>

                        <!-- Meta & Share -->
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center gap-4 text-sm text-neutral-500 dark:text-neutral-400">
                                <span>{{ formatDate(post.date) }}</span>
                                <span>•</span>
                                <span>{{ post.readingTime }} {{ t('blog.readingTime') }}</span>
                            </div>

                            <button
                                @click="sharePost"
                                class="share-item group relative flex items-center gap-2 rounded-full border border-neutral-200 bg-white/50 p-2 text-xs font-medium backdrop-blur-sm transition-all hover:bg-white hover:shadow-lg dark:border-white/10 dark:bg-neutral-900/50 dark:hover:bg-neutral-800"
                                :title="copied ? t('blog.copied') : t('blog.share')"
                            >
                                <lord-icon
                                    src="/icons/system-regular-98-link-hover-link.json"
                                    trigger="hover"
                                    target=".share-item"
                                    style="width: 18px; height: 18px"
                                    class="current-color opacity-60 group-hover:opacity-100"
                                />
                                <span
                                    v-if="copied"
                                    class="absolute -bottom-8 left-1/2 -translate-x-1/2 rounded bg-neutral-900 px-2 py-1 text-xs whitespace-nowrap text-white shadow-lg dark:bg-white dark:text-black"
                                >
                                    {{ t('blog.copied') }}
                                </span>
                            </button>
                        </div>
                    </header>

                    <!-- Mobile Table of Contents -->
                    <div v-if="headings.length > 0" ref="mobileTocRef" class="relative mb-8 w-full lg:hidden">
                        <button
                            type="button"
                            @click="mobileTocOpen = !mobileTocOpen"
                            class="flex w-full items-center justify-between gap-3 rounded-xl border border-neutral-200 bg-white px-4 py-3 text-left font-mono text-sm shadow-sm transition-colors hover:border-neutral-300 hover:bg-neutral-50 focus:ring-2 focus:ring-neutral-400/20 focus:outline-none dark:border-white/10 dark:bg-neutral-900/50 dark:hover:border-white/20 dark:hover:bg-neutral-800/50 dark:focus:ring-white/20"
                            :aria-expanded="mobileTocOpen"
                            :aria-haspopup="true"
                        >
                            <span class="truncate text-neutral-700 dark:text-neutral-300">
                                {{ t('blog.onThisPage') }}
                            </span>
                            <FontAwesomeIcon
                                icon="fa-sharp fa-light fa-chevron-down"
                                class="size-4 shrink-0 text-neutral-500 transition-transform duration-200 dark:text-neutral-400"
                                :class="{ 'rotate-180': mobileTocOpen }"
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
                                v-show="mobileTocOpen"
                                class="absolute top-full right-0 left-0 z-10 mt-1 max-h-60 overflow-auto rounded-xl border border-neutral-200 bg-white py-1 shadow-lg dark:border-white/10 dark:bg-neutral-900 dark:shadow-neutral-950/50"
                            >
                                <button
                                    v-for="heading in headings"
                                    :key="heading.id"
                                    type="button"
                                    @click="selectTocAndClose(heading.id)"
                                    class="block w-full px-4 py-2.5 text-left font-mono text-[13px] transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-800"
                                >
                                    {{ heading.text }}
                                </button>
                            </div>
                        </Transition>
                    </div>

                    <!-- Article Content -->
                    <article class="prose">
                        <template v-for="(block, index) in contentBlocks" :key="index">
                            <!-- Markdown Block -->
                            <div v-if="block.type === 'markdown'" v-html="renderMarkdown(block.content)" class="space-y-4" />

                            <!-- Code Block -->
                            <div v-else-if="block.type === 'code'" class="my-6">
                                <CodeBlock :code="block.content" :language="block.language" />
                            </div>

                            <!-- Interactive Demo -->
                            <div v-else-if="block.type === 'interactive' && block.component && hasComponent(block.component)" class="my-8">
                                <component :is="getComponent(block.component)" />
                                <Link
                                    v-if="block.component && COMPONENT_TO_PLAYGROUND_ID[block.component]"
                                    :href="`/playground#${COMPONENT_TO_PLAYGROUND_ID[block.component]}`"
                                    class="group mx-auto mt-4 mb-8 flex max-w-lg items-center justify-center gap-2 px-2 text-center !no-underline"
                                >
                                    <span
                                        class="text-sm font-medium text-neutral-500 group-hover:text-neutral-500 dark:text-neutral-500 dark:group-hover:text-neutral-400"
                                    >
                                        {{ t('blog.viewInPlayground') }}
                                    </span>
                                    <FontAwesomeIcon
                                        icon="fa-sharp fa-light fa-arrow-up-right"
                                        class="text-neutral-500 transition-all duration-300 ease-out group-hover:translate-x-1 group-hover:-translate-y-1 group-hover:text-neutral-500 dark:text-neutral-500 dark:group-hover:text-neutral-400"
                                    />
                                </Link>
                            </div>

                            <!-- Image -->
                            <div v-else-if="block.type === 'image' && block.image">
                                <MarkdownImage :src="block.image.src" :alt="block.image.alt" :caption="block.image.caption" />
                            </div>

                            <!-- Fallback for missing demo -->
                            <div
                                v-else-if="block.type === 'interactive'"
                                class="my-8 rounded-xl border-2 border-dashed border-neutral-200 bg-neutral-50 p-8 text-center dark:border-white/10 dark:bg-neutral-900"
                            >
                                <p class="text-neutral-500 dark:text-neutral-400">
                                    {{ t('blog.interactiveUnavailable') }}
                                </p>
                            </div>
                        </template>
                    </article>

                    <!-- Tags -->
                    <div v-if="post.tags && post.tags.length > 0" class="mt-12 border-t border-neutral-200 pt-8 dark:border-white/10">
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="tag in post.tags"
                                :key="tag"
                                class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-2.5 py-1 text-[10px] text-neutral-500 transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-white/10 dark:bg-white/10 dark:text-neutral-400 dark:hover:border-neutral-600 dark:hover:text-white"
                            >
                                #{{ tag }}
                            </span>
                        </div>
                    </div>
                </main>

                <aside class="hidden lg:col-span-3 lg:block">
                    <div class="sticky top-32">
                        <div v-if="headings.length > 0" class="space-y-4">
                            <div class="flex items-center">
                                <FontAwesomeIcon icon="fa-sharp fa-light fa-bars-sort" class="absolute left-1.5 text-xs md:text-sm" />
                                <h3 class="flex items-center gap-2 pl-8 text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ t('blog.onThisPage') }}
                                </h3>
                            </div>

                            <div class="relative">
                                <!-- Animated Line -->
                                <svg
                                    class="pointer-events-none absolute top-0 left-0 h-full w-full"
                                    aria-hidden="true"
                                    :viewBox="`0 0 40 ${svgHeight}`"
                                    :style="{ height: `${svgHeight}px` }"
                                    preserveAspectRatio="xMinYMin meet"
                                >
                                    <!-- Background path -->
                                    <path
                                        :d="tocPath"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1"
                                        class="text-neutral-200 dark:text-neutral-800"
                                        pathLength="1"
                                        stroke-dashoffset="0"
                                        stroke-dasharray="1 1"
                                    />
                                    <!-- Foreground path (animated progress) -->
                                    <path
                                        :d="tocPath"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1"
                                        class="text-neutral-900 transition-all duration-500 ease-out dark:text-white"
                                        pathLength="1"
                                        :stroke-dasharray="1"
                                        :stroke-dashoffset="1 - tocProgress"
                                    />
                                    <!-- Start circle -->
                                    <circle
                                        :cx="headings[0]?.level === 2 ? X_LEVEL_2 : X_LEVEL_3"
                                        :cy="START_Y"
                                        r="2.5"
                                        fill="currentColor"
                                        class="text-neutral-200 dark:text-neutral-800"
                                    />
                                    <!-- End circle -->
                                    <circle
                                        v-if="headings.length > 1"
                                        :cx="headings[headings.length - 1]?.level === 2 ? X_LEVEL_2 : X_LEVEL_3"
                                        :cy="START_Y + (headings.length - 1) * ITEM_HEIGHT"
                                        r="2.5"
                                        fill="currentColor"
                                        class="text-neutral-200 dark:text-neutral-800"
                                    />
                                    <!-- Active position circle -->
                                    <circle
                                        :cx="activeCirclePosition.x"
                                        :cy="activeCirclePosition.y"
                                        r="3.5"
                                        fill="currentColor"
                                        class="text-neutral-900 transition-all duration-300 ease-out dark:text-white"
                                    />
                                </svg>

                                <nav class="relative flex flex-col">
                                    <a
                                        v-for="heading in headings"
                                        :key="heading.id"
                                        :href="`#${heading.id}`"
                                        class="block py-1.5 font-mono text-[11px] transition-colors hover:text-neutral-900 dark:hover:text-white"
                                        :class="[
                                            activeHeading === heading.id
                                                ? 'font-medium text-neutral-900 dark:text-white'
                                                : 'text-neutral-500 dark:text-neutral-500',
                                        ]"
                                        :style="{ paddingLeft: heading.level === 2 ? '2rem' : '2.75rem' }"
                                        @click.prevent="scrollToHeading(heading.id)"
                                    >
                                        {{ heading.text }}
                                    </a>
                                </nav>
                            </div>

                            <!-- Back to top button -->
                            <div class="pl-8">
                                <button
                                    type="button"
                                    @click="scrollToTop"
                                    class="-mt-2 flex w-[75%] cursor-pointer items-center gap-2 border-t border-neutral-200 pt-3 font-mono text-[11px] text-neutral-500 transition-colors hover:text-neutral-900 dark:border-neutral-800 dark:text-neutral-500 dark:hover:text-white"
                                >
                                    <span>{{ t('blog.top') }}</span>
                                    <FontAwesomeIcon icon="fa-sharp fa-light fa-arrow-up-to-line" />
                                </button>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <!-- Not Found -->
        <main v-if="!post" class="mx-auto max-w-3xl px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div
                    class="post-not-found-area mb-6 flex size-16 items-center justify-center rounded-2xl border border-neutral-200 bg-neutral-100 text-neutral-400 dark:border-white/10 dark:bg-white/5 dark:text-neutral-500"
                >
                    <lord-icon
                        src="/icons/system-regular-14-article-hover-article.json"
                        trigger="hover"
                        target=".post-not-found-area"
                        delay="2000"
                        class="current-color size-8"
                    />
                </div>
                <h1 class="mb-2 font-sans text-xl font-bold text-neutral-900 dark:text-white">{{ t('blog.postNotFound.title') }}</h1>
                <p class="mb-6 text-neutral-600 dark:text-neutral-400">{{ t('blog.postNotFound.description') }}</p>
            </div>
        </main>

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <FooterArea />
        </div>
    </div>
</template>
