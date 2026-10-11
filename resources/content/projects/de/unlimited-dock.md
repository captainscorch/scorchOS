---
slug: unlimited-dock
title: 'Unser Studio-Betriebssystem: von der ersten Anfrage bis zur bezahlten Rechnung'
story_preview: 'unlimited.dock ist das Betriebssystem, das ich für unser Studio gebaut habe, von der ersten Anfrage bis zur bezahlten Rechnung. Claude arbeitet über einen MCP-Server und eine REST-API mit.'
fineprint: 'Allein designt und gebaut, jeden Arbeitstag im Einsatz für ein Studio mit fünf Leuten. Kunden, Projekte, Tasks, Beträge und Stunden in den Screenshots sind Platzhalter.'
---

# Unser Studio lief auf Abos. Jetzt läuft es auf Software, die ich gebaut habe.

Jahrelang lief unser Studio auf gemieteter Software. Productive.io hielt Tasks, Zeiten und Budgets, Zoho Books schrieb die Rechnungen, und Tabellen führten Buch über Hosting und Server. Jedes Tool rechnete monatlich ab, keins kannte das andere, und ein Kunde lebte an vier Orten. Linear hatte das Interaktionsmodell, das ich wollte, aber keine Ahnung, was ein Agentur-Budget ist. Also habe ich unlimited.dock gebaut: ein System, zugeschnitten auf die Arbeitsweise unseres Studios, von der ersten Anfrage bis zur bezahlten Rechnung.

## Productive.io komplett ersetzt

Das war kein Experiment im Parallelbetrieb, sondern ein vollständiger Cutover. Ich habe eine Import-Pipeline geschrieben, die unsere komplette Historie aus der API von Productive geholt hat: Tasks, Projekte, Zeiteinträge, Budgets und Kommentare, dazu ein Archiv-Export von 51 Ressourcentypen und mehreren Gigabyte Anhängen. Danach ist das erste Abo endgültig ausgelaufen. Heute trägt die Plattform rund 8.000 Tasks, 20.000 erfasste Stunden und Budgets für mehr als 90 Kunden.

## Der Tech Stack

Ein **Laravel**-Backend mit einem **Vue 3**-Frontend über **Inertia.js**, eine Codebasis für die App. Daneben stehen eine **REST-API** und ein **MCP-Server** für Agents. **Tailwind CSS** und **shadcn-vue** tragen eine schnelle, reduzierte UI, **TipTap** verarbeitet Rich Text mit Markdown als Speicherformat, und **MySQL** hält die Daten.

- **Testing:** Über 2.000 PHPUnit-Tests laufen bei jeder Änderung. Abrechnungslogik ist kein Ort für Überraschungen
- **PDF-Engine:** Angebote, Rechnungen und Abrechnungsreports rendern serverseitig über dompdf, mit Briefkopf und Hausschriften des Studios
- **Integrationen:** Transaktionsmails über Resend, Digests und Warnungen nach Slack, das Rechnungsarchiv in Google Drive, das Server-Inventar aus Laravel Forge

## Tasks nach dem Linear-Prinzip

Das Task-System übernimmt das Interaktionsmodell, das Linear so mühelos macht, und wendet es auf Agenturarbeit an:

- **Status-Ringe & Peek-Panel:** Zustände auf einen Blick erfassen, jeden Task im Seitenpanel öffnen, ohne die Liste zu verlieren
- **Command Palette:** Von überall zu jedem Task, Projekt oder Kunden springen. Die Maus ist optional
- **Kunden-Kennungen:** Tasks werden pro Kunde nummeriert, eine Referenz wie eine Ticketnummer bedeutet im Gespräch also etwas
- **Echte Struktur:** Subtasks mit Blocking, wiederkehrende Tasks, Milestones, Prioritäten und private Tasks, die Kunden nie sehen

## Gebaut für Tastatur-Geschwindigkeit

C legt einen Task an, Cmd+K findet jeden Task, jedes Projekt und jeden Kunden, T startet den Timer. Jede Liste ist filterbar, jede Aktion hat einen Shortcut, und das Interface ist auf ein Tool getrimmt, das dutzende Male am Tag benutzt wird: Latenz ist ein Feature. Keine Marketing-Seiten, keine Onboarding-Flows, keine Einstellungen für hypothetische Firmen. Software für fünf konkrete Menschen, und genau deshalb schnell.

