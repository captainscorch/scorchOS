---
slug: lyftd
title: 'Smart Training, Stronger You — Workout-Tracking-Plattform mit nativer iOS-App und KI-Coaching-Agent'
story_preview: 'lyftd ist mein Passion Project — eine anspruchsvolle Workout-Tracking-Plattform, die ich von Grund auf gebaut habe, um den gesamten Lebenszyklus von Trainingsprogrammen zu managen. Jetzt mit nativer iOS-App und einem KI-Coach, der direkt auf deinem Plan handelt, dazu umfassende Analytics, Abonnements und eine Plattform für Trainer'
fineprint: 'Komplett alleine gebaut als meine persönliche Sandbox für Full-Stack-Produktentwicklung. Von Authentifizierungs-Flows bis zur Payment-Integration, von einer nativen iOS-App bis zu einem KI-Coaching-Agenten mit eigenem Harness — jede Zeile Code stammt von mir. Hier pushe ich meine Grenzen als Entwickler.'
---

# Ich konnte keinen Workout-Tracker finden, der mir gefiel, also habe ich meinen eigenen gebaut.

lyftd entstand aus persönlicher Frustration mit bestehenden Fitness-Apps — sie waren entweder umständlich, voller Werbung oder hatten nicht die Flexibilität, die ich brauchte. Ich wollte ein Tool, das sich premium anfühlt, nahtlos auf all meinen Geräten funktioniert und sich meinem Trainingsstil anpasst. Also habe ich es gebaut. Aus der Web-App ist inzwischen eine native iOS-App geworden, dazu ein KI-Coach, der nicht nur redet, sondern handelt.

## Die Vision

Mein Ziel war es, eine modulare, hochperformante Plattform zu schaffen, die als umfassender Begleiter für Fitness-Enthusiasten dient. Es geht nicht nur darum, Gewichte zu protokollieren — sondern darum, den gesamten Lebenszyklus eines Trainingsprogramms zu managen. Vom Erstellen komplexer mehrwöchiger Pläne bis zur Durchführung von Sessions mit Fokus und Flow ist jede Interaktion auf Reibungslosigkeit ausgelegt.

## Das Tech Stack

Aufgebaut auf einem robusten **Laravel**-Backend und einem **Vue 3**-Frontend powered by **Inertia.js**, liefert die Web-App ein modernes SPA-Erlebnis ohne die Komplexität einer separaten API. **MySQL** handhabt die Datenebene, **Tailwind CSS** sorgt für eine Dark-Mode-first, datenreiche UI, und **Chart.js** treibt die Analytics an. **Resend** übernimmt Transaktions-Mails, **Sentry** das Error-Tracking.

- **Laravel Jetstream:** Vollständiges Authentifizierungssystem mit E-Mail-Verifizierung, Zwei-Faktor-Authentifizierung, Session-Management und Account-Löschung
- **Filament:** Admin-Panel für Plattform-Management, Benutzerübersicht und Abo-Monitoring
- **Paddle + Laravel Cashier:** Vollständiges Abo-System mit Core- und Elite-Stufen, Pause/Resume-Funktionalität und Billing-Portal-Integration

## Native iOS-App

lyftd gibt es jetzt als echte iOS-App, aktuell im TestFlight vor dem Launch. Es ist eine eigenständige Vue-3-Single-Page-App, mit **Capacitor 7** in ein richtiges Xcode-Projekt verpackt — keine Web-View, die man in eine Hülle geklebt hat.

- **Eigene API:** Die App spricht mit einem dedizierten `/api/mobile`-Endpunkt, abgesichert über **Laravel-Sanctum**-Tokens — Cookie-basierte Auth funktioniert von einem `capacitor://`-Origin aus nicht, mobile bekommt also einen eigenen Auth-Weg
- **Feature-Parität:** Nahezu vollständige Parität zur Web-App, mit einer bewussten Ausnahme — keine In-App-Purchase-UI. Abos bleiben auf lyftd.app, wo sie hingehören
- **Native Details:** Lokale Notifications halten den Pausen-Timer auch bei gesperrtem Bildschirm am Laufen, dazu Haptik, das native Share Sheet und ein selbst gebautes natives Tab-Bar-Plugin

## Workout-Planung & Ausführung

Das Planungssystem ist auf Flexibilität ausgelegt:

