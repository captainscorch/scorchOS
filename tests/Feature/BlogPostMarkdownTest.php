<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class BlogPostMarkdownTest extends TestCase
{
    public function test_blog_post_markdown_strips_components_and_featured_from_frontmatter(): void
    {
        $response = $this->get('/blog/craft/concentric-border-radius.md');

        $response->assertOk();
        $response->assertDontSee('components:', false);
        $response->assertDontSee('featured:', false);
        $response->assertSee('Article source:', false);
        $response->assertSee('/blog/craft/concentric-border-radius', false);
    }

    public function test_blog_post_markdown_honours_lang_query_for_translation(): void
    {
        $response = $this->get('/blog/journal/beyond-prompts-architecting-with-ai.md?lang=de');

        $response->assertOk();
        $response->assertSee('Beyond Prompts: Struktur mit KI', false);
        $response->assertDontSee('::interactive[');
    }

    public function test_blog_post_markdown_returns_404_for_unknown_slug(): void
    {
        $this->get('/blog/journal/this-slug-does-not-exist-xyz.md')->assertNotFound();
    }
}
