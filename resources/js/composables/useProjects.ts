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
    category: string;
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

export interface ProjectTranslation {
    slug: string;
    title: string;
    story_preview: string;
    story: string;
    fineprint: string;
}

// Import all project files eagerly
// English files contain complete project data (metadata + content)
// Other locale files only contain translated content fields
const enProjectFiles = import.meta.glob<{ default: Project }>('/resources/content/projects/en/*.json', {
    eager: true,
});

const translationFiles = import.meta.glob<{ default: ProjectTranslation }>('/resources/content/projects/!(en)/*.json', {
    eager: true,
});

export function useProjects() {
    const { locale } = useI18n();

    const projects: ComputedRef<Project[]> = computed(() => {
        // Get all English projects (complete data)
        const enProjects = Object.entries(enProjectFiles).map(([, module]) => module.default || module) as Project[];

        if (locale.value === 'en') {
            return enProjects.sort((a, b) => a.id - b.id);
        }

        // For other locales, merge English metadata with translated content
        const translations = Object.entries(translationFiles)
            .filter(([path]) => path.includes(`/${locale.value}/`))
            .map(([, module]) => module.default || module) as ProjectTranslation[];

        // Create a map of translations by slug for quick lookup
        const translationMap = new Map(translations.map((t) => [t.slug, t]));

        // Merge English projects with translations
        return enProjects
            .map((project) => {
                const translation = translationMap.get(project.slug);
                if (translation) {
                    return {
                        ...project,
                        title: translation.title,
                        story_preview: translation.story_preview,
                        story: translation.story,
                        fineprint: translation.fineprint,
                    };
                }
                return project;
            })
            .sort((a, b) => a.id - b.id);
    });

    const getProject = (slug: string): Project | undefined => {
        return projects.value.find((p) => p.slug === slug);
    };

    const getProjectsByCategory = (category: string): Project[] => {
        return projects.value.filter((p) => p.category === category);
    };

    return {
        projects,
        getProject,
        getProjectsByCategory,
    };
}
