<script setup lang="ts">
import CommandTrigger from '@/components/CommandTrigger.vue';
import FooterArea from '@/components/FooterArea.vue';
import ProgressiveBlur from '@/components/ProgressiveBlur.vue';
import TextReveal from '@/components/TextReveal.vue';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { useCommandMenu } from '@/composables/useCommandMenu';
import { usePosts } from '@/composables/usePosts';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faSparkles } from '@fortawesome/sharp-light-svg-icons';
import { Head, Link } from '@inertiajs/vue3';
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

library.add(faSparkles);

const { t } = useI18n();
const { open: openCommandMenu } = useCommandMenu();
const { posts, getAllCategories } = usePosts();

const getCategoryIcon = (category: string | null) => {
    switch (category) {
        case 'Journal':
            return '/icons/system-regular-20-bookmark-hover-bookmark-1.json';
        case 'Craft':
            return '/icons/system-regular-34-code-hover-code.json';
        default:
            return '/icons/system-regular-20-bookmark-hover-bookmark-1.json';
    }
};

const pageTitle = 'Daniel Schmier - Blog';
const pageDescription = 'Articles and insights on design, development, the creative industry and creative explorations.';
const ogTitle = 'Daniel Schmier – Blog';

const ogUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return '';
});

const selectedCategory = ref<string | null>(null);
const categories = getAllCategories();

const filteredPosts = computed(() => {
    if (!selectedCategory.value) {
        return posts.value;
    }
    return posts.value.filter((post) => post.category.includes(selectedCategory.value!));
});

const toggleCategory = (category: string | null) => {
    selectedCategory.value = selectedCategory.value === category ? null : category;
};

// Pagination
const breakpoints = useBreakpoints(breakpointsTailwind);
const isMobile = breakpoints.smaller('md');
const currentPage = ref(1);
const perPage = computed(() => (isMobile.value ? 6 : 9));

const paginatedPosts = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return filteredPosts.value.slice(start, start + perPage.value);
});

