<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { useI18n } from 'vue-i18n';

const open = defineModel<boolean>('open', { default: false });

const { t } = useI18n();
</script>

<template>
    <Dialog v-model:open="open">
        <!-- The content only mounts while open, so the video starts with sound on the click that opened it and stops on close. -->
        <DialogContent
            class="aspect-video w-full max-w-[min(92vw,1600px)] overflow-hidden border-white/10 bg-black p-0 text-white sm:max-w-[min(92vw,1600px)]"
        >
            <DialogTitle class="sr-only">{{ t('showreel.title') }}</DialogTitle>
            <DialogDescription class="sr-only">{{ t('showreel.description') }}</DialogDescription>
            <video class="h-full w-full" poster="/video/showreel-poster.webp" controls autoplay playsinline preload="auto">
                <source src="/video/showreel.av1.mp4" type='video/mp4; codecs="av01.0.08M.08"' />
                <source src="/video/showreel.mp4" type="video/mp4" />
            </video>
        </DialogContent>
    </Dialog>
</template>
