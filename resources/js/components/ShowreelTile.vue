<script setup lang="ts">
import ShowreelDialog from '@/components/ShowreelDialog.vue';
import { useIntersectionObserver, usePreferredReducedMotion } from '@vueuse/core';
import { Play } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const isOpen = ref(false);
const loop = ref<HTMLVideoElement | null>(null);
const isVisible = ref(false);
const reducedMotion = usePreferredReducedMotion();

useIntersectionObserver(
    loop,
    ([entry]) => {
        isVisible.value = entry?.isIntersecting ?? false;
    },
    { threshold: 0.4 },
);

// The muted loop only runs while the tile is on screen and the dialog is closed; reduced motion keeps the poster.
watch([isVisible, reducedMotion, isOpen], () => {
    const video = loop.value;
    if (!video) return;
    if (isVisible.value && reducedMotion.value !== 'reduce' && !isOpen.value) {
        video.play().catch(() => {});
    } else {
        video.pause();
    }
});
</script>

<template>
    <!-- One root element, so the page can put its fade-up directive on the tile. -->
    <div class="col-span-1 md:col-span-12">
        <button
            type="button"
            class="glowing-card mobile-focus-glow group relative block w-full cursor-pointer overflow-visible rounded-2xl text-left"
            :aria-label="`${t('home.navigation.showreel')}: ${t('home.navigation.showreelPlay')}`"
            @click="isOpen = true"
        >
            <div class="glows"></div>
            <!-- Phones get the film at 16:9 with the caption below; wider screens get a 21:9 crop with the caption on top. -->
            <div
                class="glowing-card-content relative overflow-hidden rounded-2xl border border-neutral-200 bg-teal-black transition-all duration-500 group-hover:border-brand-400/50 group-hover:shadow-xl dark:border-white/10"
            >
                <div class="relative aspect-video overflow-hidden md:aspect-[21/9]">
                    <video
                        ref="loop"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                        poster="/video/showreel-loop-poster.webp"
                        muted
                        loop
                        playsinline
                        preload="none"
                        aria-hidden="true"
                    >
                        <source src="/video/showreel-loop.av1.mp4" type='video/mp4; codecs="av01.0.08M.08"' />
                        <source src="/video/showreel-loop.mp4" type="video/mp4" />
                    </video>
                    <div
                        class="absolute inset-0 hidden bg-gradient-to-t from-teal-black from-5% via-teal-black/75 via-30% to-transparent to-65% md:block"
                    ></div>
                </div>

                <div class="relative z-10 flex flex-col p-4 md:absolute md:inset-x-0 md:bottom-0 md:flex-row md:items-end md:justify-between md:p-6">
                    <div class="p-2">
                        <h3 class="mb-1 font-sans text-xl font-bold text-white md:text-2xl">{{ t('home.navigation.showreel') }}</h3>
                        <p class="max-w-md text-xs text-white/80">{{ t('home.navigation.showreelDescription') }}</p>
                    </div>
                    <span
                        class="mt-2 ml-2 inline-flex w-fit items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2.5 text-xs font-medium text-white backdrop-blur-md transition-colors duration-300 group-hover:border-brand-400/60 group-hover:bg-brand-400/20 md:mt-0 md:ml-0"
                    >
                        <Play class="size-3.5 fill-current" aria-hidden="true" />
                        {{ t('home.navigation.showreelPlay') }}
                        <span class="font-mono text-white/60 tabular-nums">1:00</span>
                    </span>
                </div>
            </div>
        </button>

        <ShowreelDialog v-model:open="isOpen" />
    </div>
</template>
