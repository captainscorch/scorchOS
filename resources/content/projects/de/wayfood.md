---
slug: 'way.food'
title: 'Foodtruck & Catering Buchungsplattform'
story_preview: 'way.food startete als Vergleichsportal für Foodtrucks und Caterer und ist heute eine Buchungsplattform mit Stripe Connect. Das neue way.food habe ich gestaltet und entwickelt: Produkt, Interface, Architektur, DevOps und Code. Skaliert auf 2.000+ Nutzer, 650+ Anbieter und siebenstelligen Traffic.'
fineprint: 'Von Null zum Marktführer. Ich bin stolz darauf, einen komplexen, mehrseitigen Marktplatz in eine echte Zahlungsplattform verwandelt zu haben, mit Stripe Connect, steuerkorrekter Rechnungsstellung und einer Buchungsstrecke, die eine Transaktion von der ersten Nachricht bis zur Auszahlung trägt. Von Strategie und Produkt-Roadmap über Design bis zur Full-Stack-Entwicklung verantworte ich jede Ebene der neuen Plattform.'
---

# Aufbau einer Buchungsplattform für Foodtrucks & Catering

way.food ist als Vergleichsportal für Foodtrucks, Caterer und Partyservices gestartet. Mittlerweile ist daraus eine vollständige Buchungsplattform gewachsen, aktuell mit Fokus auf die DACH-Region und Plänen zur weltweiten Expansion. Als Projektleiter berate ich die Stakeholder und treffe die Geschäftsentscheidungen der Plattform mit, vom Provisions- und Anzahlungsmodell über die Abo-Stufen bis zu neuen Märkten. Ich habe an Investoren-Pitches mitgearbeitet und bin der primäre technische Ansprechpartner. Logo und Marke kommen von meinem Studio **unlimited**, das das Startup auch bei Marketing und Social Media begleitet. Am Design der ersten Version hat 2024 und 2025 mein Geschäftspartner Michel mitgewirkt. Seit 2026 gestalte und entwickle ich das neue way.food: Produkt- und Interface-Design, Systemarchitektur, DevOps, Zahlungsinfrastruktur und Full-Stack-Entwicklung.

## Infrastruktur für Skalierung

Ich habe eine **produktionsreife Server-Infrastruktur** entworfen, die auf Wachstum ausgelegt ist:

- **Multi-Environment-Setup:** Separate Server für Entwicklung, Staging und Produktion
- **CI/CD-Pipeline:** Automatisierte Deployments mit Zero-Downtime-Releases
- **Load Balancing:** Verteilte Traffic-Verarbeitung für Hochlastzeiten
- **Datenbank-Architektur:** Dedizierte Datenbankserver mit automatisierten Backups
- **S3-Integration:** Statische Medien-Verwaltung und geplante Backups zu AWS S3
- **Zwei Zahlungswege parallel:** Stripe Connect für den Buchungs-Geldfluss, Laravel Cashier für die Anbieter-Abos, live migriert ohne Ausfallzeit

## Von der Suche zur direkten Buchung

Das Kernprodukt ist nicht mehr das Durchstöbern, sondern die Buchung selbst. Anbieter bauen strukturierte Pakete und Extras mit kalenderbasierter Kapazität, Kunden finden sie über einen **Discover-Such-Assistenten**, der Gästezahl, Datum und Budget einbezieht.

- **Chat direkt in der Buchung:** Kunde und Anbieter verhandeln im Gespräch, verbindliche Angebote erscheinen darin als Karten
- **Anzahlung per Stripe:** Eine Anzahlung sichert die Buchung, der Rest wird vor dem Event eingezogen
- **Fünf Wege in eine Buchung:** die direkte Sofortbuchung, eine im Chat verhandelte Änderungsanfrage, ein individuelles Angebot auf Anfrage, ein von unserem Support gebautes Concierge-Angebot oder ein Lead von der Landingpage, der später konvertiert

## Die Anbieterseite als Buchungstresen

Discover zeigt jetzt Anbieter statt einzelner Pakete. Ein Klick führt auf eine Anbieterseite, neu gebaut als Hub: Galerie, Bewertungen, Bestseller-Pakete und eine Anfrage-Box, die Datum und Gästezahl aus der Suche übernimmt. Wer ein Paket übernimmt und Extras dazunimmt, sieht Gesamtpreis, Anzahlung, Personal und Anfahrt Zeile für Zeile, bevor überhaupt eine Adresse eingetippt ist. Passt die Gästezahl nicht zum Paket, gibt es eine Korrektur mit einem Tipp statt einer Fehlermeldung.

