<script setup lang="ts">
import BodyText from '@/components/global/typography/BodyText.vue';
import { Switch } from '@/components/ui/switch';
import { useSocials } from '@/composables/useSocials';
import { setLocale } from '@/i18n';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faClockEight, faLocationArrow } from '@fortawesome/sharp-light-svg-icons';
import { faMoon, faSun } from '@fortawesome/sharp-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useDark, useToggle } from '@vueuse/core';
import { onMounted, onUnmounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
library.add(faLocationArrow, faClockEight, faMoon, faSun);

const { t, locale } = useI18n();
const socials = useSocials();

defineProps<{
    isLandingPage?: boolean;
}>();

const year = new Date().getFullYear();
const lastCommitDate = ref<string>('');

const formatCommitDate = (dateString: string): string => {
    const date = new Date(dateString);
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};

const isDark = useDark({
    selector: 'html',
    attribute: 'class',
    valueDark: 'dark',
    valueLight: '',
    initialValue: 'dark',
});
const toggleTheme = useToggle(isDark);

const emit = defineEmits<{
    languageSwitched: [];
}>();

const switchLanguage = (lang: string) => {
    setLocale(lang);
    setTimeout(() => {
        emit('languageSwitched');
    }, 100);
};

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
    calculateTimeDifference();
};

const timeDifference = ref('');

const calculateTimeDifference = () => {
    const now = new Date();
    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    const targetTimezone = 'Europe/Berlin';

    const userDate = new Date(now.toLocaleString('en-US', { timeZone: userTimezone }));
    const targetDate = new Date(now.toLocaleString('en-US', { timeZone: targetTimezone }));

    const diffInHours = (targetDate.getTime() - userDate.getTime()) / (1000 * 60 * 60);
    const roundedDiff = Math.round(diffInHours);

    if (roundedDiff === 0) {
        timeDifference.value = t('home.clock.same');
    } else if (roundedDiff > 0) {
        timeDifference.value = t('home.clock.ahead', { hours: roundedDiff });
    } else {
        timeDifference.value = t('home.clock.behind', { hours: Math.abs(roundedDiff) });
    }
};

onMounted(async () => {
    updateClock();
    clockInterval = setInterval(updateClock, 1000);

    try {
        const response = await fetch('https://api.github.com/repos/captainscorch/scorchOS/commits?per_page=1');
        if (!response.ok) throw new Error('Failed to fetch commits');

        const commits = await response.json();
        if (commits && commits.length > 0 && commits[0].commit?.author?.date) {
            lastCommitDate.value = formatCommitDate(commits[0].commit.author.date);
        }
    } catch (error) {
        console.error('Error fetching latest commit date:', error);
        // Fallback to current date if API fails
        lastCommitDate.value = formatCommitDate(new Date().toISOString());
    }
});

onUnmounted(() => {
    if (clockInterval) {
        clearInterval(clockInterval);
    }
});
</script>

