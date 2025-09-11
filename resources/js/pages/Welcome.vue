<script setup lang="ts">
import SkillsModal from '@/components/SkillsModal.vue';
import { Switch } from '@/components/ui/switch';
import { setLocale } from '@/i18n';
import { library } from '@fortawesome/fontawesome-svg-core';
import {
    faBriefcase,
    faBuilding,
    faClockEight,
    faCode,
    faCodeBranch,
    faLocationArrow,
    faStarfighter,
    faUserBountyHunter,
} from '@fortawesome/sharp-light-svg-icons';
import { faMoon, faSun } from '@fortawesome/sharp-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { onMounted, onUnmounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

library.add(faSun, faMoon, faLocationArrow, faBriefcase, faBuilding, faClockEight, faUserBountyHunter, faCode, faCodeBranch, faStarfighter);

const { t, locale } = useI18n();

const isDark = useDark({
    selector: 'html',
    attribute: 'class',
    valueDark: 'dark',
    valueLight: '',
});
const toggleTheme = useToggle(isDark);

const switchLanguage = (lang: string) => {
    setLocale(lang);
};

const pastWorkLinks = [
    { url: 'https://way.food', label: 'way.food', image: '/img/projects/wayfood-thumbnail.webp', alt: 'way.food' },
    { url: 'https://icemaenner.com', label: 'Icemänner', image: '/img/projects/icemaenner-thumbnail.webp', alt: 'Icemänner' },
    { url: 'https://www.hubner-group.com', label: 'HÜBNER Group', image: '/img/projects/hubner-group-thumbnail.webp', alt: 'HÜBNER Group' },
    { url: 'https://theloz.co', label: 'theloz.co', image: '/img/projects/loz-thumbnail.webp', alt: 'The Loz' },
    { url: 'https://cale-design.com', label: 'cale design', image: '/img/projects/caledesign-thumbnail.webp', alt: 'cale design' },
    {
        url: 'https://tamplenplasticsurgery.com',
        label: 'Tamplen',
        image: '/img/projects/tamplenplasticsurgery-thumbnail.webp',
        alt: 'Tamplen Plastic Surgery',
    },
    { url: 'https://pawsomepet.co', label: 'Pawsome', image: '/img/projects/pawsome-thumbnail.webp', alt: 'Pawsome' },
    { url: 'https://dixxhardseltzer.com', label: 'Dixx', image: '/img/projects/dixxhardseltzer-thumbnail.webp', alt: 'Dixx Hard Seltzer' },
];

// Clock functionality
const currentTime = ref('');
const timezone = ref('');
let clockInterval: number | null = null;