- **Individuelle Workout-Pläne:** Mehrwochen-Pläne mit konfigurierbarer Dauer, Deload-Wochen und tageweiser Planung
- **Übungskonfiguration:** Sätze, Wiederholungen, Gewicht, Ziel-RPE, Pausenzeiten und Volumen-Tracking pro Übung
- **Trainingstiefe:** Supersätze, Warm-up-Ramps, Myo-Reps, seitengetrenntes Tracking, halbe Wiederholungen und ein eingebauter Plattenrechner
- **Freestyle-Workouts:** Eine Session ohne Plan im Hintergrund direkt loggen
- **Plan-Sharing & Duplizierung:** Pläne mit Viewer- oder Editor-Berechtigungen teilen, ganze Pläne, einzelne Tage oder spezifische Übungen klonen
- **Einheiten nach Wahl:** kg/lbs und RPE/RIR als App-weite Nutzereinstellung

Bei der Ausführung glänzt lyftd:

- **Präzises Tracking:** Sätze, Wiederholungen, Gewicht, Dauer und RPE in Echtzeit protokollieren
- **Pausen-Timer:** Automatischer Countdown zwischen Sätzen mit Pause/Resume-Funktionalität, läuft nativ auch bei gesperrtem Handy weiter
- **Dynamisches Hinzufügen von Übungen:** Neue Übungen mitten in der Session hinzufügen — entweder einmalig für das aktuelle Workout oder für alle zukünftigen Sessions im Plan
- **Skip-Logik:** Sätze oder ganze Übungen überspringen mit intelligentem Completion-Tracking
- **Auto-Vervollständigung:** Sessions aus vorherigen Wochen werden automatisch mit einem Hinweis versehen abgeschlossen

## KI-Coaching-Agent

Der AI Coach hat als Post-Workout-Feedback angefangen. Heute ist er ein vollwertiger Agent mit eigenem Harness, eigenen Actions und Feedback-Loops, auf Basis von **OpenAIs gpt-5.6-luna**.

- **Chat überall:** Mit dem Coach von überall in der App aus reden. Ein Intent-Service erkennt Änderungswünsche — „tausch diese Übung", „erhöh das Gewicht" — auf Deutsch oder Englisch und steuert den Prompt entsprechend
- **Actions statt nur Ratschläge:** Das Modell kann echte Änderungen an einem Plan vorschlagen — Übung tauschen, Sätze, Wiederholungen, Gewicht, Pause oder Ziel-RPE anpassen — über einen strukturierten Marker in seiner Antwort. Dieser Vorschlag wird nie ungeprüft übernommen: Der Harness löst das Ziel neu gegen deinen tatsächlich aktuellen Plan auf, whitelistet, welche Felder überhaupt geändert werden dürfen, validiert jeden Wert über dieselben Regeln wie der Plan-Editor und cached den aufgelösten Vorschlag serverseitig unter einer zufälligen ID. Dein Gerät trägt nur diese ID mit sich herum, nie die eigentliche Änderung
- **Du behältst die Kontrolle:** Jede vorgeschlagene Änderung erscheint als Alt-gegen-Neu-Diff-Karte. Du übernimmst oder verwirfst sie explizit, und beim Übernehmen validiert der Server noch einmal gegen deinen aktuellen Plan, bevor irgendetwas geschrieben wird
- **Post-Workout-Feedback-Loop:** Nach jeder Session schreibt der Coach Anpassungsempfehlungen. Du hakst ab, welche übernommen werden, künftige Sessions passen sich entsprechend an, und die Historie bleibt als Snapshot erhalten — nichts wird im Nachhinein umgeschrieben
- **Wöchentliche und monatliche Recaps:** Die KI vergleicht Volumen, Adherence, PRs und Streaks mit der Vorperiode, inklusive Deload-Empfehlung, wenn die Anstrengung steigt, das Gewicht aber stagniert
- **Plan-Generator:** Ziele, Trainingstage pro Woche, Equipment und Verletzungen rein — ein kompletter, überprüfbarer Plan raus. Schlägt außerdem den nächsten Trainingsblock vor, sobald ein Plan ausläuft
- **Logging in natürlicher Sprache:** „Bankdrücken 3x8 80kg" eintippen, und es wird zur geloggten Übung
- **Guardrails:** OpenAI-Moderation auf jeder Eingabe, Rate-Limits pro Abo-Stufe, jede Interaktion wird geloggt

