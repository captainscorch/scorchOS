---
slug: beyond-prompts-architecting-with-ai
title: 'Beyond Prompts: Struktur mit KI'
excerpt: 'Wie ich KI nutze, um Trial-and-Error Phasen zu beschleunigen, schlanke Infrastrukturen zu erhalten und qualitativen Output ohne unnötigen Ballast zu skalieren.'
tags: ['KI', 'Workflow', 'Design-Ingenieurwesen', 'Cursor', 'Produktivität']
---

Für mich war die Programmierung schon immer eine Erweiterung des Design-Loops. Als Design Engineer verbringe ich einen großen Teil meiner Zeit in dieser „Trial-and-Error“-Phase – dem unordentlichen Zwischenraum, in dem man versucht, genau den Moment zu finden, in dem sich eine Interaktion oder ein Layout endlich richtig anfühlt.

Hier hat KI meinen Workflow komplett transformiert. Es geht nicht darum, ein LLM für mich denken zu lassen; es geht darum, die Distanz zwischen einem groben Designkonzept und einem funktionalen Prototypen zu verkürzen. Wenn ein Konzept den „Feel Test“ nach ein paar Minuten schneller Iterationen nicht besteht, verwerfe ich es ohne großes Zögern. KI fungiert hier effektiv als Beschleuniger für diesen Zyklus, wodurch ich mich auf die Nuancen des Handwerks konzentrieren kann, anstatt auf die Reibung von Boilerplate-Implementierungen.

::interactive[PromptComparison]

## Die Modell-Landschaft

Die Welt der KI-Modelle bewegt sich in einem wahnsinnigen Tempo. Die Modelle haben sich in gefühlt wenigen Wochen von einfachen Autovervollständigungen zu vollwertigen, teil-autonomen Agenten entwickelt. In meinem Alltag lege ich mich nicht auf ein einziges Modell fest; ich behandle sie wie verschiedene Schraubschlüssel in einem Werkzeugkasten. **Claude 4.6** zusammen mit **GPT-5.3 Codex** sind derzeit meine täglichen Go-tos für komplexe strukturelle Logik und Refactoring, während ich für spezialisierte Aufgaben oder schnelle Dokumentationsänderungen auf andere Modelle zurückgreife. Zu wissen, welches Werkzeug für das spezifische Problem am besten geeignet ist, wird damit zu einer Kernkompetenz.

## Die Seele der Codebasis definieren

Das Geheimnis für vorhersehbare, qualitativ hochwertige KI-Ausgaben ist nicht nur besseres Prompting – es ist besserer Kontext. In Cursor verlasse ich mich stark auf `.cursorrules`, um die „Seele“ meiner Projekte zu definieren. Hier verankere ich meine Standards: von strengen Laravel- und PHP-Best-Practices bis hin zur spezifischen Art und Weise, wie ich meine Vue.js-Komponenten mit der `script setup` API und Tailwind CSS strukturiert haben möchte.

Indem ich diese Regeln in Git tracke, stelle ich sicher, dass jeder Agent, den ich starte, demselben strukturellen Standard folgt, den wir für das Team festgelegt haben. Es ist derselbe Grund, warum wir **Prettier** oder **ESLint** verwenden: Wir wollen, dass die Syntax ein gelöstes Problem ist, damit wir uns auf das Produkt konzentrieren können. Wenn die Regeln klar sind, hört die KI auf, ein generischer Assistent zu sein, und fängt an, wie ein Partner zu agieren, der genau weiß, wie ich programmiere und meine Projekte strukturiere.

