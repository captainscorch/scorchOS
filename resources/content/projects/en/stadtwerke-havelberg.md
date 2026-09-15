---
id: 13
slug: stadtwerke-havelberg
client: 'Stadtwerke Havelberg'
category: ['Concept', 'Design', 'Development']
image: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_portrait_5.webp
color: '#bb3045'
logo: havelberg
width: '90%'
height: 'md:aspect-[3/4]'
spineHeight: 'h-28 md:h-32'
date: '2026'
team:
    - src: /img/daniel.webp
      name: Daniel Schmier
services: ['Concept', 'Design', 'Development']
technologies:
    - Laravel
    - Statamic
    - Vue.js
    - Inertia.js
    - Tailwind CSS
    - DomPDF
media:
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_1.webp
      alt: 'Stadtwerke Havelberg - Desktop Hero with Energy Lines over the Town Photo and the Tariff Calculator'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_portrait_2.webp
      alt: 'Stadtwerke Havelberg - Mobile Tariff Calculator with Household Presets and Computed Monthly Price'
    - type: video
      aspectRatio: video
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_video_1.webm
      thumbnail: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_video_1.webp
      alt: 'Stadtwerke Havelberg - Desktop Tariff Calculator Interaction: Household Preset, Typed Consumption, Switch to Gas'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_3.webp
      alt: 'Stadtwerke Havelberg - Desktop Command-K Search Overlay Finding the Meter Reading Service for a Misspelled Query'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_portrait_3.webp
      alt: 'Stadtwerke Havelberg - Mobile Outage Overlay with Hotline and Mobile Search Overlay'
    - type: video
      aspectRatio: video
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_video_2.webm
      thumbnail: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_video_2.webp
      alt: 'Stadtwerke Havelberg - Desktop Search: Command-K, Typing, Results and Jump to the Section'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_4.webp
      alt: 'Stadtwerke Havelberg - Desktop Outage Overlay Switched On Above the Homepage'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_portrait_4.webp
      alt: 'Stadtwerke Havelberg - Mobile Dark Mode Homepage and Product Worlds Tiles'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_5.webp
      alt: 'Stadtwerke Havelberg - Desktop Homepage in Dark Mode'
    - type: video
      aspectRatio: '4/5'
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_portrait_video_1.webm
      thumbnail: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_portrait_video_1.webp
      alt: 'Stadtwerke Havelberg - Mobile Menu with Accordion Sections and Tariff Calculator'
    - type: video
      aspectRatio: video
      src: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_video_3.webm
      thumbnail: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_video_3.webp
      alt: 'Stadtwerke Havelberg - Desktop Homepage Scroll-Through with Reveals and Dark Mode Toggle'
title: 'Relaunch Concept for a Municipal Utility'
story_preview: 'A self-initiated relaunch concept for Stadtwerke Havelberg, not a commissioned project: Laravel, Statamic 6, Inertia and Vue with a live tariff calculator, fault-tolerant search, a CMS-switchable outage overlay and dark mode.'
fineprint: "A self-initiated concept. Stadtwerke Havelberg didn't commission it: tariffs and news are sample content, only the public contact details are real."
fineprint_media: /img/portfolio/stadtwerke-havelberg/stadtwerke-havelberg_landscape_6.webp
fineprint_media_alt: 'Stadtwerke Havelberg - Desktop Product Worlds: Electricity, Gas, District Heating, E-Mobility, Leisure Pool, Careers'
---

# A Relaunch Nobody Ordered

Many municipal utilities run websites from another decade: no viewport meta tag, a tariff PDF instead of a calculator, an outage notice that needs a developer to go live. I wanted to show what a relaunch looks like without waiting for a tender. So I built one, self-initiated and not commissioned. Stadtwerke Havelberg supplies electricity, gas and district heating to a small town on the Elbe and the Havel, runs the local leisure pool and was on my list of utilities with exactly that kind of website. The demo rebuilds it on a real stack, with real content from the public site and example prices.

## The Stack

**Laravel** and **Statamic 6** in the back, **Inertia** and **Vue 3** with server-side rendering in the front, **Tailwind CSS** for the design system. The energy structure follows how a utility is organised, and Havelberg's own layer sits on top:

- **Colours:** the exact logo pair, deep red and green, mapped to an OKLCH palette with a colour code per product world.
- **Content:** tariffs, opening hours, the outage banner and company data live in Statamic globals; news is a collection with detail pages.
- **Local specifics:** district heating from the biogas plant, the leisure pool with its 64-metre slide, the club sponsorship and the operations management for the local water and wastewater association (TAHV).

## The Tariff Calculator

The centrepiece sits directly under the hero. Postcode first: the calculator checks the delivery area and says so in plain words when an address is outside it. Consumption comes from household presets, one to five-plus persons for electricity, flat size for gas, or from a typed value. Every tariff card recalculates on each keystroke, sorted by annual cost, with the monthly instalment in front. Prices come live from the CMS, so the utility changes a tariff in Statamic and the calculator follows. One click renders a personalised price sheet as a **PDF** from the same data.

## Search That Forgives Typos, and an Overlay for Bad Days

The site has a **⌘K search** over services, product worlds and news. It tolerates typos and umlaut spellings through a Levenshtein distance and prefix matching, so "zähler" finds the meter reading service. Results update while typing, keyboard navigation works throughout, and a screen reader hears the result count via an ARIA live region.

For outages there is a **crisis overlay**: one switch in the CMS puts a full-width banner with headline, status and hotline above every page. Severity changes the colour. In the demo the overlay can be triggered from the preview dock, so a visitor sees it without touching the CMS.

## Accessible by Design, Dark Mode Included

Accessibility is built in rather than retrofitted, oriented on the BITV requirements public bodies work with: a skip link to the content, visible focus rings on every interactive element, proper ARIA roles for tabs, groups and dialogs, and motion that stops when the visitor prefers reduced motion. **Dark mode** is a toggle in the header and the mobile menu, light by default and remembered per device.

The motion that is there earns its place: sections reveal on scroll, and energy lines pulse across the hero photo. Schema.org markup describes the utility as a LocalBusiness, opening hours included, straight from the CMS. A small **preview dock** in the corner lists what already works, dark mode, search, outage overlay, PDF, CMS login, and lets a visitor try each one on the spot.

## What It Is For

Stadtwerke Havelberg gets a working preview of its own relaunch before the first conversation. Not a mockup, a site.
