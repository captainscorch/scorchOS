---
slug: unlimited-dock
title: 'Unser Studio-Betriebssystem: von der ersten Anfrage bis zur bezahlten Rechnung'
story_preview: 'unlimited.dock ist das Betriebssystem, das ich für unser Studio entwickelt habe, von der ersten Anfrage bis zur bezahlten Rechnung. Es schreibt auch jede Rechnung, und Claude arbeitet über einen MCP-Server und eine REST-API mit.'
fineprint: 'Allein designt und entwickelt, jeden Arbeitstag im Einsatz für ein Studio mit fünf Leuten. Kunden, Projekte, Tasks, Beträge und Stunden in den Screenshots sind Platzhalter.'
---

# Unser Studio lief auf Abos. Jetzt läuft es auf Software, die ich entwickelt habe.

Jahrelang lief unser Studio auf gemieteter Software. Productive.io hielt Tasks, Zeiten und Budgets, Zoho Books schrieb die Rechnungen, und Tabellen führten Buch über Hosting und Server. Jedes Tool rechnete monatlich ab, keins kannte das andere, und Kundendaten und Projektinformationen lagen an vier Orten. Linear hatte das Interaktionsmodell, das ich wollte, aber keine Ahnung, was ein Agentur-Budget ist. Also habe ich unlimited.dock entwickelt: ein System, zugeschnitten auf die Arbeitsweise unseres Studios, von der ersten Anfrage bis zur bezahlten Rechnung. Productive.io war das erste Abo, das ging. Inzwischen schreibt das Dock auch jede Rechnung.

## Productive.io komplett ersetzt

Das war kein Experiment im Parallelbetrieb, sondern ein vollständiger Cutover. Ich habe eine Import-Pipeline geschrieben, die unsere komplette Historie aus der API von Productive geholt hat: Tasks, Projekte, Zeiteinträge, Budgets und Kommentare, dazu ein Archiv-Export von 51 Ressourcentypen und mehreren Gigabyte Anhängen. Danach ist das erste Abo endgültig ausgelaufen. Heute trägt die Plattform rund 8.000 Tasks, 20.000 erfasste Stunden und Budgets für mehr als 90 Kunden.

## Der Tech Stack

Ein **Laravel**-Backend mit einem **Vue 3**-Frontend über **Inertia.js**, eine Codebasis für die App. Daneben stehen eine **REST-API** und ein **MCP-Server** für Agents. **Tailwind CSS** und **shadcn-vue** tragen eine schnelle, reduzierte UI, **TipTap** verarbeitet Rich Text mit Markdown als Speicherformat, und **MySQL** hält die Daten.

- **Testing:** Mehr als 2.600 PHPUnit-Tests laufen bei jeder Änderung. Abrechnungslogik ist kein Ort für Überraschungen
- **PDF-Engine:** Angebote, Rechnungen und Abrechnungsreports rendern serverseitig über dompdf, mit Briefkopf und Hausschriften des Studios
- **Integrationen:** Transaktionsmails über Resend, Digests und Warnungen nach Slack, das Rechnungsarchiv in Google Drive, das Server-Inventar aus dem Abgleich mit dem Hosting
- **Unsere Plattform:** Änderungswünsche aus dem Kundenportal unserer Website-Plattform kommen als Tasks an, ihr Status geht zurück ins Portal. Ein bezahlter Kauf legt den Einrichtungs-Task an
- **Anmeldung:** Zwei-Faktor-Anmeldung mit Authenticator-App ist für alle Pflicht, Passkeys gibt es zusätzlich

## Tasks nach dem Linear-Prinzip

Das Task-System übernimmt das Interaktionsmodell, das Linear so mühelos macht, und wendet es auf Agenturarbeit an:

- **Status-Ringe & Peek-Panel:** Zustände auf einen Blick erfassen, jeden Task im Seitenpanel öffnen, ohne die Liste zu verlieren
- **Eine Suche für alles:** Cmd+K findet Tasks, Kunden, Anfragen, Angebote und Rechnungen, alles, was die Rolle öffnen darf. Nummern wie US-R-2026-011 treffen exakt, Filter-Chips grenzen die Arten ein, und alle Treffer stehen auf einer Suchseite
- **Kunden-Kennungen:** Tasks werden pro Kunde nummeriert, eine Referenz wie eine Ticketnummer bedeutet im Gespräch also etwas
- **Echte Struktur:** Subtasks mit Blocking, wiederkehrende Tasks, Milestones, Prioritäten und private Tasks, die Kunden nie sehen

## Ausgelegt auf Tastatur-Tempo

C legt einen Task an, Cmd+K findet alles, T startet den Timer. Jede Liste ist filterbar, jede Aktion hat einen Shortcut, und das Interface ist auf ein Tool getrimmt, das dutzende Male am Tag benutzt wird: Latenz ist ein Feature. Keine Marketing-Seiten, keine Onboarding-Flows, keine Einstellungen für hypothetische Firmen. Software für fünf konkrete Menschen, und genau deshalb schnell.

## Zeit, Kapazität & Verfügbarkeit

Jede Stunde landet auf einem Projekt, optional auf Task und Budget-Position:

- **Timer & Timesheets:** Einen Timer aus jedem Task starten oder die Woche in einem Raster füllen, das auf Tempo ausgelegt ist
- **Team-Verfügbarkeit:** Arbeitstage und Wochenkapazität pro Person, die Planung zeigt also, was wirklich frei ist
- **Faire Rundung:** Abrechenbare Zeit rundet in 30-Minuten-Schritten, einmal pro Budget-Position und Periode. Der Rundungsmodus wird beim Anlegen eines Budgets eingefroren, vergangene Perioden verschieben sich nie rückwirkend

## Budgets & Abrechnung

Der Accounting-Kern bildet ab, wofür wir Productive genutzt haben, minus allem, was wir nie angefasst haben:

- **Budget-Positionen & Retainer:** Feste Budgets und wiederkehrende Retainer-Perioden, geleistete und abrechenbare Stunden getrennt erfasst
- **Plan gegen Ist:** Jede Position, jede Section und die Summe tragen einen Balken mit Plan-Marke, eine Überschreitung zeigt sich also lange vor der Rechnung
- **Sammelpositionen:** Mehrere Angebotszeilen lassen sich über eine Sammelposition buchen, ein Festpreis bleibt so eine Zahl
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

## Zoho Books komplett ersetzt

Acht Jahre lang hat Zoho Books unsere Rechnungen geschrieben, während das Dock schon jedes Budget, jede Stunde und jedes angenommene Angebot kannte. Die Rechnung war das letzte Dokument, das woanders entstand. Also bin ich vorgegangen wie bei Productive: erst das Archiv, dann der Wechsel. Das Dock hat das ganze Archiv aus Zoho übernommen, mit Zahlungen, Original-PDFs, wiederkehrenden Profilen und Lastschrift-Mandaten, und schreibt seitdem jede Rechnung selbst.