## Zeit, Kapazität & Verfügbarkeit

Jede Stunde landet auf einem Projekt, optional auf Task und Budget-Position:

- **Timer & Timesheets:** Einen Timer aus jedem Task starten oder die Woche in einem Raster füllen, das auf Tempo gebaut ist
- **Team-Verfügbarkeit:** Arbeitstage und Wochenkapazität pro Person, die Planung zeigt also, was wirklich frei ist
- **Faire Rundung:** Abrechenbare Zeit rundet in 30-Minuten-Schritten, einmal pro Budget-Position und Periode. Der Rundungsmodus wird beim Anlegen eines Budgets eingefroren, vergangene Perioden verschieben sich nie rückwirkend

## Budgets & Abrechnung

Der Accounting-Kern bildet ab, wofür wir Productive genutzt haben, minus allem, was wir nie angefasst haben:

- **Budget-Positionen & Retainer:** Feste Budgets und wiederkehrende Retainer-Perioden, geleistete und abrechenbare Stunden getrennt erfasst
- **Immer berechnet, nie gecacht:** Burn, Profit und Kapazität werden beim Lesen berechnet. Keine denormalisierten Zahlen, die auseinanderlaufen
- **Perioden-Reports:** Abrechnungsfertige PDF- und CSV-Reports pro Periode, mit Digest und Budget-Warnungen nach Slack
- **Privacy by Design:** Kostensätze und Profit verlassen den Server für Nicht-Admin-Rollen nie, durch Tests abgesichert

## Von der Anfrage zum Projekt

Verkaufen und Umsetzen leben im selben Tool:

- **Akquise:** Jede Anfrage vom ersten Kontakt bis gewonnen oder verloren, als Board oder Liste, mit Verantwortlichem, nächstem Schritt und Fälligkeit. Fristen von Ausschreibungen stehen auf der Karte
- **Wochen-Digest:** Fällige Nachfass-Termine kommen als eine Nachricht pro Person am Morgen, montags bekommt Slack den Überblick: was fällig ist, was überfällig ist, was sich bewegt hat
- **Angebots-Editor:** Positionen aus einer wiederverwendbaren Artikel-Library, Steuersätze pro Zeile, Rabatte und Nutzungsrechte, berechnet aus vier Lizenzparametern
- **Öffentliche Angebotsseite:** Kunden nehmen auf einer tokenisierten Seite an oder lehnen ab. Kein Login, kein PDF-Ping-Pong, mit View-Tracking, das Mail-Scanner-Bots herausfiltert
- **Der automatische Teil:** Ein angenommenes Angebot wird zum Projekt, mit Budgets aus seinen Positionen. Die Übergabe vom Vertrieb an die Umsetzung ist ein einziger Klick

## Rechnungen ohne Zoho

Jahrelang hat Zoho Books unsere Rechnungen geschrieben, mit eigenem Monatstarif, während das Dock schon jedes Budget, jede Stunde und jedes angenommene Angebot kannte. Die Rechnung war das letzte Dokument, das woanders entstand. Jetzt schreibt das Dock sie selbst:

- **Entwürfe aus dem, was da ist:** Eine Rechnung startet beim Kunden, bei einer abgeschlossenen Budget-Periode oder bei einem angenommenen Angebot, auch als Anzahlung, Teil- oder Schlussrechnung
- **Nummern, die sich nie wiederholen:** Beim Ausstellen bekommt die Rechnung eine lückenlose Nummer und ist danach gesperrt. Korrekturen laufen über Storno oder Gutschrift, und was sich nicht zurücknehmen lässt, löst erst aus, wenn man den Knopf gedrückt hält
- **Kundenseite:** Jede Rechnung hat eine Seite für den Kunden, mit dem offenen Betrag und einem GiroCode für die Banking-App
- **XRechnung:** Jede Rechnung gibt es als PDF auf Deutsch oder Englisch und als XRechnung 3.0, geprüft mit dem offiziellen KoSIT-Validator
- **SEPA & Mahnwesen:** Lastschrift-Mandate in der Kundenakte, eine Lastschrift-Liste je Einzugstag, zwei automatische Zahlungserinnerungen und eine Mahnung nach Freigabe
- **Bankabgleich:** Der Kontoauszug kommt als CSV rein. Zahlungen mit Rechnungsnummer oder von einem bekannten Zahler buchen sich selbst
- **Wiederkehrende Profile:** Hosting, Lizenzen und Retainer laufen als Profile mit Tageslauf, als Entwurf oder direkt versendet
- **Änderungsprotokoll:** Jede Änderung an Finanz- und Stammdaten landet in einem Protokoll, das nur wächst, mit wer, wann, alter und neuer Wert, so wie es die GoBD verlangen