<template>
    <footer class="mx-auto mt-16 w-full max-w-7xl pt-16 text-neutral-900 md:py-16 dark:text-white"
    :class="{ 'pb-20': isLandingPage }"
    >
        <div class="mx-auto">
            <h2 class="scramble-trigger relative mb-12 w-fit font-work-sans text-3xl font-medium text-neutral-900 md:text-4xl dark:text-white">
                {{ t('footer.contact') }}
            </h2>

            <div class="flex flex-col gap-8 lg:flex-row lg:gap-16">
                <div
                    class="flex min-h-[320px] flex-1 flex-col justify-between rounded-3xl border border-neutral-200 bg-neutral-50 p-6 md:p-12 dark:border-white/5 dark:bg-[#111]"
                >
                    <div>
                        <h3 class="mb-6 text-base font-medium text-neutral-900 dark:text-white">{{ t('footer.notes') }}</h3>
                        <p class="mb-4 max-w-lg text-sm leading-relaxed text-neutral-600 dark:text-neutral-400">
                            {{ t('footer.description') }}
                            <a
                                class="border-b border-b-teal-black/0 leading-[135%] text-neutral-900 transition-all group-hover:opacity-50 hover:cursor-ne-resize hover:border-b-brand hover:opacity-100! dark:text-white"
                                href="https://github.com/captainscorch/scorchOS/commits/main/"
                                >{{ lastCommitDate || t('footer.loading') }}</a
                            >
                        </p>
                        <div class="flex flex-col items-start gap-x-6 gap-y-2 md:flex-row">
                            <span class="min-w-fit [@media(max-height:680px)]:min-w-fit"
                                ><FontAwesomeIcon icon="fa-sharp fa-light fa-location-arrow" class="mr-2" /><BodyText size="sm" as="span">{{
                                    t('home.about.lastSeen')
                                }}</BodyText></span
                            >
                            <span class="min-w-fit [@media(max-height:680px)]:min-w-fit"
                                ><FontAwesomeIcon icon="fa-sharp fa-light fa-clock-eight" class="mr-2" /><BodyText size="sm" as="span"
                                    >{{ currentTime }} {{ timezone }} <span class="opacity-50">({{ timeDifference }})</span></BodyText
                                ></span
                            >
                        </div>
                    </div>
                    <div class="font-regular mt-10 text-sm text-neutral-500 dark:text-neutral-500">
                        <span class="font-normal italic">{{ t('footer.quote') }}</span>
                        <span class="mt-2 block text-xs text-neutral-400 dark:text-neutral-600">{{ t('footer.quoteAttribution') }}</span>
                    </div>
                </div>

                <div class="p-8 md:p-12 lg:w-1/3 lg:px-0">
                    <h3 class="text-center text-base font-medium text-neutral-900 md:text-left dark:text-white">{{ t('footer.socials') }}</h3>
                    <div
                        class="group mx-auto flex w-full max-w-[200px] flex-row flex-wrap justify-center gap-x-8 gap-y-4 py-6 md:mx-0 md:max-w-none md:justify-between"
                    >
                        <a
                            v-for="social in socials"
                            :key="social.label"
                            :href="social.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="border-b border-b-teal-black/0 leading-[135%] text-neutral-900 transition-all group-hover:opacity-50 hover:cursor-ne-resize hover:border-b-brand hover:opacity-100! dark:text-neutral-400 dark:hover:text-white"
                            v-html="social.label"
                        ></a>
                    </div>
                </div>
            </div>

            <div class="mt-8 mb-20 flex flex-col items-end justify-between gap-4 md:mt-16 md:mb-0 md:flex-row">
                <div class="flex w-full flex-col items-center justify-between gap-x-8 gap-y-4 text-sm tracking-wide uppercase md:flex-row md:gap-y-0">
                    <p class="text-xs tracking-wider text-neutral-500 uppercase md:text-neutral-600">&copy; {{ t('footer.copyright') }} {{ year }}</p>
                    <div class="flex flex-row items-center gap-x-4">
                        <div class="flex flex-row items-center gap-x-4">
                            <button
                                @click="switchLanguage('en')"
                                :class="
                                    locale === 'en'
                                        ? 'border-b border-b-teal-black dark:border-b-off-white'
                                        : 'cursor-pointer border-b border-b-teal-black/0 hover:border-b-teal-black dark:hover:border-b-off-white'
                                "
                                class="transition-all"
                            >
                                <BodyText size="sm" as="span">{{ t('languages.en') }}</BodyText>
                            </button>
                            <button
                                @click="switchLanguage('de')"
                                :class="
                                    locale === 'de'
                                        ? 'border-b border-b-teal-black dark:border-b-off-white'
                                        : 'cursor-pointer border-b border-b-teal-black/0 hover:border-b-teal-black dark:hover:border-b-off-white'
                                "
                                class="transition-all"
                            >
                                <BodyText size="sm" as="span">{{ t('languages.de') }}</BodyText>
                            </button>
                        </div>
                        <Switch :model-value="isDark" @update:model-value="toggleTheme" class="-mt-0.5">
                            <template #thumb>
                                <FontAwesomeIcon v-if="isDark" icon="fa-sharp fa-solid fa-moon" class="text-[10px]" />
                                <FontAwesomeIcon v-else icon="fa-sharp fa-solid fa-sun" class="text-[10px]" />
                            </template>
                        </Switch>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
