---
slug: cms-free-blog-with-gray-matter
title: 'A CMS-Free Blog with Markdown and Gray Matter'
date: '2026-04-25'
category: ['Craft']
tags: ['markdown', 'gray-matter', 'content-workflow', 'ai']
excerpt: How I structure blog posts as plain Markdown files with frontmatter metadata, keeping the writing surface direct while still giving the application everything it needs to render, filter, and translate articles.
---

The format you write in shapes the work you do. If the format is hard to read, the writing feels heavy. If it gets out of the way, the writing tends to flow.

Every blog post on this site is a single Markdown file. The metadata sits at the top as frontmatter, the article sits below it, and [`gray-matter`](https://github.com/jonschlinkert/gray-matter) does the small job of splitting the two when the app boots. No CMS, no second source of truth, no Markdown stuffed inside a string.

It sounds like a small detail, and that's exactly the point.

## Why JSON Was the Wrong Surface

The first version of this blog stored posts as JSON files. The article body lived inside a string, with escaped line breaks and quoted Markdown sitting next to the metadata. It worked, but it made writing feel like editing a config file.

**Before — JSON**

```json
{
    "slug": "concentric-border-radius",
    "title": "Mastering Concentric Border Radius",
    "date": "2026-01-28",
    "category": ["Craft"],
    "tags": ["ui", "css", "border-radius", "design-engineering"],
    "excerpt": "Often overlooked, concentric border radius is a small detail that makes interfaces feel significantly more polished and intentional. Here's why it matters and how to implement it.",
    "content": "In design engineering, premium quality is often a byproduct of accumulated micro-decisions. One such decision—frequently ignored in rapid development—is the logic behind nested corners.\n\n## Why Does It Matter?\n\nOur visual system is hardcoded to seek out geometric continuity..."
}
```

**After — Markdown**

Front matter is valid YAML. Label the fence as `yaml`, not `markdown`, so keys like `slug` are highlighted separately from the values next to them—same idea as JSON.

**Front matter (YAML between the `---` delimiters)**

```yaml
---
slug: concentric-border-radius
title: 'Mastering Concentric Border Radius'
date: '2026-02-04'
category: ['Craft']
tags: ['ui', 'css', 'border-radius', 'design-engineering']
excerpt: Often overlooked, concentric border radius is a small detail that makes interfaces feel significantly more polished and intentional. Here's why it matters and how to implement it.
components: ['ConcentricDemo']
featured: true
---
```

**Body (Markdown below the second `---`)**

```markdown
In the design world, premium quality is often a byproduct of accumulated micro-decisions. One such decision—frequently ignored in rapid development cycles—is the logic behind nested corners.

## Why Does It Matter?

Our visual system is hardcoded to seek out geometric continuity...
```

Same structural information, completely different writing experience. The app still gets a clean, typed object up top. The body finally looks like prose again.

## One Line, That's All

`gray-matter` does almost nothing here, which is why it's so good. The whole API I lean on is one line:

```ts
const { data, content } = matter(raw);
```

`data` becomes the frontmatter object. `content` becomes the article body. From that single split everything else falls out — typed metadata for the app, plain Markdown for the writer, and a file format that's portable and trivial for both humans and LLMs to reason about.

## From Files to Pages

The blog index and the article view both work off the same idea of a post, so what you see in a list, filter, or after switching language lines up with what you open. On the post itself, that structure fills in title, date, and the surrounding page; the content underneath stays Markdown, with a few custom pieces when an article needs a demo, a captioned image, or something that plain paragraphs cannot carry.

The German file is mostly the language layer—title, excerpt, optional tags, and the body. The shared post shape (date, category, slug, the rest) still comes from the English source, so you define structure in one place instead of maintaining two front matters that have to stay in sync.

## A Format That Plays Well With AI

This setup is also the reason AI works so well with this codebase. A Markdown file with frontmatter is the closest thing to a 'self-describing' content unit you can hand to an agent: explicit metadata, natural language body, predictable shape, and no hidden state.

When I ask an agent to refine a section, translate a post, or tighten an excerpt, I point it at one file. There's no API to authenticate against, no rich-text JSON tree to walk, no database schema to respect. The article _is_ the interface.

The same property is what makes this approachable for non-technical writers. They don't need to understand the stack to contribute. The metadata is visible at the top, the body is just text, and the structure is obvious without being intimidating.

## Final Thoughts

Markdown with `gray-matter` gives me the useful parts of a content system — typed metadata, structured queries, deterministic rendering — without the administrative overhead that usually comes with it. The content stays portable, and the article under the front matter is plain flowing text, not a body crammed in a string with escaped line breaks. It makes writing feel like writing, not like editing a config file.

It's a small choice, but those are usually the ones that compound.
