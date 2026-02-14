<script setup lang="ts">
import CommandTrigger from '@/components/CommandTrigger.vue';
import FooterArea from '@/components/FooterArea.vue';
import BodyText from '@/components/global/typography/BodyText.vue';
import LabelText from '@/components/global/typography/LabelText.vue';
import PageTitle from '@/components/global/typography/PageTitle.vue';
import SectionTitle from '@/components/global/typography/SectionTitle.vue';
import ProgressiveBlur from '@/components/ProgressiveBlur.vue';
import SkillsModal from '@/components/SkillsModal.vue';
import { useCommandMenu } from '@/composables/useCommandMenu';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faArrowsFromLine, faArrowsToLine, faMinus, faPlus, faTimes } from '@fortawesome/sharp-light-svg-icons';
import { faDot } from '@fortawesome/sharp-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

library.add(faMinus, faPlus, faTimes, faArrowsToLine, faArrowsFromLine, faDot);

const props = defineProps<{
    isOverlay?: boolean;
}>();

defineEmits(['close']);

const { t, tm, locale } = useI18n();
const { open: openCommandMenu } = useCommandMenu();

const pageTitle = 'CV – Daniel Schmier';
const pageDescription = 'Design Engineer specializing in full-stack development, product design, and modern web technologies.';
const ogTitle = 'CV – Daniel Schmier';

const ogUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});

interface TimelineItem {
    date?: string;
    title?: string;
    content: string;
    company?: string;
    companyLogo?: string;
    list?: string[];
}

type ViewMode = 'prose' | 'list' | 'timeline';

const viewMode = ref<ViewMode>('prose');
const activeSection = ref('bio');
const isSkillsModalOpen = ref(false);

const openSkillsModal = () => {
    isSkillsModalOpen.value = true;
};

const closeSkillsModal = () => {
    isSkillsModalOpen.value = false;
};

const scrollToSection = (id: string) => {
    const element = document.getElementById(id);
    if (element) {
        const headerOffset = 100;

        if (props.isOverlay) {
            const container = document.getElementById('overlay-container');
            if (container) {
                const elementPosition = element.getBoundingClientRect().top;
                const containerPosition = container.getBoundingClientRect().top;
                const offsetPosition = elementPosition - containerPosition + container.scrollTop - headerOffset;

                container.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth',
                });
            }
        } else {
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.scrollY - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth',
            });
        }
    }
};

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    activeSection.value = entry.target.id;
                }
            });
        },
        {
            rootMargin: '-40% 0px -40% 0px',
            threshold: 0,
        },
    );

    ['bio', 'skills', 'approach'].forEach((id) => {
        const element = document.getElementById(id);
        if (element) observer.observe(element);
    });
});

onUnmounted(() => {});

const lengthLevel = ref(1);
const selectedCompany = ref<string | null>(null);

const bioData = computed(() => ({
    prose: tm('bio.prose') as string[],
    list: tm('bio.list') as string[][],
    timeline: tm('bio.timeline') as TimelineItem[],
    approach: tm('bio.approach.items') as { title: string; content: string }[],
}));

const maxLengthLevels = computed(() => {
    if (viewMode.value === 'prose') {
        return bioData.value.prose.length;
    }
    if (viewMode.value === 'list') {
        return bioData.value.list.length;
    }
    if (viewMode.value === 'timeline') {
        return bioData.value.timeline.length;
    }
    return 0;
});

const clampedLengthLevel = computed(() => {
    const max = maxLengthLevels.value;
    return Math.max(1, Math.min(lengthLevel.value, max));
});

const displayedProse = computed(() => {
    if (viewMode.value !== 'prose') return [];
    const level = clampedLengthLevel.value;
    const paragraph = bioData.value.prose[level - 1];
    return paragraph ? [paragraph] : [];
});

const displayedList = computed(() => {
    if (viewMode.value !== 'list') return [];
    const level = clampedLengthLevel.value;
    const listVersion = bioData.value.list[level - 1];
    return Array.isArray(listVersion) ? listVersion : [];
});

const timelineCompanies = computed(() => {
    const companies = new Map<string, { name: string; logo?: string }>();
    bioData.value.timeline.forEach((item) => {
        if (item.company) {
            companies.set(item.company, {
                name: item.company,
                logo: item.companyLogo,
            });
        }
    });
    return Array.from(companies.values());
});

