---
slug: unlimited-studio
title: 'Meine Studio-Website und ihr Playground: Design, Entwicklung und ein KI-Berater'
story_preview: 'unlimited.studio ist die Website meines Studios: ein dunkles Editorial-System in Laravel, Inertia und Vue mit Case Studies und Posts ohne CMS, Leistungsseiten, einer Google-Ads-Landingpage, einem KI-Berater, der Anfragen annimmt, und einem begehbaren 3D-Playground des Büros in Kassel.'
fineprint: 'Von mir für mein eigenes Studio entworfen und gebaut, seit 2018 in Betrieb und 2026 neu aufgebaut. Die Seite läuft auf Laravel, Inertia und Vue mit serverseitigem Rendering, der Playground auf React Three Fiber. Alles Gezeigte ist live.'
---

# Eine Studio-Seite, die so viel arbeitet wie das Studio

unlimited.studio ist die Seite meines Studios in Kassel, und seit 2018 bin ich mein eigener Kunde. Der Umbau 2026 hat daraus ein dunkles Editorial-System gemacht: schwarze Fläche, gestrichelte Linien, ein kräftiges Markenrot, weiße Kontrastbänder und Oberflächen wie ein Terminal. Omnes Pro für die großen Zeilen, Geist für den Fließtext, Geist Mono in Versalien für jedes Label. Der Punkt vor einer Headline ist das Hauszeichen, und er ist immer rot.

## Inhalte ohne CMS

Case Studies, Blogposts, Leistungsseiten und Kundeneinträge sind Markdown-Dateien mit Frontmatter, auf dem Server geparst, auf dem Client typisiert. Englisch ist die Quelle, Deutsch überlagert die Texte. Jede Case Study und jeder Post existiert zusätzlich als `.md`-URL, dazu liefert die Seite `llms.txt` und `agents.md`, damit Sprachmodelle dieselben Inhalte lesen wie Menschen. Ein Sitemap-Eintrag pro Sprache, hreflang in beide Richtungen, x-default auf Englisch.

## Leistungsseiten, die die Frage beantworten

Dreizehn Leistungsseiten, alle aus denselben Bausteinen: Fokus, ausgewählte Kunden, Überblick, Säulen, Ablauf, ein passendes Projektbeispiel, verwandte Case Studies, Testimonials, weiterführende Posts, FAQ und ein Glossar. Die verwandten Case Studies sind ein Dreierraster, pro Seite gewählt. Daneben eine regionale Landingpage für Kassel und eine Google-Ads-Landingpage für die Starter-Website zum Festpreis; die Ads-Seite passt ihre Headline an die Anzeigengruppe an und zeigt die Demo-Galerie nach Branche.

## Die kleinen Dinge

Ein Befehlsmenü auf ⌘K, ein eigener Cursor, ein Intro-Loader, ein Kundenraster, das beim Hover die Website des Kunden zeigt, ein Team-Bereich, in dem jede Person unter dem Cursor einen eigenen Satz trägt, Testimonials aus Google-Rezensionen und eine Preissektion mit animierten Ablauf-Spuren.

## Der Playground

Das Büro in Kassel als begehbarer 3D-Raum: orange Wand, Küchenzeile, Meetingraum mit Glaswänden, die Arbeitsplätze, Ilvy im Körbchen. Gebaut mit React Three Fiber, beleuchtet vom echten Sonnenstand über Kassel, freie Kamera mit Stationen und Minimap. Die Case Studies schweben als Hologramme über dem Harz-Tisch, der Fernseher ist eine Konsole mit fünf Spielen und globalen Bestenlisten, jeder Monitor startet unlimited.os mit Terminal, Finder und Paint, und studio.js ist ein Code-Editor, der den Raum in einem Web Worker steuert. Das Gästebuch schreibt auf die orange Wand, mit reCAPTCHA, Schimpfwortfilter und Moderationslink. Besucher sehen einander live als rote Marker über Laravel Reverb. Alles zweisprachig, nichts wird über irgendjemanden gespeichert.

## unlimited.gpt, der Berater

Die Seite hat auf jeder Unterseite einen KI-Berater: ⌘J öffnet ihn, der leuchtende Button auch. Er kennt Leistungen, Case Studies, Preisspannen und Ablauf aus einer generierten Wissensdatei und verlinkt, statt zu navigieren. Beschreibt ein Besucher ein Projekt, schlägt der Berater eine Anfrage vor, der Server legt einen Einmal-Vorschlag an, und der Besucher bestätigt im Formular; Name und E-Mail sieht das Modell nie. Moderation, Verlauf nur auf dem Server, striktes Ausgabeschema, erlaubte Linkziele, Ratelimits. Derselbe Berater empfängt Besucher im Playground und reicht Anfragen signiert weiter.

## Von der Frage zur Anfrage

Wer lieber schreibt als chattet, landet beim Kontaktformular im Footer. Es fragt Preismodell und Art des Projekts mit wenigen Klicks ab, mit Honeypot, Zeitprüfung, Ratelimit und einwilligungsbasierter Attribution der Lead-Quelle. Der Cookie-Consent ist das Hausmuster: zwei Kategorien, Tag Manager erst nach Zustimmung, Consent Mode so verdrahtet, dass die Pixel in der richtigen Reihenfolge feuern.
