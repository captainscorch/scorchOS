---
id: 8
slug: multidock
client: 'multi.dock'
category: ['Product', 'Design', 'Development']
image: /img/portfolio/multidock/multidock_portrait_4.webp
color: '#fe3312'
logo: multidock
width: '95%'
height: 'md:aspect-[3/4]'
spineHeight: 'h-28 md:h-32'
date: '2026 — Present'
website: https://multidock.unlimited.studio
team:
    - src: /img/daniel.webp
      name: Daniel Schmier
services: ['Product', 'Design', 'Development', 'Motion']
technologies:
    - Swift
    - SwiftUI
    - AppKit
    - Vue.js
    - Vite
    - TypeScript
    - Tailwind CSS
    - Remotion
media:
    - type: video
      aspectRatio: video
      src: /img/portfolio/multidock/multidock_landscape_video_2.webm
      thumbnail: /img/portfolio/multidock/multidock_landscape_video_2.webp
      alt: 'multi.dock - Launch Film, Built from the Parts of the Site'
    - type: video
      aspectRatio: video
      src: /img/portfolio/multidock/multidock_landscape_video_1.webm
      thumbnail: /img/portfolio/multidock/multidock_landscape_video_1.webp
      alt: 'multi.dock - Desktop Hero: the Items Fly into the Demo Dock, then Magnification, Window Previews, Weather, a Folder Stack and the Weekend Dock'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/multidock/multidock_landscape_16.webp
      alt: 'multi.dock - Desktop Hero Demo in Dark and Light Mode'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/multidock/multidock_portrait_1.webp
      alt: 'multi.dock - Mobile Hero and Widget Gallery'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/multidock/multidock_portrait_3.webp
      alt: 'multi.dock - Mobile Manual and Widgets Section'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/multidock/multidock_landscape_2.webp
      alt: 'multi.dock - Desktop Bento Grid: Focus, Widgets, Replace Mode and Window Previews'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/multidock/multidock_landscape_14.webp
      alt: 'multi.dock - Desktop Widget Gallery with the Weather Details Open'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/multidock/multidock_landscape_4.webp
      alt: 'multi.dock - Desktop Docks Section with Work and Weekend Profiles'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/multidock/multidock_landscape_11.webp
      alt: 'multi.dock - Desktop Manual Overview'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/multidock/multidock_landscape_15.webp
      alt: 'multi.dock - Desktop Manual Chapter on Widgets'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/multidock/multidock_landscape_12.webp
      alt: 'multi.dock - Desktop FAQ'
    - type: video
      aspectRatio: video
      src: /img/portfolio/multidock/multidock_landscape_video_3.webm
      thumbnail: /img/portfolio/multidock/multidock_landscape_video_3.webp
      alt: 'multi.dock - Desktop Footer Dock with Magnification and the Ember Light Following the Pointer through the Wordmark'
title: 'A Second Dock for macOS with Live Widgets, Folder Stacks and Focus-Driven Profiles'
story_preview: 'multi.dock is my own Mac app: a second Dock with live widgets, folder stacks and several Docks that switch with your Focus. Native Swift, no account, no telemetry, with a product site built from the same parts as the app. Free to download for macOS 15 and later.'
fineprint: 'My own product, started in October 2026: the app in Swift 6 with AppKit and SwiftUI, the product site in Vue, the launch film in Remotion. The pictures on the site are built from the app''s parts, not captured from the screen.'
fineprint_media: /img/portfolio/multidock/multidock_landscape_1.webp
fineprint_media_alt: 'multi.dock - Desktop Product Site Hero with Floating Widgets and App Icons'
---

# The Dock, Extended

Apple's Dock has not changed much in twenty years, and the gap shows once you look: no widgets, one layout for every kind of work, folders that open as a grid. multi.dock is my take on a second Dock: apps, files, folders and links as usual, live widgets next to them, and several Docks that follow your Focus. Native Swift 6 with AppKit and SwiftUI, macOS 15 and later, Liquid Glass on macOS 26.

## Alongside or Instead

multi.dock runs next to Apple's Dock or replaces it. In replace mode Apple's Dock stays hidden and comes back when multi.dock quits. Badges and running indicators are read from Apple's Dock through accessibility, magnification uses Apple's own sizes, and icons shrink until the bar fits before it starts to scroll. macOS reserves screen space only for its own Dock, so a window guard watches windows that snap to the Dock's edge and lifts them above the bar.

## 22 Widgets, All Live

Clock, weather, calendar, reminders, focus timer, hydration, now playing, system activity, network, batteries, stocks, sticky notes and more, each 1.25 icons tall, with styles instead of sizes. A click opens a detail panel above the widget. Widgets are plain SwiftUI views, because macOS lets no third-party app host WidgetKit. Weather comes from Open-Meteo without an account, and nothing leaves the Mac except the requests the widgets need.

## One Dock for Work, One for After

Docks are profiles with their own apps, widgets and colour. Switch with a shortcut, a swipe, the menu bar, a URL scheme or a Focus filter, so the work Dock appears with the work Focus and the previous one comes back afterwards.

## A Site Built From the App's Parts

The product site is a static Vue site, prerendered, in English and German. The hero holds a working Dock whose magnification maths is ported from the app, and every picture on the page is drawn from components, not captured. Consent-gated analytics, downloads counted from nginx logs without cookies, and a 33-second launch film rendered in Remotion from the same parts.

## Shipping

Notarised DMGs with Developer ID, two editions from one build, a manual, a changelog and a public release page. No Mac App Store: accessibility, AppleScript and global event monitors do not fit the sandbox.

## Try It

multi.dock is a free download for macOS 15 and later at [multidock.unlimited.studio](https://multidock.unlimited.studio). No account and no sign-up. The Dock at the top of the site works in the browser, so the magnification, the widgets and the profiles can be tried before the download.
