---
slug: multidock
title: 'Ein zweites Dock für macOS mit Live-Widgets, Ordner-Stapeln und Fokus-gesteuerten Profilen'
story_preview: 'multi.dock ist meine eigene Mac-App: ein zweites Dock mit Live-Widgets, Ordner-Stapeln und mehreren Docks, die mit dem Fokus wechseln. Natives Swift, kein Konto, keine Telemetrie, dazu eine Produktseite aus denselben Bausteinen wie die App. Kostenlos zum Download für macOS 15 und neuer.'
fineprint: 'Mein eigenes Produkt, gestartet im Oktober 2026: die App in Swift 6 mit AppKit und SwiftUI, die Produktseite in Vue, der Launch-Film in Remotion. Die Bilder auf der Seite sind aus den Bausteinen der App gebaut, nicht vom Bildschirm aufgenommen.'
---

# Das Dock, erweitert

Apples Dock hat sich in zwanzig Jahren kaum verändert, und die Lücke fällt auf, sobald man hinsieht: keine Widgets, ein Layout für jede Art von Arbeit, Ordner, die als Raster aufgehen. multi.dock ist meine Version eines zweiten Docks: Apps, Dateien, Ordner und Links wie gewohnt, Live-Widgets daneben, und mehrere Docks, die dem Fokus folgen. Natives Swift 6 mit AppKit und SwiftUI, macOS 15 und neuer, Liquid Glass ab macOS 26.

## Daneben oder stattdessen

multi.dock läuft neben Apples Dock oder ersetzt es. Im Ersetzen-Modus bleibt Apples Dock versteckt und kommt zurück, sobald multi.dock beendet wird. Badges und Laufanzeigen werden per Bedienungshilfen aus Apples Dock gelesen, die Vergrößerung nutzt Apples eigene Größen, und Icons schrumpfen, bis die Leiste passt, bevor sie zu scrollen beginnt. macOS reserviert Bildschirmfläche nur für sein eigenes Dock, also beobachtet ein Fensterschutz Fenster, die an der Dock-Kante einrasten, und hebt sie über die Leiste.

## 22 Widgets, alle live

Uhr, Wetter, Kalender, Erinnerungen, Fokus-Timer, Trinken, Now Playing, Systemaktivität, Netzwerk, Batterien, Aktien, Notizen und mehr, jedes 1,25 Icons hoch, mit Stilen statt Größen. Ein Klick öffnet ein Detail-Panel über dem Widget. Widgets sind eigene SwiftUI-Views, weil macOS keiner Fremd-App erlaubt, WidgetKit zu hosten. Wetter kommt von Open-Meteo ohne Konto, und nichts verlässt den Mac außer den Anfragen, die die Widgets brauchen.

## Ein Dock für die Arbeit, eins für danach

Docks sind Profile mit eigenen Apps, Widgets und Farbe. Gewechselt wird per Kurzbefehl, Wischen, Menüleiste, URL-Schema oder Fokus-Filter, sodass das Arbeits-Dock mit dem Arbeits-Fokus erscheint und das vorherige danach zurückkommt.

## Eine Seite aus den Bausteinen der App

Die Produktseite ist eine statische Vue-Seite, vorgerendert, auf Englisch und Deutsch. Der Hero enthält ein funktionierendes Dock, dessen Vergrößerungs-Mathematik aus der App portiert ist, und jedes Bild auf der Seite ist aus Komponenten gezeichnet, nicht aufgenommen. Analytics erst nach Einwilligung, Downloads aus nginx-Logs gezählt statt per Cookie, und ein 33-Sekunden-Launch-Film, in Remotion aus denselben Teilen gerendert.

## Ausliefern

Notarisierte DMGs mit Developer ID, zwei Ausgaben aus einem Build, ein Handbuch, ein Changelog und eine öffentliche Release-Seite. Kein Mac App Store: Bedienungshilfen, AppleScript und globale Event-Monitore vertragen die Sandbox nicht.

## Selbst ausprobieren

multi.dock ist ein kostenloser Download für macOS 15 und neuer auf [multidock.unlimited.studio](https://multidock.unlimited.studio). Kein Konto, keine Anmeldung. Das Dock oben auf der Seite funktioniert im Browser, so lassen sich Vergrößerung, Widgets und Profile vor dem Download ausprobieren.
