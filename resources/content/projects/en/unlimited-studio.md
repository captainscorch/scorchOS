---
id: 10
slug: unlimited-studio
client: unlimited.studio
category: ['Design', 'Development', 'Product']
image: /img/portfolio/unlimited-studio/unlimited-studio_portrait_11.webp
color: '#fe3312'
logo: unlimited
width: '95%'
spineHeight: 'h-28 md:h-32'
date: '2018 — Present'
website: https://unlimited.studio
team:
    - src: /img/daniel.webp
      name: Daniel Schmier
services: ['Design', 'Development', 'Copywriting', 'SEO/GEO']
technologies:
    - Laravel
    - Vue.js
    - Inertia.js
    - TypeScript
    - Tailwind CSS
    - React Three Fiber
    - OpenAI API
    - Laravel Reverb
media:
    - type: video
      aspectRatio: video
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_video_1.webm
      thumbnail: /img/portfolio/unlimited-studio/unlimited-studio_landscape_video_1.webp
      alt: 'unlimited.studio - Desktop Home: the Glass Mark Reacting to the Pointer, then Services and the Case Studies'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_37.webp
      alt: 'unlimited.studio - Command Menu on ⌘K in Detail'
    - type: video
      aspectRatio: video
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_video_4.webm
      thumbnail: /img/portfolio/unlimited-studio/unlimited-studio_landscape_video_4.webp
      alt: 'unlimited.studio - Desktop Client Grid: Logos Show the Client Website on Hover, Ending on ICEMÄNNER'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_38.webp
      alt: 'unlimited.studio - Desktop Team Section with the Hover Label'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_26.webp
      alt: 'unlimited.studio - Playground: the Studio in Kassel as a Walkable 3D Room, Right after Entering'
    - type: video
      aspectRatio: video
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_video_7.webm
      thumbnail: /img/portfolio/unlimited-studio/unlimited-studio_landscape_video_7.webp
      alt: 'unlimited.studio - Playground: Entering the 3D Studio, the Stations from the Orange Wall to the Desks, a Desk Raised and unlimited.os Started'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_27.webp
      alt: 'unlimited.studio - Playground: The Orange Wall with Kitchenette, Guestbook and Case Study Holograms'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/unlimited-studio/unlimited-studio_portrait_9.webp
      alt: 'unlimited.studio - unlimited.gpt in Detail: an Answer about the Process with Intro Call and Contact Form'
    - type: video
      aspectRatio: '4/5'
      src: /img/portfolio/unlimited-studio/unlimited-studio_portrait_video_2.webm
      thumbnail: /img/portfolio/unlimited-studio/unlimited-studio_portrait_video_2.webp
      alt: 'unlimited.studio - Mobile unlimited.gpt: Two Questions, Then the Consultant Prepares the Inquiry'
    - type: video
      aspectRatio: video
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_video_6.webm
      thumbnail: /img/portfolio/unlimited-studio/unlimited-studio_landscape_video_6.webp
      alt: 'unlimited.studio - Desktop Contact Form: Details, Pricing Model and Service Categories, up to the Send Button'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-studio/unlimited-studio_landscape_39.webp
      alt: 'unlimited.studio - Desktop Footer: Let''s Work Together with the Contact Form'
title: 'My Studio Website and Its Playground: Design, Development and an AI Consultant'
story_preview: 'unlimited.studio is the website of my studio: a dark editorial system in Laravel, Inertia and Vue with CMS-free case studies and posts, service pages, a Google Ads landing page, an AI consultant that takes inquiries, and a walkable 3D playground of the office in Kassel.'
fineprint: 'Designed and built by me for my own studio, in production since 2018 and rebuilt in 2026. The site runs on Laravel, Inertia and Vue with server-side rendering, the playground on React Three Fiber. Everything shown is live.'
fineprint_media: /img/portfolio/unlimited-studio/unlimited-studio_landscape_1.webp
fineprint_media_alt: 'unlimited.studio - Desktop Home Hero with the Red Mark, Experience and Crew'
---

# A Studio Site That Works as Hard as the Studio

unlimited.studio is the site of my studio in Kassel, and it has been my own client since 2018. The 2026 rebuild turned it into a dark-first editorial system: black canvas, dashed rails, one vivid brand red, white contrast bands and terminal-inspired surfaces. Omnes Pro for the display lines, Geist for the body, Geist Mono in uppercase for every label. The dot before a headline is the house mark, and it is always red.

## Content Without a CMS

Case studies, blog posts, service pages and client entries are Markdown files with front matter, parsed on the server and typed on the client. English is the source of truth, German overlays the texts. Every case study and every post also exists as a `.md` URL, and the site publishes `llms.txt` and `agents.md`, so language models read the same content as people. One sitemap entry per locale, hreflang both ways, x-default to English.

## Service Pages That Answer the Question

Thirteen service pages, each built from the same blocks: focus, selected clients, overview, pillars, process, a relevant project example, related case studies, testimonials, further reading, FAQ and a glossary. The related case studies are a three-card grid chosen per page. A regional landing page for Kassel and a Google Ads landing page for the fixed-price starter website sit next to them; the Ads page matches its headline to the ad group and shows the demo gallery by industry.

## The Small Things

A command menu on ⌘K, a custom cursor, an intro loader, a client grid that shows the client's website on hover, a team section where every person carries a line of their own under the cursor, testimonials from Google reviews and a pricing section with animated process tracks.

## The Playground

The office in Kassel as a walkable 3D room: orange wall, kitchenette, meeting room with glass walls, the desks, Ilvy in her basket. Built with React Three Fiber, lit by the real sun position over Kassel, free camera with stations and a minimap. The case studies hover over the resin table as holograms, the TV is a console with five games and global leaderboards, each monitor starts unlimited.os with a terminal, a finder and paint, and studio.js is a code editor that scripts the room in a web worker. The guestbook writes on the orange wall, with reCAPTCHA, a profanity filter and a moderation link. Visitors see each other live as red markers over Laravel Reverb. Everything in two languages, nothing stored about anyone.

## unlimited.gpt, the Consultant

The site has an AI consultant on every page: ⌘J opens it, the glowing button too. It knows the services, the case studies, the price ranges and the process from one generated knowledge file, and it links instead of navigating. When a visitor describes a project, the consultant proposes an inquiry, the server creates a one-time proposal and the visitor confirms it in a form; the model never sees name or email. Moderation, server-side history, strict output schema, allowed link targets, rate limits. The same consultant greets visitors in the playground and hands inquiries over with a signed request.

## From Question to Inquiry

Whoever prefers writing to chatting ends up at the contact form in the footer. It asks for the pricing model and the kind of project in a few clicks, with honeypot, timing check, rate limit and consent-first attribution of the lead source. Cookie consent is the house pattern: two categories, tag manager only after consent, consent mode wired so that the pixels fire in the right order.
