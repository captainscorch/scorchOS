---
slug: starhavoc
title: 'Mein eigenes Weltraum-MMO, vom Logo bis zum Multiplayer'
story_preview: 'STARHAVOC ist ein Weltraum-MMO im Browser, für Computer, Tablet und Smartphone. Ich wollte immer ein Spiel bauen, und mein Lieblingsspiel von früher läuft auf meinem Mac nicht mehr. Also habe ich eine eigene Welt gebaut, von Logo und Story bis zum Multiplayer-Server.'
fineprint: 'Ein Side Project, entstanden in meiner Freizeit, mit Claude Code als Entwicklungspartner. Konzepte, 3D-Modelle, Illustrationen und Musik sind über Magnific entstanden, angeleitet und kuratiert von mir. Selbst spielen kannst du auf starhavoc.net.'
---

# Ein Spiel, das ich schon immer bauen wollte

Seit Jahren wollte ich ein Spiel entwickeln. Den Anstoß gab ein Browser-Weltraumspiel, in dem ich als Jugendlicher Stunden verbracht habe: Auf meinem Mac läuft es nicht mehr. Also habe ich mein eigenes entworfen. **STARHAVOC** ist ein Weltraum-MMO, das du im Browser auf Computer, Tablet und Smartphone spielst, mit gemeinsamer Welt, Fraktionen, Clans, Story und Bosskämpfen.

