---
slug: keine-panik
title: 'Festival-Website, Merch-Shop und Ticketing in einer Plattform'
story_preview: 'Website, Merch-Shop und Ticketing für das Keine Panik Festival in Nordhessen: Line-up, Camping und Merch in einem Checkout, ein Backoffice für den Veranstalter und ein Offline-Scanner für den Einlass. Im Sommer 2026 in vier Wochen entworfen und gebaut, als Plattform, die jedes Festival lizenzieren kann.'
fineprint: 'Ich habe sie im Sommer 2026 in vier Wochen entworfen und gebaut, vom Konzept bis zum Livegang, als Ersatz für eine WordPress-Seite und einen externen Ticketanbieter. Das Design für Ticketing und Line-up kam von meinem Geschäftspartner Michel. Die Plattform gehört unlimited.studio und wird pro Festival lizenziert. Backoffice- und Checkout-Screenshots zeigen lokale Demo-Daten.'
---

# Ein Checkout für Tickets, Camping und Merch

Das Keine Panik Festival ist ein Punkrock-Festival in Niedermeiser bei Kassel: vier Tage, rund 1.500 Leute, organisiert von einer kleinen Crew. Bis 2026 lag die Website auf WordPress und die Tickets bei einem externen Anbieter, jeder mit eigenen Gebühren und eigenen Daten. Ich habe beides durch eine Plattform ersetzt: Website, Merch-Shop, Ticketing, Backoffice und Einlass-Scanner in einer einzigen Laravel-App. Ich habe sie im Sommer 2026 in vier Wochen gebaut: das erste Design, die Architektur und die Plattform. Das Design für Ticketing und Line-up kam von meinem Geschäftspartner Michel.

## Eine Plattform für jedes Festival

Keine Panik mietet keinen Ticketshop, das Festival lizenziert eine Plattform: Website, Shop, Ticketing, Backoffice und Einlass-Scanner als ein Produkt, das unlimited.studio gehört. Alles, was Keine Panik ausmacht, liegt im Backoffice: das Festivaljahr, die Inhalte, das Line-up, Ticket-Typen, Preisstufen und Kontingente, Camping, Merch, der Lageplan und die Sponsoren. Nichts Festivalspezifisches steckt im Code. Ein anderes Festival bekommt dieselbe Plattform mit eigenem Look und eigenen Daten, verkauft seine Tickets ohne Ticketanbieter dazwischen und behält die Daten seiner Kunden selbst.

## Zündschnur und Glut

Das Logo ist eine Bombe mit brennender Zündschnur, und die Seite trägt dieses Bild: immer dunkel, Ruß und Glut, der Glutschein als einziger Akzent. Die Line-up-Sektion gibt das Plakat des Veranstalters eins zu eins wieder. Headliner und Co-Headliner laufen von Kante zu Kante, die Schriftgröße wird serverseitig aus gemessenen Laufweiten berechnet, also ist das Plakat bei 350 Pixeln dasselbe Bild wie bei 1.200 und springt beim Laden nie. Bis ein Act angekündigt ist, bleibt sein Platz verschwommen, mit dem Datum der nächsten Ankündigung. Die Über-uns-Seite ist eine Zündschnur-Timeline, Jahr für Jahr, das aktuelle pulsiert.

Der Hero brennt: ein WebGL-Feuer mit Hitzeflimmern, Glut, die durch die Seite treibt, und an seiner Unterkante eine Naht, die wie die Zündschnur im Logo funktioniert. Ein Funke läuft über sie und detoniert in der Mitte, die Explosionsform des Logos gibt ein Foto frei. Im Fuß brennt der Schriftzug Partikel für Partikel von links nach rechts ab und setzt sich wieder zusammen, gezeichnet auf einer 2D-Canvas statt mit einer 3D-Bibliothek.

Der Lageplan beantwortet zwei Fragen in zwei Ansichten. Das Gelände zeigt jede Bühne, Bar, Toilette und jeden Einlass als Punkt auf einer Mapbox-Karte, filterbar nach Art, und ein Tipp in der Liste fliegt die Karte dorthin. Die Anfahrt zeigt die Region mit den Routen aus Orten wie Kassel, Paderborn und Göttingen als Glutlinien mit ihrer Fahrzeit.

## Tickets, Camping, Merch, eine Bestellung

Ein Weekend Pass, Camping als zweiter Schritt, Merch als Upsell in derselben Bestellung. Der Warenkorb ist eine Leiste am unteren Rand, wo der Daumen ist, kein Symbol in der Kopfzeile. Er wächst mit jedem Ticket, klappt zu einer Liste aller Positionen mit eigenem Stepper auf und führt in eine Kasse, die die Tickets reserviert, während die Bestellung ausgefüllt wird. Preisstufen laufen automatisch über: Ist eine Stufe ausverkauft, wandert der Rest der Menge in die nächste, jede Stufe als eigene Position. Kundenkonten gibt es keine. Jede Bestellung liegt hinter einer Token-URL, mit Tickets, Rechnung, Kalenderdatei und den QR-Codes direkt in der Bestätigungsmail, weil die Mail am Tor reichen muss. Jedes Ticket lässt sich auch als Pass in Apple Wallet legen. Stripe nimmt die Zahlung per Karte, PayPal, Apple Pay, Google Pay und Klarna. Rabattcodes, Frei-Tickets für Gästelisten und Affiliate-Links mit Attribution liegen ebenfalls im Backoffice.

## Das Backoffice

Inhalte, Line-up, Sponsoren, Lageplan, Galerie, Produkte, Bestellungen, Frei-Tickets, Kampagnen und Verteiler, alles in einem dunklen Backoffice mit Befehlspalette und Tastaturnavigation, entstanden aus der Operations-Plattform meines Studios. Der Lageplan wird auf einer Mapbox-Karte gezeichnet, die Sponsorenwand per Drag-and-drop sortiert, die News aus dem alten WordPress wurden importiert.

## Der Einlass

Der Scanner ist eine PWA für die Türcrew: Er lädt alle gültigen Codes aufs Gerät, prüft Tickets offline und gleicht ab, sobald das Netz wieder da ist. Einlass, Zusatz und Abgewiesen zählen pro Tor.

## Livegang

Der Umzug von der alten Seite auf den neuen Server lief an einem Abend: DNS zu Cloudflare, die bestehenden Bestellungen vom bisherigen Ticketanbieter importiert, damit deren Tickets am Tor scannen, die Marketing-Seiten rendern serverseitig, Lighthouse liegt auf dem Desktop bei 99 bis 100. End-to-End-Tests decken den kompletten Kaufweg gegen den Stripe-Testmodus ab. Keine Panik.