- **Entwürfe aus dem, was da ist:** Eine Rechnung startet beim Kunden, bei einer abgeschlossenen Budget-Periode oder bei einem angenommenen Angebot, auch als Anzahlung, Teil- oder Schlussrechnung
- **Nummern, die sich nie wiederholen:** Beim Ausstellen bekommt die Rechnung eine lückenlose Nummer und ist danach gesperrt. Korrekturen laufen über Storno oder Gutschrift, und was sich nicht zurücknehmen lässt, löst erst aus, wenn man den Knopf gedrückt hält
- **Vor dem Versand prüfen:** Rechnung, Profil und Angebot lassen sich vorab als PDF und als Kundenseite ansehen, die Mail ebenso. Anrede und Grußformel sind editierbar, die Kopie-Empfänger kommen aus der Kundenakte
- **Kundenseite:** Jede Rechnung hat eine Seite für den Kunden, mit dem offenen Betrag und einem GiroCode für die Banking-App, den auch das PDF trägt
- **XRechnung:** Jede Rechnung gibt es als PDF auf Deutsch oder Englisch und als XRechnung 3.0, geprüft mit dem offiziellen KoSIT-Validator
- **Kunden im Ausland:** Rechnungen in Länder außerhalb der EU tragen automatisch den Reverse-Charge-Hinweis
- **Skonto:** Das Skonto aus der Kundenakte steht auf PDF, Kundenseite und XRechnung und steckt im GiroCode. Der Bankabgleich kennt den gekürzten Betrag, und Umsatz und Umsatzsteuer folgen der Zahlung
- **Lastschrift:** Das SEPA-Mandat füllt das Dock selbst aus, als einseitiges PDF-Formular, das Kunden ohne Mandat mit der Rechnung bekommen. Alle Mandate stehen in einer Übersicht, die Deckung zeigt schon der Rechnungs-Editor, und eine Lastschrift-Liste je Einzugstag fasst alles zusammen
- **Rücklastschrift:** Eine geplatzte Lastschrift löst keine Mahnung aus. Die Rechnung wartet, bis jemand mit dem Kunden gesprochen hat, dann wird erneut eingezogen oder auf Überweisung umgestellt
- **Mahnwesen:** Zwei automatische Zahlungserinnerungen und eine Mahnung nach Freigabe. Den Mahnlauf kann man vorab ansehen, und eine alte Zoho-Rechnung wird mit ihrem Original-PDF gemahnt
- **Bankabgleich:** Der Kontoauszug kommt als CSV rein. Zahlungen mit Rechnungsnummer oder von einem bekannten Zahler buchen sich selbst. Bei ähnlichem Namen, ein paar Euro Differenz oder mehreren Rechnungen in einer Überweisung schlägt das Dock die Zuordnung vor, der Rest geht mit einem Klick auf Bankgebühren
- **Wiederkehrende Profile:** Hosting, Lizenzen und Retainer laufen als Profile mit Tageslauf. Die Pauschale eines Retainers rechnet sein Profil ab, die Perioden-Rechnung trägt nur die Zusatzstunden. Vorerst landet jeder Lauf als Entwurf und wird angesehen, bevor er rausgeht
- **Kartenzahlungen:** Stripe-Verkäufe unserer Website-Plattform kommen als eigene Belege mit Original-PDF rein, in Umsatz und Umsatzsteuer
- **Änderungsprotokoll:** Jede Änderung an Finanz- und Stammdaten landet in einem Protokoll, das nur wächst, mit wer, wann, alter und neuer Wert, so wie es die GoBD verlangen

## Finanzen ohne Extra-Tool

Auch Kontierung und Finanzplanung laufen jetzt im Dock, als eigener Bereich mit eigenen Rechten.

## Die Kundenakte

Jeder Kunde auf einer Seite: der Umsatz der letzten zwölf Monate gegen die zwölf davor, offene und überfällige Posten, die durchschnittliche Zahlungsdauer, wiederkehrende Rechnungen mit dem nächsten Lauf, das Lastschrift-Mandat, der Stand im Mahnwesen und die Stunden des vergangenen Jahres. Beim Scrollen faltet sich der Kopf zu einer schmalen Leiste mit Sprungmarken zu jedem Teil. Ein Verlauf listet Belege, Zahlungen, Mandate, Angebote, Anfragen, Projekte und Änderungen an den Stammdaten, je nach Rolle. Eine Karte zeigt die Websites und das Hosting des Kunden, Belege und Angebote gibt es als ZIP oder CSV.

## Zahlen auf einen Blick

- **Dashboard:** Eine Karte mit Reitern für Umsatz, wiederkehrenden Anteil, abrechenbare Quote und Stunden, ein Zeitraum von einem Monat bis zwei Jahren, die Vorjahreslinie und ein CSV-Export
- **Umsatz seit 2018:** KPI-Karten, ein Monatsdiagramm mit Zeitfenster und eine Treemap je Kunde
- **Berichte:** Neun Finanzberichte, von offenen Posten nach Alter über Umsatz je Kunde, Umsatzsteuer und Zahlungen bis zum Hosting, jeder als PDF im Look des Studios und als CSV. Gespeicherte Berichte legen sich am Ersten des Monats selbst in Drive und Slack ab, der Jahresbericht am 1. Januar
- **Stunden-Heatmap:** Erfasste Stunden je Person und Tag über zwölf Wochen, direkt in der Kapazitätsansicht

