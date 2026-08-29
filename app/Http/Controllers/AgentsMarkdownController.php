<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class AgentsMarkdownController extends Controller
{
    public function __invoke(): Response
    {
        $path = resource_path('content/agents.md');
        if (! is_readable($path)) {
            abort(404);
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            abort(404);
        }

        return response($contents, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