const updateClock = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString('en-US', {
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
    timezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

const isSkillsModalOpen = ref(false);

const openSkillsModal = () => {
    isSkillsModalOpen.value = true;
};

const closeSkillsModal = () => {
    isSkillsModalOpen.value = false;
};

const isCardOpen = ref(false);

const toggleCard = () => {
    isCardOpen.value = !isCardOpen.value;
};

const closeCard = () => {
    isCardOpen.value = false;
};

// Mobile detection
const isMobile = ref(false);

const checkMobile = () => {
    isMobile.value = window.innerWidth <= 767;
};

// Mobile thumbnail preview
const activeThumbnailIndex = ref<number | null>(null);
const activeSpecialThumbnail = ref<string | null>(null);

const toggleThumbnail = (index: number) => {
    if (isMobile.value && activeThumbnailIndex.value === index) {
        const link = pastWorkLinks[index];
        if (link) {
            window.open(link.url, '_blank');
        }
        return;
    }

    if (activeThumbnailIndex.value === index) {
        activeThumbnailIndex.value = null;
    } else {
        activeThumbnailIndex.value = index;
        activeSpecialThumbnail.value = null;
    }
};

const toggleSpecialThumbnail = (type: string) => {
    if (isMobile.value && activeSpecialThumbnail.value === type) {
        const url =
            type === 'unlimited'
                ? 'https://unlimited.studio/?utm_campaign=watermark&utm_medium=website&utm_source=captainscor.ch'
                : 'https://lyftd.app/?utm_campaign=watermark&utm_medium=website&utm_source=captainscor.ch';
        window.open(url, '_blank');
        return;
    }

    if (activeSpecialThumbnail.value === type) {
        activeSpecialThumbnail.value = null;
    } else {
        activeSpecialThumbnail.value = type;
        activeThumbnailIndex.value = null;
    }
};

const closeThumbnail = () => {
    activeThumbnailIndex.value = null;
    activeSpecialThumbnail.value = null;
};

const profileRef = ref<HTMLElement | null>(null);

const handleClickOutside = (event: Event) => {
    if (profileRef.value && !profileRef.value.contains(event.target as Node)) {
        closeCard();
    }

    // Only close thumbnails if clicking outside of thumbnail links
    const target = event.target as HTMLElement;
    if (!target.closest('.link-with-thumbnail')) {
        closeThumbnail();
    }
};

// Starfighter animation
const isStarfighterFlying = ref(false);
const isStarfighterCooldown = ref(false);

const triggerStarfighterAnimation = () => {
    if (isStarfighterFlying.value || isStarfighterCooldown.value) return;

    isStarfighterFlying.value = true;

    setTimeout(() => {
        isStarfighterFlying.value = false;
        isStarfighterCooldown.value = true;

        setTimeout(() => {
            isStarfighterCooldown.value = false;
        }, 3000);
    }, 1000);
};

onMounted(() => {
    updateClock();
    clockInterval = setInterval(updateClock, 1000);
    document.addEventListener('click', handleClickOutside);

    checkMobile();
    window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
    if (clockInterval) {
        clearInterval(clockInterval);
    }
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('resize', checkMobile);
});
</script>

<template>
    <Head title="Daniel Schmier" />
    <div
        class="mx-auto h-[calc(100vH+100px)] min-h-dvh [max-width:1580px] px-8 md:h-full md:px-20 lg:px-40 [@media(min-width:1600px)]:max-w-[1600px] [@media(min-width:1920px)]:max-w-[1920px]"
    >
        <header class="flex h-[360px] min-h-[50dvH] items-start justify-start py-12 md:items-center md:pt-36">
            <h1 class="font-teal-black font-work-sans text-3xl leading-[130%] font-bold md:text-[clamp(2.25rem,5vw,5.8rem)] md:leading-[150%]">
                {{ t('home.tagline.prefix') }}
                <a
                    class="link-with-thumbnail growing-border z-10 inline-flex items-center py-1 hover:cursor-ne-resize"
                    :class="{ 'is-active': activeSpecialThumbnail === 'unlimited' }"
                    href="https://unlimited.studio/?utm_campaign=watermark&utm_medium=website&utm_source=captainscor.ch"
                    target="_blank"
                    @click.prevent="toggleSpecialThumbnail('unlimited')"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 1105.14 95.52"
                        class="svg-logo svg-logo--nudge-down h-[0.85em] w-auto fill-teal-black dark:fill-off-white"
                    >
                        <g>
                            <g id="content">
                                <path
                                    style="fill: #fe3312"
                                    d="M1089.21,43.86V63.62a16,16,0,0,1-16,16h-56.76a16,16,0,0,1-16-16v-3.8a16,16,0,0,1,16-16h72.72m15.93-15.93h-88.65A31.89,31.89,0,0,0,984.6,59.82v3.8a31.9,31.9,0,0,0,31.89,31.9h56.76a31.9,31.9,0,0,0,31.89-31.9V27.93Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M63,37.68V92.09c0,.66-3.29,2.11-7,2.11-4,0-8.3-1.45-10.41-7a22.64,22.64,0,0,1-.66-3.55c-4.48,6.72-11.06,11.72-21.21,11.72C7,95.38,0,83.66,0,68.77V37.68c0-6.72,3.43-9,8-9h2.11c4.88,0,8.3,2.11,8.3,9V66c0,8.7,4.48,13.84,12.12,13.84a15.64,15.64,0,0,0,14-8.43V37.68c0-6.72,3.29-9,8-9h2C59.55,28.72,63,30.83,63,37.68Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M140,54.41v31c0,6.72-3.56,8.83-8.3,8.83h-2c-4.87,0-8.3-2-8.3-8.83V57.31c0-8.56-4.35-13.83-11.85-13.83a15.52,15.52,0,0,0-14.1,8.43V85.37c0,6.72-3.43,8.83-8.17,8.83h-2c-5,0-8.43-2-8.43-8.83V30.83c0-.66,3.29-2.11,7-2.11,5,0,10,2.5,11.07,10.8,4.61-6.85,11.33-11.59,21.21-11.59C132.92,27.93,140,39.79,140,54.41Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M153.73,85.37V4.22c0-.53,3.3-2.11,7-2.11,5.53,0,11.59,2.9,11.59,14.89V85.37c0,6.72-3.42,8.83-8.17,8.83h-2C157.29,94.2,153.73,92.22,153.73,85.37Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M196.55,0C203,0,207,3.69,207,9.62s-3.82,9.62-10.41,9.62c-6.32,0-10.41-3.69-10.41-9.62S190.09,0,196.55,0ZM187.2,37.68c0-6.72,3.55-9,8.3-9h2c4.88,0,8.3,2.11,8.3,9V85.37c0,6.72-3.42,8.83-8.3,8.83h-1.84c-5,0-8.43-2-8.43-8.83Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M323.42,53.49V85.37c0,6.72-3.43,8.83-8.17,8.83h-2c-5,0-8.43-2-8.43-8.83v-29c0-8.43-3.69-12.91-11.2-12.91-5.27,0-10,3.29-12.65,7.9v34c0,6.72-3.42,8.83-8.16,8.83h-2c-4.87,0-8.43-2-8.43-8.83V56.78c0-8.82-3.69-13.3-11.07-13.3-5.27,0-10,3.29-12.64,7.9v34c0,6.72-3.43,8.83-8.17,8.83h-2c-5,0-8.43-2-8.43-8.83V30.83c0-.66,3.29-2.11,7-2.11,4.61,0,9.62,2,11.07,10.54,4.34-6.45,10.54-11.33,19.89-11.33,10.67,0,17.39,5.14,20.55,13.18,4.35-7.38,10.94-13.18,21.61-13.18C316.57,27.93,323.42,38.34,323.42,53.49Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M346.47,0c6.45,0,10.4,3.69,10.4,9.62s-3.82,9.62-10.4,9.62c-6.33,0-10.41-3.69-10.41-9.62S340,0,346.47,0Zm-9.36,37.68c0-6.72,3.56-9,8.3-9h2c4.87,0,8.3,2.11,8.3,9V85.37c0,6.72-3.43,8.83-8.3,8.83h-1.85c-5,0-8.43-2-8.43-8.83Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M375.71,73.38V43.87h-3.29c-5.27,0-7.64-2.37-7.64-6.59V36.23c0-4.35,2.37-6.85,7.51-6.85h3.42V21.21c0-6.06,3-9,8.3-9h1.71c4.88,0,8.17,2.11,8.17,9v8.17h11.2c5.27,0,7.51,2.24,7.51,6.45V37c0,4.48-2.37,6.85-7.64,6.85H393.63v27.4c0,6.33,2,9.49,7.11,9.49a9.42,9.42,0,0,0,8-4.35c.66-.26,4.61,2.37,4.61,7.25a9.39,9.39,0,0,1-3.16,7.11c-2.9,2.9-7.64,4.74-13.83,4.74C382.3,95.51,375.71,88,375.71,73.38Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M451.85,27.93c19.11,0,32,13.31,32,32.15v1.18c-.13,3.16-1.06,4.88-4.48,4.88H434.86l.4,2.1c2,8.44,8.95,13.84,18.44,13.84s15.15-4.22,18.05-9.36c.13-.26,9.09.53,9.09,7.91,0,3.56-2.5,7.24-7.12,10s-11.59,4.87-20.55,4.87c-20.94,0-34.64-13-34.64-33.46C418.53,42.42,432.49,27.93,451.85,27.93Zm-.13,12.78c-8.82,0-15.41,6.32-16.6,15.28h31.62C466.22,46.64,460.55,40.71,451.72,40.71Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M491,61.66c0-20.42,12.12-33.73,29.9-33.73,9.23,0,16.61,4.22,20.29,9.75V4.22c0-.53,3.3-2.11,7-2.11,5.54,0,11.6,2.9,11.6,14.89V85.37c0,6.72-3.29,8.83-8.17,8.83h-1.84c-4.22,0-7.38-1.45-8-6.46-.13-1.45-.26-2.9-.26-4.48-4.35,7.51-11.47,12.25-21,12.25C503.1,95.51,491,81.81,491,61.66Zm50.32-8.57c-2.76-6.32-8-10.27-15.94-10.27-10,0-16.73,7.24-16.73,18.84,0,11.2,6.85,19,16.47,19,8.83,0,16.2-6.2,16.2-15.42Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M601,81c0-4.74,5.14-7.77,5.66-7.38,4.35,4.88,11.73,8.7,21.21,8.7,7.25,0,11.2-2.5,11.2-6.59,0-5-5.14-5.92-13.7-7.77-8.83-1.84-22.53-4.74-22.53-19.23,0-12.52,10.15-20.82,26.74-20.82,13.05,0,21.08,3.56,24.38,8.56a9.54,9.54,0,0,1,1.58,5.27c0,4.88-5.67,8.17-5.93,7.78a25.52,25.52,0,0,0-18.84-8.7c-7.11,0-10.93,2.64-10.93,6.72,0,4.61,5,5.53,13.7,7.51,9.09,2,23.58,4.87,23.58,19.36,0,12.92-10.28,20.69-28.32,20.69-10.15,0-17.53-2-22.14-4.87C603,87.74,601,84.58,601,81Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M673.17,73.38V43.87h-3.29c-5.27,0-7.64-2.37-7.64-6.59V36.23c0-4.35,2.37-6.85,7.51-6.85h3.42V21.21c0-6.06,3-9,8.3-9h1.71c4.88,0,8.17,2.11,8.17,9v8.17h11.2c5.27,0,7.51,2.24,7.51,6.45V37c0,4.48-2.37,6.85-7.64,6.85H691.09v27.4c0,6.33,2,9.49,7.11,9.49a9.42,9.42,0,0,0,8-4.35c.66-.26,4.61,2.37,4.61,7.25a9.39,9.39,0,0,1-3.16,7.11c-2.9,2.9-7.64,4.74-13.83,4.74C679.76,95.51,673.17,88,673.17,73.38Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M781.07,37.68V92.09c0,.66-3.29,2.11-7,2.11-4,0-8.3-1.45-10.41-7a20.54,20.54,0,0,1-.66-3.55c-4.48,6.72-11.07,11.72-21.21,11.72-16.73,0-23.71-11.72-23.71-26.61V37.68c0-6.72,3.42-9,8-9h2.11c4.87,0,8.3,2.11,8.3,9V66c0,8.7,4.48,13.84,12.12,13.84a15.63,15.63,0,0,0,14-8.43V37.68c0-6.72,3.3-9,8-9h2C777.64,28.72,781.07,30.83,781.07,37.68Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M791.73,61.66c0-20.42,12.12-33.73,29.91-33.73,9.22,0,16.6,4.22,20.29,9.75V4.22c0-.53,3.29-2.11,7-2.11C854.44,2.11,860.5,5,860.5,17V85.37c0,6.72-3.29,8.83-8.16,8.83h-1.85c-4.21,0-7.38-1.45-8-6.46-.14-1.45-.27-2.9-.27-4.48-4.35,7.51-11.46,12.25-20.94,12.25C803.85,95.51,791.73,81.81,791.73,61.66Zm50.33-8.57c-2.77-6.32-8-10.27-15.94-10.27-10,0-16.73,7.24-16.73,18.84,0,11.2,6.85,19,16.46,19,8.83,0,16.21-6.2,16.21-15.42Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M884.74,0c6.45,0,10.41,3.69,10.41,9.62s-3.82,9.62-10.41,9.62c-6.33,0-10.41-3.69-10.41-9.62S878.28,0,884.74,0Zm-9.35,37.68c0-6.72,3.55-9,8.3-9h2c4.88,0,8.3,2.11,8.3,9V85.37c0,6.72-3.42,8.83-8.3,8.83h-1.84c-5,0-8.43-2-8.43-8.83Z"
                                />
                                <path
                                    class="cls-2"
                                    d="M939.41,27.93c20.29,0,34.12,13.83,34.12,33.86s-14,33.72-34.38,33.72S905,81.81,905,61.53C905,41.76,919,27.93,939.41,27.93Zm-.13,14.49c-10.41,0-17.92,8-17.92,19.11s7.51,19.36,17.92,19.36,17.79-8.17,17.79-19.1C957.07,50.46,949.69,42.42,939.28,42.42Z"
                                />
                                <path
                                    style="fill: #fe3312"
                                    d="M571.42,85.2c0-5.18,2.6-7.88,7.89-7.88h2c5.42,0,8,2.7,8,7.88v1.64c0,5.18-2.7,7.88-8,7.88h-2c-5.18,0-7.89-2.7-7.89-7.88Z"
                                />
                            </g>
                        </g>
                    </svg>
                    <div class="thumbnail-preview-wrapper large thumb-below-mobile" :class="{ 'is-active': activeSpecialThumbnail === 'unlimited' }">
                        <img
                            class="thumbnail-preview"
                            src="/img/projects/unlimitedstudio-thumbnail.webp"
                            alt="unlimited.studio"
                            width="300"
                            height="300"
                        />
                    </div>
                </a>
                {{ t('home.tagline.middle') }}
                <a
                    class="link-with-thumbnail growing-border z-5 inline-flex items-center py-1 hover:cursor-ne-resize"
                    :class="{ 'is-active': activeSpecialThumbnail === 'lyftd' }"
                    href="https://lyftd.app/?utm_campaign=watermark&utm_medium=website&utm_source=captainscor.ch"
                    target="_blank"
                    @click.prevent="toggleSpecialThumbnail('lyftd')"
                >
                    <svg
                        class="svg-logo h-[0.85em] w-auto fill-teal-black dark:fill-off-white"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 350.64 163.54"
                    >
                        <g>
                            <g>
                                <path
                                    d="M319.44,16.75l-6.65,29.39c-4.53-4.6-10.59-7.27-17.76-7.27-19.29,0-37.54,11.62-44.64,43.25-7.52,33.62,5.99,43.32,25.28,43.32,7.17,0,14.4-2.71,21-7.27l-1.28,5.67h31.2l24.05-107.08h-31.2ZM303.69,83.5c-2.28,10.23-9.95,18.47-17.15,18.47s-12.09-4.1-8.88-18.47c3.03-13.51,9.95-18.47,17.15-18.47s11.16,8.27,8.88,18.47Z"
                                />
                                <path
                                    d="M231.69,88.06c1.75-7.56,3.53-15.05,5.35-22.35h20.11l5.74-25.63h-19.22l5.13-22.96h-23.17l-14.76,22.96h-27.63s0-.04.03-.11l.43-2.03c.43-1.43,1.07-2.78,1.89-4.1.07-.11.18-.25.29-.36.25-.43.61-.86.93-1.28.64-.71,1.35-1.39,2.14-1.96.25-.21.54-.39.82-.61.32-.21.57-.39.86-.53.39-.21.85-.43,1.28-.61.43-.21.93-.36,1.39-.5.07,0,.18-.04.25-.07h.07q.07-.04.14-.04c.32-.07.71-.14,1.07-.18.28-.04.57-.07.85-.07h.04c.11-.04.21-.04.28-.04,4.35.14,6.6,2.53,6.6,2.53l16.97-24.85c-.46-.36-1.07-.71-1.85-1.11v-.04c-3.74-2.07-9.73-4.14-18.79-4.14-.57,0-1.14.04-1.75.04h-.14c-1.53.07-3.17.18-4.92.36-1,.11-2,.25-2.96.39h-.07c-9.63,1.46-17.11,5.13-22.92,10.66-.29.25-.57.53-.82.78-.43.43-.86.89-1.28,1.35-.03.04-.14.11-.21.21-.28.32-.61.64-.89.93-.11.14-.25.32-.36.46-4.88,5.74-8.34,13.33-10.59,22.96l-.43,1.93h-10.16v-.04h-31.91v.04c-3.28,15.87-11.7,50.56-21.36,50.56s-1.53-34.73,2.75-50.59h-32.55l-6.2,27.81s-8.81,36.74,4.68,46.82c5.86,4.91,15.74,4.2,30.97-4.62-2.6,4.88-5.32,9.22-8.19,12.88,0,.04-.03.07-.03.07-.68.89-1.39,1.71-2.1,2.53t-.07.07c-5.92,5.81-12.62,9.41-20.14,9.41-9.88,0-16.9-2.53-21.46-7.74-7.88-9.09-7.63-25.42-6.88-30.88l.14-1.35c1.18-21.78,7.09-64.1,21.61-95.05h-30.63C4.19,40.61.98,86.24.59,92.95c-.75,5.67-3.28,33.34,13.55,52.87,6.52,7.59,18.47,16.51,38.86,17.61,1.39.07,2.78.11,4.24.11,20.71,0,36.4-11.2,48.2-26.42.64-.89,1.32-1.78,1.96-2.67,1.03-1.46,2.07-2.92,3.03-4.42.32-.5.68-1,.96-1.5.64-1,1.25-2,1.85-3.03.61-1,1.21-2.03,1.75-3.07,1.14-2.07,2.25-4.17,3.31-6.31.04-.04.07-.07.07-.11.93-1.85,1.78-3.74,2.64-5.63.85-1.93,1.68-3.85,2.46-5.78,3.32-7.95,5.92-15.79,7.88-22.82.39-1.25.71-2.42,1.03-3.6.29-1.07.57-2.14.86-3.17.18-.64.36-1.28.5-1.93.68-2.5,1.28-4.96,1.85-7.38h9.66l-6.95,31.09-2.1,8.91c-.71,3.03-1.28,5.53-1.6,7.63l-2.28,10.09h31.62l5.45-24.39,7.81-33.34h28.06c-1.82,8.81-3.64,17.9-5.99,28.27-.04.18-.11.36-.14.5-.07.25-.07.46-.14.68-.25,1.11-.53,2.28-.79,3.42-4.03,17.26,4.42,26.42,23.75,26.42h1.35c5.49,0,10.77-.61,15.54-1.46h.11c1.35-.29,2.67-.53,3.96-.86l5.88-26.28c-8.66,2.46-19.25,3.35-17.11-8.34Z"
                                />
                            </g>
                        </g>
                    </svg>
                    <div class="thumbnail-preview-wrapper large thumb-below-mobile" :class="{ 'is-active': activeSpecialThumbnail === 'lyftd' }">
                        <img class="thumbnail-preview" src="/img/projects/lyftd-thumbnail.webp" alt="lyftd" width="300" height="300" />
                    </div> </a
                >{{ t('home.tagline.suffix') }}
            </h1>
        </header>
        <section class="flex h-[390px] min-h-[50dvH] flex-col justify-end gap-y-12 md:gap-y-4 [@media(max-height:680px)]:gap-y-4">
            <div class="flex flex-col justify-between gap-x-8 gap-y-12 py-4 md:flex-row">
                <div class="flex flex-col gap-2">
                    <div ref="profileRef" class="group relative">
                        <a
                            href="#"
                            tabindex="0"
                            class="w-fit border-b border-b-teal-black/50 text-left leading-[135%] transition-all hover:cursor-pointer hover:border-b-brand focus:border-b-brand md:min-w-fit dark:border-b-off-white/50 dark:hover:border-b-brand dark:focus:border-b-brand [@media(max-height:680px)]:min-w-fit"
                            @click.prevent="toggleCard"
                            ><FontAwesomeIcon icon="fa-sharp fa-light fa-user-bounty-hunter" class="mr-2" />Daniel Schmier
                        </a>

                        <div
                            class="absolute top-full left-1/2 z-50 mt-2 w-96 max-w-[calc(100vW-2rem)] -translate-x-1/2 rounded-xl border border-teal-800/40 bg-teal-black/95 p-4 shadow-2xl backdrop-blur-sm transition-all duration-300 ease-out md:top-auto md:bottom-full md:mt-0 md:mb-2 md:max-w-96"
                            :class="{
                                'invisible opacity-0': !isCardOpen,
                                'visible opacity-100': isCardOpen,
                                'md:invisible md:opacity-0 md:group-hover:visible md:group-hover:opacity-100': true,
                            }"
                        >
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <img
                                        src="/img/daniel.webp"
                                        alt="Daniel Schmier"
                                        class="h-20 w-20 rounded-full border-2 border-teal-800/40 object-cover"
                                    />
                                </div>
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <h3 class="text-sm font-medium text-teal-100">Daniel Schmier</h3>
                                        <p class="text-xs text-teal-300/80">{{ t('home.profile.title') }}</p>
                                    </div>

                                    <p class="text-xs leading-relaxed text-teal-50">
                                        {{ t('home.profile.description') }}
                                    </p>

                                    <div class="space-y-2 border-t border-teal-800/40 pt-2">
                                        <div class="flex items-center gap-2 text-xs text-teal-200">
                                            <FontAwesomeIcon icon="fa-sharp fa-light fa-building" class="text-teal-400" />
                                            <span>{{ t('home.profile.coFounder') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-teal-300">
                                            <FontAwesomeIcon icon="fa-sharp fa-light fa-code-branch" class="text-teal-400" />
                                            <span>{{ t('home.profile.openSourceContributor') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-teal-300">
                                            <FontAwesomeIcon
                                                icon="fa-sharp fa-light fa-starfighter"
                                                class="cursor-pointer text-teal-400 transition-all duration-200 hover:scale-110"
                                                :class="{
                                                    'starfighter-flying': isStarfighterFlying,
                                                    'starfighter-bounce': !isStarfighterFlying && !isStarfighterCooldown,
                                                }"
                                                @click="triggerStarfighterAnimation"
                                                @mouseenter="triggerStarfighterAnimation"
                                            />
                                            <span>{{ t('home.profile.starWarsEnthusiast') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        @click="openSkillsModal"
                        class="w-fit border-b border-b-teal-black/50 text-left leading-[135%] transition-all hover:cursor-pointer hover:border-b-brand md:min-w-fit dark:border-b-off-white/50 dark:hover:border-b-brand [@media(max-height:680px)]:min-w-fit"
                    >
                        <FontAwesomeIcon icon="fa-sharp fa-light fa-code" class="mr-2" />{{ t('home.about.skills') }}
                    </button>
                    <span class="min-w-fit [@media(max-height:680px)]:min-w-fit"
                        ><FontAwesomeIcon icon="fa-sharp fa-light fa-location-arrow" class="mr-2" />{{ t('home.about.lastSeen') }}</span
                    >
                    <span class="min-w-fit [@media(max-height:680px)]:min-w-fit"
                        ><FontAwesomeIcon icon="fa-sharp fa-light fa-clock-eight" class="mr-2" />{{ currentTime }} {{ timezone }}</span
                    >
                </div>
                <div class="flex flex-col gap-2 md:text-right">
                    <span class="font-medium"
                        ><FontAwesomeIcon icon="fa-sharp fa-light fa-briefcase" class="mr-2" />{{ t('home.pastWork.title') }}</span
                    >
                    <div class="flex w-full flex-row justify-between gap-8 pl-1 md:justify-end">
                        <div class="flex flex-col gap-1">
                            <div v-for="(link, index) in pastWorkLinks.slice(0, 4)" :key="link.url" :class="index < 3 ? 'pb-0.5 text-sm' : 'text-sm'">
                                <a
                                    :href="link.url"
                                    target="_blank"
                                    class="link-with-thumbnail border-b border-b-teal-black/50 leading-[135%] transition-all hover:cursor-ne-resize hover:border-b-brand md:border-b-teal-black/0 dark:border-b-off-white/50 dark:hover:border-b-brand dark:md:border-b-teal-black/0"
                                    :class="{ 'is-active': activeThumbnailIndex === index }"
                                    @click.prevent="toggleThumbnail(index)"
                                    >– {{ link.label }}
                                    <div class="thumbnail-preview-wrapper left-column" :class="{ 'is-active': activeThumbnailIndex === index }">
                                        <img class="thumbnail-preview" :src="link.image" :alt="link.alt" width="300" height="300" />
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <div v-for="(link, index) in pastWorkLinks.slice(4, 8)" :key="link.url" :class="index < 3 ? 'pb-0.5 text-sm' : 'text-sm'">
                                <a
                                    :href="link.url"
                                    target="_blank"
                                    class="link-with-thumbnail border-b border-b-teal-black/50 leading-[135%] transition-all hover:cursor-ne-resize hover:border-b-brand md:border-b-teal-black/0 dark:border-b-off-white/50 dark:hover:border-b-brand dark:md:border-b-teal-black/0"
                                    :class="{ 'is-active': activeThumbnailIndex === index + 4 }"
                                    @click.prevent="toggleThumbnail(index + 4)"
                                    >– {{ link.label }}
                                    <div class="thumbnail-preview-wrapper right-column" :class="{ 'is-active': activeThumbnailIndex === index + 4 }">
                                        <img class="thumbnail-preview" :src="link.image" :alt="link.alt" width="300" height="300" />
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="flex flex-col items-end justify-between gap-4 py-4 md:flex-row md:py-8">
                <div class="flex w-full flex-row flex-wrap justify-between gap-x-4 gap-y-1 md:justify-start md:gap-y-0">
                    <a
                        href="https://www.instagram.com/captainscorch"
                        target="_blank"
                        class="border-b border-b-teal-black/0 leading-[135%] transition-all hover:cursor-ne-resize hover:border-b-brand"
                        >IG</a
                    >
                    <a
                        href="https://x.com/captainscorch"
                        target="_blank"
                        class="border-b border-b-teal-black/0 leading-[135%] transition-all hover:cursor-ne-resize hover:border-b-brand"
                        >&#120143;</a
                    >
                    <a
                        href="https://www.linkedin.com/in/captainscorch"
                        target="_blank"
                        class="border-b border-b-teal-black/0 leading-[135%] transition-all hover:cursor-ne-resize hover:border-b-brand"
                        >LinkedIn</a
                    >
                    <a
                        href="https://github.com/captainscorch"
                        target="_blank"
                        class="border-b border-b-teal-black/0 leading-[135%] transition-all hover:cursor-ne-resize hover:border-b-brand"
                        >GitHub</a
                    >
                    <a
                        href="mailto:hi@captainscor.ch"
                        class="border-b border-b-teal-black/0 leading-[135%] transition-all hover:cursor-ne-resize hover:border-b-brand"
                        >Email</a
                    >
                </div>
                <div class="mt-4 flex flex-row items-center justify-end gap-x-6 text-sm tracking-wide uppercase md:mt-0">
                    <div class="flex flex-row items-center gap-x-4">
                        <button
                            @click="switchLanguage('en')"
                            :class="
                                locale === 'en'
                                    ? 'border-b border-b-teal-black dark:border-b-off-white'
                                    : 'cursor-pointer border-b border-b-teal-black/0 hover:border-b-teal-black dark:hover:border-b-off-white'
                            "
                            class="leading-[135%] transition-all"
                        >
                            {{ t('languages.en') }}
                        </button>
                        <button
                            @click="switchLanguage('de')"
                            :class="
                                locale === 'de'
                                    ? 'border-b border-b-teal-black dark:border-b-off-white'
                                    : 'cursor-pointer border-b border-b-teal-black/0 hover:border-b-teal-black dark:hover:border-b-off-white'
                            "
                            class="leading-[135%] transition-all"
                        >
                            {{ t('languages.de') }}
                        </button>
                    </div>
                    <Switch :model-value="isDark" @update:model-value="toggleTheme" class="-mt-0.5">
                        <template #thumb>
                            <FontAwesomeIcon v-if="isDark" icon="fa-sharp fa-solid fa-moon" class="text-[10px]" />
                            <FontAwesomeIcon v-else icon="fa-sharp fa-solid fa-sun" class="text-[10px]" />
                        </template>
                    </Switch>
                </div>
            </footer>
        </section>
    </div>

    <SkillsModal :is-open="isSkillsModalOpen" @close="closeSkillsModal" />
</template>

<style scoped>
@keyframes fa-bounce {
    0% {
        transform: scale(1) translateY(0);
    }
    10% {
        transform: scale(1.05, 0.96) translateY(0);
    }
    30% {
        transform: scale(0.96, 1.05) translateY(-0.28em);
    }
    50% {
        transform: scale(1.025, 0.98) translateY(0);
    }
    57% {
        transform: scale(1) translateY(-0.06em);
    }
    64% {
        transform: scale(1) translateY(0);
    }
    100% {
        transform: scale(1) translateY(0);
    }
}

.starfighter-bounce {
    animation-name: fa-bounce;
    animation-delay: 0s;
    animation-direction: normal;
    animation-duration: 1s;
    animation-iteration-count: infinite;
    animation-timing-function: cubic-bezier(0.28, 0.84, 0.42, 1);
    filter: drop-shadow(0 0 4px rgba(34, 211, 238, 0.3));
    transition: filter 0.3s ease;
}

.starfighter-bounce:hover {
    filter: drop-shadow(0 0 8px rgba(34, 211, 238, 0.6));
}

@keyframes starfighterFlyAway {
    0% {
        transform: translateX(0) translateY(0) rotate(0deg) scale(1);
        opacity: 1;
    }
    10% {
        transform: translateX(8px) translateY(-4px) rotate(6deg) scale(1.05);
        opacity: 0.95;
    }
    20% {
        transform: translateX(16px) translateY(-8px) rotate(12deg) scale(1.1);
        opacity: 0.9;
    }
    30% {
        transform: translateX(24px) translateY(-12px) rotate(18deg) scale(1.15);
        opacity: 0.85;
    }
    40% {
        transform: translateX(32px) translateY(-16px) rotate(24deg) scale(1.2);
        opacity: 0.8;
    }
    50% {
        transform: translateX(40px) translateY(-20px) rotate(30deg) scale(1.25);
        opacity: 0.75;
    }
    60% {
        transform: translateX(48px) translateY(-24px) rotate(36deg) scale(1.3);
        opacity: 0.7;
    }
    70% {
        transform: translateX(56px) translateY(-28px) rotate(42deg) scale(1.35);
        opacity: 0.6;
    }
    80% {
        transform: translateX(64px) translateY(-32px) rotate(48deg) scale(1.4);
        opacity: 0.45;
    }
    90% {
        transform: translateX(72px) translateY(-36px) rotate(54deg) scale(1.45);
        opacity: 0.25;
    }
    100% {
        transform: translateX(80px) translateY(-40px) rotate(60deg) scale(1.5);
        opacity: 0;
    }
}

@keyframes starfighterTrail {
    0% {
        opacity: 0;
        transform: scale(0);
    }
    5% {
        opacity: 0.3;
        transform: scale(0.3);
    }
    10% {
        opacity: 0.6;
        transform: scale(0.6);
    }
    15% {
        opacity: 0.8;
        transform: scale(0.8);
    }
    20% {
        opacity: 1;
        transform: scale(1);
    }
    25% {
        opacity: 0.9;
        transform: scale(1.1);
    }
    30% {
        opacity: 0.8;
        transform: scale(1.2);
    }
    40% {
        opacity: 0.7;
        transform: scale(1.3);
    }
    50% {
        opacity: 0.6;
        transform: scale(1.4);
    }
    60% {
        opacity: 0.5;
        transform: scale(1.5);
    }
    70% {
        opacity: 0.4;
        transform: scale(1.6);
    }
    80% {
        opacity: 0.3;
        transform: scale(1.7);
    }
    90% {
        opacity: 0.15;
        transform: scale(1.8);
    }
    100% {
        opacity: 0;
        transform: scale(2);
    }
}

.starfighter-flying {
    animation: starfighterFlyAway 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards !important;
    position: relative;
}

.starfighter-flying::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 4px;
    height: 4px;
    background: radial-gradient(circle, #22d3ee 0%, transparent 70%);
    border-radius: 50%;
    animation: starfighterTrail 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    pointer-events: none;
}
</style>
