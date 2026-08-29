<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ContentSlugs;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaseStudyMarkdownController extends MarkdownExportController
{
    protected const EXPORT_STRIP_FRONTMATTER_KEYS = ['id', 'image', 'color', 'logo', 'width', 'height', 'spineHeight', 'media', 'fineprint_media', 'fineprint_media_alt'];

    protected const SOURCE_LABEL = 'Case study source';

    protected const SOURCE_FALLBACK_TITLE = 'View case study';

    public function __invoke(Request $request, ContentSlugs $contentSlugs, string $slug): Response
    {
        if (! $contentSlugs->isValidCaseStudy($slug)) {
            abort(404);
        }

        $path = $contentSlugs->caseStudySourceMarkdownPath($slug, $this->resolveLocale($request));
        if ($path === null) {
            abort(404);
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            abort(404);
        }

        $htmlUrl = url("/case-study/{$slug}");
        $contents = $this->prepareMarkdownForExport($this->stripAuthoringDirectives($contents), $htmlUrl);

        return $this->markdownResponse($contents, $htmlUrl);
    }
}
