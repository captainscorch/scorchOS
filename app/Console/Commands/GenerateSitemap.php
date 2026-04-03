<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(Url::create('/about')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemap->add(Url::create('/portfolio')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(Url::create('/blog')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(Url::create('/playground')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // Projects (Case Studies) — content is Markdown + frontmatter, not JSON
        $projectFiles = glob(resource_path('content/projects/en/*.md')) ?: [];
        foreach ($projectFiles as $file) {
            $slug = $this->slugFromMarkdownFile($file);
            if ($slug === null) {
                continue;
            }

            $sitemap->add(
                Url::create("/case-study/{$slug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setLastModificationDate(Carbon::createFromTimestamp(filemtime($file)))
            );
        }

        // Blog Posts
        $postFiles = glob(resource_path('content/posts/en/*.md')) ?: [];
        foreach ($postFiles as $file) {
            $slug = $this->slugFromMarkdownFile($file);
            if ($slug === null) {
                continue;
            }

            $frontmatter = $this->frontMatterFromMarkdownFile($file);
            $category = $this->blogCategoryFromFrontMatter($frontmatter);

            $sitemap->add(
                Url::create("/blog/{$category}/{$slug}")
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setLastModificationDate(Carbon::createFromTimestamp(filemtime($file)))
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');

        return self::SUCCESS;
    }

    private function frontMatterFromMarkdownFile(string $path): ?string
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

    /**
     * Mirror {@see \App\Services\ContentSlugs::slugFromMarkdownFile} so sitemap slugs match routes.
     */
    private function slugFromMarkdownFile(string $path): ?string
    {
        $frontmatter = $this->frontMatterFromMarkdownFile($path);
        if ($frontmatter !== null) {
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

    private function blogCategoryFromFrontMatter(?string $frontmatter): string
    {
        if ($frontmatter === null) {
            return 'uncategorized';
        }

        if (preg_match('/^category:\s*\[\s*[\'"]([^\'"]+)[\'"]/m', $frontmatter, $m)) {
            return strtolower(trim($m[1]));
        }

        if (preg_match('/^category:\s*[\'"]([^\'"]+)[\'"]\s*$/m', $frontmatter, $m)) {
            return strtolower(trim($m[1]));
        }

        if (preg_match('/^category:\s*(\S+)\s*$/m', $frontmatter, $m)) {
            return strtolower(trim($m[1]));
        }

        return 'uncategorized';
    }
}
