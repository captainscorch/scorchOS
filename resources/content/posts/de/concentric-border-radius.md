---
slug: concentric-border-radius
title: 'Konzentrischen Border-Radius meistern'
excerpt: 'Oft übersehen, ist der konzentrische Border-Radius ein kleines Detail, das Interfaces deutlich polierter und durchdachter wirken lässt. Warum es wichtig ist und wie man es umsetzt.'
tags: ['UI', 'CSS', 'Border-Radius', 'Design-Engineering']
---

In der Design-Welt ist Premium-Qualität oft ein Nebenprodukt unzähliger Detail-Entscheidungen. Eine solche Entscheidung, die in schnellen Entwicklungszyklen oft ignoriert wird, ist die Logik hinter verschachtelten Ecken.

Wenn UI-Elemente ineinander verschachtelt werden, wirkt es oft unharmonisch, wenn man einfach denselben `border-radius` auf jede Ebene anwendet. Um ein wirklich hochwertiges Ergebnis zu erzielen, müssen wir geometrische Kontinuität wahren, indem wir sicherstellen, dass beide Kurven denselben Mittelpunkt teilen. Das ist der Kern der Konzentrizität.

## Warum ist das wichtig?

Unser visuelles System ist darauf programmiert, nach geometrischer Kontinuität zu suchen. Wenn die Krümmung mathematisch nicht ausgerichtet ist, entsteht eine subtile „visuelle Reibung“ – das Auge bleibt an der Unregelmäßigkeit hängen. Wenn die Radien nicht ausbalanciert sind, wirken die Ecken im Verhältnis zur Außenkante entweder zu „scharf“ oder zu „gedrängt“.

Konzentrizität stellt sicher, dass der Raum zwischen dem verschachtelten Element und seinem Container während der gesamten Kurve visuell konsistent bleibt.

::interactive[ConcentricDemo]

## Die Logik des Engineering

Während Design-Tools dies oft automatisch durch intelligente Offsets lösen, erfordert die Umsetzung im Code eine vorhersehbare, architektonische Vorgabe. Es ist ein einfaches Verhältnis zwischen Radius und Abstand:

```
äußerer Radius = innerer Radius + Padding
```

Durch diese Regel stellen wir sicher, dass der Abstand (Padding) zwischen den beiden Elementen am Scheitelpunkt der Kurve exakt derselbe ist wie an den geraden Kanten.

::image[/img/blog/concentric-radius-diagram.webp|Concentric Border Radius Diagramm|Wie die Beziehung zwischen äußerem Radius, innerem Radius und Padding eine harmonische Kurve erzeugt.]

## Umsetzung in CSS

Einer der Gründe, warum ich die Verbindung von Design und Entwicklung liebe, ist die Möglichkeit, solche Muster zu automatisieren. Mit CSS-Variablen lässt sich das super einfach umsetzen:

```css
.card {
    --inner-radius: 12px;
    --gap: 16px;

    padding: var(--gap);
    border-radius: calc(var(--inner-radius) + var(--gap));
}

.card-content {
    border-radius: var(--inner-radius);
}
```

Dieser Ansatz ist besonders nützlich in Designsystemen, in denen die Konsistenz gewahrt bleiben soll, wenn Komponenten skaliert werden oder sich Padding-Werte ändern.

## Von der physischen zur digitalen Welt

Dieses Konzept ist nicht neu oder spezifisch für das Web. Es ist ein grundlegendes Prinzip im Industriedesign. Schaut man sich hochwertige physische Produkte an – wie das Gehäuse eines MacBooks oder einer Leica-Kamera – findet man überall konzentrische Offsets. Dieses Maß an Handwerkskunst in digitale Interfaces zu übertragen, hebt außergewöhnliche digitale Produkte von gewöhnlichen ab.

## Abschließende Gedanken

Es ist ein winziges Detail, aber ich glaube, dass der Aufbau von Interfaces, die sich lebendig und premium anfühlen sollen, genau dieses Augenmerk auf Details erfordert. Wenn Nutzer sagen, ein Interface fühle sich „smooth“ oder „gut gebaut“ an, können sie vielleicht nicht genau auf den konzentrischen Border-Radius zeigen, aber sie spüren definitiv dessen Wirkung.

Es geht darum, sorgfältig genug zu sein, um es richtig zu machen – auch wenn die meisten Leute es nicht bewusst wahrnehmen.
