import { marked } from 'marked';

/**
 * Slugify a string for use in IDs and URLs
 */
export const slugify = (text: string): string => {
    return text
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');
};

/**
 * Configure marked globally for the application
 */
export const configureMarked = () => {
    marked.setOptions({
        breaks: true,
        gfm: true,
    });

    const renderer = new marked.Renderer();
    const originalLink = renderer.link;

    // Global link renderer: open external links in new tab
    renderer.link = function (token: any) {
        const link = originalLink.call(this, token);
        const isExternal = token.href.startsWith('http') || token.href.startsWith('//');

        if (isExternal) {
            return link.replace('<a', '<a target="_blank" rel="noopener noreferrer"');
        }

        return link;
    };

    // Global heading renderer: add IDs for TOC
    renderer.heading = function (token: any, ...args: any[]) {
        const text = token.text || token;
        const level = token.depth || (args[0] as number) || 1;
        const slug = slugify(text);
        return `<h${level} id="${slug}">${text}</h${level}>`;
    };

    marked.use({ renderer });
};

// Auto-configure on import
configureMarked();

export { marked };
