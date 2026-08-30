---
id: 6
slug: unlimited-dock
client: unlimited.dock
category: ['Product', 'Design', 'Development']
image: /img/portfolio/unlimited-dock/unlimited-dock_portrait_1.webp
color: '#fe3312'
logo: dock
width: '94%'
height: 'md:aspect-[4/5]'
spineHeight: 'h-26 md:h-32'
date: '2026 — Present'
team:
    - src: /img/daniel.webp
      name: Daniel Schmier
services: ['Product', 'Design', 'Development']
technologies:
    - Laravel
    - Vue.js
    - Inertia.js
    - TypeScript
    - Tailwind CSS
    - shadcn-vue
    - MySQL
    - TipTap
    - dompdf
    - Resend
    - Slack API
    - Figma
media:
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_1.webp
      alt: 'unlimited.dock - Task List with Status Groups and Project Properties'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/unlimited-dock/unlimited-dock_portrait_2.webp
      alt: 'unlimited.dock - Task Detail with Markdown Description and Code Block'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_12.webp
      alt: 'unlimited.dock - Task Peek Panel with Checklist and Quick Actions'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_2.webp
      alt: 'unlimited.dock - Command Palette with Active Task Filter'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_3.webp
      alt: 'unlimited.dock - Task Board by Status'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/unlimited-dock/unlimited-dock_portrait_3.webp
      alt: 'unlimited.dock - Comments with Mentions, Quotes and Reactions'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_4.webp
      alt: 'unlimited.dock - Team Times with Tracked Hours per Member'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_5.webp
      alt: 'unlimited.dock - Budget Detail with Expanded Time Entries'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_6.webp
      alt: 'unlimited.dock - Timer Panel'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_10.webp
      alt: 'unlimited.dock - Timer with Project Picker'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_13.webp
      alt: 'unlimited.dock - Running Timer in the Top Bar'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/unlimited-dock/unlimited-dock_portrait_4.webp
      alt: 'unlimited.dock - Public Quote Page for Clients'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/unlimited-dock/unlimited-dock_portrait_5.webp
      alt: 'unlimited.dock - Quote Options and Total Calculation'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_7.webp
      alt: 'unlimited.dock - Client Cockpit with Monthly Retainer View'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_11.webp
      alt: 'unlimited.dock - Keyboard Shortcuts Overview'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_14.webp
      alt: 'unlimited.dock - Collapsed Sidebar via Cmd+B'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_8.webp
      alt: 'unlimited.dock - Changelog Generated from Commits'
title: 'Our Studio Operating System — Tasks, Time, Budgets & Quotes in One Platform'
story_preview: 'unlimited.dock is the operations platform I built for our studio — Linear-style tasks, time tracking, team availability, budgets and quotes in one keyboard-driven app. It replaced our Productive.io subscription outright and carries around 8,000 tasks and 20,000 tracked hours'
fineprint: 'Designed and built solo, in production for a five-person studio every working day. Task names, clients and projects in the screenshots are randomized placeholders — the workload behind them is real.'
fineprint_media: /img/portfolio/unlimited-dock/unlimited-dock_landscape_9.webp
fineprint_media_alt: 'unlimited.dock - Dashboard with Team Week and Budget Warnings'
---

# Our studio ran on a subscription. Now it runs on software I built.

For years, Productive.io handled our tasks, time tracking and budgets — a capable tool that always felt one size too big for a five-person studio, at a price that grew with every seat and feature tier. Linear had the interaction model I actually wanted, but no idea what an agency budget is. So I built unlimited.dock: the depth of an agency platform with the speed of Linear, shaped exactly around how our studio works.

## Replacing Productive.io Outright

This wasn't a side-by-side experiment — it was a full cutover. I wrote an import pipeline that migrated our complete history out of Productive's API: tasks, projects, time entries, budgets and comments, plus an archive export of 51 resource types and several gigabytes of attachments. Then the subscription lapsed for good. Today the platform carries around 8,000 tasks, 20,000 tracked hours and budgets for more than 90 clients.

## The Tech Stack

A **Laravel** backend with a **Vue 3** frontend via **Inertia.js** — one codebase, no separate API. **Tailwind CSS** and **shadcn-vue** drive a fast, minimal UI, **TipTap** handles rich text with markdown as the storage format, and **MySQL** holds the data.

- **Testing:** Over 1,200 PHPUnit tests run on every change — accounting logic is not a place for surprises
- **PDF Engine:** Quotes and billing reports render server-side via dompdf with the studio's letterhead and typefaces
- **Integrations:** Transactional mail through Resend, billing digests and budget warnings into Slack, contact import from Zoho

## Tasks, the Linear Way

The task system borrows the interaction model that makes Linear feel effortless and applies it to agency work:

- **Status Rings & Peek Panel:** Scan states at a glance, open any task in a side panel without losing the list
- **Command Palette:** Jump to any task, project or client from anywhere — the mouse is optional
- **Client-Scoped Identifiers:** Tasks are numbered per client, so a reference like a ticket number means something in a conversation
- **Real Structure:** Subtasks with blocking, recurring tasks, milestones, priorities and private tasks that clients never see

## Time, Capacity & Availability

Every hour lands on a project, optionally on a task and a budget line:

- **Timers & Timesheets:** Start a timer from any task or fill the week in a grid built for speed
- **Team Availability:** Working days and weekly capacity per member, so planning shows what's actually free
- **Fair Rounding:** Billable time rounds in 30-minute steps once per budget line and period — the rounding mode is frozen when a budget is created, so past periods never shift retroactively

## Budgets & Billing

The accounting core mirrors what we used Productive for, minus everything we never touched:

- **Budget Lines & Retainers:** Fixed budgets and recurring retainer periods, with worked and billable hours tracked separately
- **Always Computed, Never Cached:** Burn, profit and capacity are calculated on read — no denormalized numbers that drift out of sync
- **Period Reports:** Billing-ready PDF and CSV reports per period, with a digest and budget warnings posted to Slack
- **Privacy by Design:** Cost rates and profit never leave the server for non-admin roles — enforced by tests

## From Quote to Project, Automatically

The newest module closes the loop between selling work and doing it:

- **Quote Editor:** Line items from a reusable article library, per-line tax rates, discounts and licensing calculated from four usage parameters
- **Public Quote Page:** Clients accept or decline on a tokenized page — no login, no PDF ping-pong, with view tracking that filters out mail-scanner bots
- **The Automated Part:** An accepted quote turns into a project with budgets created from its line items — the handover from sales to delivery is a single click

## A Cockpit Instead of Client Logins

Clients don't get accounts — a deliberate decision. They get a tokenized cockpit link per project: a roadmap grouped by topic, a capacity bar showing what's used and what's left, and a feature board where suggestions can be promoted into real tasks by the team.

## Built for Keyboard Speed

Every list is filterable, every action has a shortcut, and the interface is tuned to the reality of using a tool dozens of times a day: latency is a feature. No marketing pages, no onboarding flows, no settings for hypothetical companies — software for five specific people, which is exactly why it's fast.

## Deliberate Omissions

unlimited.dock has no CRM, no invoicing module and no resource planner — those live better elsewhere, and a roadmap file lists every omission on purpose. Building less is what made it possible to replace a commercial platform outright: it does everything our studio needs, and nothing anyone else's does.
