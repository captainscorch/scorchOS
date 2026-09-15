---
slug: 'captainscor.ch'
title: 'Meine persönliche Website, Portfolio mit Case Studies, Blog & Playground'
story_preview: 'captainscor.ch ist meine persönliche Website, Portfolio, Blog und Playground — eine Mischung aus meiner eigenen Ästhetik mit Inspiration von macOS, Kommandozeilen-Interfaces und modernen Webanwendungen. Gebaut mit Laravel, Inertia.js, Vue.js und Tailwind CSS'
fineprint: 'Ein Portfolio, das auch ein Statement ist. Von mir als kreative Sandbox gebaut — wo meine Liebe für Design, Entwicklung, Technologie und Details an einem Ort zusammenkommt.'
---

# Wo Design auf die Kommandozeile trifft

captainscor.ch ist mehr als ein Portfolio — es ist mein kreativer Playground, auf dem ich meine Liebe für sauberes Design mit meiner Leidenschaft für Entwicklung verbinde. Die Seite zieht Inspiration aus meinen Lieblings-Interfaces: der Eleganz von **macOS**, der rohen Kraft der **Kommandozeile** und der Flüssigkeit moderner **Webanwendungen**.

## Die Technologie, die ich liebe

Ich habe das mit meinem Lieblings-Stack gebaut: **Laravel** im Backend, **Vue.js** im Frontend, nahtlos verbunden mit **Inertia.js** für ein performantes SPA-Erlebnis mit smooth Page Transitions. **Tailwind CSS** übernimmt das Styling — mein aktueller Favorit, obwohl ich immer noch Nostalgie für die Bootstrap-Tage habe.

## Eine Landingpage, die spricht

Die Homepage öffnet mit einem bolden, großen Statement, das erklärt, wer ich bin und was ich mache. In den Text integriert sind die Logos von **unlimited.studio** (mein Studio) und **lyftd** (mein Passion Project).

## Bento Grid & Interaktives Verzeichnis

Anschließend präsentiert ein **Bento Grid Layout** verschiedene Bereiche der Seite. Der Portfolio-Bereich zeigt eine voll funktionsfähige **Verzeichnisbaumstruktur** — Ordner können auf- und zugeklappt werden und enthüllen Projektkategorien und Namen, so wie man es von seinem Betriebssystem gewohnt ist.

## Navigation & CMD+K

Die Top-Navigation ist **fluid und sticky** — mein Name links, ein CMD+K-Trigger rechts und kontextuelle Navigation in der Mitte, die sich je nach aktueller Seite anpasst. Die **CMD+K Command Palette** ist voll funktionsfähig:

- **Tastatur-Navigation:** Volle Pfeiltasten- und Enter-Unterstützung für Power-User
- **Sprachwechsel:** Umschalten zwischen Deutsch und Englisch
- **Dark/Light Mode:** Mit einem Old-School Klick-Soundeffekt
- **Funktionierendes Terminal:** Ein interaktives Terminal, in dem Nutzer Befehle wie `ls`, `cd /home`, `cd /portfolio` und `help` ausführen können, um die Seite via CLI zu navigieren

## Vollständige i18n & Präferenzen

- **Automatische Spracherkennung:** Die Seite erkennt Sprachpräferenzen aus den OS-Level-Einstellungen
- **Persistente Präferenzen:** Dark Mode ist Standard, aber Light Mode-Präferenzen werden im Local Storage gespeichert

## Error Pages als Kunst

Alle Error Pages sind gestaltete Erlebnisse. Jede Error Page — 404, 500, 503 und darüber hinaus — **simuliert ein Terminal-Fenster** komplett mit beweglichem Header, macOS-Style Fenster-Controls und einem voll interaktiven Command Prompt. Nutzer können Befehle wie `ls`, `cd`, `pwd` und `help` eingeben. Es ist eine Error Page, die man tatsächlich finden möchte.

