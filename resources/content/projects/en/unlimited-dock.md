---
id: 7
slug: unlimited-dock
client: unlimited.dock
category: ['Product', 'Design', 'Development']
image: /img/portfolio/unlimited-dock/unlimited-dock_portrait_9.webp
color: '#fe3312'
logo: dock
width: '94%'
height: 'md:aspect-[3/4]'
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
    - MCP
    - XRechnung
    - Laravel Forge API
    - Figma
media:
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_1.webp
      alt: 'unlimited.dock - Task List with Status Groups and Project Properties'
    - type: video
      aspectRatio: video
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_video_1.webm
      thumbnail: /img/portfolio/unlimited-dock/unlimited-dock_landscape_video_1.webp
      alt: 'unlimited.dock - Quick Add: Create a Task with the C Shortcut, Priority and Due Date'
    - type: video
      aspectRatio: video
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_video_2.webm
      thumbnail: /img/portfolio/unlimited-dock/unlimited-dock_landscape_video_2.webp
      alt: 'unlimited.dock - Command Palette: Search a Task and Open It in the Peek Panel'
    - type: video
      aspectRatio: video
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_video_3.webm
      thumbnail: /img/portfolio/unlimited-dock/unlimited-dock_landscape_video_3.webp
      alt: 'unlimited.dock - Timer Panel: Pick a Project and Service, Add a Note, Start the Timer'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_5.webp
      alt: 'unlimited.dock - Budget Detail with Budget Lines, Hours and Values'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_25.webp
      alt: 'unlimited.dock - Sales Pipeline Board from New Inquiry to Negotiation'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/unlimited-dock/unlimited-dock_portrait_4.webp
      alt: 'unlimited.dock - Public Quote Page for Clients'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/unlimited-dock/unlimited-dock_portrait_6.webp
      alt: 'unlimited.dock - Client Invoice Page with Overdue Notice and GiroCode'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_15.webp
      alt: 'unlimited.dock - Invoice List with Numbers, Status and Open Amounts'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_16.webp
      alt: 'unlimited.dock - Invoice Detail with Payment Reminders and Change History'
    - type: video
      aspectRatio: video
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_video_4.webm
      thumbnail: /img/portfolio/unlimited-dock/unlimited-dock_landscape_video_4.webp
      alt: 'unlimited.dock - Cancellation: Give a Reason, Hold the Button, the Cancellation Is Issued'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_20.webp
      alt: 'unlimited.dock - Bank Import with Automatically Matched Payments'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_17.webp
      alt: 'unlimited.dock - Revenue Since 2018 with KPI Cards, Monthly Chart and Treemap per Client'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_22.webp
      alt: 'unlimited.dock - Agents Page with Claude Runs and Every Write per Run'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_23.webp
      alt: 'unlimited.dock - Connect Claude: Connector Guide for the Claude App and Claude Code'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_24.webp
      alt: 'unlimited.dock - Feed Filtered to Changes Made by Claude'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_26.webp
      alt: 'unlimited.dock - Servers and Sites from Laravel Forge with Costs and Ubuntu Support'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/unlimited-dock/unlimited-dock_landscape_7.webp
      alt: 'unlimited.dock - Client Cockpit with Retainer Budget and Hours This Month'
title: 'Our Studio Operating System: From First Inquiry to Paid Invoice'
story_preview: 'unlimited.dock is the operating system I built for our studio, from the first inquiry to the paid invoice. Claude works in it too, through an MCP server and a REST API.'
fineprint: 'Designed and built solo, in use for a five-person studio every working day. Clients, projects, tasks, amounts and hours in the screenshots are placeholders.'
fineprint_media: /img/portfolio/unlimited-dock/unlimited-dock_landscape_9.webp
fineprint_media_alt: 'unlimited.dock - Dashboard with Monthly KPIs, Revenue Chart and Team Week'
---

# Our studio ran on subscriptions. Now it runs on software I built.

For years our studio ran on rented software. Productive.io held tasks, time and budgets, Zoho Books wrote the invoices, and spreadsheets kept track of hosting and servers. Every tool billed each month, none of them knew the others, and one client lived in four places. Linear had the interaction model I wanted, but no idea what an agency budget is. So I built unlimited.dock: one system shaped around how our studio works, from the first inquiry to the paid invoice.

## Replacing Productive.io Outright

This wasn't a side-by-side experiment, it was a full cutover. I wrote an import pipeline that migrated our complete history out of Productive's API: tasks, projects, time entries, budgets and comments, plus an archive export of 51 resource types and several gigabytes of attachments. Then the first subscription lapsed for good. Today the platform carries around 8,000 tasks, 20,000 tracked hours and budgets for more than 90 clients.

## The Tech Stack

A **Laravel** backend with a **Vue 3** frontend via **Inertia.js**, one codebase for the app. Next to it sit a **REST API** and an **MCP server** for agents. **Tailwind CSS** and **shadcn-vue** drive a fast, minimal UI, **TipTap** handles rich text with markdown as the storage format, and **MySQL** holds the data.

- **Testing:** Over 2,000 PHPUnit tests run on every change. Accounting logic is not a place for surprises
- **PDF Engine:** Quotes, invoices and billing reports render server-side via dompdf with the studio's letterhead and typefaces
- **Integrations:** Transactional mail through Resend, digests and warnings into Slack, the invoice archive in Google Drive, the server inventory from Laravel Forge

## Tasks, the Linear Way

The task system borrows the interaction model that makes Linear feel effortless and applies it to agency work:

- **Status Rings & Peek Panel:** Scan states at a glance, open any task in a side panel without losing the list
- **Command Palette:** Jump to any task, project or client from anywhere. The mouse is optional
- **Client-Scoped Identifiers:** Tasks are numbered per client, so a reference like a ticket number means something in a conversation
- **Real Structure:** Subtasks with blocking, recurring tasks, milestones, priorities and private tasks that clients never see

