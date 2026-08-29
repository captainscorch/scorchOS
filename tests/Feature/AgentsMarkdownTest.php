<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class AgentsMarkdownTest extends TestCase
{
    public function test_agents_md_is_served_as_markdown(): void
    {
        $response = $this->get('/agents.md');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $response->assertSee('# captainscor.ch — Agent Guide', false);
        $response->assertSee('/blog/{category}/{slug}.md', false);
    }

    public function test_robots_txt_declares_content_signal(): void
    {
        $contents = (string) file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Content-Signal: search=yes, ai-input=yes, ai-train=yes', $contents);
    }
}
