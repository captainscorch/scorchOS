---
slug: hof-bissee
title: 'Website für ein Natur-Hideaway in der Holsteinischen Schweiz'
story_preview: 'Eine neue Website für Hof Bissee, ein Natur-Hideaway bei Kiel: der Hof als Federzeichnung, mit einem Siegel, das sich selbst zeichnet, Menüs mit Gravuren und Icons im Federstrich. Zimmer mit teilbaren Filtern und einer Buchungsübergabe mit Zimmer und Datum, Restaurant, Kalender, sieben Eventlocations und ein eigenes Backoffice.'
fineprint: 'Konzept, Design und Entwicklung von mir, von der Gestaltungsrichtung und den gezeichneten Icons bis zur Buchungsübergabe, dem Kalender und dem Backoffice, dazu die Optimierung von Suche, Pagespeed und Barrierefreiheit.'
---

# Der Hof als Federzeichnung

Hof Bissee ist ein Natur-Hideaway in Bissee, einem Dorf mit 178 Einwohnern in der Holsteinischen Schweiz, 50 Minuten nördlich von Hamburg. 17 Zimmer und Cabins in drei Gästehäusern, Farmhouse, Corn Barn und Cottage, dazu ein Restaurant, zwei Spas, ein Privat-Kino, Grillplätze und Eventlocations für 8 bis 800 Gäste. Der Hof hatte die Fotos, den Ort und ein Siegel mit viel Charakter. Meine Aufgabe war eine Website, die das alles zeigt und Gäste ohne Umweg zur Buchung bringt.

## Ein Siegel, eine Feder, eine Gravur

Eine erste Fassung sah aus wie jede Hotelseite: Eyebrow, Headline, Kartenraster, die Marke nur in kleinen Icons. Also bekam die Seite eine Idee: der Hof als Federzeichnung. Das Siegel wurde zur Bühne der Startseite. Es zeichnet sich ein, und seine vier Felder führen zu Restaurant, Zimmern, Feiern und Spa. Gravuren sitzen als Papier-Plaketten über den Fotokanten, jedes Haus mit eigenem Motiv. Die Gästehäuser stehen unter Rundbögen wie Stallfenster, Abschnitte enden in gezeichneten Horizonten mit Gravur-Schraffur, und unter jeder Headline öffnet sich eine Wellenlinie von der Mitte aus. Der Hofplan ist eine Bleistiftzeichnung mit sieben Orten zum Anklicken. Der Footer trägt eine Bleistiftskizze der Gebäude, darüber ziehen Vögel, und der Newsletter ist eine Postkarte mit dem Siegel als Briefmarke. 32 Gravuren und 44 UI-Icons teilen sich eine Feder: Ich habe die Icon-Geometrie im Strich des Siegels neu gezeichnet, bis zum Burger, dem Schließen-Kreuz und den Social-Glyphen. Das HB-Monogramm zeichnet sich beim Laden selbst. Die Seite bleibt hell, auch wenn das Handy auf Dunkel steht.

## Vom Foto zur Buchung

Gebucht wird über RoomRaccoon, und RoomRaccoon kann Zimmer und Reisedaten direkt übernehmen. Die Website übergibt beides: Zimmer wählen, Zeitraum wählen, und die Buchungsmaske öffnet sich mit genau dieser Auswahl. Ein Aufenthalt unter dem Mindestaufenthalt bietet an, sich selbst zu verlängern. Die Zimmerliste filtert nach Haus, Personen, Hund, Frühstück und Ausstattung. Die Filter stehen in der URL und rendern auf dem Server, also lässt sich eine gefilterte Liste wie „Cottage, mit Hund“ als Link teilen. Ein Vergleich stellt die drei Häuser nebeneinander: Frühstück, Küche, Hunde, Hot Tub, Check-in. Auf dem Handy schwebt die Buchungsleiste als Glas-Pill im Daumenbereich, so gebaut, dass Safari 26 seine Leisten richtig einfärbt.

## Restaurant, Kalender, Feiern

Das Restaurant zeigt seine Öffnungszeiten live, mit „Jetzt geöffnet“ und dem Küchenschluss. Der Kalender hat eine Liste und eine Monatsansicht mit Kategorien, jeder Termin eine eigene Seite, „In den Kalender“ als ICS-Datei und ein Abo für Stammgäste. Die festen Termine jeder Woche, Brunch, Spritz-Hour, Yoga, laufen als Ticker durch. Feiern zeigt sieben Locations, vom Grillplatz für zwölf bis zur Konzertscheune für 800 Stehende. Der Event-Kalkulator von EVENTMACHINE lädt erst nach einem Klick, also erreicht nichts von dessen Servern einen Besucher, der ihn nicht nutzt. Gutscheine, Jobs mit Bewerbungsformular, ein FAQ und die Anreise mit dem handgezeichneten Plan runden die Seite ab.

## Ein Backoffice statt eines CMS

Statt eines Page-Builders bekommt der Hof ein eigenes Backoffice: Zimmer und Häuser mit Texten, Ausstattung, Buchungs-ID und einer Galerie, die per Drag & Drop sortiert wird, eine Mediathek, die AVIF und WebP in mehreren Größen schreibt und beim Upload einen Alt-Text verlangt, Weiterleitungen mit 404-Protokoll und die Bewerbungen als Board nach Status, mit einer Rolle für das Personal und der Löschung sechs Monate nach der Entscheidung. Die Statistik zählt Zimmeraufrufe, Buchungsstarts, Anrufe und Routen pro Tag, ohne Cookie und ohne IP, also ohne Einwilligung.

## Schnell und auffindbar

Jede öffentliche Seite rendert auf dem Server und trägt strukturierte Daten: Hotel, 17 Hotelzimmer, Restaurant mit Öffnungszeiten, Event, JobPosting, FAQ. Jede Seite hat einen Markdown-Zwilling für KI-Crawler, und jedes Foto bekommt ein eigenes Share-Bild in 1200 × 630, zugeschnitten um seinen Fokuspunkt. Gemessen mit Lighthouse auf dem Handy im Vergleich zur alten Seite ging die Zimmerübersicht von 33 auf 4,5 Megabyte, der Speed Index von 33 auf 3,5 Sekunden, und beim Laden verrutscht nichts.
