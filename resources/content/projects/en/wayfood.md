---
id: 2
slug: 'way.food'
client: 'way.food'
category: ['Product', 'Development']
image: /img/portfolio/wayfood/wayfood_portrait_1.webp
color: '#ff8f8f'
logo: 'way.food'
width: '90%'
height: 'md:aspect-[4/5]'
spineHeight: 'h-24 md:h-36'
date: '2024 — Present'
website: https://way.food
team:
    - src: /img/daniel.webp
      name: Daniel Schmier
    - src: /img/unlimited.webp
      name: unlimited.studio
services: ['Development', 'System Architecture', 'DevOps', 'Stripe Integration', 'Technical Consulting']
technologies:
    - Laravel
    - Livewire
    - Alpine.js
    - Tailwind CSS
    - MySQL
    - Filament
    - Stripe
    - Stripe Connect
    - Laravel Cashier
    - Mapbox GL JS
    - OpenAI API
    - Sentry
    - AWS S3
media:
    - type: video
      aspectRatio: video
      src: /img/portfolio/wayfood/wayfood_landscape_video_3.webm
      thumbnail: /img/portfolio/wayfood/wayfood_landscape_video_3.webp
      alt: 'way.food - Desktop Booking Wizard from Landing Page to Discover Results'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/wayfood/wayfood_portrait_2.webp
      alt: 'way.food - Mobile Landing Page and Discover Search'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/wayfood/wayfood_portrait_6.webp
      alt: 'way.food - Mobile Landing Page Sections How It Works and Why way.food'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_10.webp
      alt: 'way.food - Desktop Landing Page How It Works Steps with Glass Icons'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_11.webp
      alt: 'way.food - Desktop Landing Page Why Book via way.food Cards with Glass Icons'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_9.webp
      alt: 'way.food - Desktop Booking Wizard Location Step with Popular Cities'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/wayfood/wayfood_portrait_5.webp
      alt: 'way.food - Mobile Filter with Live Budget Estimate'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/wayfood/wayfood_portrait_3.webp
      alt: 'way.food - Mobile Interactive Provider Map with Live Results'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_2.webp
      alt: 'way.food - Desktop Discover Search with Bookable Packages'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_3.webp
      alt: 'way.food - Desktop Provider Profile with Availability Check'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/wayfood/wayfood_portrait_4.webp
      alt: 'way.food - Mobile Provider Profiles'
    - type: video
      aspectRatio: '4/5'
      src: /img/portfolio/wayfood/wayfood_portrait_video_1.webm
      thumbnail: /img/portfolio/wayfood/wayfood_portrait_video_1.webp
      alt: 'way.food - Mobile Discover Search and Package Booking Sheet'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_8.webp
      alt: 'way.food - Desktop Booking Chat with Offer and Payment Summary'
    - type: video
      src: /img/portfolio/wayfood/wayfood_landscape_video_2.webm
      thumbnail: /img/portfolio/wayfood/wayfood_landscape_video_2.webp
      alt: 'way.food - Desktop Provider Signup Process'
    - type: video
      src: /img/portfolio/wayfood/wayfood_landscape_video_1.webm
      thumbnail: /img/portfolio/wayfood/wayfood_landscape_video_1.webp
      alt: 'way.food - Desktop Provider Signup AI Description Optimization'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_7.webp
      alt: 'way.food - Desktop Provider Package Management'
    - type: video
      aspectRatio: video
      src: /img/portfolio/wayfood/wayfood_landscape_video_4.webm
      thumbnail: /img/portfolio/wayfood/wayfood_landscape_video_4.webp
      alt: 'way.food - Desktop Package Builder with Starter Templates and Live Preview'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_4.webp
      alt: 'way.food - Desktop Provider Analytics Dashboard'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/wayfood/wayfood_landscape_6.webp
      alt: 'way.food - Desktop Provider Booking Management with Stripe Payouts'
title: 'Foodtruck & Catering Booking Platform'
story_preview: 'Together with my team at unlimited, we built way.food from the ground up — starting as a comparison portal for foodtrucks, caterers, and party services, now grown into a full transactional booking platform with real payments on Stripe Connect. I own the technical architecture, DevOps, and full-stack development. Scaled to 1,000+ users, 300+ providers, and 7-figure traffic'
fineprint: "From zero to market leader. I'm proud of turning a complex, multi-party marketplace into a real payments platform, complete with Stripe Connect, tax-correct invoicing, and a booking engine that carries a transaction from first message to settled payout. From strategy and product roadmap to creative direction and full-stack development — I owned every layer of this platform."
fineprint_media: /img/portfolio/wayfood/wayfood_landscape_1.webp
fineprint_media_alt: 'way.food - Landing Page'
---

# Building a Foodtruck & Catering Booking Platform

