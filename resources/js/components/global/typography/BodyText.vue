<script setup lang="ts">
withDefaults(
    defineProps<{
        as?: string;
        size?: 'xs' | 'sm' | 'base';
    }>(),
    {
        as: 'p',
        size: 'sm',
    },
);
</script>

<template>
    <component
        :is="as"
        class="leading-relaxed text-neutral-600 dark:text-neutral-400"
        :class="{
            'text-xs': size === 'xs',
            'text-sm': size === 'sm',
            'text-base': size === 'base',
        }"
    >
        <slot />
    </component>
</template>
