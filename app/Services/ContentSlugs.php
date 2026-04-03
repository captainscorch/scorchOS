<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

class ContentSlugs
{
    private const CACHE_KEY = 'content_slugs';

    private const CACHE_TTL_SECONDS = 604_800; // 1 week

    public function __construct(
        private readonly CacheRepository $cache
    ) {}

    /**
     * @return array{case_study: array<int, string>, blog: array<int, string>}
     */
    private function all(): array
    {
        return $this->cache->remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            return [
                'case_study' => $this->loadSlugsFromMarkdownDirectory(resource_path('content/projects/en/*.md')),
                'blog' => $this->loadSlugsFromMarkdownDirectory(resource_path('content/posts/en/*.md')),
            ];
        });
    }

    /**
     * @return array<int, string>
     */
    public function caseStudySlugs(): array
    {
        return $this->all()['case_study'];
    }

    public function isValidCaseStudy(string $slug): bool
    {
        return in_array($slug, $this->caseStudySlugs(), true);
    }

    /**
     * @return array<int, string>
     */
    public function blogPostSlugs(): array
    {
        return $this->all()['blog'];
    }

    public function isValidBlogPost(string $slug): bool
    {
        return in_array($slug, $this->blogPostSlugs(), true);
    }

    /**
     * Path to the Markdown source file for a blog post, for a given locale (en|de).
     * Falls back to English when no translation file exists.
     */
    public function blogPostSourceMarkdownPath(string $slug, string $locale): ?string
    {
        if (! $this->isValidBlogPost($slug)) {
            return null;
        }

        $locale = $locale === 'de' ? 'de' : 'en';

        foreach (glob(resource_path('content/posts/en/*.md')) ?: [] as $englishPath) {
            if ($this->slugFromMarkdownFile($englishPath) !== $slug) {
                continue;
            }

            if ($locale === 'en') {
                return $englishPath;
            }

            $translatedPath = resource_path('content/posts/de/'.basename($englishPath));

            return is_readable($translatedPath) ? $translatedPath : $englishPath;
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    private function loadSlugsFromMarkdownDirectory(string $globPattern): array
    {
        $slugs = [];
        foreach (glob($globPattern) ?: [] as $file) {
            $slug = $this->slugFromMarkdownFile($file);
            if ($slug !== null) {
                $slugs[] = $slug;
            }
        }

        return array_values(array_unique($slugs));
    }

    private function slugFromMarkdownFile(string $path): ?string
    {
        $content = file_get_contents($path);
        if ($content !== false && preg_match('/^---\s*\n(.*?)\n---/s', $content, $match)) {
            $frontmatter = $match[1];
            if (preg_match('/^slug:\s*[\'"]([^\'"]+)[\'"]\s*$/m', $frontmatter, $m)) {
                return trim($m[1]);
            }
            if (preg_match('/^slug:\s*(\S+)\s*$/m', $frontmatter, $m)) {
                return trim($m[1]);
            }
        }

        $basename = basename($path, '.md');

        return $basename !== '' ? $basename : null;
    }
}
