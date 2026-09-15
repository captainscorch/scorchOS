---
slug: planrivo
title: 'Heimübungspläne für Physiopraxen in unter 60 Sekunden'
story_preview: 'planrivo ist mein eigenes Produkt für Physiopraxen, vermarktet und betrieben über mein Studio: ein Heimübungsplan in unter 60 Sekunden, übergeben per QR-Code oder Ausdruck, ohne Patientenkonto.'
fineprint: "Mein eigenes Produkt, von Anfang bis Ende selbst gebaut: vom ersten Konzept und der Marke bis zum Backoffice für Therapeuten und dem Patientenportal, vermarktet über mein Studio. Alle Screenshots zeigen eine Demo-Praxis."
---

# Der Plan steht, bevor der Patient die Praxis verlässt

Eine Physiotherapie-Sitzung dauert 15 bis 25 Minuten. Der Patient sieht jede Übung einmal und soll sie wochenlang zu Hause korrekt wiederholen. In der Praxis werden Übungen vergessen, falsch gemacht oder gar nicht. planrivo ist meine Antwort: Der Therapeut stellt den Heimübungsplan in unter 60 Sekunden zusammen, mitten in der Behandlung, und der Patient hat ihn per QR-Code auf dem Handy oder auf Papier in der Hand, bevor er geht. Kein Konto, keine App-Installation. Ich habe planrivo komplett konzipiert und gebaut, von Konzept und Marke bis Backoffice, Patientenportal und gedrucktem Plan. Mein Studio unlimited.studio vermarktet und betreibt es.

## Zeit ist die Metrik, nicht die Bibliotheksgröße

Wettbewerber verkaufen über die Übungsanzahl. Niemand konkurriert über Sekunden bis zum fertigen Plan, und im 20-Minuten-Takt ist das die einzige Zahl, die im Praxisalltag zählt. Also habe ich jede Ansicht gegen die Stoppuhr geprüft. Die Bibliothek umfasst 55 Übungen mit Schritten, Körperfigur, Hilfsmitteln und Standarddosierung, alle extern fachlich geprüft und freigegeben. Sie ist absichtlich klein: Finden schlägt Scrollen.

## Der 60-Sekunden-Flow

**Patient:** Ein Vorname genügt, alles Weitere ist optional und nachtragbar. **Übungen:** Suche nach Name oder Schritt, Filter nach Körperregion, Hilfsmittel oder Muskelgruppe auf einer interaktiven Muskelkarte, oder Start aus einer Praxis-Vorlage. Ein Hinweis bietet den letzten Plan des Patienten zur Übernahme an. **Dosierung:** Die Standardwerte stehen schon drin, Sätze, Wiederholungen und Häufigkeit ändern sich per Stepper direkt in der Liste, kein Dialog, kein Formularschritt. **Übergabe:** Der QR-Code öffnet sich im Vollbild zum Abscannen, die Druckansicht ist einen Klick entfernt, ein gebrandetes A4-Blatt mit demselben Code. Druck ist ein gleichwertiger Weg, kein Fallback: Viele Patienten sind in einem Alter, in dem ein Smartphone nicht selbstverständlich ist.

## Kein Konto für den Patienten

Der Patientenlink trägt einen Token statt eines Logins. Beim ersten Öffnen fragt das Portal das Geburtsjahr als zweiten Faktor ab, danach ist der Plan auf diesem Gerät 90 Tage offen. Die Praxis kann den Zugang jederzeit verlängern, neu erzeugen oder sperren und sieht, wann er zuletzt geöffnet wurde. Wer seinen Plan behalten will, sichert ihn später mit einer E-Mail-Adresse, muss aber nicht. Das Portal selbst bleibt schlicht: große Schrift, Schritte als nummerierter Text, die Körperfigur zur Orientierung, ein Druckknopf.

## Ein Rückkanal, der auf der richtigen Seite der Linie bleibt

Patienten können sich aus dem Portal zurückmelden: ein Schmerzwert von 0 bis 10 und eine kurze Notiz. Die Praxis sieht das unter Rückmeldungen und auf der Übersicht, die Statistik zeigt, wie viele Pläne zu Hause überhaupt geöffnet wurden. Genauso bewusst ist, was planrivo nicht tut. Die Software schlägt nie aus Schmerz- oder Befunddaten Übungen vor. Diese Grenze ist gewollt. Keine Streaks, keine Achievements, kein Trainingslog: Der Patient soll die Übungen verstehen und machen, nicht eine App pflegen.

## Gebaut für Gesundheitsdaten

Zwei-Faktor-Authentifizierung ist für jedes Therapeutenkonto Pflicht, eine PIN-Sperre schützt das offene Tablet im Behandlungsraum, ein Protokoll hält jede Änderung an Patientendaten fest. Gelöschte Patienten landen erst im Papierkorb und werden zeitgesteuert endgültig entfernt. Die Plattform kommt ohne Analyse-Cookies aus, rendert serverseitig und ist standardmäßig hell, weil viele Patienten älter sind. Das Backoffice hat ein Befehlsmenü, eine Einführungstour und ein Handbuch, damit eine Praxis ohne Telefonat startet.

## Die Landingpage ist die Demo

Die Behauptung lautet: Ein Plan dauert unter eine Minute. Das beweist kein Screenshot. Deshalb steht im Hero der Startseite eine bedienbare Miniatur des Plan-Builders: Übungen anklicken, Dosierung mit den Steppern verschieben, auf die Übergabe umschalten und QR-Code und Patientenansicht sehen. Keine Netzwerkanfragen, kein Konto. Die Seite steht in Instrument Serif auf einer leicht lavendelfarbenen Leinwand, darunter echte Produkt-Screenshots.

## Der Stack

Laravel, Inertia und Vue in TypeScript, Tailwind, Server-Side Rendering und MySQL, dazu eine umfangreiche automatisierte Testsuite, die die Kernabläufe Ende zu Ende absichert.