## Trainer-Plattform

Über dem Consumer-Produkt liegt eine B2B-Ebene: ein Workspace für Trainer, um ihre Klienten durch lyftd zu führen.

- **Roster & Billing:** Trainer laden Klienten per Link ein und verwalten ein Roster; Seats werden zusätzlich zum Paddle-Abo abgerechnet, Klienten im Roster eines Trainers bekommen Elite kostenlos
- **Plan-Verteilung:** Pläne und Templates einzelnen Klienten oder ganzen Gruppen auf einmal zuweisen
- **Reporting:** Per-Klient-Statistiken, gebrandete PDF-Fortschrittsberichte und ein wöchentlicher Adherence-Digest per Mail
- **Kommunikation:** Direkter Trainer-Klient-Chat, dazu Intake-Fragebögen zum Onboarding neuer Klienten
- **Physio-Modus:** Ein Pain-Tracking-Modus für Reha- und Physiotherapie-Anwendungsfälle
- **White-Label-Branding:** Trainer können ihre eigene Marke auf die Experience ihrer Klienten legen

## Fortschritt & Analytics

Umfassendes Tracking zur Visualisierung deiner Journey:

- **Muskel-Karten:** Eine anatomische Heatmap des Trainingsvolumens plus eine Recovery-Map, die zeigt, welche Muskelgruppen wieder bereit fürs Training sind
- **Strength Standards & Ziele:** Deine Lifts gegen Kraft-Standards vergleichen, Lift-Ziele setzen und eine Trendprojektion dorthin sehen
- **Wrapped:** Ein teilbares Jahresrückblick-Stat-Sheet, pro Jahr generiert
- **Workout-Heatmaps & Volumen-Charts:** Aktivitätskalender, Gesamt-Volumen-Trends, durchschnittlicher RPE, Sätze pro Workout
- **Übungs-Fortschritt & PRs:** Gewicht-, Volumen- und Wiederholungs-Verbesserungen pro Übung, mit automatischer PR-Erkennung
- **Streak-Tracking:** Aktuelle und längste Workout-Streaks

## Übungsbibliothek & Custom Exercises

- **Kuratierte Bibliothek:** Umfassende Datenbank mit Kategorien, Muskelgruppen, Equipment-Tags und Visualisierungen
- **Eigene Übungen:** Private Übungen erstellen mit stufenbasierten Limits
- **Filtering & Suche:** Nach Tags, Kategorien und Übungstyp sortieren

## Achievement-System

Ich habe eine Gamification-Ebene gebaut, um User-Engagement und Retention zu fördern. Der `Achievement Service` wird nach jeder abgeschlossenen Session getriggert und prüft die Performance-Daten gegen historische Rekorde und vordefinierte Schwellenwerte:

- **Streak-Tracking:** Aufeinanderfolgende Workout-Tage und längste Streaks
- **Konsistenz-Metriken:** Wöchentliche Workout-Frequenz-Ziele
- **Volumen-Meilensteine:** Kumulierte gehobene Gewichts-Schwellenwerte
- **Persönliche Rekorde:** Automatische Erkennung neuer Gewichts-PRs pro Übung
- **Ausdauer-Badges:** Dauerbasierte Achievements für zeitgesteuerte Übungen

## Workout-Historie & Bearbeitung

- Detaillierte Aufschlüsselungen vergangener Sessions ansehen
- Sätze, Wiederholungen, Gewicht und RPE für Workouts der letzten 7 Tage bearbeiten
- Session-Notizen und subjektives Feedback-Tracking
- KI-generierte Workout-Zusammenfassungen für jede Session

## Ein lebendes Produkt

lyftd ist mehr als ein Side Project — es ist ein reelles Produkt, das echte Nutzer bedient. Das jüngste Release, locker das größte seit lyftd live ist, hat aus der Web-App eine Plattform gemacht: eine native iOS-App, ein KI-Coach, der auf deinem Plan handelt statt ihn nur zu kommentieren, und eine Trainer-Plattform für Coaches, die ihre Klienten darüber betreuen. Es entwickelt sich ständig weiter, während ich mit neuen Techniken experimentiere, und gibt mir praktische Erfahrung in allem von Datenbankschema-Design über native App-Entwicklung bis zu KI-Agent-Design und Abo-Management.
