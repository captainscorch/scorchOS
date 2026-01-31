import '@/lib/buffer-polyfill';
import matter from 'gray-matter';
import { computed, type ComputedRef } from 'vue';
import { useI18n } from 'vue-i18n';

export interface BlogPost {
    slug: string;
    title: string;
    date: string;
    category: string[];
    tags: string[];
    excerpt: string;
    content: string;
    components?: string[];
    readingTime: number;
    featured?: boolean;
}

interface PostFrontmatter {
    slug: string;
    title: string;
    date: string;
    category: string[];
    tags: string[];
    excerpt: string;
    components?: string[];
    featured?: boolean;
}

interface TranslationFrontmatter {
    slug: string;
    title: string;
    excerpt: string;
    tags?: string[];
}

const postMdFiles = import.meta.glob<string>('/resources/content/posts/*/*.md', {
    eager: true,
    query: '?raw',
    import: 'default',
});

const EN_PREFIX = '/resources/content/posts/en/';

function getRaw(path: string): string {
    const mod = postMdFiles[path];
    return typeof mod === 'string' ? mod : ((mod as { default?: string })?.default ?? '');
}

function parseEnPost(path: string, raw: string): BlogPost {
    const slug = path.replace(/^.*\/([^/]+)\.md$/, '$1');
    const { data, content } = matter(raw);
    const fm = data as PostFrontmatter;
    const text = (content ?? '').trim();
    const wordCount = text.split(/\s+/).filter(Boolean).length;
    const readingTime = Math.ceil(wordCount / 200);
    return {
        slug: fm.slug ?? slug,
        title: fm.title ?? '',
        date: fm.date ?? '',
        category: Array.isArray(fm.category) ? fm.category : [],
        tags: Array.isArray(fm.tags) ? fm.tags : [],
        excerpt: fm.excerpt ?? '',
        content: text,
        components: fm.components,
        featured: fm.featured,
        readingTime,
    };
}

function parseTranslation(path: string, raw: string): { slug: string; title: string; excerpt: string; tags?: string[]; content: string } {
    const slug = path.replace(/^.*\/([^/]+)\.md$/, '$1');
    const { data, content } = matter(raw);
    const fm = data as TranslationFrontmatter;
    return {
        slug: fm.slug ?? slug,
        title: fm.title ?? '',
        excerpt: fm.excerpt ?? '',
        tags: Array.isArray(fm.tags) ? fm.tags : undefined,
        content: (content ?? '').trim(),
    };
}

// en/*.md = source of truth (slug, date, category, etc.); other locales overlay title, excerpt, tags, content
const enPosts: BlogPost[] = (() => {
    const entries = Object.entries(postMdFiles).filter(([path]) => path.startsWith(EN_PREFIX));
    return entries.map(([path]) => parseEnPost(path, getRaw(path))).sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime());
})();

const translationsByLocale = new Map<string, Map<string, { title: string; excerpt: string; tags?: string[]; content: string }>>();

for (const path of Object.keys(postMdFiles)) {
    const match = path.match(/\/posts\/([^/]+)\/([^/]+)\.md$/);
    if (!match) continue;
    const [, loc] = match;
    if (loc === 'en') continue;
    const raw = getRaw(path);
    const t = parseTranslation(path, raw);
    if (!translationsByLocale.has(loc)) {
        translationsByLocale.set(loc, new Map());
    }
    translationsByLocale.get(loc)!.set(t.slug, { title: t.title, excerpt: t.excerpt, tags: t.tags, content: t.content });
}

export function usePosts() {
    const { locale } = useI18n();

    const posts: ComputedRef<BlogPost[]> = computed(() => {
        if (locale.value === 'en') {
            return enPosts;
        }
        const trans = translationsByLocale.get(locale.value);
        if (!trans) return enPosts;
        return enPosts.map((post) => {
            const t = trans.get(post.slug);
            if (!t) return post;
            const wordCount = t.content.split(/\s+/).filter(Boolean).length;
            return {
                ...post,
                title: t.title,
                excerpt: t.excerpt,
                tags: t.tags ?? post.tags,
                content: t.content,
                readingTime: Math.ceil(wordCount / 200),
            };
        });
    });

    const getPost = (slug: string): BlogPost | undefined => {
        return posts.value.find((p) => p.slug === slug);
    };

    const getPostsByCategory = (category: string): BlogPost[] => {
        return posts.value.filter((p) => p.category.includes(category));
    };

    const getPostsByTag = (tag: string): BlogPost[] => {
        return posts.value.filter((p) => p.tags.includes(tag));
    };

    const getFeaturedPosts = (): BlogPost[] => {
        return posts.value.filter((p) => p.featured);
    };

    const BLOG_CATEGORIES = ['Journal', 'Craft'] as const;

    const getAllCategories = (): readonly string[] => {
        return BLOG_CATEGORIES;
    };

    const getAllTags = (): string[] => {
        const tags = new Set<string>();
        posts.value.forEach((p) => p.tags.forEach((t) => tags.add(t)));
        return Array.from(tags).sort();
    };

    return {
        posts,
        getPost,
        getPostsByCategory,
        getPostsByTag,
        getFeaturedPosts,
        getAllCategories,
        getAllTags,
    };
}
