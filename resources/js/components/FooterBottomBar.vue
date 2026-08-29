<script setup lang="ts">
import BodyText from '@/components/global/typography/BodyText.vue';
import { Switch } from '@/components/ui/switch';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMoon, faSunBright } from '@fortawesome/sharp-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useI18n } from 'vue-i18n';
library.add(faMoon, faSunBright);

defineProps<{
    isDark: boolean;
    year: number;
}>();

const emit = defineEmits<{
    switchLanguage: [lang: string];
    toggleTheme: [];
}>();

const { t, locale } = useI18n();
</script>

<template>
    <div class="mt-8 mb-20 flex flex-col items-end justify-between gap-4 sm:mt-0 md:mb-0 md:flex-row lg:mt-16">
        <div class="flex w-full flex-col items-center justify-between gap-x-8 gap-y-4 text-sm tracking-wide uppercase md:flex-row md:gap-y-0">
            <div class="flex flex-col items-center gap-2 md:flex-row md:items-center md:gap-4">
                <p class="text-xs tracking-wider text-neutral-500 uppercase md:text-neutral-600">&copy; {{ t('footer.copyright') }} {{ year }}</p>
                <span class="hidden h-3 w-px shrink-0 bg-neutral-400/45 md:block dark:bg-neutral-500/45" aria-hidden="true" />
                <a
                    href="/llms.txt"
                    target="_blank"
                    class="text-xs tracking-wider text-neutral-500 normal-case no-underline transition-colors hover:text-neutral-600 md:text-neutral-600 md:hover:text-neutral-700 dark:hover:text-neutral-400"
                    :title="t('footer.llmsTxtTitle')"
                    >{{ t('footer.llmsTxt') }}</a
                >
                <span class="hidden h-3 w-px shrink-0 bg-neutral-400/45 md:block dark:bg-neutral-500/45" aria-hidden="true" />
                <a
                    href="/agents.md"
                    target="_blank"
                    class="text-xs tracking-wider text-neutral-500 normal-case no-underline transition-colors hover:text-neutral-600 md:text-neutral-600 md:hover:text-neutral-700 dark:hover:text-neutral-400"
                    :title="t('footer.agentsMdTitle')"
                    >{{ t('footer.agentsMd') }}</a
                >
            </div>
            <div class="flex flex-row items-center gap-x-4">
                <div class="flex flex-row items-center gap-x-4">
                    <button
                        @click="emit('switchLanguage', 'en')"
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
                        @click="emit('switchLanguage', 'de')"
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
                <Switch :model-value="isDark" @update:model-value="emit('toggleTheme')" class="-mt-0.5">
                    <template #thumb>
                        <FontAwesomeIcon v-if="isDark" icon="fa-sharp fa-solid fa-moon" class="text-[10px]" />
                        <FontAwesomeIcon v-else icon="fa-sharp fa-solid fa-sun-bright" class="text-[10px]" />
                    </template>
                </Switch>
            </div>
        </div>
    </div>
</template>
