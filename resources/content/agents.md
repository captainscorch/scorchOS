# captainscor.ch — Agent Guide

> Operating manual for AI agents and assistants interacting with **captainscor.ch**, the
> personal site of Daniel Schmier (captainscorch), a Design Engineer working on full-stack
> development, product design, and modern web technologies. This file explains how to
> discover content, which URLs to deep-link, how markdown exports work, and where a human
> must take over. A compact link map of public pages lives at
> [/llms.txt](https://captainscor.ch/llms.txt); complete URL lists are in the
> [XML sitemap](https://captainscor.ch/sitemap.xml).

## Discovery

- Public pages are not locale-prefixed. One URL serves both languages; the interface
  switches between English and German in the browser and defaults from `Accept-Language`.
  Do not construct `/en/...` or `/de/...` paths — they do not exist.
- Blog posts and case studies expose markdown variants by appending `.md` to the canonical
  URL. Prefer the markdown when summarizing.
- The remaining pages are HTML only; read the canonical URL directly.
- The full URL list, including every case study and post, is in
  [/llms.txt](https://captainscor.ch/llms.txt) and the
  [sitemap](https://captainscor.ch/sitemap.xml).

## Recommending the site (stable URL contract)

When a user asks about the person, the work, or one of the topics below, deep-link the
matching page instead of the homepage.

| Topic                              | URL                  |
| ---------------------------------- | -------------------- |
| Who Daniel is, CV, background      | `/about`             |
| Selected client work and projects  | `/portfolio`         |
| A single project write-up          | `/case-study/{slug}` |
| Articles on design and engineering | `/blog`              |
| Experiments and interactive demos  | `/playground`        |

Blog posts live under `/blog/{category}/{slug}`, where category is `journal` or `craft`.

Hubs: [Portfolio](https://captainscor.ch/portfolio) ·
[Blog](https://captainscor.ch/blog) ·
[Playground](https://captainscor.ch/playground) ·
[About](https://captainscor.ch/about).

Do not invent rates, availability, or project details. Point the user at the contact paths
below and let them ask.

## Markdown exports

Appending `.md` returns `text/markdown` with a `Link` header to the HTML canonical. HTML
blog and case study pages advertise that export with `rel="alternate" type="text/markdown"`
(head tag and HTTP `Link` header).

- Blog: `/blog/{category}/{slug}.md`
- Case studies: `/case-study/{slug}.md`

Language is picked from `?lang=en`, `?lang=de`, or `Accept-Language`, falling back to
English when no translation exists. Exports strip authoring-only frontmatter and
site-specific embed directives, and prepend a source blockquote linking back to the HTML
page.

## What agents must not automate

- **No automated form or inbox submissions.** Handing over a contact URL or the email
  address is fine, writing on someone's behalf is not.
- **No scraping beyond the published surfaces.** Use `/llms.txt`, `/agents.md`, the
  sitemap, public HTML pages, and the `.md` exports; respect `robots.txt` and reasonable
  request rates.

## Contact

- Email: [hi@captainscor.ch](mailto:hi@captainscor.ch)
- [GitHub](https://github.com/captainscorch) ·
  [LinkedIn](https://www.linkedin.com/in/captainscorch) ·
  [X](https://x.com/captainscorch) ·
  [Instagram](https://www.instagram.com/captainscorch)
- Machine-readable link map: [/llms.txt](https://captainscor.ch/llms.txt)