- **Such-Pill:** Wo, Wann und Wie viele in einer Leiste, Kalender und Gästezahl öffnen sich direkt darunter
- **Bottom Sheets auf dem Handy:** Anfrage-Box, Discover-Filter und Navigation öffnen als Drawer vom unteren Rand
- **Buchungsmodal:** Anlass, Extras, Eventadresse und Bedingungen in einem Schritt, danach direkt in den Stripe-Checkout
- **Ehrlich bei der Verfügbarkeit:** Ist an einem Tag nur noch ein Slot frei, sagen es Karte und Anfrage-Box

## Verhandeln im Chat

Nicht jedes Event passt in ein Paket. Für alles Individuelle öffnet die Anfrage einen Chat zwischen Kunde und Anbieter, und der Anbieter antwortet mit einem verbindlichen Angebot: Preis pro Gast, Personal, Anfahrt, Extras und Anzahlung, aufgeschlüsselt wie bei einer Direktbuchung und als PDF abrufbar. Der Kunde nimmt es im Chat an oder lehnt ab und zahlt die Anzahlung direkt dort. Der Anbieter sieht denselben Verlauf von der anderen Seite, mit seiner Auszahlung statt des Kundenpreises und Buttons zum Korrigieren oder Zurückziehen. Telefonnummer und E-Mail bleiben verborgen, bis die Buchung bezahlt ist, damit der Auftrag nicht an der Plattform vorbeiläuft.

## Antworten vor den Fragen

Ein durchsuchbares FAQ trennt seine Antworten nach Veranstaltern, Anbietern und Partnern, mit Kategorie-Navigation und einer Kontaktbox für alles, was dort nicht steht. Anzahlung, Stornierung und höhere Gewalt bekommen jeweils eine eigene Antwort statt eines Verweises auf die AGB.

## Kleine Teile, ein System

Ich habe das Interface als ein System entworfen, von der Landingpage bis zum Anbieter-Cockpit. Der neue Footer inszeniert die Marke als Foodtruck-Szene im Clay-Look, der Newsletter öffnet als Modal. Navigations-Drawer, Kontomenü und die Sprach- und Länderwahl (drei Sprachen, fünf Märkte, jeweils mit eigener Währung) teilen sich einen Look. Die Anbieter-Formulare laufen auf Komponenten, portiert aus Arc UI: Geld- und Zahlenfelder, die beim Tippen formatieren, Upload-Zonen mit einem einheitlichen Drag-Zustand, gleitende Tabs, ein Toast-Stack und Bestätigungsdialoge statt des Browser-confirm().

Auch die Mails haben ein eigenes Audit bekommen. Anbieter bekommen höchstens eine Onboarding-Mail pro Tag, Buchungsbestätigung und Stornobedingungen kommen in einer Mail mit ihren Dokumenten, und eine Admin-Seite rendert jede Mail, die der Server verschicken kann, zur Prüfung vor dem Versand.

## Zahlungen, Steuern und Belege

Jede Buchung läuft über **Stripe Connect** mit Destination Charges. Die Plattformprovision wird als Application Fee auf Anbieterseite einbehalten, Auszahlungen laufen automatisiert, Streitfälle lösen automatisch einen Zahlungsstopp aus.

Dahinter steht eine **steuerkorrekte Preisengine**: Katalogpreise sind netto, die Plattform schlägt ihre Marge auf, die Umsatzsteuer wird pro Position berechnet, 7 % auf Speisen, 19 % auf Getränke und Service, mit einem eigenen Pfad für Kleinunternehmer. Jede Buchung ergibt eine saubere, aufgeschlüsselte Abrechnung statt eines Pauschalbetrags.

Den gesamten Lebenszyklus begleiten **automatisch erzeugte, gebrandete Dokumente**: Kundenrechnung, Provisionsabrechnung für Anbieter, Stornorechnung und Angebots-PDF entstehen und werden zum richtigen Zeitpunkt versendet, ohne manuellen Eingriff.

## Fair by Design

