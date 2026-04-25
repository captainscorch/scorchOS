---
slug: cms-free-blog-with-gray-matter
title: 'Ein CMS-freier Blog mit Markdown und Gray Matter'
excerpt: 'Wie ich Blogposts als reine Markdown-Dateien mit Frontmatter-Metadaten strukturiere – direkt beim Schreiben und gleichzeitig vollständig genug, damit die Anwendung alles bekommt, was sie zum Rendern, Filtern und Übersetzen der Artikel braucht.'
tags: ['Markdown', 'Gray Matter', 'Content-Workflow', 'KI']
---

Das Format, in dem man schreibt, prägt die Arbeit, die dabei entsteht. Wenn das Format schwer leserlich ist, fühlt sich auch das Schreiben schwer an. Bleibt es unsichtbar, fließt der Text von selbst.

Jeder Blogpost auf dieser Website ist eine einzelne Markdown-Datei. Die Metadaten stehen oben als Frontmatter, der Artikel darunter und [`gray-matter`](https://github.com/jonschlinkert/gray-matter) übernimmt beim Boot der App die kleine Aufgabe, beides voneinander zu trennen. Kein CMS, keine zweite Source of Truth, kein Markdown, das in einen String gequetscht werden muss.

Es klingt nach einem kleinen Detail – und genau das ist der Punkt.

## Warum JSON die falsche Oberfläche war

Die erste Version dieses Blogs hat Posts als JSON-Dateien gespeichert. Der Artikeltext lebte in einem String, mit escaped Line Breaks und gequotetem Markdown direkt neben den Metadaten. Es hat funktioniert, aber das Schreiben fühlte sich an wie das Bearbeiten einer Config-Datei.

**Vorher — JSON**

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

**Nachher — Markdown**

Das Frontmatter ist gültiges YAML. Kennzeichne den Code-Block als `yaml` (nicht `markdown`), dann werden Schlüssel wie `slug` getrennt vom daneben stehenden Wert eingefärbt – wie bei JSON.

**Front matter (YAML zwischen den `---`‑Markern)**

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

**Text (Markdown unter dem zweiten `---`)**

```markdown
In the design world, premium quality is often a byproduct of accumulated micro-decisions. One such decision—frequently ignored in rapid development cycles—is the logic behind nested corners.

## Why Does It Matter?

Our visual system is hardcoded to seek out geometric continuity...
```

Dieselbe strukturelle Information, ein komplett anderes Schreib-Erlebnis. Die App bekommt weiterhin ein sauberes, strukturiertes Frontmatter-Objekt. Der Text sieht endlich wieder nach Fließtext aus.

## Eine Zeile, die reicht

`gray-matter` macht hier fast nichts und genau deshalb ist es so gut. Die ganze API, die ich nutze, ist eine Zeile:

```ts
const { data, content } = matter(raw);
```

`data` wird zum Frontmatter-Objekt. `content` wird zum Artikeltext. Aus diesem einen Split fällt alles Weitere von selbst heraus – strukturierte Metadaten für die App, sauberes Markdown für die Autorin oder den Autor und ein Dateiformat, das portabel und sowohl für Menschen als auch für LLMs einfach zu verstehen ist.

## Von den Dateien zu den Seiten

Die Übersicht und die Artikelseite arbeiten mit derselben Vorstellung von einem Post, damit das, was in Liste, Filter oder Sprache sichtbar ist, zu dem passt, was man öffnet. Auf der Seite füllt dieselbe Struktur Titel, Datum und den Rahmen um den Text; der eigentliche Inhalt bleibt Markdown, ergänzt um Demos, Bilder oder andere Bausteine, wenn der Artikel mehr braucht als fließenden Text.

Die deutsche Datei enthält vor allem den übersetzten Inhalt – Titel, Excerpt, optional Tags, Fließtext. Was zum Post „gehört“ (Datum, Kategorie, Slug und der Rest), kommt aus dem englischen Original. Die Struktur lebt an einer Stelle, nicht doppelt pro Sprache.

## Ein Format, das gut mit KI funktioniert

Dieses Setup ist auch der Grund, warum KI mit dieser Codebasis so gut funktioniert. Eine Markdown-Datei mit Frontmatter ist das Nächste an einer „selbsterklärenden“ Content-Einheit, das man einem Agent geben kann: explizite Metadaten, natürliche Sprache im Body, eine vorhersehbare Form und kein versteckter Zustand.

Wenn ich einen Agent bitte, einen Abschnitt zu überarbeiten, einen Post zu übersetzen oder einen Excerpt zu schärfen, zeige ich ihm eine einzige Datei. Es gibt keine API, gegen die er sich authentifizieren muss, keinen Rich-Text-JSON-Tree, den er durchwandern muss, kein Datenbankschema, das er respektieren muss. Der Artikel _ist_ die Schnittstelle.

Dieselbe Eigenschaft macht das Setup auch für nicht-technische Autorinnen und Autoren zugänglich. Sie müssen den Stack nicht verstehen, um beizutragen. Die Metadaten sind oben sichtbar, der Body ist einfach Text und die Struktur ist offensichtlich, ohne einschüchternd zu wirken.

## Abschließende Gedanken

Markdown mit `gray-matter` gibt mir die nützlichen Teile eines Content-Systems – strukturierte Metadaten, strukturierte Queries, deterministisches Rendering – ohne den administrativen Aufwand, der damit normalerweise einhergeht. Der Content bleibt portabel und unter dem Frontmatter steht der Artikel als Fließtext – nicht als Wert in einem String mit escaped Line Breaks. So fühlt sich Schreiben nicht mehr an wie die Bearbeitung einer Config-Datei.

Es ist eine kleine Entscheidung, aber genau diese sind oft die, die sich über die Zeit auszahlen.
