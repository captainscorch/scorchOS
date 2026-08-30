---
slug: unlimited-dock
title: 'Unser Studio-Betriebssystem — Tasks, Zeit, Budgets & Angebote in einer Plattform'
story_preview: 'unlimited.dock ist die Operations-Plattform, die ich für unser Studio gebaut habe — Linear-artige Tasks, Zeiterfassung, Team-Verfügbarkeit, Budgets und Angebote in einer tastaturgesteuerten App. Sie hat unser Productive.io-Abo komplett ersetzt und trägt rund 8.000 Tasks und 20.000 erfasste Stunden'
fineprint: 'Alleine designt und gebaut, jeden Arbeitstag im Produktiveinsatz für ein Fünf-Personen-Studio. Task-Namen, Kunden und Projekte in den Screenshots sind randomisierte Platzhalter — die Arbeit dahinter ist echt.'
---

# Unser Studio lief auf einem Abo. Jetzt läuft es auf Software, die ich gebaut habe.

Jahrelang hat Productive.io unsere Tasks, Zeiterfassung und Budgets verwaltet — ein fähiges Tool, das sich für ein Fünf-Personen-Studio immer eine Nummer zu groß anfühlte, zu einem Preis, der mit jedem Seat und jeder Feature-Stufe wuchs. Linear hatte das Interaktionsmodell, das ich eigentlich wollte, aber keine Ahnung, was ein Agentur-Budget ist. Also habe ich unlimited.dock gebaut: die Tiefe einer Agentur-Plattform mit der Geschwindigkeit von Linear, exakt zugeschnitten auf die Arbeitsweise unseres Studios.

## Productive.io komplett ersetzt

Das war kein Experiment im Parallelbetrieb, sondern ein vollständiger Cutover. Ich habe eine Import-Pipeline geschrieben, die unsere komplette Historie aus Productives API migriert hat: Tasks, Projekte, Zeiteinträge, Budgets und Kommentare, dazu ein Archiv-Export von 51 Ressourcentypen und mehreren Gigabyte Anhängen. Danach ist das Abo endgültig ausgelaufen. Heute trägt die Plattform rund 8.000 Tasks, 20.000 erfasste Stunden und Budgets für mehr als 90 Kunden.

## Das Tech Stack

Ein **Laravel**-Backend mit einem **Vue 3**-Frontend via **Inertia.js** — eine Codebasis, keine separate API. **Tailwind CSS** und **shadcn-vue** treiben eine schnelle, minimale UI an, **TipTap** verarbeitet Rich Text mit Markdown als Speicherformat, und **MySQL** hält die Daten.

- **Testing:** Über 1.200 PHPUnit-Tests laufen bei jeder Änderung — Abrechnungslogik ist kein Ort für Überraschungen
- **PDF-Engine:** Angebote und Abrechnungsreports rendern serverseitig via dompdf mit Briefkopf und Hausschriften des Studios
- **Integrationen:** Transaktionsmails über Resend, Billing-Digests und Budget-Warnungen nach Slack, Kontakt-Import aus Zoho

## Tasks nach dem Linear-Prinzip

Das Task-System übernimmt das Interaktionsmodell, das Linear so mühelos macht, und wendet es auf Agenturarbeit an:

- **Status-Ringe & Peek-Panel:** Zustände auf einen Blick erfassen, jeden Task im Seitenpanel öffnen, ohne die Liste zu verlieren
- **Command Palette:** Von überall zu jedem Task, Projekt oder Kunden springen — die Maus ist optional
- **Kunden-Kennungen:** Tasks werden pro Kunde nummeriert, eine Referenz wie eine Ticketnummer bedeutet im Gespräch also etwas
- **Echte Struktur:** Subtasks mit Blocking, wiederkehrende Tasks, Milestones, Prioritäten und private Tasks, die Kunden nie sehen

## Zeit, Kapazität & Verfügbarkeit

Jede Stunde landet auf einem Projekt, optional auf Task und Budget-Position:

- **Timer & Timesheets:** Einen Timer aus jedem Task starten oder die Woche in einem auf Geschwindigkeit gebauten Raster füllen
- **Team-Verfügbarkeit:** Arbeitstage und Wochenkapazität pro Mitglied, die Planung zeigt also, was wirklich frei ist
- **Faire Rundung:** Abrechenbare Zeit rundet in 30-Minuten-Schritten, einmal pro Budget-Position und Periode — der Rundungsmodus wird beim Anlegen eines Budgets eingefroren, vergangene Perioden verschieben sich nie rückwirkend

## Budgets & Abrechnung

Der Accounting-Kern bildet ab, wofür wir Productive genutzt haben, minus allem, was wir nie angefasst haben:

- **Budget-Positionen & Retainer:** Feste Budgets und wiederkehrende Retainer-Perioden, mit getrennt erfassten geleisteten und abrechenbaren Stunden
- **Immer berechnet, nie gecacht:** Burn, Profit und Kapazität werden beim Lesen berechnet — keine denormalisierten Zahlen, die auseinanderlaufen
- **Perioden-Reports:** Abrechnungsfertige PDF- und CSV-Reports pro Periode, mit Digest und Budget-Warnungen nach Slack
- **Privacy by Design:** Kostensätze und Profit verlassen den Server für Nicht-Admin-Rollen nie — durch Tests abgesichert

## Vom Angebot zum Projekt, automatisch

Das neueste Modul schließt den Kreis zwischen Verkaufen und Umsetzen:

- **Angebots-Editor:** Positionen aus einer wiederverwendbaren Artikel-Library, Steuersätze pro Zeile, Rabatte und Nutzungsrechte, berechnet aus vier Lizenzparametern
- **Öffentliche Angebotsseite:** Kunden nehmen auf einer tokenisierten Seite an oder lehnen ab — kein Login, kein PDF-Ping-Pong, mit View-Tracking, das Mail-Scanner-Bots herausfiltert
- **Der automatische Teil:** Ein angenommenes Angebot wird zum Projekt, mit Budgets aus seinen Positionen — die Übergabe vom Vertrieb an die Umsetzung ist ein einziger Klick

## Ein Cockpit statt Kunden-Logins

Kunden bekommen keine Accounts — eine bewusste Entscheidung. Sie bekommen einen tokenisierten Cockpit-Link pro Projekt: eine Roadmap nach Themen gruppiert, einen Kapazitätsbalken mit Verbrauch und Rest, und ein Feature-Board, auf dem das Team Vorschläge zu echten Tasks befördern kann.

## Gebaut für Tastatur-Geschwindigkeit

Jede Liste ist filterbar, jede Aktion hat einen Shortcut, und das Interface ist auf die Realität eines Tools getrimmt, das dutzende Male am Tag benutzt wird: Latenz ist ein Feature. Keine Marketing-Seiten, keine Onboarding-Flows, keine Einstellungen für hypothetische Firmen — Software für fünf konkrete Menschen, und genau deshalb schnell.

## Bewusste Auslassungen

unlimited.dock hat kein CRM, kein Rechnungsmodul und keinen Resource-Planner — das lebt woanders besser, und eine Roadmap-Datei führt jede Auslassung mit Absicht auf. Weniger zu bauen hat es überhaupt erst möglich gemacht, eine kommerzielle Plattform komplett zu ersetzen: Es kann alles, was unser Studio braucht, und nichts, was ein anderes bräuchte.
