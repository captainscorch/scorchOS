---
id: 1
slug: lyftd
client: lyftd.app
category: ['Product', 'Design', 'Development']
image: /img/portfolio/lyftd/lyftd_portrait_1.webp
color: '#fc6612'
logo: lyftd
width: '100%'
height: 'md:aspect-[4/6]'
spineHeight: 'h-26 md:h-32'
date: '2024 — Present'
website: https://lyftd.app
team:
    - src: /img/daniel.webp
      name: Daniel Schmier
services: ['Product', 'Design', 'Development', 'Branding']
technologies:
    - Laravel
    - Vue.js
    - Inertia.js
    - Tailwind CSS
    - Capacitor
    - MySQL
    - Filament
    - Laravel Jetstream
    - Laravel Sanctum
    - Laravel Cashier
    - Paddle
    - OpenAI API
    - Chart.js
    - Resend
    - Sentry
    - Figma
    - Illustrator
    - Photoshop
media:
    - type: video
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_video_1.webm
      thumbnail: /img/portfolio/lyftd/lyftd_portrait_video_1.webp
      alt: 'lyftd.app - Workout Execution Flow'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_2.webp
      alt: 'lyftd.app - Branding and Logo Design'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_3.webp
      alt: 'lyftd.app - iOS App Today Screen and AI Coach Action Proposal'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_4.webp
      alt: 'lyftd.app - Workout Plan with Supersets and Warm-up Execution'
    - type: video
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_video_3.webm
      thumbnail: /img/portfolio/lyftd/lyftd_portrait_video_3.webp
      alt: 'lyftd.app - Exercise Library Filtering and Exercise Details Page'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_2.webp
      alt: 'lyftd.app - iOS App Stats, Execution and Coach Recap Screens'
    - type: video
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_video_2.webm
      thumbnail: /img/portfolio/lyftd/lyftd_portrait_video_2.webp
      alt: 'lyftd.app - Exercise Progress Chart Views'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_8.webp
      alt: 'lyftd.app - AI Coach Chat with Confirmable Action Proposal'
    - type: video
      aspectRatio: video
      src: /img/portfolio/lyftd/lyftd_landscape_video_1.webm
      thumbnail: /img/portfolio/lyftd/lyftd_landscape_video_1.webp
      alt: 'lyftd.app - Desktop Landingpage'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_6.webp
      alt: 'lyftd.app - Weekly AI Coach Recap'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_5.webp
      alt: 'lyftd.app - Exercise Statistics and Volume Charts'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_7.webp
      alt: 'lyftd.app - Superset Execution with Rest Timer'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_3.webp
      alt: 'lyftd.app - Trainer Workspace with Student Roster'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_7.webp
      alt: 'lyftd.app - Trainer Analytics with Student Progress Charts'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_6.webp
      alt: 'lyftd.app - Yearly Training Wrapped'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_4.webp
      alt: 'lyftd.app - Achievements Overview'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_5.webp
      alt: 'lyftd.app - Progress Statistics Overview'
title: 'Smart Training, Stronger You — Workout Tracking Platform with a Native iOS App and an AI Coaching Agent'
story_preview: 'lyftd is my passion project — a sophisticated workout tracking platform I built from scratch to manage the entire lifecycle of training programs. Now with a native iOS app and an AI coach that can act on your plan directly, plus comprehensive analytics, subscriptions, and a platform for trainers'
fineprint: 'Built entirely solo as my personal sandbox for full-stack product development. From authentication flows to payment integration, from a native iOS app to an AI coaching agent with its own harness — every line of code is mine. This is where I push my limits as a developer.'
fineprint_media: /img/portfolio/lyftd/lyftd_landscape_1.webp
fineprint_media_alt: 'lyftd.app - Dashboard with AI Coach Recap'
---

# I couldn't find a workout tracker I liked, so I built the one I wanted.

lyftd was born out of personal frustration with existing fitness apps — they were either clunky, riddled with ads, or lacked the flexibility I needed. I wanted a tool that felt premium, worked seamlessly across all my devices, and adapted to my training style. So I built it. What started as a web app has since grown into a native iOS app and an AI coach that doesn't just talk, it acts.

## The Vision

My goal was to create a modular, high-performance platform that serves as a comprehensive companion for fitness enthusiasts. It's not just about logging weights — it's about managing the entire lifecycle of a training program. From creating intricate multi-week plans to executing sessions with focus and flow, every interaction is designed to be frictionless.

## The Tech Stack

Built on a robust **Laravel** backend with a **Vue 3** frontend powered by **Inertia.js**, the web app delivers a modern SPA experience without the complexity of a separate API. **MySQL** handles the data layer, **Tailwind CSS** ensures a dark-mode-first, data-rich UI, and **Chart.js** drives the analytics. **Resend** handles transactional email, **Sentry** covers error tracking.

- **Laravel Jetstream:** Full authentication system with email verification, two-factor authentication, session management, and account deletion
- **Filament:** Admin panel for platform management, user oversight, and subscription monitoring
- **Paddle + Laravel Cashier:** Complete subscription system with Core and Elite tiers, pause/resume functionality, and billing portal integration

## Native iOS App

lyftd now ships as a real iOS app, currently in TestFlight ahead of launch. It's a dedicated Vue 3 single-page app, wrapped with **Capacitor 7** into a proper Xcode project rather than a web view bolted onto a shell.

- **Its own API:** The app talks to a dedicated `/api/mobile` endpoint secured with **Laravel Sanctum** tokens — cookie-based auth doesn't work from a `capacitor://` origin, so mobile gets its own authentication path
- **Feature parity:** Near-total parity with the web app, with one deliberate exception — no in-app purchase UI. Subscriptions stay on lyftd.app, where they belong
- **Native touches:** Local notifications keep the rest timer running even with the screen locked, plus haptics, the native share sheet, and a custom-built native tab bar plugin

