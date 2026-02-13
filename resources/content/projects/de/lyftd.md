---
slug: lyftd
title: 'Smart Training, Stronger You — Full-Stack Workout-Tracking-Plattform'
story_preview: 'lyftd ist mein Passion Project — eine anspruchsvolle Workout-Tracking-Plattform, die ich von Grund auf gebaut habe, um den gesamten Lebenszyklus von Trainingsprogrammen zu managen. Mit KI-gestütztem Coaching, umfassenden Analytics, Abonnements und Plan-Sharing'
fineprint: 'Komplett alleine gebaut als meine persönliche Sandbox für Full-Stack-Produktentwicklung. Von Authentifizierungs-Flows bis zur Payment-Integration, von KI-Coaching bis zu Achievement-Systemen — jede Zeile Code stammt von mir. Hier pushe ich meine Grenzen als Entwickler.'
---

# Ich konnte keinen Workout-Tracker finden, der mir gefiel – also habe ich meinen eigenen gebaut.

lyftd entstand aus persönlicher Frustration mit bestehenden Fitness-Apps — sie waren entweder umständlich, voller Werbung oder hatten nicht die Flexibilität, die ich brauchte. Ich wollte ein Tool, das sich premium anfühlt, nahtlos auf all meinen Geräten funktioniert und sich meinem Trainingsstil anpasst. Also habe ich es gebaut.

## Die Vision

Mein Ziel war es, eine modulare, hochperformante Webanwendung zu schaffen, die als umfassender Begleiter für Fitness-Enthusiasten dient. Es geht nicht nur darum, Gewichte zu protokollieren — sondern darum, den gesamten Lebenszyklus eines Trainingsprogramms zu managen. Vom Erstellen komplexer mehrwöchiger Pläne bis zur Durchführung von Sessions mit Fokus und Flow ist jede Interaktion auf Reibungslosigkeit ausgelegt.

## Das Tech Stack

Aufgebaut auf einem robusten **Laravel**-Backend und einem **Vue.js**-Frontend powered by **Inertia.js**, liefert die App ein modernes SPA-Erlebnis ohne die Komplexität einer separaten API. **MySQL** handhabt die Datenebene, während **Tailwind CSS** eine Dark-Mode-first, datenreiche UI sicherstellt.

- **Laravel Jetstream:** Vollständiges Authentifizierungssystem mit E-Mail-Verifizierung, Zwei-Faktor-Authentifizierung, Session-Management und Account-Löschung
- **Filament:** Admin-Panel für Plattform-Management, Benutzerübersicht und Abo-Monitoring
- **Paddle + Laravel Cashier:** Vollständiges Abo-System mit Core- und Elite-Stufen, Pause/Resume-Funktionalität und Billing-Portal-Integration

## Workout-Planung & Ausführung

Das Planungssystem ist auf Flexibilität ausgelegt:

- **Individuelle Workout-Pläne:** Mehrwochen-Pläne mit konfigurierbarer Dauer, Deload-Wochen und tageweiser Planung
- **Übungskonfiguration:** Sätze, Wiederholungen, Gewicht, Ziel-RPE, Pausenzeiten und Volumen-Tracking pro Übung
- **Plan-Sharing:** Pläne mit anderen Nutzern teilen mit Viewer- oder Editor-Berechtigungen
- **Duplizierung:** Ganze Pläne, einzelne Tage oder spezifische Übungen klonen

Die Ausführungs-Engine ist, wo lyftd glänzt:

- **Präzises Tracking:** Sätze, Wiederholungen, Gewicht, Dauer und RPE in Echtzeit protokollieren
- **Pausen-Timer:** Automatischer Countdown zwischen Sätzen mit Pause/Resume-Funktionalität
- **Dynamisches Hinzufügen von Übungen:** Neue Übungen mitten in der Session hinzufügen — entweder einmalig für das aktuelle Workout oder für alle zukünftigen Sessions im Plan
- **Skip-Logik:** Sätze oder ganze Übungen überspringen mit intelligentem Completion-Tracking
- **Auto-Vervollständigung:** Sessions aus vorherigen Wochen werden automatisch mit einem Hinweis versehen abgeschlossen

## KI-gestütztes Coaching

Nach jedem Workout analysiert der **AI Coach** die Performance mit **OpenAIs GPT-5 nano** — gewählt für Token-Effizienz bei gleichbleibender Output-Qualität:

- Vergleicht aktuelle vs. vorherige Workout-Metriken
- Analysiert RPE, Volumen und Completion-Raten
- Generiert personalisiertes Feedback und Trainingsempfehlungen
- Mehrsprachige Unterstützung (Deutsch/Englisch)
- Rate-limitierter Zugang basierend auf Abo-Stufe

## Fortschritt & Analytics

Umfassendes Tracking zur Visualisierung deiner Journey:

- **Workout-Heatmaps:** Aktivitätskalender zeigt Konsistenz über Zeit
- **Volumen-Charts:** Gesamt-Volumen-Trends, durchschnittlicher RPE, Sätze pro Workout
- **Übungs-Fortschritt:** Gewicht-, Volumen- und Wiederholungs-Verbesserungen pro Übung
- **Persönliche Rekorde:** Automatische PR-Erkennung und Tracking
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

lyftd ist mehr als ein Side Project — es ist ein reeles Produkt, das echte Nutzer bedient. Es entwickelt sich ständig weiter, während ich mit neuen Techniken experimentiere und gibt mir praktische Erfahrung in allem von Datenbankschema-Design bis zu Deployment, User-Support, KI-Integration und Abo-Management.
