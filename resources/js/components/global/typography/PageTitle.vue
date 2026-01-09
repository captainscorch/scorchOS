<script setup lang="ts">
withDefaults(
    defineProps<{
        as?: string;
    }>(),
    {
        as: 'h1',
    },
);
</script>

<template>
    <component :is="as" class="font-work-sans text-3xl font-medium text-neutral-900 md:text-4xl dark:text-white">
        <slot />
    </component>
</template>
