<script setup lang="ts">
import { useSocials } from '@/composables/useSocials';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const socials = useSocials();

const year = new Date().getFullYear();
const lastCommitDate = ref<string>('');

const formatCommitDate = (dateString: string): string => {
    const date = new Date(dateString);
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};

onMounted(async () => {
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
</script>

<template>
    <footer class="mx-auto mt-16 w-full max-w-7xl py-16 text-neutral-900 md:py-16 dark:text-white">
        <div class="mx-auto">
            <h2 class="scramble-trigger relative mb-12 w-fit font-work-sans text-3xl font-medium text-neutral-900 md:text-4xl dark:text-white">
                {{ t('footer.contact') }}
            </h2>

            <div class="flex flex-col gap-8 lg:flex-row lg:gap-16">
                <div
                    class="flex min-h-[320px] flex-1 flex-col justify-between rounded-3xl border border-neutral-200 bg-neutral-50 p-8 md:p-12 dark:border-white/5 dark:bg-[#111]"
                >
                    <div class="space-y-6">
                        <h3 class="text-base font-medium text-neutral-900 dark:text-white">{{ t('footer.notes') }}</h3>
                        <p class="max-w-lg text-sm leading-relaxed text-neutral-600 dark:text-neutral-400">
                            {{ t('footer.description') }}
                            <a
                                class="border-b border-b-teal-black/0 leading-[135%] text-neutral-900 transition-all group-hover:opacity-50 hover:cursor-ne-resize hover:border-b-brand hover:opacity-100! dark:text-white"
                                href="https://github.com/captainscorch/scorchOS/commits/main/"
                                >{{ lastCommitDate || t('footer.loading') }}</a
                            >
                        </p>
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

            <div class="mt-8 flex justify-center md:mt-16">
                <p class="text-xs tracking-wider text-neutral-600 uppercase">&copy; {{ t('footer.copyright') }} {{ year }}</p>
            </div>
        </div>
    </footer>
</template>
