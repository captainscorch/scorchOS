import '@/lib/buffer-polyfill';
import matter from 'gray-matter';
import { computed, type ComputedRef } from 'vue';
import { useI18n } from 'vue-i18n';

export interface ProjectMedia {
    type: 'image' | 'video';
    src: string;
    thumbnail?: string;
    alt?: string;
    aspectRatio?: string;
}

export interface ProjectTeamMember {
    src: string;
    name: string;
}

export interface Project {
    id: number;
    slug: string;
    client: string;
    category: string | string[];
    image: string;
    color: string;
    logo: string;
    width: string;
    height: string;
    spineHeight: string;
    date: string;
    team: ProjectTeamMember[];
    services: string[];
    technologies: string[];
    media: ProjectMedia[];
    title: string;
    story_preview: string;
    story: string;
    fineprint: string;
    fineprint_media?: string;
    fineprint_media_alt?: string;
    website?: string;
}

interface ProjectFrontmatter {
    id: number;
    slug: string;
    client: string;
    category: string | string[];
    image: string;
    color: string;
    logo: string;
    width: string;
    height: string;
    spineHeight: string;
    date: string;
    team?: ProjectTeamMember[];
    services?: string[];
    technologies?: string[];
    media?: ProjectMedia[];
    title: string;
    story_preview: string;
    fineprint: string;
    fineprint_media?: string;
    fineprint_media_alt?: string;
    website?: string;
}

interface ProjectTranslationFrontmatter {
    slug: string;
    title: string;
    story_preview: string;
    fineprint: string;
}

// en/*.md = source of truth; other locales overlay title, story_preview, story, fineprint
const projectMdFiles = import.meta.glob<string>('/resources/content/projects/*/*.md', {
    eager: true,
    query: '?raw',
    import: 'default',
});

const enProjectFiles = import.meta.glob<{ default: Project }>('/resources/content/projects/en/*.json', {
    eager: true,
});

const translationFiles = import.meta.glob<{ default: ProjectTranslation }>('/resources/content/projects/!(en)/*.json', {
    eager: true,
});

export interface ProjectTranslation {
    slug: string;
    title: string;
    story_preview: string;
    story: string;
    fineprint: string;
}

const EN_PREFIX = '/resources/content/projects/en/';

function getMdRaw(path: string): string {
    const mod = projectMdFiles[path];
    return typeof mod === 'string' ? mod : ((mod as { default?: string })?.default ?? '');
}

function ensureCategory(c: string | string[] | undefined): string | string[] {
    if (Array.isArray(c)) return c;
    if (typeof c === 'string') return c;
    return [];
}

function parseEnProjectMd(path: string, raw: string): Project {
    const slug = path.replace(/^.*\/([^/]+)\.md$/, '$1');
    const { data, content } = matter(raw);
    const fm = data as ProjectFrontmatter;
    const story = (content ?? '').trim();
    return {
        id: fm.id ?? 0,
        slug: fm.slug ?? slug,
        client: fm.client ?? '',
        category: ensureCategory(fm.category),
        image: fm.image ?? '',
        color: fm.color ?? '',
        logo: fm.logo ?? '',
        width: fm.width ?? '',
        height: fm.height ?? '',
        spineHeight: fm.spineHeight ?? '',
        date: fm.date ?? '',
        team: Array.isArray(fm.team) ? fm.team : [],
        services: Array.isArray(fm.services) ? fm.services : [],
        technologies: Array.isArray(fm.technologies) ? fm.technologies : [],
        media: Array.isArray(fm.media) ? fm.media : [],
        title: fm.title ?? '',
        story_preview: fm.story_preview ?? '',
        story,
        fineprint: fm.fineprint ?? '',
        fineprint_media: fm.fineprint_media,
        fineprint_media_alt: fm.fineprint_media_alt,
        website: fm.website,
    };
}

function parseProjectTranslationMd(
    path: string,
    raw: string,
): { slug: string; title: string; story_preview: string; fineprint: string; story: string } {
    const slug = path.replace(/^.*\/([^/]+)\.md$/, '$1');
    const { data, content } = matter(raw);
    const fm = data as ProjectTranslationFrontmatter;
    return {
        slug: fm.slug ?? slug,
        title: fm.title ?? '',
        story_preview: fm.story_preview ?? '',
        fineprint: fm.fineprint ?? '',
        story: (content ?? '').trim(),
    };
}

const enProjectsFromMd: Project[] = (() => {
    const entries = Object.entries(projectMdFiles).filter(([path]) => path.startsWith(EN_PREFIX));
    return entries.map(([path]) => parseEnProjectMd(path, getMdRaw(path)));
})();

const enSlugsFromMd = new Set(enProjectsFromMd.map((p) => p.slug));

const enProjectsFromJson: Project[] = Object.entries(enProjectFiles).map(([, module]) => module.default || module) as Project[];

const enProjects: Project[] = [...enProjectsFromMd, ...enProjectsFromJson.filter((p) => !enSlugsFromMd.has(p.slug))].sort((a, b) => a.id - b.id);

const translationsByLocale = new Map<string, Map<string, { title: string; story_preview: string; fineprint: string; story: string }>>();

for (const path of Object.keys(projectMdFiles)) {
    const match = path.match(/\/projects\/([^/]+)\/([^/]+)\.md$/);
    if (!match) continue;
    const [, loc] = match;
    if (loc === 'en') continue;
    const raw = getMdRaw(path);
    const t = parseProjectTranslationMd(path, raw);
    if (!translationsByLocale.has(loc)) {
        translationsByLocale.set(loc, new Map());
    }
    translationsByLocale.get(loc)!.set(t.slug, { title: t.title, story_preview: t.story_preview, fineprint: t.fineprint, story: t.story });
}

export function useProjects() {
    const { locale } = useI18n();

    const projects: ComputedRef<Project[]> = computed(() => {
        if (locale.value === 'en') {
            return enProjects;
        }

        const mdTrans = translationsByLocale.get(locale.value);
        const jsonTrans = Object.entries(translationFiles)
            .filter(([path]) => path.includes(`/${locale.value}/`))
            .map(([, module]) => module.default || module) as ProjectTranslation[];

        const translationMap = new Map<string | undefined, ProjectTranslation>();
        jsonTrans.forEach((t) => translationMap.set(t.slug, t));

        return enProjects
            .map((project) => {
                const fromMd = mdTrans?.get(project.slug);
                const fromJson = translationMap.get(project.slug);
                const t = fromMd
                    ? { title: fromMd.title, story_preview: fromMd.story_preview, fineprint: fromMd.fineprint, story: fromMd.story }
                    : fromJson;
                if (!t) return project;
                return {
                    ...project,
                    title: t.title,
                    story_preview: t.story_preview,
                    story: t.story,
                    fineprint: t.fineprint,
                };
            })
            .sort((a, b) => a.id - b.id);
    });

    const getProject = (slug: string): Project | undefined => {
        return projects.value.find((p) => p.slug === slug);
    };

    const getProjectsByCategory = (category: string): Project[] => {
        return projects.value.filter((p) => {
            const categories = Array.isArray(p.category) ? p.category : [p.category];
            return categories.includes(category);
        });
    };

    return {
        projects,
        getProject,
        getProjectsByCategory,
    };
}
