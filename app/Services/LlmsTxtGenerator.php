<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Builds public/llms.txt for https://llmstxt.org — kept in sync with Markdown content via {@see \App\Console\Commands\GenerateSitemap}.
 */
final class LlmsTxtGenerator
{
    public function __construct(
        private readonly string $baseUrl,
    ) {}

    public static function fromAppConfig(): self
    {
        return new self(rtrim((string) config('app.url'), '/'));
    }

    public function writeToFile(string $path): void
    {
        $written = file_put_contents($path, $this->contents());
        if ($written === false) {
            throw new \RuntimeException("Could not write llms.txt to {$path}");
        }
    }

    public function contents(): string
    {
        $u = $this->baseUrl;

        $caseStudyLines = array_map(
            fn (array $row): string => sprintf(
                '- **%s** — [%s](%s/case-study/%s)',
                $this->escapeMarkdownLinkLabel($row['client']),
                $this->escapeMarkdownLinkLabel($row['title']),
                $u,
                rawurlencode($row['slug']),
            ),
            $this->caseStudyRows(),
        );

        $blogIntro = 'Each post is also available as Markdown at the same path with a `.md` suffix (`?lang=` / `Accept-Language` supported).';

        $blogLines = array_map(
            fn (array $row): string => sprintf(
                '- [%s](%s/blog/%s/%s) · [Markdown](%s/blog/%s/%s.md)',
                $this->escapeMarkdownLinkLabel($row['title']),
                $u,
                $row['category'],
                rawurlencode($row['slug']),
                $u,
                $row['category'],
                rawurlencode($row['slug']),
            ),
            $this->blogRows(),
        );

        $siteBlock = <<<MARKDOWN
## Site

- [Home]({$u}/): Landing and overview.
- [About]({$u}/about): About me / CV.
- [Portfolio]({$u}/portfolio): Selected case studies and personal projects.
- [Blog]({$u}/blog): Articles and insights on design, development and beyond.
- [Playground]({$u}/playground): Experiments and interactive demos.

MARKDOWN;

        $optionalBlock = <<<MARKDOWN
## Contact

- [Instagram](https://www.instagram.com/captainscorch)
- [X (Twitter)](https://x.com/captainscorch)
- [LinkedIn](https://www.linkedin.com/in/captainscorch)
- [GitHub](https://github.com/captainscorch)
- [Email](mailto:hi@captainscor.ch)

## Optional

- [Robots]({$u}/robots.txt): Crawl directives for automated agents.
- [Sitemap]({$u}/sitemap.xml): Index of canonical public URLs on the site.
- [llms.txt]({$u}/llms.txt): This LLM-oriented overview file.
MARKDOWN;

        return <<<MARKDOWN
# captainscor.ch

> Personal portfolio of Daniel Schmier (captainscorch): Design Engineer specializing in full-stack development, product design, and modern web technologies.

Use the pages below for structured context about projects, articles, and contact paths.

{$siteBlock}
## Case studies

{$this->joinLines($caseStudyLines)}

## Blog

{$blogIntro}

{$this->joinLines($blogLines)}

{$optionalBlock}

MARKDOWN;
    }

    /**
     * @return list<array{slug: string, client: string, title: string}>
     */
    private function caseStudyRows(): array
    {
        $rows = [];
        foreach (glob(resource_path('content/projects/en/*.md')) ?: [] as $file) {
            $frontmatter = $this->frontMatterFromFile($file);
            $slug = $this->slugFromFrontmatterOrBasename($frontmatter, $file);
            if ($slug === null) {
                continue;
            }
            $client = $this->scalarYamlStringFromFrontmatter($frontmatter, 'client') ?? '—';
            $title = $this->scalarYamlStringFromFrontmatter($frontmatter, 'title')
                ?? $this->scalarYamlStringFromFrontmatter($frontmatter, 'client')
                ?? $slug;
            $rows[] = [
                'slug' => $slug,
                'client' => $client,
                'title' => $title,
                'mtime' => filemtime($file) ?: 0,
            ];
        }

        usort($rows, fn (array $a, array $b): int => $b['mtime'] <=> $a['mtime']);

        return array_values(array_map(fn (array $r): array => [
            'slug' => $r['slug'],
            'client' => $r['client'],
            'title' => $r['title'],
        ], $rows));
    }

    /**
     * @return list<array{slug: string, title: string, category: string}>
     */
    private function blogRows(): array
    {
        $rows = [];
        foreach (glob(resource_path('content/posts/en/*.md')) ?: [] as $file) {
            $frontmatter = $this->frontMatterFromFile($file);
            $slug = $this->slugFromFrontmatterOrBasename($frontmatter, $file);
            if ($slug === null) {
                continue;
            }
            $title = $this->scalarYamlStringFromFrontmatter($frontmatter, 'title') ?? $slug;
            $categoryRaw = $this->firstBlogCategoryFromFrontmatter($frontmatter) ?? 'uncategorized';
            $category = strtolower($categoryRaw);
            $rows[] = [
                'slug' => $slug,
                'title' => $title,
                'category' => $category,
                'mtime' => filemtime($file) ?: 0,
            ];
        }

        usort($rows, fn (array $a, array $b): int => $b['mtime'] <=> $a['mtime']);

        return array_values(array_map(fn (array $r): array => [
            'slug' => $r['slug'],
            'title' => $r['title'],
            'category' => $r['category'],
        ], $rows));
    }

    private function joinLines(array $lines): string
    {
        return implode("\n", $lines);
    }

    /**
     * CommonMark-style escaping for [...](...) link labels.
     */
    private function escapeMarkdownLinkLabel(string $text): string
    {
        return str_replace(['\\', '[', ']'], ['\\\\', '\\[', '\\]'], $text);
    }

    private function frontMatterFromFile(string $path): ?string
    {
        $content = file_get_contents($path);
        if ($content === false) {
            return null;
        }
        if (preg_match('/^---\s*\n(.*?)\n---/s', $content, $match)) {
            return $match[1];
        }

        return null;
    }

    private function slugFromFrontmatterOrBasename(?string $frontmatter, string $path): ?string
    {
        if ($frontmatter !== null) {
            $quoted = $this->scalarYamlStringFromFrontmatter($frontmatter, 'slug');
            if ($quoted !== null) {
                return $quoted;
            }
            if (preg_match('/^slug:\s*(\S+)\s*$/m', $frontmatter, $m)) {
                return trim($m[1]);
            }
        }

        $basename = basename($path, '.md');

        return $basename !== '' ? $basename : null;
    }

    private function firstBlogCategoryFromFrontmatter(?string $frontmatter): ?string
    {
        if ($frontmatter === null) {
            return null;
        }
        if (preg_match('/^category:\s*\[\s*[\'"]([^\'"]+)[\'"]/m', $frontmatter, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/^category:\s*[\'"]([^\'"]+)[\'"]\s*$/m', $frontmatter, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/^category:\s*(\S+)\s*$/m', $frontmatter, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    private function scalarYamlStringFromFrontmatter(?string $frontmatter, string $key): ?string
    {
        if ($frontmatter === null) {
            return null;
        }
        $pattern = '/^'.preg_quote($key, '/').':\s*(.+)$/m';
        if (! preg_match($pattern, $frontmatter, $m)) {
            return null;
        }

        $raw = trim($m[1]);
        if ($raw === '' || str_starts_with($raw, '[') || str_starts_with($raw, '{')) {
            return null;
        }

        if (preg_match('/^\'((?:\\\\\'|[^\'])*)\'\s*$/s', $raw, $q)) {
            return str_replace("\\'", "'", $q[1]);
        }
        if (preg_match('/^"((?:\\\\"|[^"])*)"\s*$/s', $raw, $q)) {
            return str_replace('\\"', '"', $q[1]);
        }

        return $raw;
    }
}
