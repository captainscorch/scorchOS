---
slug: concentric-border-radius
title: 'Mastering Concentric Border Radius'
date: '2026-02-04'
category: ['Craft']
tags: ['ui', 'css', 'border-radius', 'design-engineering']
excerpt: Often overlooked, concentric border radius is a small detail that makes interfaces feel significantly more polished and intentional. Here's why it matters and how to implement it.
components: ['ConcentricDemo']
featured: true
---

In the design world, premium quality is often a byproduct of accumulated micro-decisions. One such decision—frequently ignored in rapid development cycles—is the logic behind nested corners.

When UI elements are layered, simply applying the same `border-radius` to every layer creates a disjointed look. To achieve a truly high-end finish, we need to maintain geometric continuity by ensuring both curves share the same center point. This is the essence of concentricity.

## Why Does It Matter?

Our visual system is hardcoded to seek out geometric continuity. When curvature isn't mathematically aligned, it creates subtle 'visual friction'—the eye snags on the irregularity. When the radii aren't balanced, the corners can look either too 'sharp' or too 'crowded' relative to the outer edge.

Maintaining concentricity ensures that the space between the nested element and its container remains visually consistent throughout the entire turn of the corner.

::interactive[ConcentricDemo]

## The Engineering Logic

While design tools often handle this automatically through intelligent offsets, implementing it in code requires a predictable, architectural constraint. It's a simple relationship between radius and spacing:

```
outer radius = inner radius + padding
```

By following this rule, we ensure that the gap (padding) between the two elements is exactly the same at the apex of the curve as it is on the straight edges.

::image[/img/blog/concentric-radius-diagram.webp|Concentric Border Radius Diagram|How the relationship between outer radius, inner radius, and padding creates a balanced curve.]

## Implementation in CSS

One of the reasons I love bridging the gap between design and development is being able to automate these patterns. Using CSS variables makes this trivial to manage:

```css
.card {
    --inner-radius: 12px;
    --gap: 16px;

    padding: var(--gap);
    border-radius: calc(var(--inner-radius) + var(--gap));
}

.card-content {
    border-radius: var(--inner-radius);
}
```

This approach is particularly useful in design systems where you want to maintain consistency as components scale or padding values change.

## From Physical to Digital

This concept isn't new or unique to the web. It's a fundamental principle in industrial design. If you look at high-end physical products—like the chassis of a MacBook or the casing of a Leica camera—you'll see concentric offsets everywhere. Translating this level of craftsmanship to digital interfaces sets apart exceptional digital products from ordinary ones.

## Final Thoughts

It's a tiny detail, but I believe that building interfaces that feel 'alive' and premium requires this level of attention to detail. When users say an interface feels 'smooth' or 'well-built', they might not be able to point to the concentric border radius, but they're definitely feeling the effects of it.

It's about caring enough to do it right, even when most people won't consciously notice.