## Built for Keyboard Speed

C creates a task, Cmd+K finds any task, project or client, T starts the timer. Every list is filterable, every action has a shortcut, and the interface is tuned to a tool used dozens of times a day: latency is a feature. No marketing pages, no onboarding flows, no settings for hypothetical companies. Software for five specific people, which is exactly why it's fast.

## Time, Capacity & Availability

Every hour lands on a project, optionally on a task and a budget line:

- **Timers & Timesheets:** Start a timer from any task or fill the week in a grid built for speed
- **Team Availability:** Working days and weekly capacity per member, so planning shows what's actually free
- **Fair Rounding:** Billable time rounds in 30-minute steps once per budget line and period. The rounding mode is frozen when a budget is created, so past periods never shift retroactively

## Budgets & Billing

The accounting core mirrors what we used Productive for, minus everything we never touched:

- **Budget Lines & Retainers:** Fixed budgets and recurring retainer periods, with worked and billable hours tracked separately
- **Always Computed, Never Cached:** Burn, profit and capacity are calculated on read. No denormalized numbers that drift out of sync
- **Period Reports:** Billing-ready PDF and CSV reports per period, with a digest and budget warnings posted to Slack
- **Privacy by Design:** Cost rates and profit never leave the server for non-admin roles, enforced by tests

## From Inquiry to Project

Selling work and doing it live in the same tool:

- **Sales Pipeline:** Every inquiry from first contact to won or lost, as board or list, with owner, next step and due date. Tender deadlines sit on the card
- **Weekly Digest:** Due follow-ups land in one morning message per person, and on Mondays Slack gets the overview: what is due, what is overdue, what moved
- **Quote Editor:** Line items from a reusable article library, per-line tax rates, discounts and licensing calculated from four usage parameters
- **Public Quote Page:** Clients accept or decline on a tokenized page. No login, no PDF ping-pong, with view tracking that filters out mail-scanner bots
- **The Automated Part:** An accepted quote turns into a project with budgets created from its line items. The handover from sales to delivery is a single click

## Invoicing Without Zoho

Zoho Books wrote our invoices for years, on its own monthly plan, while the Dock already knew every budget, every hour and every accepted quote. The invoice was the last document still written somewhere else. So the Dock now writes it too:

- **Drafts From What Exists:** An invoice starts from a client, a closed budget period or an accepted quote, including deposit, partial and final invoices
- **Numbers That Never Repeat:** Issuing assigns a gapless number and locks the document. Corrections go through a cancellation or a credit note, and irreversible steps only fire while you hold the button
- **Client Page:** Every invoice has a page for the client with the open amount and a GiroCode for the banking app
- **XRechnung:** Every invoice ships as PDF in German or English and as XRechnung 3.0, checked against the official KoSIT validator
- **SEPA & Dunning:** Direct debit mandates in the client file, a collection list per debit day, two automatic payment reminders and a formal notice after approval
- **Bank Matching:** The bank statement comes in as CSV. Payments with an invoice number or from a known payer book themselves
- **Recurring Profiles:** Hosting, licences and retainers run as profiles with a daily run, as a draft or sent straight away
- **Audit Trail:** Every change to finance and master data lands in an append-only log with who, when, old and new value, as GoBD asks for it

## Numbers at a Glance

- **Dashboard:** Revenue this month against the same stretch last year, the recurring share, the billable ratio, hours and open items
- **Revenue Since 2018:** KPI cards, a monthly chart with a time window and a treemap per client
- **Finance Report:** A monthly PDF and CSV with VAT by invoice date and by payment, filed in Drive on the first of the month
- **Hours Heatmap:** Tracked hours per person and day over twelve weeks, right in the capacity view

## Claude Works in the Dock

The Dock has a second door, built for AI agents: a REST API and an MCP server with 27 tools. The same door serves Claude Code in the terminal, the Claude app on the phone and on the Mac, and jobs that run on a schedule.

- **On the Go:** After a client meeting, two sentences in the Claude app become a task with checklist, due date and the right project
- **In the Terminal:** Claude Code turns call notes into tasks, comments on them, starts my timer and moves inquiries along the pipeline, without a browser tab
- **The Person's Rights, Never More:** Every token works for one person. Claude sees exactly what that person may see: no amounts for members, no private tasks of others
- **Every Change Signed:** Entries read "Claude (for Daniel)", and the Agents page lists every run with job, channel and each single write
- **One Code Path:** API and MCP tools run through the same services and validation as the web app. A task feature without its agent counterpart does not ship
- **Out of Reach on Purpose:** No deleting, no settings, no budgets, no credentials

The model gets its context from the same payloads the interface reads. Claude and the team look at the same data, and the history shows who did what.

## Hosting in View

Servers and sites used to live in a spreadsheet, kept by hand and out of date a week later. Now they come from Laravel Forge every night, each site assigned to its client, each server with its monthly cost and the end of its Ubuntu support. Which client runs where is one search away.

## A Cockpit Instead of Client Logins

Clients don't get accounts, a deliberate decision. They get a tokenized cockpit link per project: a roadmap grouped by topic, a capacity bar showing what's used and what's left, and a feature board where suggestions can be promoted into real tasks by the team.

## Deliberate Omissions

unlimited.dock has no resource planner, no multi-currency and no client logins, and a roadmap file lists every omission on purpose. Building less is what made it possible to replace several platforms at once. Productive.io is gone, Zoho Books follows, and the spreadsheets for hosting and servers go with it. Each of them was a monthly bill for software built for thousands of other companies. What is left does everything our studio needs, and nothing anyone else's does.
