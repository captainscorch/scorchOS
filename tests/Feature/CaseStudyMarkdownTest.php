<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class CaseStudyMarkdownTest extends TestCase
{
    public function test_case_study_markdown_strips_layout_only_frontmatter(): void
    {
        $response = $this->get('/case-study/cale.md?lang=en');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $response->assertSee('Case study source:', false);
        $response->assertSee('/case-study/cale', false);

        $body = (string) $response->getContent();

        // Layout-only keys, including the nested media block, must be gone
        foreach (['id', 'image', 'color', 'logo', 'width', 'height', 'spineHeight', 'media', 'fineprint_media'] as $key) {
            $this->assertDoesNotMatchRegularExpression('/^'.$key.':/m', $body, "Frontmatter still carries {$key}:");
        }
        $this->assertStringNotContainsString('aspectRatio:', $body);
        $this->assertStringNotContainsString('thumbnail:', $body);

        // Editorial frontmatter survives
        $this->assertMatchesRegularExpression('/^client:/m', $body);
        $this->assertMatchesRegularExpression('/^technologies:/m', $body);
    }

    public function test_case_study_markdown_honours_lang_query_for_translation(): void
    {
        $english = $this->get('/case-study/cale.md?lang=en');
        $german = $this->get('/case-study/cale.md?lang=de');

        $english->assertOk();
        $german->assertOk();
        $this->assertNotSame($english->getContent(), $german->getContent());
    }

    public function test_case_study_markdown_returns_404_for_unknown_slug(): void
    {
        $this->get('/case-study/this-slug-does-not-exist-xyz.md')->assertNotFound();
    }

    public function test_case_study_html_advertises_its_markdown_export(): void
    {
        $response = $this->get('/case-study/cale');

        $response->assertOk();
        $this->assertStringContainsString(
            '/case-study/cale.md>; rel="alternate"; type="text/markdown"',
            (string) $response->headers->get('Link'),
        );
    }
}
