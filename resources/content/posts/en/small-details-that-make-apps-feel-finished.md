---
slug: small-details-that-make-apps-feel-finished
title: 'Small Details That Make Apps Feel Finished'
date: '2026-09-27'
category: ['Craft']
tags: ['ui', 'ux', 'design-engineering', 'saas', 'ai-agents', 'vue']
excerpt: Ten small UI conventions that make an app feel finished, from command menus to AI agent chats. Here's why they matter, each with a live demo that lets you switch the rule off.
components:
    [
        'CommandMenuEtiquetteDemo',
        'OptimisticUndoDemo',
        'HoverIntentDemo',
        'InlineEditDemo',
        'LoadingThresholdsDemo',
        'TabularNumbersDemo',
        'HoverLiftDemo',
        'StickToBottomDemo',
        'ToolCallCardsDemo',
        'ComposerRulesDemo',
    ]
featured: true
---

In my post on [concentric border radius](/blog/craft/concentric-border-radius), I wrote that premium quality is often a byproduct of accumulated micro-decisions. Most of those decisions never show up in a screenshot. Nobody praises an app for its menus, but everyone notices when a menu flickers, a status change takes a second, or a spinner blinks for a single frame.

This post collects the conventions I keep coming back to. Some of them come from Linear and Raycast, which I use every day, and one from Attio's tables. The chat patterns grew out of building the AI coach in [lyftd](/case-study/lyftd). Each section includes a small demo with a toggle to switch the rule off—seeing the broken version is often the best explanation.

## Web Apps

### Command Menus

A command menu is the fastest way through an app—Raycast is built entirely around one. That's also why small mistakes there are so noticeable. Three rules make the difference:

- **Highlight only after the pointer moves.** If the mouse happens to rest where the menu opens, the row under it shouldn't light up. Otherwise Enter picks something you never pointed at.
- **Select on click, not on press.** That way you can still back out of a mis-click by moving away before you let go.
- **Hide keyboard hints on touch devices.** Without a keyboard, they're just noise.

::interactive[CommandMenuEtiquetteDemo]

### Optimistic Updates Instead of Confirm Dialogs

Changing an issue's status in Linear feels instant because the interface doesn't wait for the server. The pattern behind it: apply the change right away, send the request in the background, and only roll back—with a short toast—if it actually fails.

The second half matters just as much: the success toast offers an Undo. That's what lets you get rid of the confirmation dialog altogether. A dialog costs you a click on every single action, while an undo only costs you one when you actually made a mistake.

::interactive[OptimisticUndoDemo]

### Hover Intent and the Safe Triangle

Dropdowns that open on the very first pixel of hover open by accident. Dropdowns that close the moment the pointer leaves the trigger are gone before you reach them. Two rules solve both problems: open only after the cursor has rested for about 150 ms, and once the menu is open, keep it alive while the cursor is moving toward it.

The area the cursor is allowed to cross forms a triangle between the point where it left the trigger and the near corners of the panel. [Ben Kamens broke down Amazon's mega dropdown](https://bjk5.com/post/44698559168/breaking-down-amazons-mega-dropdown) back in 2013 and showed exactly this technique, yet most SaaS navigation still doesn't use it. The demo draws the triangle, so you can see it in action.

::interactive[HoverIntentDemo]

### Inline Editing Without a Mode

Attio's tables don't have a separate edit mode. A cell is plain text until you click it. Then it turns into an input with the same padding and height, so nothing on the page shifts. The keyboard behaves like it does in any spreadsheet: Escape restores the previous value, Enter or clicking away saves, and Tab jumps to the next cell and opens it right away.

Compare that to an edit panel sliding in below the table, and the difference becomes obvious. One feels like working in a table, the other like filling out a form.

::interactive[InlineEditDemo]

### Loading Thresholds

A spinner that shows up for 80 ms is worse than no spinner at all: the screen flashes, and users perceive a delay that wasn't really there. Two thresholds fix this. First, don't show anything for the first 200 ms, so fast responses never trigger an indicator. Second, once an indicator is visible, keep it on screen for at least 300 ms, so it never just blinks.

Try dragging the latency slider through the range and watch which responses get an indicator at all.

::interactive[LoadingThresholdsDemo]

### Tabular Numbers

Many typefaces use proportional figures by default: a 1 is narrower than an 8. In running text that looks better, but as soon as numbers change or line up in a column, it becomes a problem. A timer jitters with every tick, and prices in a table no longer align on the right.

A single CSS property fixes this: `font-variant-numeric: tabular-nums` gives every digit the same width. I use it wherever digits line up or change in front of the user—in lyftd, that means every stat card, weight input and animated counter. In running text, I deliberately stick with proportional figures.

::interactive[TabularNumbersDemo]

### Hover Effects That Don't Flicker

A card that lifts on hover moves its own bottom edge away from the cursor. For a moment the pointer is outside the card, the hover ends, the card drops back under the pointer, and the hover starts again. Right at the bottom edge, that turns into a loop.

The fix is structural: the link keeps its geometry, and only an inner element carries the transform and shadow. That way the hit area never moves. I ran into exactly this bug on the portfolio cards of this site and fixed it the same way.

::interactive[HoverLiftDemo]

## Agent Chats

With AI agents, chat interfaces have found a second life, and they come with their own set of details. Responses arrive token by token, tools run in the middle of an answer, and users need a way to interrupt them. All three demos below run on a scripted reply, so no API is involved.

### Sticking to the Bottom

While a reply is streaming, the chat should stay pinned to the latest message. Unless the user has scrolled up to read something—then every new token pulling them back down is about the most annoying thing a chat can do.

The rule is simple: only stay anchored while the user is already at the bottom. As soon as they scroll up, let go, and offer a small button to jump back to the latest message.

::interactive[StickToBottomDemo]

### Tool Calls as Status Cards

An agent that reads your data can do so on its own. An agent that changes your data should wait for your approval. In lyftd, the coach looks at your training history without asking, but any change to your plan arrives as a proposal you can apply or dismiss.

In the conversation, both kinds of actions deserve a card with a clear status: pending, running, done or failed. Write actions get Run and Reject buttons. And the follow-up text only starts streaming once every tool has finished—an answer that references a result it doesn't have yet is worse than a short pause.

::interactive[ToolCallCardsDemo]

### The Composer

The input field comes with four rules of its own. It grows with its content up to six lines, then scrolls. Enter sends and Shift+Enter adds a new line, with one exception: when typing Japanese or Chinese, Enter first confirms the characters, so the handler has to ignore it while an IME composition is active. Otherwise the message goes out half-typed. While a reply is streaming, the send button turns into a stop button, and Escape stops it too. Finally, a small hint below the field tells you which of these applies right now.

::interactive[ComposerRulesDemo]

## Final Thoughts

None of these rules show up in a screenshot, which is exactly why they're usually the first to go when a deadline gets tight. They only reveal themselves in use, as an absence of friction. And none of them are expensive to build. You can find every demo along with its code snippet on the [Playground](/playground), next to the concentric radius experiment.

It's the same as with concentric corners: most people will never consciously notice these details, but they'll feel the difference when they're missing.