Alles lag bei mir: **Logo und Marke, Art Direction, Game Design, Story, 3D-Pipeline, Client und Server**. Das Spiel läuft auf [starhavoc.net](https://starhavoc.net). Probier es gern selbst aus, wähl eine Fraktion und flieg eine Runde.

## Ein zerbrochener Stern als Logo

Die Welt beginnt mit einem Satz: STARHAVOC ist die Nacht, in der der Stern Kessar zerbrach. Das Zeichen erzählt genau das. Es ist ein **zerbrochener Stationsring**, ein Achteck, dem eine Platte aus der Ecke gesprengt wurde, um einen vierzackigen Stern. Die lose Platte ist die einzige Farbe im Logo: Signal-Amber.

Die **Wortmarke ist aus Geometrie gebaut, nicht aus einer Schrift gesetzt**. Breite Versalien als gefüllte Umrisse, große Außen- und enge Innenradien, A und V ohne Querstrich. Nach der ersten Runde habe ich das Zeichen an die Wortmarke angeglichen: Der Ring hat jetzt dieselbe Strichstärke und dieselben Eckradien wie das O. Alles entsteht im Code, ein Script schreibt Logos, Favicon, App-Icons und das Vorschaubild für Links. Ein Brand Guide und ein Export-Ordner mit jedem Lockup in jedem Format halten das System fest. Dafür habe ich 16 Konzepte durchgespielt und Ideen wie das ΛV aus der Wortmarke als Nebenmotiv verworfen, weil man es als „AV“ liest.

Wo das Spiel lädt, bewegt sich das Zeichen: In **Fracture** bricht der Ring in sieben Platten, sie treiben um den Stern auseinander und rasten nacheinander wieder ein, die Amber-Platte zuletzt. Kleine Platzhalter bekommen eine ruhigere Fassung, in der nur die Platte wegdriftet und zurückschnappt. Im Logo-Intro fliegen die Platten ein und rasten ein, danach baut sich die Wortmarke Buchstabe für Buchstabe auf, danach bricht der Ring einmal auf.

## Eine Formsprache aus dem Zeichen

Die Oberfläche spricht die Form des Logos. **Alles, was du drücken kannst, ist ein Achteck oder hat eine Fase**, wie der Ring. Panels, Fenster, Buttons und Tags tragen zwei abgeschrägte Ecken. Runde Formen bleiben Signalen vorbehalten, die nichts auslösen: Online-Punkte, Kartensymbole, der Daumen des Touch-Sticks. Keine Pillen, eine Akzentfarbe, Knochenweiß auf Graphit und tiefem Raum.

## Eine Landingpage im HUD-Look

[starhavoc.net](https://starhavoc.net) öffnet mit einer Landingpage, die aussieht wie das Cockpit des Spiels: ein HUD-Rahmen um die Seite, ein Sternenfeld mit Parallax und das Logo-Intro aus dem Brand-Film, bei dem eine Amber-Scanlinie über jeden Buchstaben fährt. Eine **Sternenkarte** führt von den Heimatsystemen bis in den Shattered Nexus, mit Codex-Bildern und automatischer Tour, **Codex-Karten** scannen sich beim Hover ein, und selbst die Cursor kommen aus der Marke: ein knochenweißer Pfeil, ein Amber-Zeiger, ein rotes Fadenkreuz über Gegnern.

## Art Direction: Used Future

Der Look ist kantig und abgenutzt, eine **Used Future** mit Kristallschwärmen in Teal und Magenta vor industriellen Rümpfen. Schiffe folgen einer Pipeline: ein **Konzeptbild mit Nano Banana**, dann ein **3D-Modell mit meshy oder tripo**, dann die Optimierung mit **gltf-transform** auf WebP-Texturen und unter 60.000 Dreiecke pro Schiff. Waffenplätze, Triebwerke und Glow stehen je Modell in einer Tabelle, und fehlt ein Modell, springt ein prozedurales Schiff ein.

Jedes Schiff lässt sich **lackieren**: Rumpflack, ein Akzent für Lichtstreifen, Schild und Muster, eine Farbe für die Triebwerksflamme und Muster wie Streifen, Winkel oder Hex-Platten. Der Lack läuft als Shader-Tönung auf dem Modell, darum passt jede Kombination auf jeden Rumpf. Manche Lacke gibt es nur als Belohnung, über Meisterränge, den Quartiermeister einer Fraktion oder einen Saison-Boss.

## Gebaut mit Claude Code und Agenten

STARHAVOC ist mit **Claude Code** als Entwicklungspartner entstanden, in langen Sessions mit parallelen Agenten: Einer hat die Drohnen-Modelle gebaut, einer hat nach Sicherheitslücken wie Item-Duplizieren oder Belohnungs-Farming gesucht, andere haben Browser-Tests auf Desktop und Handy gefahren. Über das **Magnific MCP** kamen Konzepte, Modelle, Illustrationen und Musik direkt aus dem Gespräch. Ich habe die Richtung vorgegeben und jedes Konzept geprüft.

Nach jedem Playtest habe ich aufgeschrieben, was sich falsch anfühlte, manchmal 25 Punkte auf einmal: zitternde Schiffe, harte Musikwechsel, eine Feuertaste, die auf dem iPhone hinter einer Tutorial-Karte lag. Jede Runde ging mit Messungen zurück in den Code, nicht mit Vermutungen.

## Blender per Script

Nicht alles kam aus einem Generator. Bosse wie der **Hive, der Shard Overseer und der Void Herald**, die kleinen Schwarmgegner und ein Bausatz aus 11 Waffen und 15 Modulen sind **Blender-Modelle, geschrieben als Python-Scripts**. Jedes Script baut ein Low-Poly-Mesh aus mehreren Teilen, backt Grundfarbe, Normalen, Rauheit und Leuchten mit Cycles und exportiert ein optimiertes Modell. Stationen, Sprungtore und Clan-Basen bekommen gebackene Hüllentexturen ebenfalls aus Blender. So bleibt jedes Modell reproduzierbar: Parameter ändern, Script laufen lassen, neu exportieren.

## Figuren, Story und Sound

Das Spiel erzählt seine Geschichte in **drei Kapiteln**, vom Bergungsrecht bis zum letzten Kampf gegen den Devourer. Eine Weltbibel hält den Sternhaufen aus zehn Systemen, die Fraktionen und die Nebenfiguren fest, und ein **Codex mit 98 Einträgen** füllt sich beim Spielen. **Thea Marlow, Liv Lindqvist und Idris Vasse** führen dich durch Briefings und Funksprüche, mit Porträts im Stil der Key-Arts. Jedes Kapitel startet mit einem kurzen animierten Intro, und jedes System hat seine eigene Musik.

## Echtzeit-Multiplayer-Architektur

Unter der Haube steckt ein echtes Online-Spiel, **gebaut ohne Game-Engine**:

- **Client:** three.js und TypeScript, ohne Framework. HUD und Fenster sind schlichtes DOM, Namen und Schadenszahlen liegen auf einem Overlay-Canvas. Fenster laden lazy und three.js hat einen eigenen Chunk, damit sank der erste Download von 1,37 MB auf 413 kB.
- **Autoritativer Server:** Node 24 führt TypeScript direkt aus. Der Client schickt nur Absichten (Ziel, Kurs, Kauf). Ein Takt mit 20 Hz rechnet alle Sektoren, und jeder Pilot bekommt 10 Snapshots pro Sekunde per WebSocket, komprimiert von rund 7 KB auf 1,5 KB pro Kampf-Snapshot.
- **Gemeinsame Regeln:** Inhalte, Wirtschaft und Protokoll liegen in einem gemeinsamen Ordner. Der Server führt sie aus, der Client nutzt sie zur Anzeige.
- **Vorhersage:** Das Flugmodell rechnet auf Server und Client identisch, darum reagiert das eigene Schiff in **5 bis 13 ms statt 94 bis 278 ms** bei bis zu 150 ms Ping. Alle anderen Schiffe werden interpoliert.
- **Persistenz:** eine SQLite-Datenbank im selben Prozess, mit täglichem Backup.
- **Härtung:** Origin-Check, Verbindungs- und Login-Limits, gehashte Passwörter und Sitzungstokens, Content Security Policy und Deploys ohne Ausfallzeit, die vor jedem Neustart alle Piloten speichern.

## Sicherheit und Pentests

Ein offenes Online-Spiel zieht Bots, Cheater und Trolle an, darum bekam die Sicherheit dieselbe Aufmerksamkeit wie das Gameplay:

- **Konten:** Registrierung mit E-Mail-Bestätigung, Cloudflare Turnstile gegen Bots, Limits pro IP für Verbindungen, Logins, Registrierungen und Passwort-Resets.
- **Chat:** Text wird nie als HTML ausgegeben, und die Content Security Policy erlaubt nur die eigenen Scripts des Spiels. Nachrichten haben höchstens 200 Zeichen und 400 ms Abstand, ein Filter maskiert Beleidigungen (auch in Leetspeak) und lehnt anstößige Pilotennamen ab, Admins können stummschalten und sperren.
- **Edge:** Cloudflare mit Web Application Firewall vor dem Server.
- **Pentests:** Zwei Agenten haben parallel eine lokale Instanz mit Produktions-Konfiguration angegriffen, jeder mit eigenem Server und eigener Datenbank. Einer nahm sich Web, Login und Admin vor, der andere Spielprotokoll und Wirtschaft, auf der Suche nach WebSocket-Missbrauch und Item-Dupes. Die Produktion selbst habe ich nur von außen und ohne Schreiben geprüft: Header, TLS, offene Pfade und direkter Zugriff über die IP.

## Auch fürs Handy gebaut

STARHAVOC ist eine **PWA** mit einem HUD, das vom Handy aus gedacht ist: Die Aktionstasten liegen auf zwei symmetrischen Bögen unter dem Daumen, Fenster öffnen sich im Vollbild, und das Layout respektiert Dynamic Island und Home-Leiste. Die Bildrate im Kampf auf dem Handy habe ich von 42 auf 54 FPS gebracht und die Downloads beim Login von 42 MB auf 14 MB gesenkt.

## Mehr als Fliegen und Schießen

Du fliegst aus **zwei Perspektiven**: aus der klassischen Draufsicht des Genres oder mit einer **Verfolgerkamera** direkt hinter deinem Schiff, jederzeit mit einer Taste umschaltbar. Dasselbe Spiel, derselbe Server, aber der Schwarm kommt plötzlich auf dich zu, statt über eine Karte zu krabbeln.

Fünfzehn Schiffe mit echten Unterschieden in Tempo, Wendigkeit, Trägheit und Panzerung. Drohnen mit Formationen und Kernen. Ein Skilltree, drei Fraktionen mit Heimatsystemen und Ruf, Clans mit Basen, Gates mit Wellen und Zielzeiten, wöchentliche Modifikatoren, ein Saison-Weltboss, Duelle mit Elo-Wertung, Ranglisten, eine Sternenkarte mit Autopilot über Sprungtore und ein **Foto-Modus** mit freier Kamera und Filtern.

## Admin, Design-Ansicht und Dokumentation

Ein Admin-Backend verwaltet Piloten, Sperren, geplante Events und ein Wirtschafts-Journal. Seine **Design-Ansicht** rendert jeden Gegner, jedes Schiff, jede Waffe, Explosion und jedes Icon unter dem Licht des Spiels, so prüfe ich Grafik an einem Ort. Auch ein Dokument zur Systemarchitektur liegt im Admin, mit dem Quellpfad neben jeder Zahl, die es nennt.

## Getestet wie ein Produkt

Mehr als **600 Unit-, Simulations- und Server-Tests** und Playwright-End-to-End-Tests sichern das Spiel ab. Ein Progressions-Simulator spielt jedes Jagdgebiet mehrfach durch, um das Level-Tempo auszubalancieren, und eigene Scripts messen Zittern, Kollisionen und Ruckler beim Sektorwechsel.

## Das Ergebnis

Ein Spiel, das ich schon immer bauen wollte, auf meinem eigenen Server: mit eigener Marke, eigener Welt und einer Multiplayer-Architektur, die auch auf dem Handy trägt. Und endlich wieder ein Weltraumspiel, das auf meinem Mac läuft. Flieg mit auf [starhavoc.net](https://starhavoc.net).
