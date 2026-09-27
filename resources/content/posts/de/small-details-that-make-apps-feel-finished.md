---
slug: small-details-that-make-apps-feel-finished
title: 'Kleine Details, die eine App rund machen'
excerpt: 'Zehn kleine UI-Konventionen, die eine App rund machen, vom Command-Menü bis zum KI-Agent-Chat. Zu jeder gibt es eine Live-Demo, in der du die Regel abschalten kannst.'
tags: ['UI', 'UX', 'Design-Engineering', 'SaaS', 'KI-Agenten', 'Vue']
---

In meinem Artikel über den [konzentrischen Border-Radius](/blog/craft/concentric-border-radius) habe ich geschrieben, dass Premium-Qualität meist aus unzähligen kleinen Entscheidungen entsteht. Die meisten davon sieht man auf keinem Screenshot. Niemand lobt eine App für ihre Menüs, aber jeder merkt, wenn ein Menü flackert, eine Statusänderung eine Sekunde dauert oder ein Spinner einen einzigen Frame lang aufblitzt.

In diesem Artikel sammle ich die Konventionen, auf die ich immer wieder zurückkomme. Einige stammen aus Linear und Raycast, die ich täglich nutze, eine aus den Tabellen von Attio. Die Chat-Muster sind bei der Entwicklung des KI-Coachs in [lyftd](/case-study/lyftd) entstanden. Zu jedem Abschnitt gibt es eine kleine Demo, in der sich die Regel abschalten lässt – die kaputte Version ist oft die beste Erklärung.

## Web-Apps

### Command-Menüs

Mit einem Command-Menü kommt man am schnellsten durch eine App – Raycast besteht im Grunde aus nichts anderem. Genau deshalb fallen kleine Fehler dort so auf. Drei Regeln sind dabei entscheidend:

- **Erst markieren, wenn sich der Zeiger bewegt.** Liegt die Maus zufällig dort, wo das Menü aufgeht, darf die Zeile darunter nicht aufleuchten. Sonst wählt Enter etwas aus, auf das man nie gezeigt hat.
- **Beim Klick auswählen, nicht beim Drücken.** So lässt sich ein Fehlklick noch abbrechen, indem man vor dem Loslassen wegzieht.
- **Tastatur-Hinweise auf Touch-Geräten ausblenden.** Ohne Tastatur stören sie nur.

::interactive[CommandMenuEtiquetteDemo]

### Optimistische Updates statt Bestätigungsdialog

Wer in Linear den Status eines Issues ändert, sieht das Ergebnis sofort, weil die Oberfläche nicht erst auf den Server wartet. Das Muster dahinter: die Änderung direkt anzeigen, den Request im Hintergrund schicken und sie nur rückgängig machen, wenn er tatsächlich fehlschlägt. Ein kurzer Toast sagt dann Bescheid.

Der zweite Teil ist genauso wichtig: Der Erfolgs-Toast bietet ein Undo an. Erst dadurch lässt sich der Bestätigungsdialog komplett streichen. Ein Dialog kostet bei jeder einzelnen Aktion einen Klick, ein Undo nur dann, wenn man sich wirklich vertan hat.

::interactive[OptimisticUndoDemo]

### Hover-Intent und das sichere Dreieck

Dropdowns, die schon bei der kleinsten Mausbewegung aufgehen, öffnen sich ständig aus Versehen. Dropdowns, die sofort zugehen, sobald der Zeiger den Trigger verlässt, sind weg, bevor man sie erreicht. Zwei Regeln lösen beide Probleme: Das Menü öffnet sich erst, wenn der Cursor etwa 150 ms stillsteht. Und ist es einmal offen, bleibt es offen, solange sich der Cursor darauf zubewegt.

