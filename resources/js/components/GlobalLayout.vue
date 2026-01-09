<script setup lang="ts">
import CommandMenu from '@/components/CommandMenu.vue';
import GlobalContextMenu from '@/components/GlobalContextMenu.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <GlobalContextMenu>
        <slot />
        <CommandMenu />
        <Toaster />
    </GlobalContextMenu>
</template>