// Reset pagination when category changes
watch(selectedCategory, () => {
    currentPage.value = 1;
});

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
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
    <div class="min-h-screen bg-white pb-20 pt-32 text-neutral-900 md:pb-0 md:pt-40 dark:bg-black dark:text-white">
        <!-- Header / Navigation -->
        <header class="z-100 pointer-events-none fixed left-0 right-0 top-0 mx-auto">
            <ProgressiveBlur class="absolute inset-0 -z-10 h-full w-full" />
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between p-6 px-6 sm:px-6 md:p-6 lg:px-8">
                <!-- Name (Top Left) -->
                <div class="pointer-events-auto">
                    <Link href="/" class="scramble-trigger text-sm font-bold uppercase tracking-wider text-neutral-900 dark:text-white"
                        >Daniel Schmier</Link
                    >
                </div>

                <div
                    class="backdrop-blur-xs pointer-events-auto fixed bottom-6 left-1/2 flex -translate-x-1/2 transform items-center gap-2 rounded-full border border-neutral-200 bg-white/80 p-1.5 shadow-2xl md:absolute md:bottom-auto dark:border-white/10 dark:bg-neutral-900/80"
                >
                    <button
                        @click="toggleCategory(null)"
                        class="flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium transition-all duration-300"
                        :class="
                            selectedCategory === null
                                ? 'bg-neutral-900 text-white shadow-lg dark:bg-white dark:text-black'
                                : 'text-neutral-500 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white'
                        "
                    >
                        <span>{{ t('blog.filters.all') }}</span>
                    </button>
                    <button
                        v-for="category in categories"
                        :key="category"
                        @click="toggleCategory(category)"
                        :class="[
                            `cat-btn-${category}`,
                            'group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium transition-all duration-300',
                            selectedCategory === category
                                ? 'bg-neutral-900 text-white shadow-lg dark:bg-white dark:text-black'
                                : 'text-neutral-500 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white',
                        ]"
                    >
                        <lord-icon :src="getCategoryIcon(category)" trigger="hover" :target="`.cat-btn-${category}`" class="current-color size-4" />
                        <span>{{ t(`blog.categories.${category}`) }}</span>
                    </button>
                </div>

                <!-- Command Menu (Top Right) -->
                <div class="pointer-events-auto flex items-center gap-4">
                    <CommandTrigger @click="openCommandMenu" />
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Hero Text -->
            <div class="mb-22 mx-auto max-w-[calc(1280px-64px)] md:mb-32">
                <h2 class="mb-6 text-sm uppercase tracking-widest text-neutral-500 dark:text-white/30">{{ t('blog.title') }}</h2>
                <h1 class="max-w-2xl font-sans text-3xl font-bold text-neutral-900 dark:text-white">
                    <TextReveal :text="t('blog.hero.line1')" />
                    <br />
                    <TextReveal :text="t('blog.hero.line2')" class="text-neutral-400 dark:text-white/30" :delay="1.5" />
                </h1>
            </div>

            <!-- Blog Posts Grid -->
            <div v-if="filteredPosts.length > 0">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="post in paginatedPosts"
                        :key="post.slug"
                        :href="`/blog/${post.category[0].toLowerCase()}/${post.slug}`"
                        class="hover:border-brand/50 group relative flex flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl dark:border-white/10 dark:bg-neutral-900"
                    >
                        <!-- Hover Gradient Overlay -->
                        <div
                            class="from-brand/5 absolute inset-0 z-0 bg-gradient-to-br to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        ></div>

                        <!-- Categories -->
                        <div class="relative z-10 mb-4 flex flex-wrap gap-2">
                            <span
                                v-for="category in post.category"
                                :key="category"
                                class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-3 py-1 text-[10px] text-neutral-600 transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-neutral-800 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                            >
                                {{ t(`blog.categories.${category}`) }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3
                            class="relative z-10 mb-2 font-sans text-xl font-bold text-neutral-900 transition-colors group-hover:text-neutral-700 dark:text-white dark:group-hover:text-neutral-200"
                        >
                            {{ post.title }}
                        </h3>

                        <!-- Excerpt -->
                        <p class="relative z-10 mb-8 line-clamp-3 flex-1 text-sm leading-relaxed text-neutral-600 dark:text-neutral-400">
                            {{ post.excerpt }}
                        </p>

                        <!-- Footer: Date & Reading Time -->
                        <div class="relative z-10 flex items-center justify-between text-xs text-neutral-500 dark:text-neutral-500">
                            <span>{{ formatDate(post.date) }}</span>
                            <span>{{ post.readingTime }} {{ t('blog.readingTime') }}</span>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="filteredPosts.length > perPage" class="mt-8 flex justify-center">
                    <Pagination
                        v-slot="{ page }"
                        :total="filteredPosts.length"
                        :sibling-count="1"
                        show-edges
                        :default-page="1"
                        :items-per-page="perPage"
                        :page="currentPage"
                        @update:page="currentPage = $event"
                    >
                        <PaginationContent v-slot="{ items }">
                            <PaginationPrevious />

                            <template v-for="(item, index) in items">
                                <PaginationItem
                                    v-if="item.type === 'page'"
                                    :key="index"
                                    :value="item.value"
                                    :is-active="item.value === page"
                                    class="size-9 transition-colors"
                                >
                                    {{ item.value }}
                                </PaginationItem>
                                <PaginationEllipsis v-else :key="item.type" :index="index" />
                            </template>

                            <PaginationNext />
                        </PaginationContent>
                    </Pagination>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state-area flex flex-col items-center justify-center py-24 text-center">
                <div
                    class="mb-6 flex size-16 items-center justify-center rounded-2xl border border-neutral-200 bg-neutral-100 text-neutral-400 dark:border-white/10 dark:bg-white/5 dark:text-neutral-500"
                >
                    <lord-icon
                        :src="getCategoryIcon(selectedCategory)"
                        trigger="hover"
                        target=".empty-state-area"
                        delay="2000"
                        class="current-color size-8"
                    />
                </div>
                <h3 class="mb-2 font-sans text-xl font-bold text-neutral-900 dark:text-white">{{ t('blog.empty.title') }}</h3>
                <p class="text-neutral-600 dark:text-neutral-400">{{ t('blog.empty.description') }}</p>
            </div>
        </main>

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <FooterArea :is-landingpage-page="true" />
        </div>
    </div>
</template>
