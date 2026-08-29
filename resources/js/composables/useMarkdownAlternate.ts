import { usePage } from '@inertiajs/vue3';
import { computed, type ComputedRef } from 'vue';

/**
 * Absolute URL of the public markdown export for the current HTML page
 * (`{canonical path}.md`).
 */
export function useMarkdownAlternate(): { markdownAlternateUrl: ComputedRef<string> } {
    const page = usePage();

    const markdownAlternateUrl = computed(() => {
        const baseUrl = typeof page.props.appUrl === 'string' ? page.props.appUrl.replace(/\/$/, '') : 'https://captainscor.ch';
        const path = (page.url.split('?')[0] ?? '/').replace(/\/$/, '');

        return `${baseUrl}${path}.md`;
    });

    return { markdownAlternateUrl };
}