Mit den neuesten Ergänzungen durch Tools wie [Laravel Boost](https://laravel.com/ai/boost) und dessen MCP-Server mit plattformspezifischen Werkzeugen für den Agenten kann ich sogar noch weiter gehen – Boost ermöglicht es beispielsweise Laravel-Projekten, strukturelle Absichten direkt im Agent-Workflow zu kodifizieren und durchzusetzen. Die Nutzung in Kombination mit festgelegten Rules bedeutet, dass jeder KI-Agent oder jede Integration, sei es beim Generieren von Code, beim Reviewen oder beim Scaffolden von Features, dies mit einem tiefen Verständnis für meine Konventionen und die gewünschte Struktur meiner Anwendung tut.

## Den Ballast entfernen

Selbst mit großartigen Regeln kann KI manchmal etwas „gesprächig“ werden. Ich verwende spezifische Befehle, um die Codebasis schlank zu halten. Einer meiner Favoriten ist `/deslop`, den [Eric von Cursor](https://x.com/ericzakariasson/status/1995671800643297542) auf X geteilt hat. Es ist ein schneller Weg, um defensives Over-Engineering und generischen „Fluff“, den Modelle bei fehlender Kontrolle tendenziell injizieren, zu entfernen.

::interactive[CommandPreview]

Ich erstelle auch benutzerdefinierte Befehle, die auf spezifische Projekte zugeschnitten sind. Ob es ein `/review`-Befehl ist, um unsere Barrierefreiheitsstandards zu prüfen, oder ein `/component`-Befehl, um neue UI-Elemente zu scaffolden, die unser Designsystem respektieren.

## Strukturelles Prompting

Ich „frage“ selten nur noch nach Code, besonders in den frühen Phasen eines Projekts. Ich verlasse mich immer öfter auf den **Plan-Modus**. Bevor eine einzige Zeile Code geschrieben wird, lasse ich den Agenten die Logik durchlaufen und das Feature in kleinere, logische Meilensteine unterteilen. Dieser strukturelle Ansatz beim Prompting macht die finale Implementierung viel sauberer und damit auch viel einfacher zu debuggen. Er verwandelt den Prozess von einem Ratespiel in einen strukturierten Ablauf.

::interactive[StructuredPromptDemo]

## Der Verdichtungs-Effekt

Vielleicht der am meisten übersehene Vorteil von KI ist, wie sie dabei hilft, **technisches Gewicht zu reduzieren**. KI ist unglaublich gut darin, wiederkehrende Muster in einer großen Codebasis zu erkennen. Ich nutze sie oft, um nach doppelter Logik oder fragmentierten Styles zu suchen, die in vereinheitlichte Primitive konsolidiert werden sollten. Was früher eine Stunde manuelles Audit erforderte, dauert jetzt nur noch wenige Sekunden. Das Endergebnis ist nicht nur Geschwindigkeit; es ist eine sauberere, wartbarere Struktur, die Abstraktion über Wiederholung priorisiert.

## Schlanke Infrastruktur durch Design

Diese Website ist ein perfektes Beispiel für diese Philosophie. Anstatt mit einem überladenen CMS und Tech-Debt zu kämpfen, habe ich ein benutzerdefiniertes System gebaut, bei dem jeder Beitrag einfach eine Markdown-Datei mit Frontmatter-Metadaten ist. Alles befindet sich an einem Ort – Inhalt, Tags, interaktive Komponenten – was die Bearbeitung trivial und das Rendering rasend schnell macht. Dieser Ansatz gibt mir die volle Kontrolle über die Performance-Optimierung und ermöglicht die nahtlosen interaktiven Demos, die man in diesen Artikeln sehen kann. KI hat die Entwicklung dieser benutzerdefinierten Rendering-Pipeline drastisch beschleunigt und mir den Freiraum gegeben, mich auf das Erstellen von Inhalten und die User Experience zu konzentrieren, anstatt gegen administrative Oberflächen zu kämpfen.

### Case Study: lyftd.app

Bei **lyftd.app** habe ich KI genutzt, um den Kern des `Achievement Service` und die KI-Coach-Logik zu entwerfen. Dies hat mir ermöglicht, Dutzende von Feedback-Schleifen in der Zeit zu iterieren, die man normalerweise für das manuelle Schreiben einer einzigen Vergleichsfunktion benötigt hätte. Es ist ein massiver Multiplikator, aber nur, wenn man das Handwerk bereits gut genug versteht, um die Ausführung steuern zu können.

## Abschließende Gedanken

Da die Kosten für die Code-Generierung stetig sinken, schießt der Wert der _Steuerung_ in die Höhe. In einer Welt, in der jeder ein Layout generieren kann, wird die Qualität, Durchdachtheit und Handwerkskunst der Person, die diese Ausgabe steuert, das sein, was Premium-Produkte definiert. Nutze KI für die Fleißarbeit, aber lass sie niemals die Seele und die Intentionalität ersetzen, die einem digitalen Produkt erst das Gefühl echter Wertigkeit verleihen.
