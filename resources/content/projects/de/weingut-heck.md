---
slug: weingut-heck
title: 'Website-Relaunch für ein Familienweingut in Rheinhessen'
story_preview: 'Eine neue Website für das Weingut Heck in Alsheim: ein Design voller Aquarell, Halbton-Illustration und Sticker, gebaut als Statamic-Seite mit serverseitigem Rendering, Scroll-Animationen, Age-Gate und Newsletter mit Double-Opt-in.'
fineprint: 'Das Design kam von [Marie Grob](https://mariegrob.com) und [Jacky Grob](https://jackygrob.com). Ich habe die Seite gebaut, vom CMS und dem Animationssystem bis zu Age-Gate, Cookie-Consent und Newsletter.'
---

# Ein Design, das nicht stillhält

Das Weingut Heck ist ein Familienweingut in Alsheim, Rheinhessen: Dieter und Lotte Heck, die Söhne schon im Keller. Die neue Seite hatte ein Design, bevor sie einen Entwickler hatte, von [Marie Grob](https://mariegrob.com), Fotografin und Motion Designerin in Mainz, und [Jacky Grob](https://jackygrob.com), Designerin in Potsdam: Aquarell-Flecken, Halbton-Illustrationen, Sticker, Laufbänder, Flaschen, die auf der Seite liegen und sich aufstellen, wenn man ihre Zeile öffnet. Meine Aufgabe war, genau das zu bauen, Pixel für Pixel, und es schnell zu machen.

## Statamic unter der Haube

Die Seite läuft auf Statamic mit Laravel, Inertia und Vue. Jede Seite ist ein Stapel aus Modulen, die das Weingut im CMS pflegt: Stages, Produkt-Teaser, Kartenraster, Galerien, Laufbänder und die Öffnungszeiten mit Sommer- und Wintersaison. Die Seiten rendern serverseitig, also trägt der erste Paint schon den Inhalt und die Illustrationen kommen ohne Flackern. Das Menü öffnet sich zu einer Karte mit einem Foto je Bereich, und das Kontakt-Panel markiert in den Öffnungszeiten den heutigen Tag.

## Animation ohne Gewicht

Das Wein-Akkordeon ist das Herzstück. Eine Flasche liegt in ihrer Zeile, und wenn die Zeile aufgeht, fliegt eine Geisterkopie rotierend in die Showcase-Pose, während sich das Panel morpht, beides auf einer Uhr. Die Form auf der Startseite zeichnet sich einmal und dreht sich pro Scroll-Abschnitt um 120 Grad. Scroll-Reveals liefern ihren versteckten Startzustand schon im Server-HTML, also springt nichts. Die Flaschenschatten gingen nach einem Profiling-Durchgang von sechs Blur-Pässen auf vier: aus zwölf langen Frames wurden null.

## Illustrationen fürs Handy

Die Illustrations-Bibliothek ist Halbton mit Alpha, über fünf Megabyte in 2x. Jede Illustration hat jetzt eine Handy-Kopie in der Breite ihrer größten mobilen Darstellung, und die CMS-Fotos laufen unterhalb des Tablet-Breakpoints über Glide. Die Startseite ging auf dem Handy von 4,5 auf 1,3 Megabyte. Ein Fund nebenbei: Ein 12,9-Zoll-iPad ist im Hochformat exakt 1.024 Pixel breit und traf den Desktop-Breakpoint, also ist der Breakpoint um einen Pixel gewandert.

## Age-Gate, Consent, Newsletter

Eine Weinseite fragt zuerst nach dem Alter. Die Antwort liegt in einem notwendigen Cookie, ohne Banner. Der Cookie-Consent läuft im Studio-Muster mit zwei Kategorien, Analytics lädt erst nach Zustimmung. Der Newsletter hat Double-Opt-in mit signiertem Link, bestätigte Adressen erreichen das Weingut als Digest mit CSV, im Takt, der im CMS steht.

## Browser-Archäologie

Ein Kundenkommentar, „die Merlot-Flasche verdeckt den ersten Satz“, war am Ende ein Browser-Bug. Die Stage-Skalierung teilte eine Länge durch eine Länge, das parst nur Chrome ab Version 140. Firefox verwarf die Regel, und die Flasche hing 150 Pixel ins nächste Modul. Der Faktor kommt jetzt aus einem ResizeObserver, CSS setzt nur noch den Startwert. Stay wild, drink easy.
