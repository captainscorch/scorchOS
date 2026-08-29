<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class LlmsTxtGenerationTest extends TestCase
{
    public function test_sitemap_command_writes_llms_txt_with_case_study_titles(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $path = public_path('llms.txt');
        $this->assertFileExists($path);

        $content = (string) file_get_contents($path);
        $this->assertStringContainsString('## Case studies', $content);
        $this->assertStringContainsString('## Blog', $content);
        $this->assertStringContainsString('**cale design**', $content);
        $this->assertStringContainsString('[Minimalist Shopify Store for Premium Furniture', $content);
        $this->assertStringContainsString('/case-study/cale', $content);
        $this->assertStringContainsString(' · [Markdown](', $content);
    }

    public function test_llms_txt_points_at_the_agent_operating_manual(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = (string) file_get_contents(public_path('llms.txt'));
        $this->assertStringContainsString('## Agent files', $content);
        $this->assertStringContainsString('/agents.md', $content);
        $this->assertStringContainsString('Content-Signal', $content);
    }
}
