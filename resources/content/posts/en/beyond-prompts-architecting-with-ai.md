---
slug: beyond-prompts-architecting-with-ai
title: 'Beyond Prompts: Architecting with AI'
date: '2026-02-08'
category: ['Journal']
tags: ['ai', 'workflow', 'design-engineering', 'cursor', 'productivity']
excerpt: How I leverage AI to accelerate trial and error phases, maintain lean infrastructure, and scale high-quality output without adding bloat.
components: ['PromptComparison', 'CommandPreview', 'StructuredPromptDemo']
---

For me, development has always been an extension of the design loop. As a design engineer, I spend a significant amount of time in that 'trial and error' phase—the messy space where you're trying to find the exact moment when an interaction or a layout finally feels right.

This is where AI has completely transformed my workflow. It's not about letting an LLM do the thinking for me; it's about shortening the distance between a raw design concept and a functional prototype. If a concept fails the 'feel test' after a few minutes of rapid iterations, I discard it without much hesitation. AI effectively acts as a accelerator for this cycle, allowing me to focus on the nuance of the craft rather than the friction of boilerplate implementation.

::interactive[PromptComparison]

## The Model Landscape

The world of AI models is moving lightning fast. The models have gone from simple autocompletions to full-blown, partially autonomous agents in what feels like weeks. In my day-to-day, I don't stick to a single model; I treat them like different wrenches in a tool kit. **Claude 4.6** alongside **GPT-5.3 Codex** are my current daily drivers for complex architectural logic and refactoring, while I might lean on other models for specialized tasks or quick documentation pivots. Knowing which tool is sharpest for the specific problem at hand is becoming a core skill in itself.

## Defining the Soul of the Codebase

The secret to predictable, high-quality AI output isn't just better prompting—it's better context. In Cursor, I rely heavily on `.cursorrules` to define the 'soul' of my projects. This is where I bake in my standards: from strict Laravel and PHP best practices to the specific way I want my Vue.js components structured using the `script setup` API and Tailwind CSS.

By checking these rules into git, I'm ensuring that every agent I spin up follows the same architectural standard we've set for the team. It's the same reason we use **Prettier** or **ESLint**: we want the syntax to be a solved problem so we can focus on the product. When the rules are clear, the AI stops being a generic assistant and starts acting like a collaborator who knows exactly how I like to build and structure my projects.

With the newest additions of tools like [Laravel Boost](https://laravel.com/ai/boost) and it's MCP server with platform-specific tools for the Agent, I can go even further—Boost for example enables Laravel projects to codify and enforce architectural intent directly into the agent workflow. Using this together with fixed rules means every AI agent or integration, whether generating code, reviewing, or scaffolding features, does so with a deep awareness of my conventions and how I want my application to be built.

## Pruning the Slop

Even with great rules, AI can sometimes get a bit 'wordy'. I use specific commands to keep the codebase lean. One of my favorites is `/deslop`, which [Eric from Cursor](https://x.com/ericzakariasson/status/1995671800643297542) shared on X. It's a fast way to strip away the defensive over-engineering and generic 'fluff' that models tend to inject when left unchecked.

::interactive[CommandPreview]

I also build custom commands tailored to specific projects. Whether it's a `/review` command to audit our accessibility standards or a `/component` command to scaffold out new UI elements that respect our design system.

## Architectural Prompting

I rarely just 'ask' for code anymore, especially in the early stages of a project. I rely heavily on **Plan mode**. Before a single line of code is written, I have the agent walk through the logic and break the feature down into smaller, logical milestones. This architectural approach to prompting makes the final implementation much cleaner and therefore also way easier to debug. It turns the process from a guessing game into a structured process.

::interactive[StructuredPromptDemo]

## The Density Multiplier

Perhaps the most overlooked benefit of AI is how it helps you **reduce technical weight**. AI is incredible at spotting recurring patterns across a large codebase. I often use it to scan for duplicate logic or fragmented styles that should be consolidated into unified primitives. What used to be an hour of manual audit now takes seconds. The end result isn't just speed; it's a leaner, more maintainable architecture that prioritizes abstraction over repetition.

## Lean Infrastructure by Design

This website is a perfect example of that philosophy. Rather than wrestling with a bloated CMS, I built a custom system where each post is simply a Markdown file with frontmatter metadata. Everything lives in one place—content, tags, interactive components—making it trivial to edit and blazingly fast to render. This approach gives me complete control over performance optimization and enables the seamless interactive demos you see throughout these articles. AI accelerated the development of this custom rendering pipeline dramatically, freeing me to focus on crafting content and user experience instead of battling administrative interfaces.

### Case Study: lyftd.app

On **lyftd.app**, I leveraged AI to architect the core `Achievement Service` and the AI Coach logic. It allowed me to iterate through dozens of feedback loops in the time it would have taken to manually write a single comparison function. It's a massive multiplier, but only if you already understand the craft well enough to direct the execution.

## Final Thoughts

As the cost of generating code drops, the value of _direction_ skyrockets. In a world where anyone can generate a layout, the quality, thoughtfulness, and craftsmanship of the person directing that output will be what defines premium products. Use AI to handle the heavy lifting, but never let it replace the soul and the intentionality that makes a digital product feel truly well-built.