## Zahlen auf einen Blick

- **Dashboard:** Umsatz im laufenden Monat gegen denselben Zeitraum im Vorjahr, der wiederkehrende Anteil, die abrechenbare Quote, Stunden und offene Posten
- **Umsatz seit 2018:** KPI-Karten, ein Monatsdiagramm mit Zeitfenster und eine Treemap je Kunde
- **Finanzbericht:** Monatlich als PDF und CSV, Umsatzsteuer nach Soll und Ist, am Ersten des Monats im Drive
- **Stunden-Heatmap:** Erfasste Stunden je Person und Tag über zwölf Wochen, direkt in der Kapazitätsansicht

## Claude arbeitet im Dock

Das Dock hat eine zweite Tür, gebaut für AI-Agents: eine REST-API und einen MCP-Server mit 27 Tools. Durch dieselbe Tür kommen Claude Code im Terminal, die Claude-App auf iPhone und Mac und Jobs, die nach Zeitplan laufen.

- **Unterwegs:** Nach einem Kundentermin werden zwei Sätze in der Claude-App zum Task, mit Checkliste, Fälligkeit und im richtigen Projekt
- **Im Terminal:** Claude Code macht aus Call-Notizen Tasks, kommentiert sie, startet meinen Timer und schiebt Anfragen durch die Akquise, ohne dass ich einen Browser-Tab öffne
- **Die Rechte der Person, nie mehr:** Jeder Token arbeitet für eine Person. Claude sieht genau das, was diese Person sehen darf: keine Beträge für Mitglieder, keine privaten Tasks anderer
- **Jede Änderung gezeichnet:** Einträge heißen „Claude (für Daniel)“, und die Agenten-Seite zeigt jeden Lauf mit Job, Kanal und jeder einzelnen Änderung
- **Ein Code-Pfad:** API und MCP-Tools laufen durch dieselben Services und dieselbe Validierung wie die Web-App. Ein Task-Feature ohne Gegenstück für Agents geht nicht live
- **Bewusst außer Reichweite:** Kein Löschen, keine Einstellungen, keine Budgets, keine Zugangsdaten

Den Kontext bekommt das Modell aus denselben Payloads, die auch die Oberfläche liest. Claude und das Team schauen auf dieselben Daten, und der Verlauf zeigt, wer was getan hat.

## Hosting im Blick

Server und Sites standen früher in einer Tabelle, von Hand gepflegt und eine Woche später veraltet. Jetzt kommen sie jede Nacht aus Laravel Forge, jede Site ihrem Kunden zugeordnet, jeder Server mit seinen Monatskosten und dem Ende seines Ubuntu-Supports. Welcher Kunde wo läuft, ist eine Suche entfernt.

## Ein Cockpit statt Kunden-Logins

Kunden bekommen keine Accounts, eine bewusste Entscheidung. Sie bekommen einen tokenisierten Cockpit-Link pro Projekt: eine Roadmap nach Themen gruppiert, einen Kapazitätsbalken mit Verbrauch und Rest, und ein Feature-Board, auf dem das Team Vorschläge zu echten Tasks machen kann.

## Bewusste Auslassungen

unlimited.dock hat keinen Resource-Planner, keine Fremdwährungen und keine Kunden-Logins, und eine Roadmap-Datei führt jede Auslassung mit Absicht auf. Weniger zu bauen hat es überhaupt erst möglich gemacht, mehrere Plattformen auf einmal zu ersetzen. Productive.io ist weg, Zoho Books folgt, und die Tabellen für Hosting und Server gehen mit. Jede davon war eine Monatsrechnung für Software, die für tausende andere Firmen gebaut ist. Was bleibt, kann alles, was unser Studio braucht, und nichts, was ein anderes bräuchte.
