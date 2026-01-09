<script setup lang="ts">
withDefaults(
    defineProps<{
        as?: string;
        size?: 'xs' | 'sm';
        weight?: 'normal' | 'medium' | 'semibold';
    }>(),
    {
        as: 'span',
        size: 'sm',
        weight: 'normal',
    },
);
</script>

<template>
    <component
        :is="as"
        class="tracking-widest text-neutral-500 uppercase dark:text-white/30"
        :class="{
            'text-xs': size === 'xs',
            'text-sm': size === 'sm',
            'font-normal': weight === 'normal',
            'font-medium': weight === 'medium',
            'font-semibold': weight === 'semibold',
        }"
    >
        <slot />
    </component>
</template>
