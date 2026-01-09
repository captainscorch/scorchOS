<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';
import { faSearch } from '@fortawesome/sharp-light-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faSearch);

defineEmits(['click']);
</script>

<template>
    <button
        @click="$emit('click')"
        class="group flex size-10 items-center justify-center rounded-full border border-neutral-200 bg-white/80 px-2 font-medium text-neutral-500 shadow-sm backdrop-blur-md transition-all hover:cursor-pointer hover:bg-neutral-100 hover:text-neutral-900 md:size-auto md:px-3 md:py-1.5 dark:border-white/10 dark:bg-neutral-900/80 dark:text-white/50 dark:hover:bg-white/10 dark:hover:text-white"
    >
        <div class="flex items-center gap-1">
            <FontAwesomeIcon icon="fa-sharp fa-light fa-search" class="text-sm md:mr-1 md:text-xs" />
            <div
                class="hidden size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-sm text-neutral-500 group-hover:text-neutral-900 md:flex dark:border-white/10 dark:bg-neutral-800/80 dark:text-white/70 dark:group-hover:text-white"
            >
                <span>⌘</span>
            </div>
            <div
                class="hidden size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 font-mono text-[10px] text-neutral-500 group-hover:text-neutral-900 md:flex dark:border-white/10 dark:bg-neutral-800/80 dark:text-white/70 dark:group-hover:text-white"
            >
                <span>K</span>
            </div>
        </div>
    </button>
</template>