## Claude arbeitet im Dock

Das Dock hat eine zweite Tür, gedacht für AI-Agents: eine REST-API und einen MCP-Server mit 30 Tools. Durch dieselbe Tür kommen Claude Code im Terminal, die Claude-App auf iPhone und Mac und Jobs, die nach Zeitplan laufen.

- **Unterwegs:** Nach einem Kundentermin werden zwei Sätze in der Claude-App zum Task, mit Checkliste, Fälligkeit und im richtigen Projekt
- **Im Terminal:** Claude Code macht aus Call-Notizen Tasks, kommentiert sie, arbeitet meine Inbox ab, startet meinen Timer und schiebt Anfragen durch die Akquise, ohne dass ich einen Browser-Tab öffne
- **Die Rechte der Person, nie mehr:** Jeder Token arbeitet für eine Person. Claude sieht genau das, was diese Person sehen darf: keine Beträge für Mitglieder, keine privaten Tasks anderer
- **Jede Änderung gezeichnet:** Einträge heißen „Claude (für Daniel)“, und die Agenten-Seite zeigt jeden Lauf mit Job, Kanal und jeder einzelnen Änderung
- **Ein Code-Pfad:** API und MCP-Tools laufen durch dieselben Services und dieselbe Validierung wie die Web-App. Ein Task-Feature ohne Gegenstück für Agents geht nicht live
- **Bewusst außer Reichweite:** Kein Löschen, keine Einstellungen, keine Budgets, keine Zugangsdaten

Den Kontext bekommt das Modell aus denselben Payloads, die auch die Oberfläche liest. Claude und das Team schauen auf dieselben Daten, und der Verlauf zeigt, wer was getan hat.

## Hosting im Blick

Server und Sites standen früher in einer Tabelle, von Hand gepflegt und eine Woche später veraltet. Jetzt gleicht das Dock sie stündlich mit unseren Servern ab und ordnet jede Site selbst ihrem Kunden zu. Jeder Server zeigt seine Monatskosten, das Ende seines System-Supports und seinen Patch-Stand, darüber steht eine Ampel je Server. Auch die Kostentabelle fürs Hosting ist umgezogen, als Tabelle mit Filtern je Merkmal, einer Auswertung nach Anbieter, Kostenart und Service und einem CSV-Export.

Alle fünf Minuten prüft das Dock jede Site selbst: ob sie antwortet, wie schnell, wie lange ihr Zertifikat hält. Ein Ausfall erreicht das Team innerhalb von Minuten, die Störungen der letzten 30 Tage bleiben auf der Seite, und jede Site zeigt, welcher Stand live ist, rot, wenn ein Deploy fehlgeschlagen ist. Dazu läuft eine Sicherheits-Frühwarnung: Sie gleicht offizielle Sicherheitsmeldungen und eine öffentliche Schwachstellen-Datenbank mit dem ab, was auf unseren Live-Sites läuft, meldet jede Warnung einmal und legt für die kritischen einen Task an.

## Ein Cockpit statt Kunden-Logins

Kunden bekommen keine Accounts, eine bewusste Entscheidung. Sie bekommen einen tokenisierten Cockpit-Link pro Projekt: eine Roadmap nach Themen gruppiert, einen Kapazitätsbalken mit Verbrauch und Rest, und ein Feature-Board, auf dem das Team Vorschläge zu echten Tasks machen kann.

## Bewusste Auslassungen

unlimited.dock hat keinen Resource-Planner, keine Fremdwährungen und keine Kunden-Logins, und eine Roadmap-Datei führt jede Auslassung mit Absicht auf. Weniger zu entwickeln hat es überhaupt erst möglich gemacht, mehrere Plattformen auf einmal zu ersetzen. Productive.io und Zoho Books sind ersetzt, ebenso die Tabellen für Hosting und Server. Jede davon war eine laufende Rechnung für Software, die für tausende andere Firmen gemacht ist. Was bleibt, kann alles, was unser Studio braucht, und nichts, was ein anderes bräuchte.
