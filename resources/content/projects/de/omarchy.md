---
slug: omarchy
title: 'Omarchy — Vom Redesign-Pitch ins Omarchy Design Team'
story_preview: 'DHH hat nach Redesigns für omarchy.org gefragt. Ich habe eins geschickt, wurde ins neue Omarchy Design Team eingeladen und habe danach Teile der Site gebaut, die als offizielle omarchy.org-Website live ging: Logo-Animation, News und Kennzahlen, Installer, Teams und Testimonials'
fineprint: 'Omarchy ist die Linux-Distribution von [David Heinemeier Hansson (DHH)](https://dhh.dk). Die Site ist Teamarbeit: [Barış Girişmen](https://x.com/BarisGirismen) hat das Fundament entworfen und den Pixel-Shader gebaut, [Christoffer Hallas](https://x.com/hicsfh) hat Prototypen, Animation und Theme-Übergänge beigesteuert. Meine Sektionen sind die hier beschriebenen. Alles Open Source auf [github.com/omacom/omarchy-site](https://github.com/omacom/omarchy-site).'
---

# Ein Tweet, ein Wochenende, ein Platz am Tisch.

Am 30. August 2026 hat DHH gepostet, dass die Omarchy-Homepage sich selbst entwachsen ist und er ein Omarchy Design Team aufbauen will. Die Bewerbung war einfach: ein Redesign an design@omarchy.org schicken, und wenn es passt, bist du drin. Mir gefiel, was DHH mit Omarchy macht, ein Betriebssystem für das Zeitalter der Agenten, und als Terminal- und Tech-Geek wollte ich Teil davon sein. Dazu hatte ich gerade Monate mit Canvas-Interfaces für meine eigenen Projekte verbracht. Also habe ich den Tag darauf verwendet.

<blockquote class="twitter-tweet"><a class="x-link" href="https://x.com/dhh/status/2093946369731854766" aria-label="View on X"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a><p lang="en" dir="ltr">I think the current homepage design/layout for Omarchy has outgrown itself! I'd like to spin up a new Omarchy Design team to work on that (and other projects). Interested? Send a redesign of the homepage to design@omarchy.org. If it vibes, you're in.</p>&mdash; DHH (@dhh) <a href="https://x.com/dhh/status/2093946369731854766">August 30, 2026</a></blockquote>

## Der Pitch: omarchy.org als Omarchy-Desktop

Meine Einreichung hat die ganze Site als den Desktop nachgebaut, den sie bewirbt. Eine Topbar im Waybar-Stil mit Workspaces als Sektionsanker und einer Live-Uhr, der Inhalt gekachelt wie Hyprland-Fenster, jedes betitelt mit dem Shell-Befehl, der es öffnen würde. Die Wortmarke auf einem Canvas gerendert, entschlüsselt sich beim Laden und scrambelt unter dem Cursor. Und der Teil, der gezündet hat: ein Theme-Switcher auf der Taste `t`, der durch die echten Omarchy-Themes wechselt, jede Palette 1:1 aus den `colors.toml`-Dateien der Distribution.

- **Kein Build, keine Dependencies:** Eine statische HTML-Datei, JetBrains Mono als einzige Schrift. Passend für ein Projekt, dessen Gründer eine klare Meinung zu Build-Steps hat
- **Die ganze Site, kein Mockup:** Manual mit allen 51 Kapiteln, jeder News-Artikel, Teams, Patrons, Sponsorships, Meetups, eine Command Palette. Live auf [omarchy.captainscor.ch](https://omarchy.captainscor.ch) am selben Abend, [gepostet](https://x.com/captainscorch/status/2094139135971794958) unter seinem Tweet
- **Die Antwort, in derselben Nacht:** DHH hat per DM geantwortet. „That theme switching looks sick!! … Want to join us in Omarchy Design?"

## Dann die eigentliche Arbeit

Das Team stand innerhalb weniger Tage. Barış Girişmens Entwurf wurde das Fundament der neuen Site, und eine Woche lang Anfang September dauerten Entscheidungen im Gruppenchat Minuten und Merges Stunden. Ich habe die Sektionen übernommen, die noch fehlten, bestehende verfeinert und alles als Pull Requests gegen Barış' Branch geliefert, ein Thema pro PR, im Stil, den das Repository schon hatte.

- **Das Logo:** Die Omarchy-Marke in der Navigation zeichnet sich beim Hover selbst, jeder Strich in eigener Reihenfolge und eigenem Timing. Als DHH den neuen Marken-Gradient zur Kernidentität erklärt hat, bekam die Marke noch am selben Tag ihre fünf Bänder
- **Momentum by the numbers:** Eine Kennzahlen-Sektion mit den Foundation-Zusagen als Stufendiagramm, ISO-Downloads mit Gestern, Woche und Monat und GitHub-Stars mit Commit-Sparkline, direkt neben den News
- **Der Installer:** Eine Install-Sektion, die zeigt, was die ISO tatsächlich tut, daneben die Try-Apps für Mac und Windows und ein Hinweis, was vor dem Booten zu tun ist
- **Teams:** Alle Teams als Avatar-Cluster in einer Zeile auf der Homepage und eine Teams-Seite im selben Layout
- **People love Omarchy:** Die Testimonial-Sektion, die zitiert, was Leute nach der Installation auf X gepostet haben, mit Link zu mehr davon
- **Die Details:** Der Install-Befehl auf dem Handy auf zwei Zeilen gehalten, die Installer-Notiz auf eine, die Strichreihenfolge der Marke repariert, nachdem ein Refactor sie verschoben hatte

## Der Launch

Am 7. September ging das Redesign als offizielle omarchy.org-Website live. DHHs [Launch-Post](https://omarchy.org/news/2026/09/omarchy-org-redesign-launches-with-29-languages/) erwähnt mich für die Sektionen Logo, News, Install, Team, Foundation und Testimonials. Sein Tweet zur Site und [Barış'](https://x.com/BarisGirismen/status/2096912834382770503) sagen, wie es sich angefühlt hat.

<blockquote class="twitter-tweet"><a class="x-link" href="https://x.com/dhh/status/2096912550323511476" aria-label="View on X"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a><p lang="en" dir="ltr">Omarchy now has the coolest website I've ever seen. Truly incredible work by the Omarchy website design team lead by <a href="https://x.com/BarisGirismen">@BarisGirismen</a>. This is what computers can look (and sound) like! <a href="https://omarchy.org">https://omarchy.org</a></p>&mdash; DHH (@dhh) <a href="https://x.com/dhh/status/2096912550323511476">September 7, 2026</a></blockquote>

## Was ich mitnehme

In einem Open-Source-Team mit einem Gründer, der nicht lange fackelt, gelten andere Regeln als bei Kundenarbeit. Jede Änderung ist öffentlich, und das Review ist der Merge. Geschmacksfragen werden im Chat entschieden, in Minuten, von dem, dem es am wichtigsten ist. Die Site ist nie fertig, und genau das ist der Punkt.

Computer sollen Spaß machen. Websites auch.
