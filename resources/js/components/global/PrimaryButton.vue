<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    href?: string;
}>();

const classes =
    'pointer-events-auto relative flex items-center gap-2 rounded-full border border-neutral-200 bg-white/80 p-1.5 shadow-2xl backdrop-blur-xs dark:border-white/10 dark:bg-neutral-900/80';
</script>

<template>
    <Link v-if="href" :href="href" :class="classes">
        <span
            class="group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium text-neutral-500 transition-all duration-300 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white"
        >
            <slot />
        </span>
    </Link>
    <button v-else :class="classes">
        <span
            class="group flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium text-neutral-500 transition-all duration-300 hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 dark:text-white/50 dark:hover:bg-white/5 dark:hover:text-white"
        >
            <slot />
        </span>
    </button>
</template>
