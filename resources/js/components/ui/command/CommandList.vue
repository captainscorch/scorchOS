<script setup lang="ts">
import type { ListboxContentProps } from "reka-ui"
import type { HTMLAttributes } from "vue"
import { reactiveOmit } from "@vueuse/core"
import { ListboxContent, useForwardProps } from "reka-ui"
import { cn } from "@/lib/utils"

const props = defineProps<ListboxContentProps & { class?: HTMLAttributes["class"] }>()

const delegatedProps = reactiveOmit(props, "class")

const forwarded = useForwardProps(delegatedProps)
</script>

<template>
  <ListboxContent
    data-slot="command-list"
    v-bind="forwarded"
    :class="cn('max-h-[300px] supports-timeline-scroll:scroll-fade-y scroll-py-1 overflow-x-hidden overflow-y-auto', props.class)"
  >
    <div role="presentation">
      <slot />
    </div>
  </ListboxContent>
</template>

<style scoped>
@property --top-mask-height {
  syntax: "<length>";
  inherits: false;
  initial-value: 0px;
}

@property --bottom-mask-height {
  syntax: "<length>";
  inherits: false;
  initial-value: 0px;
}

/* Scroll Fade Y */
@supports (animation-timeline:scroll()) {
  .supports-timeline-scroll\:scroll-fade-y {
    --mask-height: 64px;
    --scroll-buffer: 2rem;

    /* Mask Image Definition */
    /* 1. Top Fade (controlled by top-mask-height) */
    /* 2. Bottom Fade (controlled by bottom-mask-height) */
    /* 3. Content (solid) */
    
    -webkit-mask-image: 
      linear-gradient(to top, transparent, var(--foreground) 90%), 
      linear-gradient(to bottom, transparent 0%, var(--foreground) 100%), 
      linear-gradient(var(--background), var(--background));
    mask-image: 
      linear-gradient(to top, transparent, var(--foreground) 90%), 
      linear-gradient(to bottom, transparent 0%, var(--foreground) 100%), 
      linear-gradient(var(--background), var(--background));

    /* Mask Sizes */
    -webkit-mask-size: 100% var(--top-mask-height), 100% var(--bottom-mask-height), 100% 100%;
    mask-size: 100% var(--top-mask-height), 100% var(--bottom-mask-height), 100% 100%;

    /* Mask Positions */
    -webkit-mask-position: 0 0, 0 100%, 0 0;
    mask-position: 0 0, 0 100%, 0 0;

    /* Mask Repeat */
    -webkit-mask-repeat: no-repeat, no-repeat, no-repeat;
    mask-repeat: no-repeat, no-repeat, no-repeat;

    /* Mask Composite: Exclude to cut out the fades */
    -webkit-mask-composite: xor; /* Webkit uses xor for exclude behavior in some contexts */
    mask-composite: exclude;

    /* Animation Config */
    animation-name: show-top-mask, hide-bottom-mask;
    animation-timeline: scroll(self), scroll(self);
    animation-range: 0 var(--scroll-buffer), calc(100% - var(--scroll-buffer)) 100%;
    animation-fill-mode: both;
  }

  @keyframes show-top-mask {
    from { --top-mask-height: 0px; }
    to { --top-mask-height: var(--mask-height); }
  }

  @keyframes hide-bottom-mask {
    from { --bottom-mask-height: var(--mask-height); }
    to { --bottom-mask-height: 0px; }
  }
}
</style>