Die Fläche, die der Cursor dabei überqueren darf, bildet ein Dreieck zwischen der Stelle, an der er den Trigger verlassen hat, und den nahen Ecken des Panels. [Ben Kamens hat 2013 das Mega-Dropdown von Amazon analysiert](https://bjk5.com/post/44698559168/breaking-down-amazons-mega-dropdown) und genau diese Technik beschrieben. Trotzdem fehlt sie in den meisten SaaS-Apps bis heute. Die Demo zeichnet das Dreieck ein, damit du siehst, wie es funktioniert.

::interactive[HoverIntentDemo]

### Bearbeiten ohne Modus

Die Tabellen in Attio haben keinen eigenen Bearbeitungsmodus. Eine Zelle ist reiner Text, bis man sie anklickt. Dann wird sie zu einem Eingabefeld mit demselben Innenabstand und derselben Höhe, sodass sich auf der Seite nichts verschiebt. Die Tastatur verhält sich wie in jeder Tabellenkalkulation: Escape stellt den alten Wert wieder her, Enter oder ein Klick daneben speichert, und Tab springt direkt in die nächste Zelle.

Im Vergleich zu einem Bearbeitungs-Panel, das unter der Tabelle einfährt, ist der Unterschied sofort klar. Das eine fühlt sich an wie Arbeiten in einer Tabelle, das andere wie das Ausfüllen eines Formulars.

::interactive[InlineEditDemo]

### Ladeanzeigen mit zwei Schwellen

Ein Spinner, der nur 80 ms lang auftaucht, ist schlimmer als gar keiner: Der Bildschirm flackert kurz, und Nutzer nehmen eine Verzögerung wahr, die es eigentlich nicht gab. Zwei Schwellenwerte lösen das. Erstens erscheint 200 ms lang gar nichts, damit schnelle Antworten nie eine Ladeanzeige auslösen. Zweitens bleibt eine Anzeige, die einmal sichtbar ist, mindestens 300 ms stehen, damit sie nie nur kurz aufblinkt.

Zieh einfach den Latenz-Regler durch den Bereich und schau, welche Antworten überhaupt eine Anzeige bekommen.

::interactive[LoadingThresholdsDemo]

### Tabellenziffern

Viele Schriften nutzen standardmäßig Proportionalziffern: Eine 1 ist schmaler als eine 8. Im Fließtext sieht das besser aus, aber sobald sich Zahlen ändern oder untereinander stehen, wird es zum Problem. Ein Timer zappelt bei jedem Tick hin und her, und Preise in einer Tabelle stehen rechts nicht mehr bündig.

Eine einzige CSS-Eigenschaft löst das: `font-variant-numeric: tabular-nums` gibt jeder Ziffer dieselbe Breite. Ich setze sie überall dort ein, wo Ziffern untereinander stehen oder sich vor den Augen der Nutzer ändern – in lyftd also in jeder Statistik-Karte, jedem Gewichtsfeld und jedem animierten Zähler. Im Fließtext bleibe ich bewusst bei Proportionalziffern.

::interactive[TabularNumbersDemo]

### Hover-Effekte ohne Flackern

Eine Karte, die beim Hover leicht abhebt, zieht ihre eigene Unterkante unter dem Cursor weg. Für einen Moment liegt der Zeiger außerhalb der Karte, der Hover endet, die Karte fällt zurück unter den Zeiger, und der Hover beginnt von vorn. Genau an der Unterkante entsteht so eine Schleife.

Die Lösung liegt in der Struktur: Der Link behält seine Größe und Position, nur ein inneres Element bewegt sich und bekommt den Schatten. So bleibt die Trefferfläche immer an derselben Stelle. Diesen Bug hatte ich selbst auf den Portfolio-Karten dieser Website und habe ihn genau so behoben.

::interactive[HoverLiftDemo]

## Agent-Chats

Mit KI-Agenten erleben Chat-Oberflächen einen zweiten Frühling, und sie bringen ihre ganz eigenen Details mit. Antworten kommen Token für Token, Tools laufen mittendrin, und Nutzer brauchen eine Möglichkeit, sie zu unterbrechen. Alle drei Demos laufen mit einer geskripteten Antwort, es ist also keine API im Spiel.

### Automatisch mitscrollen

Während eine Antwort streamt, sollte der Chat automatisch zur neuesten Nachricht mitscrollen. Außer der Nutzer hat nach oben gescrollt, um etwas nachzulesen – dann ist jeder neue Token, der ihn wieder nach unten zieht, so ziemlich das Nervigste, was ein Chat tun kann.

Die Regel ist einfach: nur mitscrollen, solange der Nutzer ohnehin unten ist. Sobald er nach oben scrollt, bleibt der Verlauf stehen, und ein kleiner Button bringt ihn zurück zur neuesten Nachricht.

::interactive[StickToBottomDemo]

### Tool-Calls als Status-Karten

Ein Agent, der Daten liest, darf das von sich aus tun. Ein Agent, der Daten verändert, sollte auf eine Freigabe warten. In lyftd schaut der Coach ohne Rückfrage in die Trainingshistorie, jede Änderung am Plan kommt dagegen als Vorschlag, den man übernehmen oder verwerfen kann.

Im Chat bekommen beide Arten von Aktionen eine Karte mit klarem Status: pending, running, done oder failed. Aktionen, die etwas ändern, haben zusätzlich Run- und Reject-Buttons. Und der Folgetext beginnt erst zu streamen, wenn alle Tools fertig sind – eine Antwort, die sich auf ein Ergebnis bezieht, das noch gar nicht da ist, ist schlimmer als eine kurze Pause.

::interactive[ToolCallCardsDemo]

### Der Composer

Für das Eingabefeld gelten vier eigene Regeln. Es wächst mit dem Inhalt bis auf sechs Zeilen und scrollt dann. Enter sendet, Shift+Enter fügt eine neue Zeile ein, mit einer Ausnahme: Bei japanischer oder chinesischer Eingabe bestätigt Enter zuerst die Zeichen, deshalb muss der Handler Enter ignorieren, solange eine IME-Eingabe aktiv ist. Sonst geht die Nachricht halb getippt raus. Während eine Antwort streamt, wird aus dem Senden-Button ein Stop-Button, und Escape bricht ebenfalls ab. Und ein kleiner Hinweis unter dem Feld zeigt, was davon gerade gilt.

::interactive[ComposerRulesDemo]

## Abschließende Gedanken

Keine dieser Regeln taucht auf einem Screenshot auf, und genau deshalb fliegen sie meistens als Erstes raus, wenn eine Deadline knapp wird. Sie zeigen sich erst in der Benutzung: Dann läuft einfach alles reibungslos. Aufwendig ist dabei keine von ihnen. Alle Demos findest du samt Code-Snippet im [Playground](/playground), zusammen mit dem Experiment zum konzentrischen Radius.

Es ist wie bei konzentrischen Ecken: Die meisten nehmen diese Details nie bewusst wahr. Aber sie merken sofort, wenn sie fehlen.
