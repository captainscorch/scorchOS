<script setup lang="ts">
import { useProjects } from '@/composables/useProjects';
import { router } from '@inertiajs/vue3';
import 'swiper/css';
import 'swiper/css/effect-coverflow';
import { EffectCoverflow, Mousewheel } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    currentSlug?: string;
}>();

const { projects: allProjects } = useProjects();

const projects = computed(() => {
    return allProjects.value.filter((p) => p.slug !== props.currentSlug);
});

const isDragging = ref(false);

const onSlideChange = () => {};

const onTouchStart = () => {
    isDragging.value = true;
};

const onTouchEnd = () => {
    isDragging.value = false;
};

const handleCardClick = (slug: string) => {
    router.visit(`/case-study/${slug}`);
};

const { t } = useI18n();
</script>

<template>
    <div class="relative flex w-full flex-col items-center justify-center overflow-hidden pt-16">
        <div class="px-6 text-center">
            <h2 class="scramble-trigger relative pb-4 font-work-sans text-3xl font-medium text-neutral-900 md:text-4xl dark:text-white">
                {{ t('portfolio.title') }}
            </h2>
            <p class="text-sm tracking-widest text-neutral-500 uppercase dark:text-white/30">{{ t('portfolio.subtitle') }}</p>
        </div>

        <div class="w-full max-w-5xl">
            <Swiper
                :modules="[EffectCoverflow, Mousewheel]"
                :effect="'coverflow'"
                :grabCursor="true"
                :initialSlide="1"
                :centeredSlides="true"
                :slidesPerView="'auto'"
                :coverflowEffect="{
                    rotate: 50,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: true,
                }"
                :pagination="true"
                :mousewheel="{
                    forceToAxis: true,
                    sensitivity: 1,
                    thresholdDelta: 5,
                    thresholdTime: 100,
                }"
                class="project-swiper"
                :class="{ 'is-dragging': isDragging }"
                @slideChange="onSlideChange"
                @touchStart="onTouchStart"
                @touchEnd="onTouchEnd"
                @sliderMove="onTouchStart"
                @transitionEnd="onTouchEnd"
            >
                <SwiperSlide
                    v-for="project in projects"
                    :key="project.id"
                    class="!flex !h-[300px] !w-[200px] flex-col items-center justify-center gap-2 md:!h-[440px] md:!w-[350px]"
                >
                    <div
                        class="group relative h-full w-full overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-500 hover:shadow-xl hover:shadow-neutral-900/5 dark:bg-neutral-900 dark:hover:shadow-neutral-100/5 touch:shadow-xl touch:shadow-neutral-900/5 dark:touch:shadow-neutral-100/5"
                        @click="handleCardClick(project.slug)"
                    >
                        <img
                            :src="project.image"
                            :alt="project.title"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />

                        <!-- Category Badge(s) (Top Left) -->
                        <div
                            class="absolute top-4 left-4 z-20 flex flex-wrap gap-2 pr-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100 touch:hidden"
                        >
                            <span
                                v-for="(category, index) in Array.isArray(project.category) ? project.category : [project.category]"
                                :key="index"
                                class="cursor-default rounded-full border border-neutral-200 bg-neutral-100 px-3 py-1.5 text-[10px] text-neutral-600 backdrop-blur-xs transition-colors hover:border-neutral-300 hover:text-neutral-900 dark:border-white/10 dark:bg-neutral-900/50 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-white"
                            >
                                {{ category }}
                            </span>
                        </div>

                        <!-- Overlay Text -->
                        <div
                            class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-transparent to-transparent p-6 opacity-0 transition-opacity duration-500 group-hover:opacity-100 touch:hidden"
                        >
                            <div class="translate-y-4 transform transition-transform duration-500 group-hover:translate-y-0">
                                <h3 class="mb-1 font-work-sans text-xl font-bold text-white md:text-2xl">{{ project.client }}</h3>
                                <p class="line-clamp-2 font-work-sans text-xs text-white/80 md:text-sm">{{ project.title }}</p>
                            </div>
                        </div>
                    </div>
                    <h3 class="mobile-title font-work-sans text-base font-medium text-neutral-900 md:hidden dark:text-white">{{ project.client }}</h3>
                </SwiperSlide>
            </Swiper>
        </div>
    </div>
</template>

<style scoped>
.project-swiper {
    width: 100%;
    padding-top: 50px;
    padding-bottom: 50px;
    overflow: hidden !important;
}

/* Hide Swiper's default slide shadows when we're managing transitions manually or dragging */
:deep(.swiper-slide-shadow-left),
:deep(.swiper-slide-shadow-right) {
    display: none;
}

:deep(.swiper-slide) {
    background-position: center;
    background-size: cover;
    filter: blur(2px);
    opacity: 0.5;
    transition: all 0.5s ease-in-out;
}

/* When dragging, reset transforms to avoid jumping/overlay issues */
.is-dragging :deep(.swiper-slide > div) {
    transform: translateX(0) !important;
    transition: transform 0.5s ease-in-out;
}

.is-dragging :deep(.swiper-slide-active > div) {
    transform: scale(1.1) !important;
    transition: transform 0.5s ease-in-out;
}

.is-dragging :deep(.mobile-title) {
    transform: translateY(12px) !important;
    transition: transform 0.5s ease-in-out;
}

:deep(.swiper-slide.swiper-slide-prev > div) {
    transform: scale(0.9) translateX(80px);
    transition: all 0.5s ease-in-out;
}

:deep(.swiper-slide.swiper-slide-next > div) {
    transform: scale(0.9) translateX(-80px);
    transition: all 0.5s ease-in-out;
}

:deep(.mobile-title) {
    transform: translateY(0) !important;
    transition: transform 0.5s ease-in-out;
}

@media (min-width: 768px) {
    :deep(.swiper-slide.swiper-slide-prev > div) {
        transform: scale(0.9) translateX(200px);
    }

    :deep(.swiper-slide.swiper-slide-next > div) {
        transform: scale(0.9) translateX(-200px);
    }
}

:deep(.swiper-slide-active) {
    filter: blur(0);
    opacity: 1;
    z-index: 10;
}

:deep(.swiper-slide-active > div) {
    transform: scale(1) translateX(0);
    transition: all 0.5s ease-in-out;
}
</style>
