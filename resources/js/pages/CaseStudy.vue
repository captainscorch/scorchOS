<script setup lang="ts">
import CommandTrigger from '@/components/CommandTrigger.vue';
import FooterArea from '@/components/FooterArea.vue';
import PrimaryButton from '@/components/global/PrimaryButton.vue';
import BodyText from '@/components/global/typography/BodyText.vue';
import LabelText from '@/components/global/typography/LabelText.vue';
import PageTitle from '@/components/global/typography/PageTitle.vue';
import SectionTitle from '@/components/global/typography/SectionTitle.vue';
import MarkdownContent from '@/components/MarkdownContent.vue';
import ProgressiveBlur from '@/components/ProgressiveBlur.vue';
import ProjectSlider from '@/components/ProjectSlider.vue';
import TextReveal from '@/components/TextReveal.vue';
import { Drawer, DrawerClose, DrawerContent, DrawerDescription, DrawerHeader, DrawerTitle, DrawerTrigger } from '@/components/ui/drawer';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { useCommandMenu } from '@/composables/useCommandMenu';
import { useProjects } from '@/composables/useProjects';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faArrowLeftLong, faArrowUpRight } from '@fortawesome/sharp-light-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, Link } from '@inertiajs/vue3';
import FsLightbox from 'fslightbox-vue';
import 'swiper/css';
import { Mousewheel } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { computed, nextTick, onUnmounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

library.add(faArrowLeftLong, faArrowUpRight);

const props = defineProps<{
    slug: string;
}>();

const { t } = useI18n();
const { open: openCommandMenu } = useCommandMenu();
const { getProject } = useProjects();
const isDrawerOpen = ref(false);
const isStoryDrawerOpen = ref(false);
const fineprintToggler = ref(false);

const clickedImageIndex = ref<number | null>(null);
const drawerContentRef = ref<HTMLElement | null>(null);

const project = computed(() => {
    return getProject(props.slug);
});

const pageTitle = computed(() => (project.value ? `${project.value.client} - Case Study` : t('caseStudy.notFound')));
const pageDescription = computed(() => project.value?.story_preview || t('caseStudy.defaultDescription'));
const ogTitle = computed(() => (project.value ? `${project.value.client} – ${project.value.title}` : 'Case Study'));
const ogImage = computed(() => project.value?.image || '');

const ogUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});

const isAnyDrawerOpen = computed(() => isDrawerOpen.value || isStoryDrawerOpen.value);

const galleryMedia = computed(() => {
    return project.value?.media ?? [];
});

const sliderMedia = computed(() => {
    const original = galleryMedia.value || [];
    if (original.length === 0) return [];

    let result = original.map((item, index) => ({
        ...item,
        originalIndex: index,
        uniqueKey: `original-${index}`,
    }));

    const MIN_SLIDES = 8;

    // Duplicate until minimum slides count is reached
    while (result.length < MIN_SLIDES) {
        const duplicates = original.map((item, index) => ({
            ...item,
            originalIndex: index,
            uniqueKey: `dup-${result.length}-${index}`,
        }));
        result = [...result, ...duplicates];
    }

    return result;
});

// Get aspect ratio for a media item (defaults: images = 3/4, videos = 16/9)
const getAspectRatio = (media: any): string => {
    if (media.aspectRatio) return media.aspectRatio;
    return media.type === 'image' ? '3/4' : '16/9';
};

// Parse aspect ratio string (e.g., "3/4" or "16/9") to width/height ratio
const parseAspectRatio = (aspectRatio: string): number => {
    if (aspectRatio === 'video') return 16 / 9;
    const [width, height] = aspectRatio.split('/').map(Number);
    return height / width; // Returns height multiplier (e.g., 4/3 for 3/4)
};

