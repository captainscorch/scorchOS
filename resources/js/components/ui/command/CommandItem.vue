<script setup lang="ts">
import type { ListboxItemEmits, ListboxItemProps } from "reka-ui"
import type { HTMLAttributes } from "vue"
import { reactiveOmit, useCurrentElement } from "@vueuse/core"
import { ListboxItem, useForwardPropsEmits, useId } from "reka-ui"
import { computed, onMounted, onUnmounted, ref, watch } from "vue"
import { cn } from "@/lib/utils"
import { useCommand, useCommandGroup } from "."

const props = defineProps<ListboxItemProps & { class?: HTMLAttributes["class"] }>()
const emits = defineEmits<ListboxItemEmits>()

const delegatedProps = reactiveOmit(props, "class")

const forwarded = useForwardPropsEmits(delegatedProps, emits)

const id = useId()
const { filterState, allItems, allGroups } = useCommand()
const groupContext = useCommandGroup()

const isRender = computed(() => {
  if (!filterState.search) {
    return true
  }
  else {
    const filteredCurrentItem = filterState.filtered.items.get(id)

    if (filteredCurrentItem === undefined) {
      return true
    }

    return filteredCurrentItem > 0
  }
})

const itemRef = ref()
const currentElement = useCurrentElement(itemRef)
onMounted(() => {
  if (!(currentElement.value instanceof HTMLElement))
    return

  // reka-ui highlights the first item on mount via scrollIntoView, which scrolls every ancestor,
  // the window included. Keep the scroll inside the list so an inline menu never moves the page.
  const el = currentElement.value
  el.scrollIntoView = () => {
    const list = el.closest<HTMLElement>("[data-slot=command-list]")
    if (!list)
      return
    const item = el.getBoundingClientRect()
    const box = list.getBoundingClientRect()
    if (item.top < box.top)
      list.scrollTop -= box.top - item.top
    else if (item.bottom > box.bottom)
      list.scrollTop += item.bottom - box.bottom
  }

  allItems.value.set(id, (currentElement.value.textContent ?? "") + " " + (props.value?.toString() ?? ""))

  const groupId = groupContext?.id
  if (groupId) {
    if (!allGroups.value.has(groupId)) {
      allGroups.value.set(groupId, new Set([id]))
    }
    else {
      allGroups.value.get(groupId)?.add(id)
    }
  }
})

watch(() => props.value, () => {
  if (currentElement.value instanceof HTMLElement) {
    allItems.value.set(id, (currentElement.value.textContent ?? "") + " " + (props.value?.toString() ?? ""))
  }
})

onUnmounted(() => {
  allItems.value.delete(id)
})
</script>

<template>
  <ListboxItem
    v-if="isRender"
    v-bind="forwarded"
    :id="id"
    ref="itemRef"
    data-slot="command-item"
    :class="cn('data-[highlighted]:bg-accent hover:bg-accent hover:text-accent-foreground data-[highlighted]:text-accent-foreground [&_svg:not([class*=\'text-\'])]:text-muted-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4', props.class)"
    @select="() => {
      filterState.search = ''
    }"
  >
    <slot />
  </ListboxItem>
</template>
