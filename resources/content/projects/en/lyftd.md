---
id: 1
slug: lyftd
client: lyftd.app
category: ['Product', 'Design', 'Development']
image: /img/portfolio/lyftd/lyftd_portrait_1.webp
color: '#fc6612'
logo: lyftd
width: '100%'
height: 'md:aspect-[4/5]'
spineHeight: 'h-24 md:h-32'
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
    - MySQL
    - Filament
    - Laravel Jetstream
    - Laravel Cashier
    - Paddle
    - OpenAI API
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
      alt: 'lyftd.app - User Welcome Screen and Dashboard'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_4.webp
      alt: 'lyftd.app - Exercise Library and Details Page'
    - type: video
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_video_3.webm
      thumbnail: /img/portfolio/lyftd/lyftd_portrait_video_3.webp
      alt: 'lyftd.app - Exercise Library Filtering and Exercise Details Page'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_2.webp
      alt: 'lyftd.app - Exercise Statistics and Progress'
    - type: video
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_video_2.webm
      thumbnail: /img/portfolio/lyftd/lyftd_portrait_video_2.webp
      alt: 'lyftd.app - Exercise Progress Chart Views'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_8.webp
      alt: 'lyftd.app - Total Volume and Workout Completion History'
    - type: video
      aspectRatio: video
      src: /img/portfolio/lyftd/lyftd_landscape_video_1.webm
      thumbnail: /img/portfolio/lyftd/lyftd_landscape_video_1.webp
      alt: 'lyftd.app - Desktop Landingpage'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_6.webp
      alt: 'lyftd.app - Workout Plan Overview and Progress Stats'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_5.webp
      alt: 'lyftd.app - Most Performed Exercises'
    - type: image
      aspectRatio: '4/5'
      src: /img/portfolio/lyftd/lyftd_portrait_7.webp
      alt: 'lyftd.app - Workout Logbook and Session Details'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_3.webp
      alt: 'lyftd.app - Workout Days Overview and Exercise Editing'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_6.webp
      alt: 'lyftd.app - Desktop Edit Exercise Page'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_4.webp
      alt: 'lyftd.app - Desktop Achievements Page'
    - type: image
      aspectRatio: '16/9'
      src: /img/portfolio/lyftd/lyftd_landscape_5.webp
      alt: 'lyftd.app - Desktop Workout Session Detail Table View'
title: 'Smart Training, Stronger You — Full-Stack Workout Tracking Platform'
story_preview: 'lyftd is my passion project — a sophisticated workout tracking platform I built from scratch to manage the entire lifecycle of training programs. Featuring AI-powered coaching, comprehensive analytics, subscriptions, and plan sharing'
fineprint: 'Built entirely solo as my personal sandbox for full-stack product development. From authentication flows to payment integration, from AI coaching to achievement systems — every line of code is mine. This is where I push my limits as a developer.'
fineprint_media: /img/portfolio/lyftd/lyftd_landscape_1.webp
fineprint_media_alt: 'lyftd.app - Workout Tracking Interface'
---

# I couldn't find a workout tracker I liked, so I built the one I wanted.

lyftd was born out of personal frustration with existing fitness apps — they were either clunky, riddled with ads, or lacked the flexibility I needed. I wanted a tool that felt premium, worked seamlessly across all my devices, and adapted to my training style. So I built it.

## The Vision

My goal was to create a modular, high-performance web application that serves as a comprehensive companion for fitness enthusiasts. It's not just about logging weights — it's about managing the entire lifecycle of a training program. From creating intricate multi-week plans to executing sessions with focus and flow, every interaction is designed to be frictionless.

## The Tech Stack

Built on a robust **Laravel** backend with a **Vue.js** frontend powered by **Inertia.js**, the app delivers a modern SPA experience without the complexity of a separate API. **MySQL** handles the data layer, while **Tailwind CSS** ensures a dark-mode-first, data-rich UI.

- **Laravel Jetstream:** Full authentication system with email verification, two-factor authentication, session management, and account deletion
- **Filament:** Admin panel for platform management, user oversight, and subscription monitoring
- **Paddle + Laravel Cashier:** Complete subscription system with Core and Elite tiers, pause/resume functionality, and billing portal integration

## Workout Planning & Execution

The planning system is built for flexibility:

- **Custom Workout Plans:** Multi-week plans with configurable duration, deload weeks, and day-by-day scheduling
- **Exercise Configuration:** Sets, reps, load, target RPE, rest times, and volume tracking per exercise
- **Plan Sharing:** Share plans with other users with viewer or editor permissions
- **Duplication:** Clone entire plans, individual days, or specific exercises

The execution engine is where lyftd shines:

- **Precision Tracking:** Log sets, reps, weight, duration, and RPE in real-time
- **Rest Timer:** Automatic countdown between sets with pause/resume functionality
- **Dynamic Exercise Addition:** Add new exercises mid-session — either as a one-off for the current workout or propagated to all future sessions in the plan
- **Skip Logic:** Skip sets or entire exercises with intelligent completion tracking
- **Auto-Completion:** Sessions from previous weeks are automatically completed with a note

## AI-Powered Coaching

After each workout, the **AI Coach** analyzes performance using **OpenAI's GPT-5 nano** — chosen for token efficiency while maintaining quality output:

- Compares current vs. previous workout metrics
- Analyzes RPE, volume, and completion rates
- Generates personalized feedback and training recommendations
- Multi-language support (English/German)
- Rate-limited access based on subscription tier

## Progress & Analytics

Comprehensive tracking to visualize your journey:

- **Workout Heatmaps:** Activity calendar showing consistency over time
- **Volume Charts:** Total volume trends, average RPE, sets per workout
- **Exercise Progress:** Weight, volume, and rep improvements per exercise
- **Personal Records:** Automatic PR detection and tracking
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

lyftd is more than a side project — it's a real product serving real users. It constantly evolves as I experiment with new techniques, giving me hands-on experience in everything from database schema design to deployment, user support, AI integration, and subscription management.
