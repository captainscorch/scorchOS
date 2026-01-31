<script setup lang="ts">
import { ContextMenu, ContextMenuContent, ContextMenuItem, ContextMenuSeparator, ContextMenuTrigger } from '@/components/ui/context-menu';
import { toast } from 'vue-sonner';

const copyToClipboard = async (text: string, type: 'Mark' | 'URL') => {
    try {
        await navigator.clipboard.writeText(text);
        toast.success(`${type} copied to clipboard`);
    } catch {
        toast.error('Failed to copy to clipboard');
    }
};

const handleBack = () => {
    window.history.back();
};

const handleForward = () => {
    window.history.forward();
};

const handleReload = () => {
    window.location.reload();
};

const handleCopyMark = () => {
    const markSvg = `<svg version="1.1" id="captainscorch_logo" xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 56.2 46.5" xml:space="preserve"><style>.white{fill:#fff}</style><g id="XMLID_536_"><path id="XMLID_547_" class="white" d="M13.2 32.1c0 3.2 2.6 5.7 5.7 5.7h18.3c3.2 0 5.7-2.6 5.7-5.7V23H36v4c0 2.2-1.8 4-4 4H13.2v1.1z"/><path id="XMLID_541_" class="white" d="M42.9 13.8c0-3.1-2.6-5.7-5.7-5.7H19c-3.2 0-5.7 2.6-5.7 5.7v9.1h6.9v-4c0-2.2 1.8-4 4-4H43v-1.1z"/><path id="XMLID_540_" class="white" d="M25.8 19.7c-.5 0-.9.4-.9.9v4.6c0 .5.4.9.9.9h4.6c.5 0 .9-.4.9-.9v-4.6c0-.5-.4-.9-.9-.9h-4.6z"/><path id="XMLID_539_" class="white" d="M47.9 33.8c0 5.1-4.2 9.3-9.3 9.3H17.5c-5.1 0-9.3-4.2-9.3-9.3V30.9H5.6v2.9c0 6.6 5.3 11.9 11.9 11.9h21.2c6.6 0 11.9-5.3 11.9-11.9V22.9H48v10.9z"/><path id="XMLID_537_" class="white" d="M38.7.7H17.5C10.9.7 5.6 6 5.6 12.6v10.3h2.6V12.6c0-5.1 4.2-9.3 9.3-9.3h21.2c5.1 0 9.3 4.2 9.3 9.3V15h2.6v-2.3c0-6.6-5.4-12-11.9-12z"/></g></svg>`;
    copyToClipboard(markSvg, 'Mark');
};

const handleCopyUrl = () => {
    copyToClipboard(window.location.href, 'URL');
};
</script>

