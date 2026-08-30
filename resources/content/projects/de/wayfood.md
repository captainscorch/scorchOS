---
slug: 'way.food'
title: 'Foodtruck & Catering Buchungsplattform'
story_preview: 'Zusammen mit meinem Team bei unlimited haben wir way.food von Grund auf aufgebaut. Gestartet als Vergleichsportal für Foodtrucks, Caterer und Partyservices, heute eine vollständige Buchungsplattform mit echter Zahlungsabwicklung über Stripe Connect. Ich verantworte die technische Architektur, DevOps und die Full-Stack-Entwicklung. Skaliert auf 1.000+ Nutzer, 300+ Anbieter und siebenstelligen Traffic'
fineprint: 'Von Null zum Marktführer. Ich bin stolz darauf, einen komplexen, mehrseitigen Marktplatz in eine echte Zahlungsplattform verwandelt zu haben, mit Stripe Connect, steuerkorrekter Rechnungsstellung und einer Buchungsstrecke, die eine Transaktion von der ersten Nachricht bis zur Auszahlung trägt. Von Strategie und Produkt-Roadmap über Creative Direction bis zur Full-Stack-Entwicklung — ich war für jede Ebene dieser Plattform verantwortlich.'
---

# Aufbau einer Buchungsplattform für Foodtrucks & Catering

way.food ist als Vergleichsportal für Foodtrucks, Caterer und Partyservices gestartet. Mittlerweile ist daraus eine vollständige Buchungsplattform gewachsen, aktuell mit Fokus auf die DACH-Region und Plänen zur weltweiten Expansion. Als Projektleiter habe ich die Produktstrategie mitgestaltet, zu Investoren-Pitches beigetragen und war der primäre technische Ansprechpartner. Mein Team bei **unlimited** unterstützte das Startup in allen Bereichen, von der initialen Markenidentität bis hin zu Marketing und Social-Media-Management. Mein Kernfokus: die gesamte technische Architektur, von Strategie, Systemdesign und DevOps über Zahlungsinfrastruktur bis zur Full-Stack-Entwicklung.

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

## Zahlungen, Steuern und Belege

Jede Buchung läuft über **Stripe Connect** mit Destination Charges. Die Plattformprovision wird als Application Fee auf Anbieterseite einbehalten, Auszahlungen laufen automatisiert, Streitfälle lösen automatisch einen Zahlungsstopp aus.

Dahinter steht eine **steuerkorrekte Preisengine**: Katalogpreise sind netto, die Plattform schlägt ihre Marge auf, die Umsatzsteuer wird pro Position berechnet, 7 % auf Speisen, 19 % auf Getränke und Service, mit einem eigenen Pfad für Kleinunternehmer. Jede Buchung ergibt eine saubere, aufgeschlüsselte Abrechnung statt eines Pauschalbetrags.

Den gesamten Lebenszyklus begleiten **automatisch erzeugte, gebrandete Dokumente**: Kundenrechnung, Provisionsabrechnung für Anbieter, Stornorechnung und Angebots-PDF entstehen und werden zum richtigen Zeitpunkt versendet, ohne manuellen Eingriff.

## Fair by Design

Stornierungen laufen über eine eigene **Storno-Engine**: Die Gebühr staffelt sich nach Abstand zum Event-Datum, die Konditionen sind zum Buchungszeitpunkt fixiert, und es gibt ein kostenloses Storno-Fenster, bevor Gebühren greifen. No-Shows laufen über einen eigenen Pfad, unser Support kann Gebühren mit nachvollziehbarer Historie anpassen. Checkout-Texte und Konditionen wurden rechtlich geprüft, Versionsstände sind pro Buchung festgehalten.

Suchergebnisse ranken nach einem **transparenten, gewichteten Score** aus Abo-Stufe, Verhalten, Bewertungsschnitt und Anzahlungsquote, die Formel steht öffentlich auf einer eigenen Ranking-Seite. Keine bezahlte Platzierung, keine versteckten Boosts. Anbieter im kostenlosen Tarif sind vollständig gelistet und buchbar, nur ganz hinten gerankt.

## Anbieter-Erlebnis

Anbieter steuern ihr Geschäft aus einem eigenen Cockpit:

- **Buchungen & Analytics:** Eine Buchungstabelle plus Dashboard für Umsatz, Anfragen und Antwortzeiten
- **Paket- & Extras-Builder:** Anbieter stellen ihr Angebot und ihre Preise selbst zusammen
- **Verfügbarkeitskalender:** Kapazität und geblockte Termine, direkt an die Buchungsstrecke angebunden
- **Verifizierte Bewertungen:** Nur Kunden mit abgeschlossener, verifizierter Buchung können bewerten
- **Vier Abo-Stufen:** Free, Basic, Premium und Premium+, jeweils mit mehr Sichtbarkeit und Werkzeugen

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

- **1.000+ registrierte Nutzer**
- **300+ verifizierte Anbieter**
- **Tausende Buchungsanfragen**
- **Siebenstelliger jährlicher Traffic**

Eine Plattform, die für Skalierung gebaut wurde und beweist, dass Startup-Geschwindigkeit und Enterprise-Grade-Architektur sich nicht ausschließen.