const expandedItems = ref(new Set<string>());

const getItemKey = (item: TimelineItem) => {
    return `${item.company || ''}-${item.title || ''}-${item.date || ''}`;
};

const toggleExpanded = (item: TimelineItem) => {
    const key = getItemKey(item);
    if (expandedItems.value.has(key)) {
        expandedItems.value.delete(key);
    } else {
        expandedItems.value.add(key);
    }
};

const isExpanded = (item: TimelineItem) => {
    return expandedItems.value.has(getItemKey(item));
};

onMounted(() => {
    if (displayedTimeline.value.length > 0) {
        expandedItems.value.add(getItemKey(displayedTimeline.value[0]));
    }
});

const displayedTimeline = computed(() => {
    if (viewMode.value !== 'timeline') return [];

    let items = [...bioData.value.timeline];

    if (selectedCompany.value) {
        items = items.filter((item) => item.company === selectedCompany.value);
    }

    const sorted = items.sort((a, b) => {
        const dateA = a.date ? new Date(a.date).getTime() : 0;
        const dateB = b.date ? new Date(b.date).getTime() : 0;
        return dateB - dateA;
    });

    return sorted;
});

const hasAutoExpanded = ref(false);

watch(
    displayedTimeline,
    (newVal) => {
        if (!hasAutoExpanded.value && newVal.length > 0) {
            expandedItems.value.add(getItemKey(newVal[0]));
            hasAutoExpanded.value = true;
        }
    },
    { immediate: true },
);

const increaseLength = () => {
    if (lengthLevel.value < maxLengthLevels.value) {
        lengthLevel.value++;
    }
};

const decreaseLength = () => {
    if (lengthLevel.value > 1) {
        lengthLevel.value--;
    }
};

const setViewMode = (mode: ViewMode) => {
    viewMode.value = mode;
    lengthLevel.value = 1;
    if (mode !== 'timeline') {
        selectedCompany.value = null;
    }
};

const toggleCompanyFilter = (company: string) => {
    if (selectedCompany.value === company) {
        selectedCompany.value = null;
    } else {
        selectedCompany.value = company;
    }
};

const getDateLocale = (): string => {
    const localeMap: Record<string, string> = {
        en: 'en-US',
        de: 'de-DE',
    };
    return localeMap[locale.value as string] || 'en-US';
};

const formatDate = (dateString?: string): string => {
    if (!dateString) return '';

    if (dateString.includes(' - ')) {
        const [startDate, endDate] = dateString.split(' - ').map((d) => d.trim());
        const formattedStart = formatSingleDate(startDate);

        if (endDate.toLowerCase() === 'present') {
            return `${formattedStart} - ${t('bio.present', 'Present')}`;
        }

        const formattedEnd = formatSingleDate(endDate);
        return `${formattedStart} - ${formattedEnd}`;
    }

    return formatSingleDate(dateString);
};

