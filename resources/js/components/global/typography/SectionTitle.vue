<script setup lang="ts">
withDefaults(
    defineProps<{
        as?: string;
    }>(),
    {
        as: 'h3',
    },
);
</script>

<template>
    <component :is="as" class="text-base font-medium text-neutral-900 dark:text-white">
        <slot />
    </component>
</template>