Stornierungen laufen über eine eigene **Storno-Engine**: Die Gebühr staffelt sich nach Abstand zum Event-Datum, die Konditionen sind zum Buchungszeitpunkt fixiert, und es gibt ein kostenloses Storno-Fenster, bevor Gebühren greifen. No-Shows laufen über einen eigenen Pfad, unser Support kann Gebühren mit nachvollziehbarer Historie anpassen. Checkout-Texte und Konditionen wurden rechtlich geprüft, Versionsstände sind pro Buchung festgehalten.

Suchergebnisse ranken nach einem **transparenten, gewichteten Score** aus Abo-Stufe, Verhalten, Bewertungsschnitt und Anzahlungsquote, die Formel steht öffentlich auf einer eigenen Ranking-Seite. Keine bezahlte Platzierung, keine versteckten Boosts. Anbieter im kostenlosen Tarif sind vollständig gelistet und buchbar, nur ganz hinten gerankt.

## Anbieter-Erlebnis

Anbieter steuern ihr Geschäft aus einem eigenen Cockpit:

- **Registrierung in fünf Schritten:** Basis, Angebot, Einsatz, Bilder & Links, Abschluss. Jeder Schritt speichert einen Entwurf, ein Zähler zeigt die offenen Pflichtfelder, die Beschreibung formuliert auf Wunsch die KI um, und bleibt ein Entwurf liegen, kommt eine Erinnerung
- **Dashboard & Statistik:** KPI-Kacheln mit 30-Tage-Trends, Zeitraumvergleich, eigener Zeitraum aus dem Kalender und ein Jahr Profilaufrufe als Heatmap
- **Benachrichtigungen:** Jede Anbieter-Mail landet zusätzlich in der Glocke im Dashboard
- **Buchungseingang:** Status-Tabs für offene, bestätigte, erledigte und stornierte Buchungen
- **Paket- & Extras-Builder:** Anbieter stellen ihr Angebot und ihre Preise selbst zusammen
- **Verfügbarkeitskalender:** Kapazität und geblockte Termine, direkt an die Buchungsstrecke angebunden
- **Verifizierte Bewertungen:** Nur Kunden mit abgeschlossener, verifizierter Buchung können bewerten
- **Vier Abo-Stufen:** Free, Basic, Premium und Premium+ als Plan-Karten, mit einem echten Dialog vor jedem kostenpflichtigen Wechsel

## Admin & Operations

Im Hintergrund läuft eine interne Plattform auf **Filament**: KPI-Dashboards für Stornoquote, Auszahlungs-Alarme und Antwortzeiten, Arbeitswarteschlangen für Anbieter-Onboarding, Katalogpflege, Leads und offene Ausschreibungen, ein Builder für Concierge-Angebote sowie ein Marketing-Dashboard mit Kanal-Heatmaps.

## Marketing, Reichweite & Content

Ich habe ein **selbst gehostetes Attributionssystem** entwickelt: Click-ID- und UTM-Erfassung ohne Tag-Manager oder Pixel, an Einwilligung gekoppelt, gespeist in den Offline-Conversion-Import bei Google Ads, inklusive Rücknahme bei Stornierung.

Die Plattform selbst läuft auf einer interaktiven **Mapbox GL JS**-Karte mit Taxonomie-Filtern, ist vollständig mehrsprachig (Deutsch, Englisch, Niederländisch) mit hreflang-Sitemaps, bietet offene Ausschreibungen, bei denen Kunden ihr Event einstellen und Anbieter sich bewerben, und einen Blog mit KI-gestütztem Content-Tooling.

## Tech Stack & Monitoring

Gebaut mit **Laravel 12** und **Livewire** für reaktive Komponenten sowie **Alpine.js** für leichtgewichtige Interaktivität. **Tailwind CSS** sorgt für ein konsistentes, responsives Design, **MySQL** übernimmt die Datenhaltung. Die Admin- und Ops-Plattform läuft auf **Filament**, Zahlungen laufen über **Stripe Connect** und **Laravel Cashier**, KI-Features nutzen die **OpenAI**-API.

Für Zuverlässigkeit habe ich **Sentry** für Echtzeit-Fehlertracking und Performance-Monitoring integriert.

## Die Ergebnisse

way.food ist skaliert auf:

- **2.000+ registrierte Nutzer**
- **650+ verifizierte Anbieter**
- **Tausende Buchungsanfragen**
- **Siebenstelliger jährlicher Traffic**

Eine Plattform, die für Skalierung gebaut wurde und beweist, dass Startup-Geschwindigkeit und Enterprise-Grade-Architektur sich nicht ausschließen.