const formatSingleDate = (dateString: string): string => {
    const dateLocale = getDateLocale();

    if (/^\d{4}$/.test(dateString.trim())) {
        return dateString.trim();
    }

    try {
        const date = new Date(dateString);
        if (!isNaN(date.getTime())) {
            return date.toLocaleDateString(dateLocale, {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        }
    } catch {}

    return dateString;
};

const skills = computed(() => ({
    designTools: ['Figma', 'Adobe CC', 'Photoshop', 'Illustrator', 'InDesign', 'After Effects', 'Premiere Pro'],
    codeLanguages: ['HTML', 'CSS', 'JavaScript', 'PHP', 'Liquid'],
    technologies: [
        {
            category: t('bio.skills.categories.backend'),
            items: ['PHP: Laravel, Livewire, Filament, Nova, Jetstream, Cashier, Spark, Inertia', 'JS: Nuxt', 'Shopify: Liquid, Hydrogen, Oxygen'],
        },
        {
            category: t('bio.skills.categories.frontend'),
            items: ['JS: Vue.js, Alpine.js', 'CSS: Tailwind, Bootstrap'],
        },
        {
            category: t('bio.skills.categories.devOps'),
            items: ['CLI, Git, CI/CD, AWS, Docker, S3, EC2, Nginx, Node.js, Envoyer, Forge'],
        },
        {
            category: t('bio.skills.categories.databases'),
            items: ['MySQL, SQLite, GraphQL'],
        },
        {
            category: t('bio.skills.categories.misc'),
            items: ['Homebrew, Prompt Engineering, Agents, MCP, Cursor, OpenAI API'],
        },
        {
            category: t('bio.skills.categories.analytics'),
            items: [
                'Google Analytics, Google Tag Manager, Google Search Console, Google Lighthouse, PageSpeed Insights, Conversions API, Microsoft Clarity, Meta Pixel, Hotjar',
            ],
        },
    ],
}));
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
        <meta name="twitter:url" :content="ogUrl" />
        <meta name="twitter:title" :content="ogTitle" />
        <meta name="twitter:description" :content="pageDescription" />
    </Head>
    <div class="min-h-screen bg-white pt-32 pb-20 text-neutral-900 md:pt-40 md:pb-0 dark:bg-black dark:text-white">
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
                    <button
                        @click="scrollToSection('bio')"
                        class="group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium transition-all duration-300"
                        :class="
                            activeSection === 'bio'
                                ? 'bg-neutral-900 text-white shadow-lg dark:bg-white dark:text-black'
                                : 'text-neutral-500 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white'
                        "
                    >
                        <span>{{ t('bio.sections.bio') }}</span>
                    </button>
                    <button
                        @click="scrollToSection('skills')"
                        class="group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium transition-all duration-300"
                        :class="
                            activeSection === 'skills'
                                ? 'bg-neutral-900 text-white shadow-lg dark:bg-white dark:text-black'
                                : 'text-neutral-500 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white'
                        "
                    >
                        <span>{{ t('bio.sections.skills') }}</span>
                    </button>
                    <button
                        @click="scrollToSection('approach')"
                        class="group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium transition-all duration-300"
                        :class="
                            activeSection === 'approach'
                                ? 'bg-neutral-900 text-white shadow-lg dark:bg-white dark:text-black'
                                : 'text-neutral-500 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white'
                        "
                    >
                        <span>{{ t('bio.approach.title') }}</span>
                    </button>
                </div>

                <!-- Command Menu (Top Right) -->
                <div class="pointer-events-auto flex items-center gap-4">
                    <CommandTrigger @click="openCommandMenu" />
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 lg:px-8">
            <!--<ProgressiveBlur position="bottom" class="fixed right-0 bottom-[-128px] left-0 z-20 hidden h-64 md:block" />-->
            <div id="bio" class="mx-auto flex flex-col items-start gap-4 md:grid md:grid-cols-12">
                <div
                    class="relative top-0 z-10 col-span-12 -ml-4 w-[calc(100%+2rem)] px-4 pt-4 pb-0 md:sticky md:top-32 md:col-span-3 md:w-auto md:py-0"
                >
                    <PageTitle as="h1" class="sr-only"> {{ t('bio.title') }} </PageTitle>
                    <PageTitle as="h2" class="scramble-trigger relative pb-4"> {{ t('bio.sections.bio') }} </PageTitle>
                </div>
                <div class="col-span-12 w-full md:col-span-9">
                    <nav class="mb-6 flex h-16 flex-row items-center pb-8">
                        <div class="flex items-center gap-4 md:gap-6">
                            <button
                                @click="setViewMode('prose')"
                                :class="[
                                    'cursor-pointer text-sm font-medium transition-colors',
                                    viewMode === 'prose'
                                        ? 'text-neutral-900 dark:text-white'
                                        : 'text-neutral-400 hover:text-neutral-900 dark:text-white/30 dark:hover:text-white',
                                ]"
                            >
                                {{ t('bio.modes.story') }}
                            </button>
                            <button
                                @click="setViewMode('list')"
                                :class="[
                                    'cursor-pointer text-sm font-medium transition-colors',
                                    viewMode === 'list'
                                        ? 'text-neutral-900 dark:text-white'
                                        : 'text-neutral-400 hover:text-neutral-900 dark:text-white/30 dark:hover:text-white',
                                ]"
                            >
                                {{ t('bio.modes.list') }}
                            </button>
                            <button
                                @click="setViewMode('timeline')"
                                :class="[
                                    'cursor-pointer text-sm font-medium transition-colors',
                                    viewMode === 'timeline'
                                        ? 'text-neutral-900 dark:text-white'
                                        : 'text-neutral-400 hover:text-neutral-900 dark:text-white/30 dark:hover:text-white',
                                ]"
                            >
                                {{ t('bio.modes.timeline') }}
                            </button>
                        </div>

                        <Transition
                            mode="out-in"
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95"
                        >
                            <div
                                v-if="viewMode === 'timeline'"
                                key="timeline-filters"
                                class="mx-4 flex flex-wrap items-center gap-3 border-l border-neutral-200 pl-3 md:mx-6 md:gap-3 md:pl-6 dark:border-neutral-700"
                            >
                                <template v-if="timelineCompanies.length > 0">
                                    <button
                                        v-for="company in timelineCompanies"
                                        :key="company.name"
                                        @click="toggleCompanyFilter(company.name)"
                                        :class="[
                                            'flex size-10 items-center justify-center rounded-sm transition-all',
                                            selectedCompany === company.name
                                                ? 'bg-neutral-100 focus:ring-1 focus:ring-neutral-400 focus:ring-offset-2 focus:ring-offset-white dark:bg-neutral-800 dark:focus:ring-offset-black'
                                                : 'text-neutral-400 hover:text-neutral-600 dark:text-white/30 dark:hover:text-neutral-200',
                                        ]"
                                        :aria-label="t('bio.ariaLabels.filterByCompany', { company: company.name })"
                                    >
                                        <img
                                            v-if="company.logo"
                                            :src="company.logo"
                                            :alt="company.name"
                                            :class="[
                                                'size-6 object-contain invert-100 transition-all dark:invert-0',
                                                selectedCompany === company.name ? 'opacity-100' : 'cursor-pointer opacity-20 hover:opacity-100',
                                            ]"
                                        />
                                        <span v-else class="text-xs font-medium">{{ company.name.charAt(0) }}</span>
                                    </button>
                                </template>
                            </div>

                            <div
                                v-else
                                key="length-controls"
                                class="mx-4 flex items-center gap-0 border-l border-neutral-200 pl-4 md:mx-6 md:gap-3 md:pl-6 dark:border-neutral-700"
                            >
                                <span class="mr-3 text-sm font-medium text-neutral-400 dark:text-white/30">{{ t('bio.length') }}</span>
                                <button
                                    @click="decreaseLength"
                                    :disabled="clampedLengthLevel === 1"
                                    :class="[
                                        'flex size-8 items-center justify-center rounded-sm text-base leading-none transition-all md:size-10',
                                        clampedLengthLevel === 1
                                            ? 'cursor-not-allowed text-neutral-300 dark:text-white/30'
                                            : 'cursor-pointer text-neutral-900 hover:bg-neutral-100 hover:text-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:hover:text-white',
                                    ]"
                                    :aria-label="t('bio.ariaLabels.decreaseLength')"
                                >
                                    <FontAwesomeIcon icon="fa-sharp fa-light fa-minus" class="text-xs md:text-sm" />
                                </button>
                                <button
                                    @click="increaseLength"
                                    :disabled="lengthLevel >= maxLengthLevels"
                                    :class="[
                                        'flex size-8 items-center justify-center rounded-sm text-base leading-none transition-all md:size-10',
                                        lengthLevel >= maxLengthLevels
                                            ? 'cursor-not-allowed text-neutral-300 dark:text-white/30'
                                            : 'cursor-pointer text-neutral-900 hover:bg-neutral-100 hover:text-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:hover:text-white',
                                    ]"
                                    :aria-label="t('bio.ariaLabels.increaseLength')"
                                >
                                    <FontAwesomeIcon icon="fa-sharp fa-light fa-plus" class="text-xs md:text-sm" />
                                </button>
                            </div>
                        </Transition>
                    </nav>

                    <div class="bio-content">
                        <Transition
                            mode="out-in"
                            enter-active-class="transition-all duration-500 ease-out"
                            enter-from-class="opacity-0 translate-y-4"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition-all duration-300 ease-in"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 -translate-y-4"
                        >
                            <div v-if="viewMode === 'prose'" key="prose" class="relative">
                                <Transition
                                    mode="out-in"
                                    enter-active-class="transition-all duration-500 ease-out"
                                    enter-from-class="opacity-0 translate-y-4"
                                    enter-to-class="opacity-100 translate-y-0"
                                    leave-active-class="transition-all duration-300 ease-in"
                                    leave-from-class="opacity-100 translate-y-0"
                                    leave-to-class="opacity-0 -translate-y-4"
                                >
                                    <BodyText v-if="displayedProse.length > 0" :key="clampedLengthLevel">
                                        <span v-html="displayedProse[0]" />
                                    </BodyText>
                                </Transition>
                            </div>

                            <div v-else-if="viewMode === 'list'" key="list" class="relative">
                                <Transition
                                    mode="out-in"
                                    enter-active-class="transition-all duration-500 ease-out"
                                    enter-from-class="opacity-0 translate-y-4"
                                    enter-to-class="opacity-100 translate-y-0"
                                    leave-active-class="transition-all duration-300 ease-in"
                                    leave-from-class="opacity-100 translate-y-0"
                                    leave-to-class="opacity-0 -translate-y-4"
                                >
                                    <ul v-if="displayedList.length > 0" :key="clampedLengthLevel" class="list-none space-y-3 pl-0">
                                        <li v-for="(item, index) in displayedList" :key="index" class="relative pl-6">
                                            <font-awesome-icon icon="fa-sharp fa-solid fa-dot" class="absolute top-[3px] left-0 text-neutral-400" />
                                            <BodyText as="span">{{ item }}</BodyText>
                                        </li>
                                    </ul>
                                </Transition>
                            </div>

                            <div v-else-if="viewMode === 'timeline'" key="timeline" class="relative">
                                <div
                                    class="absolute top-2 bottom-0 left-6 w-px bg-gradient-to-b from-neutral-200 via-neutral-300 dark:via-neutral-800"
                                ></div>

                                <div class="space-y-10 md:space-y-16">
                                    <div v-for="(item, index) in displayedTimeline" :key="index" class="relative">
                                        <div class="absolute top-2 left-6 flex -translate-x-[45%] items-center justify-center">
                                            <div
                                                class="h-2 w-2 rounded-full border-2"
                                                :class="{
                                                    'border-neutral-900 bg-neutral-900 dark:border-white dark:bg-white': index === 0,
                                                    'border-neutral-300 bg-white dark:border-neutral-400 dark:bg-black': index !== 0,
                                                }"
                                            ></div>
                                        </div>

                                        <div class="ml-14">
                                            <div
                                                class="group mb-3 flex cursor-pointer items-start justify-between"
                                                @click="item.list && item.list.length > 0 ? toggleExpanded(item) : null"
                                                :class="{ 'cursor-default': !item.list || item.list.length === 0 }"
                                            >
                                                <div class="space-y-1">
                                                    <SectionTitle
                                                        v-if="item.title"
                                                        as="h3"
                                                        class="transition-colors"
                                                        :class="{
                                                            'group-hover:text-neutral-600 dark:group-hover:text-neutral-200':
                                                                item.list && item.list.length > 0,
                                                        }"
                                                    >
                                                        {{ item.title }}
                                                    </SectionTitle>
                                                    <div v-if="item.date" class="text-sm text-neutral-500 dark:text-white/30">
                                                        {{ formatDate(item.date) }}
                                                    </div>
                                                </div>
                                                <div
                                                    v-if="item.list && item.list.length > 0"
                                                    class="ml-4 text-neutral-400 transition-colors group-hover:text-neutral-900 dark:text-white/30 dark:group-hover:text-white"
                                                >
                                                    <FontAwesomeIcon
                                                        :icon="
                                                            isExpanded(item)
                                                                ? 'fa-sharp fa-light fa-arrows-to-line'
                                                                : 'fa-sharp fa-light fa-arrows-from-line'
                                                        "
                                                        class="text-sm"
                                                    />
                                                </div>
                                            </div>
                                            <div class="space-y-4">
                                                <div
                                                    class="prose prose-neutral dark:prose-invert max-w-none text-sm text-neutral-600 dark:text-neutral-400"
                                                >
                                                    <BodyText>{{ item.content }}</BodyText>
                                                </div>
                                                <ul
                                                    v-if="item.list && item.list.length > 0"
                                                    v-show="isExpanded(item)"
                                                    class="list-none space-y-2 pl-0"
                                                >
                                                    <li v-for="(listItem, listIndex) in item.list" :key="listIndex" class="relative pl-6">
                                                        <span class="absolute left-0 text-neutral-400">•</span>
                                                        <BodyText size="xs">{{ listItem }}</BodyText>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>
            </div>
            <div class="mx-auto">
                <hr class="mx-auto my-10 border-0 border-neutral-200 md:my-32 md:border dark:border-neutral-700" />
            </div>
            <div id="skills" class="mx-auto flex flex-col items-start gap-4 md:grid md:grid-cols-12">
                <div
                    class="relative top-0 z-10 col-span-12 -ml-4 w-[calc(100%+2rem)] px-4 pt-4 pb-0 md:sticky md:top-32 md:col-span-3 md:w-auto md:py-0"
                >
                    <PageTitle as="h2" class="scramble-trigger relative pb-2 md:pb-4"> {{ t('bio.sections.skills') }} </PageTitle>
                    <button
                        type="button"
                        @click="openSkillsModal"
                        class="mt-2 mb-4 w-fit border-b border-b-neutral-300/50 text-left text-xs text-neutral-500 transition-all hover:cursor-pointer hover:border-b-brand-400 hover:text-neutral-700 dark:border-b-neutral-600/50 dark:text-neutral-400 dark:hover:border-b-brand-400 dark:hover:text-neutral-200"
                    >
                        <FontAwesomeIcon icon="fa-sharp fa-light fa-code" class="mr-1.5" />
                        {{ t('bio.skills.viewSkillsInCode') }}
                    </button>
                </div>
                <div class="col-span-12 w-full md:col-span-9">
                    <div class="space-y-10 md:space-y-16">
                        <!-- Design Tools -->
                        <div>
                            <LabelText as="h3" class="mb-6 block">
                                {{ t('bio.skills.designTools') }}
                            </LabelText>
                            <div class="flex flex-wrap gap-3">
                                <span
                                    v-for="tool in skills.designTools"
                                    :key="tool"
                                    class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-4 py-2 text-xs text-neutral-600 transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-white/10 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                                >
                                    {{ tool }}
                                </span>
                            </div>
                        </div>

                        <!-- Code Languages -->
                        <div>
                            <LabelText as="h3" class="mb-6 block">
                                {{ t('bio.skills.codeLanguages') }}
                            </LabelText>
                            <div class="flex flex-wrap gap-3">
                                <span
                                    v-for="lang in skills.codeLanguages"
                                    :key="lang"
                                    class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-4 py-2 text-xs text-neutral-600 transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-neutral-800 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                                >
                                    {{ lang }}
                                </span>
                            </div>
                        </div>

                        <!-- Technologies -->
                        <div>
                            <LabelText as="h3" class="mb-6 block">
                                {{ t('bio.skills.technologies') }}
                            </LabelText>
                            <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-2 md:gap-y-10">
                                <div v-for="tech in skills.technologies" :key="tech.category" class="space-y-3">
                                    <SectionTitle as="h4">{{ tech.category }}</SectionTitle>
                                    <ul class="space-y-2">
                                        <li v-for="(item, idx) in tech.items" :key="idx">
                                            <BodyText size="xs">{{ item }}</BodyText>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mx-auto">
                <hr class="mx-auto my-10 border-0 border-neutral-200 md:my-32 md:border dark:border-neutral-700" />
            </div>
            <div id="approach" class="mx-auto flex flex-col items-start gap-4 md:grid md:grid-cols-12">
                <div
                    class="relative top-0 z-10 col-span-12 -ml-4 w-[calc(100%+2rem)] px-4 pt-4 pb-0 md:sticky md:top-32 md:col-span-3 md:w-auto md:py-0"
                >
                    <PageTitle as="h2" class="scramble-trigger relative pb-4">
                        {{ t('bio.approach.title') }}
                    </PageTitle>
                </div>
                <div class="col-span-12 w-full md:col-span-9">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-2 md:gap-y-16 lg:grid-cols-3">
                        <div v-for="(item, index) in bioData.approach" :key="index" class="space-y-3">
                            <LabelText>
                                {{ (index + 1).toString().padStart(2, '0') }}
                            </LabelText>
                            <SectionTitle as="h3">
                                <span v-html="item.title" />
                            </SectionTitle>
                            <BodyText size="xs">
                                {{ item.content }}
                            </BodyText>
                        </div>
                    </div>
                </div>
            </div>

            <FooterArea />
        </main>
        <SkillsModal :is-open="isSkillsModalOpen" @close="closeSkillsModal" />
    </div>
</template>
