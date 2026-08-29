<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddMarkdownAlternateLinkHeader
{
    /**
     * HTML pages that expose a public `{path}.md` export.
     */
    private const PATH_PATTERN = '#^(blog/[^/]+/[^/]+|case-study/[^/]+)$#';

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $path = $request->path();
        if (str_ends_with($path, '.md') || preg_match(self::PATH_PATTERN, $path) !== 1) {
            return $response;
        }

        $response->headers->set(
            'Link',
            '<'.url('/'.$path.'.md').'>; rel="alternate"; type="text/markdown"',
            false,
        );

        return $response;
    }
}