<template>
    <ContextMenu>
        <ContextMenuTrigger class="block min-h-screen w-full">
            <slot />
        </ContextMenuTrigger>
        <ContextMenuContent class="w-64">
            <ContextMenuItem @select="handleBack" class="group/item gap-2">
                <lord-icon
                    src="/icons/system-regular-507-arrow-left-morph-select.json"
                    trigger="hover"
                    target=".group\/item"
                    style="width: 18px; height: 18px"
                    class="current-color mr-2 h-4 w-4 opacity-60 group-hover/item:opacity-100"
                >
                </lord-icon>
                <span>Back</span>
                <div class="ml-auto flex items-center gap-1">
                    <div
                        class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-sm text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                    >
                        <span>⌘</span>
                    </div>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">+</span>
                    <div
                        class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-[10px] text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                    >
                        <span>←</span>
                    </div>
                </div>
            </ContextMenuItem>
            <ContextMenuItem @select="handleForward" class="group/item gap-2">
                <lord-icon
                    src="/icons/system-regular-506-arrow-right-morph-select.json"
                    trigger="hover"
                    target=".group\/item"
                    style="width: 18px; height: 18px"
                    class="current-color mr-2 h-4 w-4 opacity-60 group-hover/item:opacity-100"
                >
                </lord-icon>
                <span>Forward</span>
                <div class="ml-auto flex items-center gap-1">
                    <div
                        class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-sm text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                    >
                        <span>⌘</span>
                    </div>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">+</span>
                    <div
                        class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-[10px] text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                    >
                        <span>→</span>
                    </div>
                </div>
            </ContextMenuItem>
            <ContextMenuItem @select="handleReload" class="group/item gap-2">
                <lord-icon
                    src="/icons/system-regular-18-autorenew-hover-autorenew.json"
                    trigger="hover"
                    target=".group\/item"
                    style="width: 18px; height: 18px"
                    class="current-color mr-2 h-4 w-4 opacity-60 group-hover/item:opacity-100"
                >
                </lord-icon>
                <span>Reload</span>
                <div class="ml-auto flex items-center gap-1">
                    <div
                        class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-sm text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                    >
                        <span>⌘</span>
                    </div>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">+</span>
                    <div
                        class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-[10px] text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                    >
                        <span>R</span>
                    </div>
                </div>
            </ContextMenuItem>
            <ContextMenuSeparator />
            <ContextMenuItem @select="handleCopyMark" class="group/item gap-2">
                <svg
                    version="1.1"
                    id="Ebene_1"
                    xmlns="http://www.w3.org/2000/svg"
                    x="0"
                    y="0"
                    viewBox="0 0 56.2 46.5"
                    xml:space="preserve"
                    class="mr-2 h-4 w-4 opacity-60 group-hover/item:opacity-100"
                >
                    <g id="XMLID_536_">
                        <path
                            id="XMLID_547_"
                            fill="currentColor"
                            d="M13.2 32.1c0 3.2 2.6 5.7 5.7 5.7h18.3c3.2 0 5.7-2.6 5.7-5.7V23H36v4c0 2.2-1.8 4-4 4H13.2v1.1z"
                        />
                        <path
                            id="XMLID_541_"
                            fill="currentColor"
                            d="M42.9 13.8c0-3.1-2.6-5.7-5.7-5.7H19c-3.2 0-5.7 2.6-5.7 5.7v9.1h6.9v-4c0-2.2 1.8-4 4-4H43v-1.1z"
                        />
                        <path
                            id="XMLID_540_"
                            fill="currentColor"
                            d="M25.8 19.7c-.5 0-.9.4-.9.9v4.6c0 .5.4.9.9.9h4.6c.5 0 .9-.4.9-.9v-4.6c0-.5-.4-.9-.9-.9h-4.6z"
                        />
                        <path
                            id="XMLID_539_"
                            fill="currentColor"
                            d="M47.9 33.8c0 5.1-4.2 9.3-9.3 9.3H17.5c-5.1 0-9.3-4.2-9.3-9.3V30.9H5.6v2.9c0 6.6 5.3 11.9 11.9 11.9h21.2c6.6 0 11.9-5.3 11.9-11.9V22.9H48v10.9z"
                        />
                        <path
                            id="XMLID_537_"
                            fill="currentColor"
                            d="M38.7.7H17.5C10.9.7 5.6 6 5.6 12.6v10.3h2.6V12.6c0-5.1 4.2-9.3 9.3-9.3h21.2c5.1 0 9.3 4.2 9.3 9.3V15h2.6v-2.3c0-6.6-5.4-12-11.9-12z"
                        />
                    </g>
                </svg>
                <span>Copy Mark as SVG</span>
            </ContextMenuItem>
            <ContextMenuItem @select="handleCopyUrl" class="group/item gap-2">
                <lord-icon
                    src="/icons/system-regular-98-link-hover-link.json"
                    trigger="hover"
                    target=".group\/item"
                    style="width: 18px; height: 18px"
                    class="current-color mr-2 h-4 w-4 opacity-60 group-hover/item:opacity-100"
                >
                </lord-icon>
                <span>Copy URL to Clipboard</span>
            </ContextMenuItem>
        </ContextMenuContent>
    </ContextMenu>
</template>