## Workout Planning & Execution

The planning system is built for flexibility:

- **Custom Workout Plans:** Multi-week plans with configurable duration, deload weeks, and day-by-day scheduling
- **Exercise Configuration:** Sets, reps, load, target RPE, rest times, and volume tracking per exercise
- **Training Depth:** Supersets, warm-up ramps, myo-reps, per-side unilateral tracking, half reps, and a built-in plate calculator
- **Freestyle Workouts:** Log a session on the fly with no plan behind it
- **Plan Sharing & Duplication:** Share plans with viewer or editor permissions, clone entire plans, individual days, or specific exercises
- **Units, Your Way:** kg/lbs and RPE/RIR as app-wide user settings

The execution engine is where lyftd shines:

- **Precision Tracking:** Log sets, reps, weight, duration, and RPE in real-time
- **Rest Timer:** Automatic countdown between sets with pause/resume functionality, running natively even when the phone is locked
- **Dynamic Exercise Addition:** Add new exercises mid-session — either as a one-off for the current workout or propagated to all future sessions in the plan
- **Skip Logic:** Skip sets or entire exercises with intelligent completion tracking
- **Auto-Completion:** Sessions from previous weeks are automatically completed with a note

## AI Coaching Agent

The AI Coach started as post-workout feedback. It's now a full agent with its own harness, actions, and feedback loops, running on **OpenAI's gpt-5.6-luna**.

- **Chat anywhere:** Talk to the coach from anywhere in the app. An intent service detects change requests — "swap this exercise", "raise the weight" — in German or English and steers the prompt accordingly
- **Actions, not just advice:** The model can propose real changes to a plan — swapping an exercise, updating sets, reps, load, rest, or target RPE — through a structured marker in its reply. That proposal is never trusted at face value: the harness re-resolves the target against your actual current plan, whitelists which fields are allowed to change, validates every value through the same rules as the plan editor, and caches the resolved proposal server-side under a random id. Your device only ever carries that id, never the underlying mutation
- **You stay in control:** Every proposed change shows up as an old-versus-new diff card. You apply or dismiss it explicitly, and on apply the server validates once more against your live plan before writing anything
- **Post-workout feedback loop:** After each session, the coach writes adjustment recommendations. You tick which ones to apply, future sessions update accordingly, and history stays snapshotted so nothing gets rewritten after the fact
- **Weekly and monthly recaps:** The AI compares volume, adherence, PRs, and streaks against the prior period, including a deload recommendation when effort is climbing but load has stalled
- **Plan generator:** Feed it your goals, days per week, equipment, and injuries, and get a complete, reviewable plan back. It also proposes the next training block once a plan runs out
- **Natural-language logging:** Type "bench press 3x8 80kg" and it becomes a logged exercise
- **Guardrails:** OpenAI moderation on every input, per-tier rate limits, and every interaction logged

## Trainer Platform

A B2B layer sits on top of the consumer product: a workspace for trainers to run their client base through lyftd.

- **Roster & billing:** Trainers invite students via link and manage a roster; seats are billed on top of the Paddle subscription, and students on a trainer's roster get Elite free
- **Plan distribution:** Assign plans and templates to individual students or whole groups at once
- **Reporting:** Per-student stats, branded PDF progress reports, and a weekly adherence digest email
- **Communication:** Direct trainer-to-student messaging, plus intake questionnaires to onboard new clients
- **Physio mode:** A pain-tracking mode for rehab and physiotherapy use cases
- **White-label branding:** Trainers can put their own brand on the experience their students see

## Progress & Analytics

Comprehensive tracking to visualize your journey:

- **Muscle Maps:** An anatomical heat map of training volume plus a recovery map showing which muscle groups are ready to train again
- **Strength Standards & Goals:** Compare your lifts against strength standards, set lift goals, and see a trend projection toward them
- **Wrapped:** A shareable year-in-training stat sheet, generated per year
- **Workout Heatmaps & Volume Charts:** Activity calendar, total volume trends, average RPE, sets per workout
- **Exercise Progress & PRs:** Weight, volume, and rep improvements per exercise, with automatic PR detection
- **Streak Tracking:** Current and longest workout streaks

## Exercise Library & Custom Exercises

- **Curated Library:** Comprehensive database with categories, muscle groups, equipment tags, and visualizations
- **Custom Exercises:** Create private exercises with tier-based limits
- **Filtering & Search:** Sort by tags, categories, and exercise type

## Achievement System

I built a gamification layer to drive user engagement and retention. The `Achievement Service` triggers after each completed session, checking performance data against historical records and predefined thresholds:

- **Streak Tracking:** Consecutive workout days and longest streaks
- **Consistency Metrics:** Weekly workout frequency targets
- **Volume Milestones:** Cumulative weight lifted thresholds
- **Personal Records:** Automatic detection of new weight PRs per exercise
- **Endurance Badges:** Duration-based achievements for timed exercises

## Workout History & Editing

- View detailed breakdowns of past sessions
- Edit sets, reps, weight, and RPE for workouts within the last 7 days
- Session notes and subjective feedback tracking
- AI-generated workout summaries for each session

## A Living Product

lyftd is more than a side project — it's a real product serving real users. Its latest release, easily the biggest since lyftd went live, took it from a web app to a platform: a native iOS app, an AI coach that acts on your plan instead of just commenting on it, and a trainer platform for coaches running their clients through it. It keeps evolving as I experiment with new techniques, giving me hands-on experience in everything from database schema design to native app development, AI agent design, and subscription management.