way.food started as a comparison portal for foodtrucks, caterers, and party services. It has since grown into a full transactional booking platform — currently focused on the DACH region with plans to expand worldwide. As project lead, I helped shape the product strategy, contributed to investor pitches, and served as the primary technical point of contact. My team at **unlimited** supported the startup across everything, from the initial brand identity to marketing and social media management. My core focus: owning the entire technical architecture, from strategy, system design and DevOps to payment infrastructure and full-stack development.

## Infrastructure at Scale

I architected a **production-grade server infrastructure** designed for growth:

- **Multi-environment setup:** Separate servers for development, staging, and production
- **CI/CD pipeline:** Automated deployments with zero-downtime releases
- **Load balancing:** Distributed traffic handling for high-demand periods
- **Database architecture:** Dedicated database servers with automated backups
- **S3 integration:** Static media handling and scheduled backups to AWS S3
- **Two payment rails in parallel:** Stripe Connect handles the booking money flow, Laravel Cashier handles provider subscriptions — migrated live, without downtime

## From Discovery to Direct Booking

The core of way.food is no longer just browsing — it's booking. Providers build structured packages and extras with calendar-based capacity, and customers move through a **Discover search wizard** that factors in guest count, date, and budget.

- **In-booking chat:** Customers and providers negotiate directly inside the platform, with binding offers rendered as cards right in the conversation
- **Deposit checkout:** A Stripe deposit secures the booking, with the remainder collected before the event
- **Five ways a booking can start:** an instant direct booking, a change request negotiated in chat, a custom price-on-request offer, a concierge offer built by our support team, or a landing-page lead converted later

## Payments, Tax, and Paper Trail

Every booking runs through a **Stripe Connect** setup with destination charges — the platform commission is taken as an application fee on the provider's side, payouts are automated, and disputes trigger holds automatically.

Behind it sits a **tax-correct pricing engine**: catalog prices are net, the platform applies its markup, and VAT is calculated per line item — 7% on food, 19% on drinks and service — with a dedicated path for small-business providers. Every booking produces a clean, itemized breakdown instead of a single lump sum.

The whole lifecycle is backed by **automated, branded documents**: customer invoices, provider commission statements, cancellation invoices, and offer PDFs are generated and mailed at the right moment, without anyone touching them by hand.

## Fair by Design

Cancellations are handled by a dedicated **cancellation engine**: fees are tiered by how close the cancellation is to the event date, terms are frozen to the booking at the moment it's made, and there's a free-cancellation window before fees apply. No-shows settle through a separate path, and our support team can adjust fees with a full history of the change. Checkout copy and terms were reviewed for legal compliance, with versioned terms stamped to each booking.

Search results are ranked by a **transparent, weighted score** — subscription tier, behavior, review average, and deposit rate — with the exact formula published on a public ranking page. There's no paid placement and no hidden boosts. Free-tier providers are fully listed and bookable, just ranked last.

## Provider Experience

Providers manage their business from a dedicated cockpit:

- **Bookings & analytics:** A bookings table plus a dashboard for revenue, requests, and response times
- **Packages & extras builder:** Providers structure their own offering and pricing
- **Availability calendar:** Capacity and blocked dates, tied directly into the booking flow
- **Verified reviews:** Only customers with a completed, verified booking can leave one
- **Four subscription tiers:** Free, Basic, Premium, and Premium+, each unlocking more visibility and tooling

## Admin & Operations

Behind the scenes, an internal platform built on **Filament** runs the operational side: KPI dashboards for cancellation rate, payout alarms, and response times; work queues for provider onboarding, catalog review, leads, and open tenders; a builder for concierge offers; and a marketing dashboard with channel heatmaps.

## Marketing, Reach & Content

I built a self-hosted **marketing attribution** system — click-ID and UTM capture without third-party tag managers or pixels, gated behind consent, feeding conversion data back into Google Ads including retractions when a booking is cancelled.

The platform itself runs on an interactive **Mapbox GL JS** map with taxonomy filters, is fully multi-language (German, English, Dutch) with hreflang sitemaps, supports open tenders where customers post events and providers apply, and includes a blog with AI-assisted content tooling.

## Tech Stack & Monitoring

Built on **Laravel 12** with **Livewire** for reactive components and **Alpine.js** for lightweight interactivity. **Tailwind CSS** ensures a consistent, responsive design, and **MySQL** handles the data layer. The admin and ops platform runs on **Filament**, payments run on **Stripe Connect** and **Laravel Cashier**, and AI features are powered by the **OpenAI API**.

For reliability, I integrated **Sentry** for real-time error tracking and performance monitoring.

## The Results

way.food has scaled to:

- **1,000+ registered users**
- **300+ verified providers**
- **Thousands of booking requests**
- **7-figure annual traffic**

A platform built for scale, proving that startup speed and enterprise-grade architecture aren't mutually exclusive.
