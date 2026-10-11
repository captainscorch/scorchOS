---
id: 5
slug: weingut-heck
client: 'Weingut Heck'
category: ['Development']
image: /img/portfolio/weingut-heck/weingut-heck_portrait_6.webp
color: '#f5b002'
logo: heck
width: '95%'
height: 'md:aspect-[3/4]'
spineHeight: 'h-28 md:h-32'
date: '2026'
website: https://weingut-heck.de
team:
    - src: /img/daniel.webp
      name: Daniel Schmier
services: ['Development', 'Animation', 'Technical Consulting', 'Hosting']
technologies:
    - Statamic
    - Laravel
    - Vue.js
    - Inertia.js
    - TypeScript
    - Tailwind CSS
media:
    - type: video
      aspectRatio: video
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_video_1.webm
      thumbnail: /img/portfolio/weingut-heck/weingut-heck_landscape_video_1.webp
      alt: 'Weingut Heck - Desktop Home from the HECK Stage to the Top Drops, with the Bottle Picker Switching Wine and Collage'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/weingut-heck/weingut-heck_portrait_1.webp
      alt: 'Weingut Heck - Mobile Home Stage and Product Teaser'
    - type: video
      aspectRatio: video
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_video_2.webm
      thumbnail: /img/portfolio/weingut-heck/weingut-heck_landscape_video_2.webp
      alt: 'Weingut Heck - Desktop Wine Accordion: a Lying Bottle Flies into Its Showcase Pose as the Row Opens'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_4.webp
      alt: 'Weingut Heck - Desktop Wines Stage with the Bottle Picker'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/weingut-heck/weingut-heck_portrait_2.webp
      alt: 'Weingut Heck - Mobile Wines Stage and Philosophy Stage'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_16.webp
      alt: 'Weingut Heck - Desktop Card Grid about the Soils'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_6.webp
      alt: 'Weingut Heck - Desktop Winery Stage with Opening Hours'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_7.webp
      alt: 'Weingut Heck - Desktop Card Grid with Tastings and Events'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_8.webp
      alt: 'Weingut Heck - Desktop Philosophy Stage'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_11.webp
      alt: 'Weingut Heck - Desktop Wine Lounge Section'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/weingut-heck/weingut-heck_portrait_4.webp
      alt: 'Weingut Heck - Mobile Card Grid and Wine Lounge Section'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_17.webp
      alt: 'Weingut Heck - Desktop Events Section with Polaroid Cards'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_15.webp
      alt: 'Weingut Heck - Desktop Gallery with Shaped Image Masks'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_18.webp
      alt: 'Weingut Heck - Desktop Menu with Photo Cards for the Subpages'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/weingut-heck/weingut-heck_portrait_7.webp
      alt: 'Weingut Heck - Mobile Menu and Contact Panel with Opening Hours'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/weingut-heck/weingut-heck_landscape_13.webp
      alt: 'Weingut Heck - Desktop News Page with the Newsletter Signup'
title: 'Website Relaunch for a Family Winery in Rheinhessen'
story_preview: 'A new website for Weingut Heck in Alsheim: a design full of watercolour, halftone illustration and stickers, built as a Statamic site with server-side rendering, scroll-driven animation, an age gate and a newsletter with double opt-in.'
fineprint: 'The design came from [Marie Grob](https://mariegrob.com) and [Jacky Grob](https://jackygrob.com). I built the site, from the CMS and the animation system to the age gate, the cookie consent and the newsletter.'
fineprint_media: /img/portfolio/weingut-heck/weingut-heck_landscape_1.webp
fineprint_media_alt: 'Weingut Heck - Desktop Home Stage with the HECK Wordmark over the Vineyard Photo'
---

# A Design That Does Not Sit Still

Weingut Heck is a family winery in Alsheim, Rheinhessen: Dieter and Lotte Heck, the sons already in the cellar. The new site had a design before it had a developer, by [Marie Grob](https://mariegrob.com), photographer and motion designer in Mainz, and [Jacky Grob](https://jackygrob.com), designer in Potsdam: watercolour blobs, halftone illustrations, stickers, marquees, bottles that lie on the page and stand up when you open their row. My job was to build exactly that, pixel for pixel, and make it fast.

## Statamic Under the Hood

The site runs on Statamic with Laravel, Inertia and Vue. Every page is a stack of modules the winery edits in the CMS: stages, product teasers, card grids, galleries, marquees, and the opening hours with a summer and a winter season. The pages render server-side, so the first paint carries the content and the illustrations come in without a flash. The menu opens into a card with a photo for every section, and its contact panel marks today in the opening hours.

## Animation Without the Weight

The wine accordion is the centrepiece. A bottle lies in its row, and when the row opens, a ghost copy flies rotating into the showcase pose while the panel morphs, both on one clock. The home shape draws itself once and turns 120 degrees per scroll section. Scroll reveals ship their hidden start state in the server HTML, so nothing jumps. The bottle shadows went from six blur passes to four after a profiling pass: twelve long frames became none.

## Illustrations for the Phone

The illustration library is halftone with alpha, over five megabytes at 2x. Every illustration now has a phone copy sized to its widest mobile rendering, and the CMS photos go through Glide below the tablet breakpoint. The home page went from 4.5 to 1.3 megabytes on the phone. One finding along the way: a 12.9-inch iPad in portrait is exactly 1,024 pixels wide and hit the desktop breakpoint, so the breakpoint moved by one pixel.

## Age Gate, Consent, Newsletter

A wine site asks for the age first. The answer lives in a necessary cookie, no banner needed. Cookie consent runs in the house style with two categories, and analytics load only after consent. The newsletter has double opt-in with a signed link, and confirmed addresses reach the winery as a digest with CSV, on a schedule set in the CMS.

## Browser Archaeology

One client comment, "the Merlot bottle covers the first sentence", turned out to be a browser bug. The stage scaling divided a length by a length, which only Chrome 140 and newer parses. Firefox dropped the rule, and the bottle hung 150 pixels into the next module. The factor now comes from a ResizeObserver, and CSS only sets the starting value. Stay wild, drink easy.