## About Page Flexibilität

Die About Page bietet einen adaptiven Bio-Bereich, in dem Nutzer die **Textlänge mit Plus/Minus-Toggles anpassen** können, oder zwischen drei Ansichtsmodi wechseln: Prosa, Liste oder Timeline-Graph. Die Timeline kann nach Unternehmen gefiltert werden. Skills werden als **kategorisierte Tags** dargestellt, die Design Tools, Code-Sprachen und Technologien umfassen. Alternativ zeigt ein Skills-Modal meine technischen Fähigkeiten als **formatierte JavaScript-Datei** — weil, warum nicht.

## Portfolio: Bücher im Regal

Die Case Study-Übersicht bietet zwei unterschiedliche Ansichten: eine **vom Buchcover inspirierte Grid-Galerie**, oder eine **Rücken-Ansicht**, bei der farbenfrohe Buchrücken (basierend auf den Brand-Farben) sich vom unteren Rand der Seite stapeln, als würde man ein Bücherregal von vorne betrachten. Jede Ansicht ist darauf ausgelegt, sich einzigartig anzufühlen.

## Case Study Experience

Einzelne Case Studies bieten eine moderne **Swiper.js Galerie** mit automatischer Aspect Ratio-Handhabung, Video-Autoplay und einem Drawer, der sich öffnet und automatisch zum angeklickten Bild scrollt. Der Story-Preview-Text verwendet einen **Typing-Effekt**, der jede Case Study zum Leben erweckt.

## Blog: Markdown + Frontmatter

Beiträge sind schlichte **Markdown-Dateien** mit YAML-Frontmatter für Metadaten — geparst mit **gray-matter** und in ein kleines Composable gefüttert. Eine Datei pro Beitrag hält Content und Metadaten zusammen und ist damit leicht editierbar — ganz ohne CMS.

## Der Kontaktbereich entschlüsselt sich unter dem Cursor

Die Notes-Karte im Kontaktbereich ist kein normaler Text, sondern ein Canvas. Die Karte rendert als monochrome Teal-Chiffre, und nur was in der Nähe des Cursors liegt, löst sich in lesbare Zeichen auf — mit weicher Kante, damit die beiden Zustände ineinander übergehen statt umzuschalten. Bewegt man den Zeiger weg, verschlüsselt sich der Text wieder.

Das läuft über WebGL2 und Chromes HTML-in-Canvas-API, die die lebende Karte in eine Textur rastert, über die der Shader malen kann. Diese API steckt noch in einem Origin Trial, der Effekt erscheint also vorerst in Chrome. Wo der Browser das nicht kann — oder wo mit dem Finger statt mit dem Cursor bedient wird — rendert die Karte schlicht als normaler Text.

## Ein Seitenboden zum Abziehen

Der untere Rand jeder Seite verhält sich wie ein Blatt Papier. Führt man den Cursor an die Kante, rollt sich das Blatt weg und gibt darunter mein Logo als ASCII-Rendering frei. Die Marke ist ein echtes 3D-Objekt: Man greift sie und sie dreht sich mit der Bewegung mit, lässt man los, gleitet sie nach ein paar Sekunden Stillstand zurück in ihren Ausgangswinkel.

Dahin führte ein Umweg. Zuerst hatte ich den Peel über die ganze Seite gebaut, aber ein WebGL-Canvas um das komplette Layout schluckt den Decrypt-Reveal vollständig, und einen Kompromiss zwischen beiden gab es nicht — deshalb bleibt der Peel im unteren Band, wo er nichts kostet. Browser ohne die Canvas-API bekommen stattdessen einen animierten Chevron, der dieselbe Marke in einem Panel aufklappt.

## Ein lebendiger Playground

Diese Seite ist nie fertig. Hier experimentiere ich mit neuen Techniken, überschreite kreative Grenzen und zeige nicht nur, was ich gebaut habe — sondern wie ich denke.
