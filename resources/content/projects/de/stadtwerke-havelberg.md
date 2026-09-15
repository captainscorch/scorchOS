---
slug: stadtwerke-havelberg
title: 'Relaunch-Konzept für einen kommunalen Versorger'
story_preview: 'Relaunch-Konzept für die Stadtwerke Havelberg aus Eigeninitiative, kein Auftrag: Laravel, Statamic 6, Inertia und Vue mit Live-Tarifrechner, fehlertoleranter Suche, Störungs-Overlay aus dem CMS und Dark Mode.'
fineprint: "Ein Konzept auf eigene Initiative. Die Stadtwerke Havelberg haben nichts beauftragt: Tarife und News sind Beispielinhalte, nur die öffentlichen Kontaktdaten sind echt."
---

# Ein Relaunch ohne Auftrag

Viele Stadtwerke betreiben Websites aus einem anderen Jahrzehnt: kein Viewport-Tag, ein Tarif-PDF statt eines Rechners, eine Störungsmeldung, für die erst ein Entwickler ran muss. Ich wollte zeigen, wie ein Relaunch aussieht, ohne auf eine Ausschreibung zu warten. Also habe ich einen gebaut. Aus Eigeninitiative, kein Auftrag. Die Stadtwerke Havelberg versorgen eine Kleinstadt an Elbe und Havel mit Strom, Erdgas und Fernwärme, betreiben das Erlebnisbad und standen auf meiner Liste der Versorger mit genau so einer Website. Die Demo baut sie auf einem echten Stack nach, mit echten Inhalten der öffentlichen Seite und Beispielpreisen.

## Der Stack

**Laravel** und **Statamic 6** hinten, **Inertia** und **Vue 3** mit Server-Side Rendering vorn, **Tailwind CSS** für das Designsystem. Die Energie-Struktur folgt dem Aufbau eines Versorgers, Havelbergs eigene Schicht liegt obendrauf:

- **Farben:** das exakte Logo-Paar aus dunklem Rot und Grün, als OKLCH-Palette angelegt, mit einem Farbcode je Produktwelt.
- **Inhalte:** Tarife, Öffnungszeiten, Störungsbanner und Unternehmensdaten liegen in Statamic-Globals; News sind eine Collection mit Detailseiten.
- **Lokales:** Fernwärme aus dem Biogas-BHKW, das Erlebnisbad mit seiner 64-Meter-Rutsche, die Vereinsaktion und die Betriebsführung für den Trinkwasser- und Abwasserzweckverband (TAHV).

## Der Tarifrechner

Das Herzstück sitzt direkt unter dem Hero. Zuerst die Postleitzahl: Der Rechner prüft das Liefergebiet und sagt in klaren Worten, wenn eine Adresse außerhalb liegt. Der Verbrauch kommt aus Haushalts-Presets, eine bis fünf und mehr Personen beim Strom, Wohnfläche beim Gas, oder aus einem selbst eingetippten Wert. Jede Tarifkarte rechnet bei jedem Tastendruck neu, sortiert nach Jahreskosten, mit dem monatlichen Abschlag vorn. Die Preise kommen live aus dem CMS: Das Stadtwerk ändert einen Tarif in Statamic, der Rechner zieht nach. Ein Klick rendert aus denselben Daten ein persönliches Preisblatt als **PDF**.

## Eine Suche, die Tippfehler verzeiht, und ein Overlay für schlechte Tage

Die Seite hat eine **⌘K-Suche** über Services, Produktwelten und News. Sie verzeiht Tippfehler und Umlaut-Schreibweisen über Levenshtein-Distanz und Präfix-Abgleich, „zähler" findet also die Zählerstandsmeldung. Ergebnisse erscheinen beim Tippen, die Tastaturnavigation funktioniert durchgehend, und ein Screenreader hört die Trefferzahl über eine ARIA-Live-Region.

Für Störungen gibt es ein **Krisen-Overlay**: Ein Schalter im CMS legt ein Banner mit Headline, Stand und Hotline über jede Seite. Die Schwere bestimmt die Farbe. In der Demo lässt sich das Overlay aus dem Vorschau-Dock auslösen, ein Besucher sieht es also, ohne das CMS anzufassen.

## Barrierefrei by Design, Dark Mode inklusive

Barrierefreiheit ist eingebaut statt nachgerüstet, orientiert an der BITV, mit der öffentliche Stellen arbeiten: Skip-Link zum Inhalt, sichtbare Fokus-Ringe auf jedem interaktiven Element, saubere ARIA-Rollen für Tabs, Gruppen und Dialoge, und Bewegung, die stillsteht, sobald der Besucher reduzierte Bewegung wünscht. **Dark Mode** ist ein Schalter im Header und im mobilen Menü, standardmäßig hell und pro Gerät gemerkt.

Die Bewegung, die bleibt, verdient ihren Platz: Sektionen blenden beim Scrollen ein, Energielinien pulsieren über das Hero-Foto. Schema.org-Markup beschreibt das Stadtwerk als LocalBusiness, Öffnungszeiten inklusive, direkt aus dem CMS. Ein kleines **Vorschau-Dock** in der Ecke listet, was schon funktioniert, Dark Mode, Suche, Störungs-Overlay, PDF, CMS-Login, und lässt jeden Punkt direkt ausprobieren.

## Wofür das Ganze

Die Stadtwerke Havelberg bekommen eine funktionierende Vorschau ihres eigenen Relaunchs, bevor das erste Gespräch stattfindet. Kein Mockup, eine Seite.
