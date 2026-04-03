<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ContentSlugs;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogPostMarkdownController extends Controller
{
    private const EXPORT_STRIP_FRONTMATTER_KEYS = ['components', 'featured'];

    public function __invoke(Request $request, ContentSlugs $contentSlugs, string $category, string $slug): Response
    {
        if (! $contentSlugs->isValidBlogPost($slug)) {
            abort(404);
        }

        $lang = $request->query('lang');
        if (! is_string($lang) || ! in_array($lang, ['en', 'de'], true)) {
            $lang = $request->getPreferredLanguage(['de', 'en']) ?: 'en';
        }

        $path = $contentSlugs->blogPostSourceMarkdownPath($slug, $lang);
        if ($path === null) {
            abort(404);
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            abort(404);
        }

        $contents = $this->stripAuthoringDirectives($contents);

        $htmlUrl = url("/blog/{$category}/{$slug}");

        $contents = $this->prepareMarkdownForExport($contents, $htmlUrl);

        return response($contents, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
            'Link' => '<'.$htmlUrl.'>; rel="alternate"; type="text/html"',
        ]);
    }

    /**
     * Drop authoring-only frontmatter keys, then insert a short blockquote with the canonical HTML URL.
     */
    private function prepareMarkdownForExport(string $markdown, string $htmlUrl): string
    {
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n?(.*)$/s', $markdown, $m)) {
            $frontmatter = $this->stripFrontmatterKeyLines($m[1], self::EXPORT_STRIP_FRONTMATTER_KEYS);
            $frontmatter = trim($frontmatter)."\n";
            $body = $m[2];
            $title = $this->scalarYamlStringFromBlock($frontmatter, 'title') ?? 'View article';
            $heading = '> **Article source:** ['.$this->escapeMarkdownLinkLabel($title).']('.$htmlUrl.")\n\n";

            return "---\n".$frontmatter."---\n\n".$heading.$body;
        }

        return '> **Article source:** [View article]('.$htmlUrl.")\n\n".$markdown;
    }

    /**
     * @param  list<string>  $keys
     */
    private function stripFrontmatterKeyLines(string $yamlBlock, array $keys): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $yamlBlock) ?: [];
        $filtered = [];

        foreach ($lines as $line) {
            $drop = false;
            foreach ($keys as $key) {
                if (preg_match('/^\s*'.preg_quote($key, '/').':/', $line) === 1) {
                    $drop = true;
                    break;
                }
            }
            if (! $drop) {
                $filtered[] = $line;
            }
        }

        return implode("\n", $filtered);
    }

    private function scalarYamlStringFromBlock(string $frontmatter, string $key): ?string
    {
        $pattern = '/^'.preg_quote($key, '/').':\s*(.+)$/m';
        if (preg_match($pattern, $frontmatter, $m) !== 1) {
            return null;
        }

        $raw = trim($m[1]);
        if ($raw === '' || str_starts_with($raw, '[') || str_starts_with($raw, '{')) {
            return null;
        }

        if (preg_match('/^\'((?:\\\\\'|[^\'])*)\'\s*$/s', $raw, $q) === 1) {
            return str_replace("\\'", "'", $q[1]);
        }
        if (preg_match('/^"((?:\\\\"|[^"])*)"\s*$/s', $raw, $q) === 1) {
            return str_replace('\\"', '"', $q[1]);
        }

        return $raw;
    }

    private function escapeMarkdownLinkLabel(string $text): string
    {
        return str_replace(['\\', '[', ']'], ['\\\\', '\\[', '\\]'], $text);
    }

    /**
     * Remove site-specific authoring directives (interactive embeds, image shortcodes)
     * so pasted or fetched Markdown is readable for humans and LLMs without broken syntax.
     */
    private function stripAuthoringDirectives(string $markdown): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $markdown) ?: [];
        $kept = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed !== '' && (str_starts_with($trimmed, '::interactive[') || str_starts_with($trimmed, '::image['))) {
                continue;
            }
            $kept[] = $line;
        }

        $joined = implode("\n", $kept);
        $normalized = preg_replace("/\n{3,}/", "\n\n", $joined);

        return $normalized ?? $joined;
    }
}
