<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ContentSlugs;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogPostMarkdownController extends MarkdownExportController
{
    protected const EXPORT_STRIP_FRONTMATTER_KEYS = ['components', 'featured'];

    protected const SOURCE_LABEL = 'Article source';

    protected const SOURCE_FALLBACK_TITLE = 'View article';

    public function __invoke(Request $request, ContentSlugs $contentSlugs, string $category, string $slug): Response
    {
        if (! $contentSlugs->isValidBlogPost($slug)) {
            abort(404);
        }

        $path = $contentSlugs->blogPostSourceMarkdownPath($slug, $this->resolveLocale($request));
        if ($path === null) {
            abort(404);
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            abort(404);
        }

        $htmlUrl = url("/blog/{$category}/{$slug}");
        $contents = $this->prepareMarkdownForExport($this->stripAuthoringDirectives($contents), $htmlUrl);

        return $this->markdownResponse($contents, $htmlUrl);
    }
}
