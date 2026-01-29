<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    public function handle()
    {
        $sitemap = \Spatie\Sitemap\Sitemap::create();

        // Static pages
        $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/')->setPriority(1.0)->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/about')->setPriority(0.8)->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/portfolio')->setPriority(0.9)->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/blog')->setPriority(0.9)->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY));

        // Projects (Case Studies)
        $projectFiles = glob(resource_path('content/projects/en/*.json'));
        foreach ($projectFiles as $file) {
            $data = json_decode(file_get_contents($file), true);
            if (isset($data['slug'])) {
                $url = \Spatie\Sitemap\Tags\Url::create("/case-study/{$data['slug']}")
                    ->setPriority(0.8)
                    ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setLastModificationDate(\Carbon\Carbon::createFromTimestamp(filemtime($file)));
                
                $sitemap->add($url);
            }
        }

        // Blog Posts
        $postFiles = glob(resource_path('content/posts/en/*.json'));
        foreach ($postFiles as $file) {
            $data = json_decode(file_get_contents($file), true);
            if (isset($data['slug'])) {
                $category = is_array($data['category']) ? strtolower($data['category'][0]) : strtolower($data['category'] ?? 'uncategorized');
                $url = \Spatie\Sitemap\Tags\Url::create("/blog/{$category}/{$data['slug']}")
                    ->setPriority(0.7)
                    ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setLastModificationDate(\Carbon\Carbon::createFromTimestamp(filemtime($file)));

                $sitemap->add($url);
            }
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