// Format website URL
const formatWebsiteUrl = (url: string) => {
    return url.replace(/^https?:\/\//, '').replace(/^www\./, '');
};

// Calculate width for a media item to match reference height
// Reference: videos at 600px/800px with 16/9 aspect ratio
const getMediaWidth = (media: any, referenceWidth: number): number => {
    const referenceHeight = referenceWidth * (9 / 16); // Height of reference video (16:9)

    const mediaAspect = parseAspectRatio(getAspectRatio(media));
    const mediaWidth = referenceHeight / mediaAspect;

    return Math.round(mediaWidth);
};

// Open drawer and scroll to specific image
const openDrawerAtImage = (index: number) => {
    clickedImageIndex.value = index;
    isDrawerOpen.value = true;
};

watch(isDrawerOpen, async (isOpen) => {
    if (isOpen && clickedImageIndex.value !== null) {
        // Ensure we don't try to scroll to an image beyond what's displayed (max 6 images in drawer)
        const maxDisplayedIndex = Math.min(clickedImageIndex.value, galleryMedia.value.length - 1, 5);

        const scrollToImage = async (retries = 5) => {
            await nextTick();
            await new Promise((resolve) => setTimeout(resolve, 150));

            if (!drawerContentRef.value) {
                if (retries > 0) {
                    return scrollToImage(retries - 1);
                }
                return;
            }

            const targetElement = drawerContentRef.value.querySelector(`[data-image-index="${maxDisplayedIndex}"]`) as HTMLElement | null;

            if (targetElement && drawerContentRef.value) {
                const container = drawerContentRef.value;
                const containerRect = container.getBoundingClientRect();
                const elementRect = targetElement.getBoundingClientRect();

                const scrollTop = container.scrollTop + (elementRect.top - containerRect.top) - containerRect.height / 2 + elementRect.height / 2;

                container.scrollTo({
                    top: scrollTop,
                    behavior: 'smooth',
                });
            } else if (retries > 0) {
                return scrollToImage(retries - 1);
            }
        };

        await scrollToImage();
    }

    if (!isOpen) {
        clickedImageIndex.value = null;
    }
});

watch(isAnyDrawerOpen, (isOpen) => {
    const app = document.getElementById('app');
    if (!app) return;

    if (isOpen) {
        app.style.transition = 'opacity 0.5s ease-out, filter 0.5s ease-out, clip-path 0.5s ease-out, transform 0.5s ease-out';
        app.style.opacity = '0.4';
        const scrollY = window.scrollY;
        app.style.clipPath = `xywh(0px ${scrollY}px 100vw 100lvh round 24px)`;
        app.style.transform = 'translateY(16px) scale(0.95)';
        app.style.transformOrigin = `center ${scrollY}px`;

        const isDark = document.documentElement.classList.contains('dark');
        document.body.style.backgroundColor = isDark ? '#262626' : '#d4d4d4';
        document.body.style.backgroundImage = 'none';
    } else {
        app.style.opacity = '';
        app.style.clipPath = '';
        app.style.transform = '';
        app.style.transformOrigin = '';
        app.style.backgroundImage = '';
        document.body.style.backgroundColor = '';

        setTimeout(() => {
            if (!isAnyDrawerOpen.value && app.style.transition.includes('opacity 0.5s')) {
                app.style.transition = '';
            }
        }, 500);
    }
});

onUnmounted(() => {
    const app = document.getElementById('app');
    if (app) {
        app.style.opacity = '';
        app.style.filter = '';
        app.style.clipPath = '';
        app.style.transform = '';
        app.style.transformOrigin = '';
        app.style.transition = '';
    }
    document.body.style.backgroundColor = '';
});
</script>

<template>
    <Head :title="pageTitle">
        <!-- Standard meta tags -->
        <meta name="description" :content="pageDescription" />

        <!-- Open Graph / Facebook (Meta) -->
        <meta property="og:url" :content="ogUrl" />
        <meta property="og:title" :content="ogTitle" />
        <meta property="og:description" :content="pageDescription" />
        <meta property="og:image" :content="ogImage" />

        <!-- Twitter (X) -->
        <meta property="twitter:url" :content="ogUrl" />
        <meta property="twitter:title" :content="ogTitle" />
        <meta property="twitter:description" :content="pageDescription" />
        <meta property="twitter:image" :content="ogImage" />
    </Head>

    <div
        class="min-h-screen overflow-x-hidden bg-white pt-32 pb-20 text-neutral-900 transition-all duration-500 ease-out md:pt-40 md:pb-0 dark:bg-black dark:text-white"
    >
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
                    <Link href="/portfolio">
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
                            <span> {{ t('caseStudy.backToPortfolio') }} </span>
                        </button>
                    </Link>
                </div>

                <!-- Hamburger Menu (Top Right) -->
                <div class="pointer-events-auto flex items-center gap-4">
                    <CommandTrigger @click="openCommandMenu" />
                </div>
            </div>
        </header>

        <main>
            <div class="space-y-12">
                <div class="mx-auto mb-22 flex w-full max-w-7xl flex-col items-start gap-4 space-y-4 px-6 md:mb-32 md:grid md:grid-cols-12 lg:px-8">
                    <div class="col-span-12 flex items-center gap-4 md:col-span-3 md:w-auto md:py-0">
                        <h3
                            class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-4 py-2 text-xs text-neutral-600 transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-white/10 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                        >
                            {{ project?.category }}
                        </h3>
                    </div>

                    <div class="col-span-12 flex w-full flex-col items-start gap-4 md:col-span-9">
                        <PageTitle>
                            {{ project?.client }}
                        </PageTitle>

                        <LabelText as="h2">
                            {{ project?.title }}
                        </LabelText>
                    </div>
                </div>

                <!-- Desktop: Swiper Slider -->
                <div class="gallery-slider-container relative hidden w-full md:w-screen lg:block">
                    <Swiper
                        :slides-per-view="'auto'"
                        :space-between="24"
                        :grabCursor="true"
                        :mousewheel="{
                            forceToAxis: true,
                            sensitivity: 1,
                            thresholdDelta: 5,
                            thresholdTime: 100,
                        }"
                        :loop="true"
                        :modules="[Mousewheel]"
                    >
                        <SwiperSlide
                            v-for="(media, index) in sliderMedia"
                            :key="media.uniqueKey"
                            :class="getAspectRatio(media) === '16/9' || getAspectRatio(media) === 'video' ? '!w-[600px] md:!w-[800px]' : ''"
                            :style="
                                getAspectRatio(media) !== '16/9' && getAspectRatio(media) !== 'video'
                                    ? {
                                          '--media-width-mobile': `${getMediaWidth(media, 600)}px`,
                                          '--media-width-desktop': `${getMediaWidth(media, 800)}px`,
                                          width: 'var(--media-width-mobile)',
                                      }
                                    : {}
                            "
                        >
                            <div
                                :class="[
                                    'w-full cursor-pointer overflow-hidden rounded-2xl bg-neutral-100 transition-transform duration-300 hover:scale-[1.02] dark:bg-neutral-900',
                                    getAspectRatio(media) === 'video' || getAspectRatio(media) === '16/9'
                                        ? 'aspect-video'
                                        : `aspect-[${getAspectRatio(media)}]`,
                                ]"
                                @click="openDrawerAtImage(media.originalIndex)"
                            >
                                <img
                                    v-if="media.type === 'image'"
                                    :src="media.src"
                                    :alt="(media as any).alt || `${project?.title} - Gallery ${index + 1}`"
                                    class="h-full w-full object-cover"
                                />
                                <video
                                    v-else-if="media.type === 'video'"
                                    :src="media.src"
                                    :poster="(media as any).thumbnail"
                                    :aria-label="(media as any).alt || `${project?.title} - Gallery ${index + 1}`"
                                    class="h-full w-full object-cover"
                                    muted
                                    loop
                                    autoplay
                                    playsinline
                                />
                            </div>
                        </SwiperSlide>
                    </Swiper>
                </div>

                <!-- Mobile: Gallery Card -->
                <div class="block px-6 lg:hidden">
                    <div
                        class="cursor-pointer overflow-hidden rounded-3xl bg-neutral-50 p-4 shadow-[0_0_20px_rgba(0,0,0,0.12)] transition-transform active:scale-[0.98] md:p-6 dark:bg-neutral-900"
                        @click="openDrawerAtImage(0)"
                    >
                        <div class="grid grid-cols-2 gap-4 md:gap-6">
                            <!-- First Media (Full Width) -->
                            <div
                                v-if="galleryMedia[0]"
                                :class="[
                                    'col-span-2 cursor-pointer overflow-hidden rounded-2xl bg-neutral-200 dark:bg-neutral-800',
                                    getAspectRatio(galleryMedia[0]) === 'video' || getAspectRatio(galleryMedia[0]) === '16/9'
                                        ? 'aspect-video'
                                        : `aspect-[${getAspectRatio(galleryMedia[0])}]`,
                                ]"
                                @click.stop="openDrawerAtImage(0)"
                            >
                                <img
                                    v-if="galleryMedia[0].type === 'image'"
                                    :src="galleryMedia[0].src"
                                    class="h-full w-full object-cover"
                                    :alt="(galleryMedia[0] as any).alt || `${project?.title} - Gallery 1`"
                                />
                                <video
                                    v-else-if="galleryMedia[0].type === 'video'"
                                    :src="galleryMedia[0].src"
                                    :poster="(galleryMedia[0] as any).thumbnail"
                                    :aria-label="(galleryMedia[0] as any).alt || `${project?.title} - Gallery 1`"
                                    class="h-full w-full object-cover"
                                    muted
                                    loop
                                    autoplay
                                    playsinline
                                />
                            </div>
                            <!-- Second Media -->
                            <div
                                v-if="galleryMedia[1]"
                                :class="[
                                    'col-span-1 cursor-pointer overflow-hidden rounded-2xl bg-neutral-200 dark:bg-neutral-800',
                                    getAspectRatio(galleryMedia[1]) === 'video' || getAspectRatio(galleryMedia[1]) === '16/9'
                                        ? 'aspect-video'
                                        : `aspect-[${getAspectRatio(galleryMedia[1])}]`,
                                ]"
                                @click.stop="openDrawerAtImage(1)"
                            >
                                <img
                                    v-if="galleryMedia[1].type === 'image'"
                                    :src="galleryMedia[1].src"
                                    class="h-full w-full object-cover"
                                    :alt="(galleryMedia[1] as any).alt || `${project?.title} - Gallery 2`"
                                />
                                <video
                                    v-else-if="galleryMedia[1].type === 'video'"
                                    :src="galleryMedia[1].src"
                                    :poster="(galleryMedia[1] as any).thumbnail"
                                    :aria-label="(galleryMedia[1] as any).alt || `${project?.title} - Gallery 2`"
                                    class="h-full w-full object-cover"
                                    muted
                                    loop
                                    autoplay
                                    playsinline
                                />
                            </div>
                            <!-- Third Media -->
                            <div
                                v-if="galleryMedia[2]"
                                :class="[
                                    'col-span-1 cursor-pointer overflow-hidden rounded-2xl bg-neutral-200 dark:bg-neutral-800',
                                    getAspectRatio(galleryMedia[2]) === 'video' || getAspectRatio(galleryMedia[2]) === '16/9'
                                        ? 'aspect-video'
                                        : `aspect-[${getAspectRatio(galleryMedia[2])}]`,
                                ]"
                                @click.stop="openDrawerAtImage(2)"
                            >
                                <img
                                    v-if="galleryMedia[2].type === 'image'"
                                    :src="galleryMedia[2].src"
                                    class="h-full w-full object-cover"
                                    :alt="(galleryMedia[2] as any).alt || `${project?.title} - Gallery 3`"
                                />
                                <video
                                    v-else-if="galleryMedia[2].type === 'video'"
                                    :src="galleryMedia[2].src"
                                    :poster="(galleryMedia[2] as any).thumbnail"
                                    :aria-label="(galleryMedia[2] as any).alt || `${project?.title} - Gallery 3`"
                                    class="h-full w-full object-cover"
                                    muted
                                    loop
                                    autoplay
                                    playsinline
                                />
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between px-2 md:mt-6 md:px-4">
                            <span class="text-xs font-medium text-neutral-500 dark:text-white/50">{{ t('caseStudy.exploreGallery') }}</span>
                            <FontAwesomeIcon
                                icon="fa-sharp fa-light fa-arrow-up-right-and-arrow-down-left-from-center"
                                class="text-neutral-500 dark:text-white/50"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-16 flex w-full max-w-7xl flex-col items-start gap-4 space-y-4 px-6 md:mt-32 md:grid md:grid-cols-12 lg:px-8">
                <div class="col-span-12 flex items-center gap-4 md:col-span-3 md:w-auto md:py-0">
                    <div class="space-y-3 md:max-w-48">
                        <SectionTitle as="h4">{{ t('caseStudy.technologies') }}</SectionTitle>
                        <ul class="space-y-2">
                            <li class="relative">
                                <BodyText size="xs"> {{ project?.technologies?.join(', ') }} </BodyText>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-span-12 flex w-full flex-col items-start gap-4 md:col-span-9">
                    <span class="max-w-[650px] font-work-sans text-3xl font-bold text-neutral-900 dark:text-white">
                        <TextReveal
                            :scroll-trigger="true"
                            :scrub="1"
                            start="top 80%"
                            end="+=400"
                            :markers="false"
                            :text="project?.story_preview?.substring(0, 200) + '...'"
                        />
                    </span>

                    <Drawer v-model:open="isStoryDrawerOpen">
                        <DrawerTrigger as-child>
                            <PrimaryButton class="mt-4">
                                {{ t('caseStudy.readFullStory') }}
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 640 640"
                                    class="inline size-4 fill-neutral-500 transition-colors group-hover:fill-neutral-900 dark:fill-white/50 dark:group-hover:fill-white"
                                >
                                    <path
                                        d="M356.7 260.7L345.4 272L368 294.6L379.3 283.3L544 118.6L544 240L576 240L576 64L400 64L400 96L521.4 96L356.7 260.7z"
                                        class="transition-transform duration-300 ease-out group-hover:translate-x-10 group-hover:-translate-y-10"
                                    />
                                    <path
                                        d="M283.3 379.3L294.6 368L272 345.4L260.7 356.7L96 521.4L96 400L64 400L64 576L240 576L240 544L118.6 544L283.3 379.3z"
                                        class="transition-transform duration-300 ease-out group-hover:-translate-x-10 group-hover:translate-y-10"
                                    />
                                </svg>
                            </PrimaryButton>
                        </DrawerTrigger>
                        <DrawerContent class="outline-none">
                            <DrawerHeader class="sr-only">
                                <DrawerTitle>{{ project?.client }}</DrawerTitle>
                                <DrawerDescription> {{ project?.title }} — {{ t('caseStudy.fullStory') }} </DrawerDescription>
                            </DrawerHeader>
                            <DrawerClose
                                as-child
                                class="group absolute top-6 right-6 block rounded-xs opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground touch:hidden [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
                            >
                                <lord-icon
                                    src="/icons/system-regular-29-cross-hover-cross-3.json"
                                    trigger="hover"
                                    target=".drawer-close"
                                    class="current-color size-8 opacity-60 group-hover:opacity-100"
                                >
                                </lord-icon>
                                <span class="sr-only">{{ t('caseStudy.close') }}</span>
                            </DrawerClose>
                            <div class="mx-auto w-full max-w-[650px] overflow-y-auto px-6 py-12 touch:pt-0">
                                <MarkdownContent :content="project?.story || ''" class="space-y-6" />
                            </div>
                        </DrawerContent>
                    </Drawer>
                </div>
            </div>

            <div class="mx-auto mt-16 max-w-7xl px-6 py-16 md:py-16 lg:px-8">
                <div class="card-outline-1"></div>
                <div class="card-outline-2"></div>
                <div
                    class="flex min-h-[320px] flex-1 flex-col justify-between rounded-3xl border border-neutral-200 bg-neutral-50 p-8 md:p-12 dark:border-white/5 dark:bg-[#111]"
                >
                    <div class="grid grid-cols-1 gap-6 space-y-6 md:grid-cols-2 md:gap-12">
                        <div class="grid w-full grid-cols-1 gap-y-8 md:grid-cols-12 md:gap-y-12">
                            <!-- Team -->
                            <div class="col-span-12 flex flex-col gap-4 md:col-span-12 md:flex-row md:items-start md:gap-12">
                                <LabelText size="xs" weight="semibold" class="w-24">{{ t('caseStudy.team') }}</LabelText>
                                <div class="flex -space-x-3">
                                    <TooltipProvider>
                                        <Tooltip v-for="(member, index) in project?.team" :key="index">
                                            <TooltipTrigger>
                                                <div class="h-10 w-10 overflow-hidden rounded-full border-2 border-neutral-50 dark:border-[#111]">
                                                    <img :src="member.src" :alt="member.name" class="h-full w-full object-cover" />
                                                </div>
                                            </TooltipTrigger>
                                            <TooltipContent>
                                                <p>{{ member.name }}</p>
                                            </TooltipContent>
                                        </Tooltip>
                                    </TooltipProvider>
                                </div>
                            </div>

                            <!-- Services -->
                            <div class="col-span-12 flex flex-col gap-4 md:col-span-12 md:flex-row md:items-start md:gap-12">
                                <LabelText size="xs" weight="semibold" class="w-24">{{ t('caseStudy.tools') }}</LabelText>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(service, index) in project?.services"
                                        :key="index"
                                        class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-4 py-2 text-xs text-neutral-600 transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-white/10 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                                    >
                                        {{ service }}
                                    </span>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="col-span-12 flex flex-col gap-4 md:col-span-12 md:flex-row md:items-start md:gap-12">
                                <LabelText size="xs" weight="semibold" class="w-24">{{ t('caseStudy.date') }}</LabelText>
                                <SectionTitle as="span">{{ project?.date }}</SectionTitle>
                            </div>

                            <!-- Website -->
                            <div
                                v-if="project?.website"
                                class="col-span-12 -mt-1 flex flex-col gap-4 md:col-span-12 md:flex-row md:items-start md:gap-12"
                            >
                                <LabelText size="xs" weight="semibold" class="w-24">{{ t('caseStudy.website') }}</LabelText>
                                <a :href="project.website" target="_blank" rel="noopener noreferrer" class="group -mt-0.5 flex items-center gap-2">
                                    <SectionTitle as="span" class="transition-colors group-hover:text-neutral-500 dark:group-hover:text-neutral-400">
                                        {{ formatWebsiteUrl(project.website) }}
                                    </SectionTitle>
                                    <FontAwesomeIcon
                                        icon="fa-sharp fa-light fa-arrow-up-right"
                                        class="text-neutral-900 transition-all duration-300 ease-out group-hover:translate-x-1 group-hover:-translate-y-1 group-hover:text-neutral-500 dark:text-white dark:group-hover:text-neutral-400"
                                    />
                                </a>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <SectionTitle as="h3">{{ t('caseStudy.fineprint') }}</SectionTitle>
                            <BodyText>
                                {{ project?.fineprint }}
                            </BodyText>
                        </div>
                    </div>
                    <div
                        class="mt-10 aspect-video w-full translate-y-0 cursor-pointer overflow-hidden rounded-2xl bg-neutral-100 transition-transform duration-300 hover:translate-y-1 hover:scale-[1.02] dark:bg-neutral-900"
                        @click="fineprintToggler = !fineprintToggler"
                    >
                        <img :src="project?.fineprint_media" :alt="project?.fineprint_media_alt" class="h-full w-full object-cover" />
                        <FsLightbox :toggler="fineprintToggler" :sources="[project?.fineprint_media]" />
                    </div>
                </div>
            </div>

            <div class="px-0 lg:px-8">
                <ProjectSlider :current-slug="slug" />
            </div>

            <div class="mx-auto -mt-[50px] max-w-7xl px-6 lg:px-8">
                <FooterArea />
            </div>
        </main>
    </div>

    <!-- Shared Drawer -->
    <Drawer v-model:open="isDrawerOpen">
        <DrawerContent class="outline-none">
            <DrawerHeader class="sr-only">
                <DrawerTitle>{{ project?.client }}</DrawerTitle>
                <DrawerDescription>{{ project?.title }} {{ t('caseStudy.gallery') }}</DrawerDescription>
            </DrawerHeader>
            <DrawerClose
                as-child
                class="group absolute top-6 right-6 block rounded-xs opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground touch:hidden [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
            >
                <lord-icon
                    src="/icons/system-regular-29-cross-hover-cross-3.json"
                    trigger="hover"
                    target=".drawer-close"
                    class="current-color size-8 opacity-60 group-hover:opacity-100"
                >
                </lord-icon>
                <span class="sr-only">Close</span>
            </DrawerClose>
            <div ref="drawerContentRef" class="mx-auto w-full max-w-7xl overflow-y-auto px-6 py-12 touch:pt-0">
                <!-- Gallery Grid -->
                <div class="mb-0 grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 touch:mb-[14px]">
                    <!-- 1. Full Width -->
                    <div v-if="galleryMedia[0]" class="col-span-1 md:col-span-2">
                        <div
                            data-image-index="0"
                            :class="[
                                'w-full overflow-hidden rounded-xl bg-neutral-100 dark:bg-neutral-800',
                                getAspectRatio(galleryMedia[0]) === 'video' || getAspectRatio(galleryMedia[0]) === '16/9'
                                    ? 'aspect-video'
                                    : `aspect-[${getAspectRatio(galleryMedia[0])}]`,
                            ]"
                        >
                            <img
                                v-if="galleryMedia[0].type === 'image'"
                                :src="galleryMedia[0].src"
                                class="h-full w-full object-cover"
                                :alt="(galleryMedia[0] as any).alt || `${project?.title} - Gallery 1`"
                            />
                            <video
                                v-else-if="galleryMedia[0].type === 'video'"
                                :src="galleryMedia[0].src"
                                :poster="(galleryMedia[0] as any).thumbnail"
                                :aria-label="(galleryMedia[0] as any).alt || `${project?.title} - Gallery 1`"
                                class="h-full w-full object-cover"
                                muted
                                loop
                                autoplay
                                playsinline
                            />
                        </div>
                    </div>

                    <!-- 2. Grid Col 2 (Desktop) / 1 (Mobile) -->
                    <div v-if="galleryMedia[1]" class="col-span-1">
                        <div
                            data-image-index="1"
                            :class="[
                                'w-full overflow-hidden rounded-xl bg-neutral-100 dark:bg-neutral-800',
                                getAspectRatio(galleryMedia[1]) === 'video' || getAspectRatio(galleryMedia[1]) === '16/9'
                                    ? 'aspect-video'
                                    : `aspect-[${getAspectRatio(galleryMedia[1])}]`,
                            ]"
                        >
                            <img
                                v-if="galleryMedia[1].type === 'image'"
                                :src="galleryMedia[1].src"
                                class="h-full w-full object-cover"
                                :alt="(galleryMedia[1] as any).alt || `${project?.title} - Gallery 2`"
                            />
                            <video
                                v-else-if="galleryMedia[1].type === 'video'"
                                :src="galleryMedia[1].src"
                                :poster="(galleryMedia[1] as any).thumbnail"
                                :aria-label="(galleryMedia[1] as any).alt || `${project?.title} - Gallery 2`"
                                class="h-full w-full object-cover"
                                muted
                                loop
                                autoplay
                                playsinline
                            />
                        </div>
                    </div>
                    <div v-if="galleryMedia[2]" class="col-span-1">
                        <div
                            data-image-index="2"
                            :class="[
                                'w-full overflow-hidden rounded-xl bg-neutral-100 dark:bg-neutral-800',
                                getAspectRatio(galleryMedia[2]) === 'video' || getAspectRatio(galleryMedia[2]) === '16/9'
                                    ? 'aspect-video'
                                    : `aspect-[${getAspectRatio(galleryMedia[2])}]`,
                            ]"
                        >
                            <img
                                v-if="galleryMedia[2].type === 'image'"
                                :src="galleryMedia[2].src"
                                class="h-full w-full object-cover"
                                :alt="(galleryMedia[2] as any).alt || `${project?.title} - Gallery 3`"
                            />
                            <video
                                v-else-if="galleryMedia[2].type === 'video'"
                                :src="galleryMedia[2].src"
                                :poster="(galleryMedia[2] as any).thumbnail"
                                :aria-label="(galleryMedia[2] as any).alt || `${project?.title} - Gallery 3`"
                                class="h-full w-full object-cover"
                                muted
                                loop
                                autoplay
                                playsinline
                            />
                        </div>
                    </div>

                    <!-- 3. Full Width -->
                    <div v-if="galleryMedia[3]" class="col-span-1 md:col-span-2">
                        <div
                            data-image-index="3"
                            :class="[
                                'w-full overflow-hidden rounded-xl bg-neutral-100 dark:bg-neutral-800',
                                getAspectRatio(galleryMedia[3]) === 'video' || getAspectRatio(galleryMedia[3]) === '16/9'
                                    ? 'aspect-video'
                                    : `aspect-[${getAspectRatio(galleryMedia[3])}]`,
                            ]"
                        >
                            <img
                                v-if="galleryMedia[3].type === 'image'"
                                :src="galleryMedia[3].src"
                                class="h-full w-full object-cover"
                                :alt="(galleryMedia[3] as any).alt || `${project?.title} - Gallery 4`"
                            />
                            <video
                                v-else-if="galleryMedia[3].type === 'video'"
                                :src="galleryMedia[3].src"
                                :poster="(galleryMedia[3] as any).thumbnail"
                                :aria-label="(galleryMedia[3] as any).alt || `${project?.title} - Gallery 4`"
                                class="h-full w-full object-cover"
                                muted
                                loop
                                autoplay
                                playsinline
                            />
                        </div>
                    </div>

                    <!-- 4. Grid Col 2 (Desktop) / 1 (Mobile) -->
                    <div v-if="galleryMedia[4]" class="col-span-1">
                        <div
                            data-image-index="4"
                            :class="[
                                'w-full overflow-hidden rounded-xl bg-neutral-100 dark:bg-neutral-800',
                                getAspectRatio(galleryMedia[4]) === 'video' || getAspectRatio(galleryMedia[4]) === '16/9'
                                    ? 'aspect-video'
                                    : `aspect-[${getAspectRatio(galleryMedia[4])}]`,
                            ]"
                        >
                            <img
                                v-if="galleryMedia[4].type === 'image'"
                                :src="galleryMedia[4].src"
                                class="h-full w-full object-cover"
                                :alt="(galleryMedia[4] as any).alt || `${project?.title} - Gallery 5`"
                            />
                            <video
                                v-else-if="galleryMedia[4].type === 'video'"
                                :src="galleryMedia[4].src"
                                :poster="(galleryMedia[4] as any).thumbnail"
                                :aria-label="(galleryMedia[4] as any).alt || `${project?.title} - Gallery 5`"
                                class="h-full w-full object-cover"
                                muted
                                loop
                                autoplay
                                playsinline
                            />
                        </div>
                    </div>
                    <div v-if="galleryMedia[5]" class="col-span-1">
                        <div
                            data-image-index="5"
                            :class="[
                                'w-full overflow-hidden rounded-xl bg-neutral-100 dark:bg-neutral-800',
                                getAspectRatio(galleryMedia[5]) === 'video' || getAspectRatio(galleryMedia[5]) === '16/9'
                                    ? 'aspect-video'
                                    : `aspect-[${getAspectRatio(galleryMedia[5])}]`,
                            ]"
                        >
                            <img
                                v-if="galleryMedia[5].type === 'image'"
                                :src="galleryMedia[5].src"
                                class="h-full w-full object-cover"
                                :alt="(galleryMedia[5] as any).alt || `${project?.title} - Gallery 6`"
                            />
                            <video
                                v-else-if="galleryMedia[5].type === 'video'"
                                :src="galleryMedia[5].src"
                                :poster="(galleryMedia[5] as any).thumbnail"
                                :aria-label="(galleryMedia[5] as any).alt || `${project?.title} - Gallery 6`"
                                class="h-full w-full object-cover"
                                muted
                                loop
                                autoplay
                                playsinline
                            />
                        </div>
                    </div>
                </div>
            </div>
        </DrawerContent>
    </Drawer>
</template>

<style scoped>
.gallery-slider-container {
    margin-left: calc((100vw - min(100vw, 76rem)) / 2 + 2rem);
}

.swiper {
    overflow: visible !important;
}

@media (min-width: 768px) {
    .gallery-slider-container :deep(.swiper-slide[style*='--media-width-desktop']) {
        width: var(--media-width-desktop) !important;
    }
}

@media (min-width: 1310px) {
    .gallery-slider-container {
        margin-left: calc((100vw - min(100vw, 76rem)) / 2);
    }
}
</style>
