<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shared plumbing for the public `{path}.md` exports: locale negotiation, frontmatter
 * cleanup, and the source blockquote pointing back at the canonical HTML page.
 */
abstract class MarkdownExportController extends Controller
{
    /**
     * Frontmatter keys that only drive the site's own layout.
     *
     * @var list<string>
     */
    protected const EXPORT_STRIP_FRONTMATTER_KEYS = [];

    /**
     * Label of the blockquote prepended to the export.
     */
    protected const SOURCE_LABEL = 'Source';

    /**
     * Fallback link text when the document carries no title.
     */
    protected const SOURCE_FALLBACK_TITLE = 'View page';

    protected function resolveLocale(Request $request): string
    {
        $lang = $request->query('lang');
        if (is_string($lang) && in_array($lang, ['en', 'de'], true)) {
            return $lang;
        }

        return $request->getPreferredLanguage(['de', 'en']) ?: 'en';
    }

    protected function markdownResponse(string $contents, string $htmlUrl): Response
    {
        return response($contents, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
            'Link' => '<'.$htmlUrl.'>; rel="alternate"; type="text/html"',
        ]);
    }

    /**
     * Drop authoring-only frontmatter keys, then insert a short blockquote with the canonical HTML URL.
     */
    protected function prepareMarkdownForExport(string $markdown, string $htmlUrl): string
    {
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n?(.*)$/s', $markdown, $m)) {
            $frontmatter = $this->stripFrontmatterKeyLines($m[1], static::EXPORT_STRIP_FRONTMATTER_KEYS);
            $frontmatter = trim($frontmatter)."\n";
            $body = $m[2];
            $title = $this->scalarYamlStringFromBlock($frontmatter, 'title')
                ?? $this->scalarYamlStringFromBlock($frontmatter, 'client')
                ?? static::SOURCE_FALLBACK_TITLE;
            $heading = '> **'.static::SOURCE_LABEL.':** ['.$this->escapeMarkdownLinkLabel($title).']('.$htmlUrl.")\n\n";

            return "---\n".$frontmatter."---\n\n".$heading.$body;
        }

        return '> **'.static::SOURCE_LABEL.':** ['.static::SOURCE_FALLBACK_TITLE.']('.$htmlUrl.")\n\n".$markdown;
    }

    /**
     * Remove a key line and any indented block that belongs to it, so nested lists
     * such as `media:` do not leave orphaned items behind.
     *
     * @param  list<string>  $keys
     */
    protected function stripFrontmatterKeyLines(string $yamlBlock, array $keys): string
    {
        if ($keys === []) {
            return $yamlBlock;
        }

        $lines = preg_split('/\r\n|\r|\n/', $yamlBlock) ?: [];
        $filtered = [];
        $dropping = false;

        foreach ($lines as $line) {
            if ($dropping) {
                // Indented lines and blank lines still belong to the dropped key
                if (trim($line) === '' || preg_match('/^\s/', $line) === 1) {
                    continue;
                }
                $dropping = false;
            }

            $drop = false;
            foreach ($keys as $key) {
                if (preg_match('/^'.preg_quote($key, '/').':/', $line) === 1) {
                    $drop = true;
                    break;
                }
            }

            if ($drop) {
                $dropping = true;

                continue;
            }

            $filtered[] = $line;
        }

        return implode("\n", $filtered);
    }

    protected function scalarYamlStringFromBlock(string $frontmatter, string $key): ?string
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

    protected function escapeMarkdownLinkLabel(string $text): string
    {
        return str_replace(['\\', '[', ']'], ['\\\\', '\\[', '\\]'], $text);
    }

    /**
     * Remove site-specific authoring directives (interactive embeds, image shortcodes)
     * so pasted or fetched Markdown is readable for humans and LLMs without broken syntax.
     */
    protected function stripAuthoringDirectives(string $markdown): string
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
