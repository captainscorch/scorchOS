<script setup lang="ts">
import Terminal from '@/components/Terminal.vue';
import { CommandDialog, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList, CommandSeparator } from '@/components/ui/command';
import { useCommandMenu } from '@/composables/useCommandMenu';
import { useTerminal } from '@/composables/useTerminal';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faGithub, faInstagram, faLinkedin, faXTwitter } from '@fortawesome/free-brands-svg-icons';
import { faArrowRight, faCheck, faEnvelope, faGlobe, faMoon, faSun, faText } from '@fortawesome/sharp-light-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { router } from '@inertiajs/vue3';
import { useDark, useMagicKeys } from '@vueuse/core';
import { CornerDownLeft } from 'lucide-vue-next';
import { watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { toast } from 'vue-sonner';
import 'vue-sonner/style.css';

import { setLocale } from '@/i18n';

library.add(faArrowRight, faInstagram, faXTwitter, faLinkedin, faGithub, faEnvelope, faMoon, faSun, faGlobe, faCheck, faText);

const { t, locale } = useI18n();
const { isOpen, close, toggle } = useCommandMenu();
const { isOpen: isTerminalOpen, open: openTerminal, close: closeTerminal } = useTerminal();

const isDark = useDark({
    selector: 'html',
    attribute: 'class',
    valueDark: 'dark',
    valueLight: '',
    initialValue: 'dark',
});

const year = new Date().getFullYear();

// Sound effect for theme toggle
const playThemeSound = () => {
    const audio = new Audio('/sounds/button-click-sound.mp3');
    audio.volume = 0.5;
    audio.play().catch((e) => console.error('Error playing sound:', e));
};

watch(isDark, () => {
    playThemeSound();
});

useMagicKeys({
    passive: false,
    onEventFired(e) {
        if (e.key === 'k' && (e.metaKey || e.ctrlKey)) {
            e.preventDefault();
            toggle();
        }

        if (e.key === 'l' && (e.metaKey || e.ctrlKey)) {
            e.preventDefault();
            isDark.value = false;
        }

        if (e.key === 'd' && (e.metaKey || e.ctrlKey)) {
            e.preventDefault();
            isDark.value = true;
        }
    },
});

const runCommand = (command: () => void) => {
    close();
    command();
};

const navigateTo = (page: string) => {
    runCommand(() => {
        router.visit(route(page));
    });
};

const openLink = (url: string) => {
    runCommand(() => {
        window.open(url, '_blank');
    });
};

const switchLanguage = (lang: string) => {
    setLocale(lang);
};

const copyToClipboard = async (text: string, type: 'Mark' | 'Logotype') => {
    try {
        await navigator.clipboard.writeText(text);
        toast.success(t('commandMenu.toast.copied', { type }), {
            description: t('commandMenu.toast.copiedDescription'),
        });
    } catch {
        toast.error(t('commandMenu.toast.copyFailed'));
    }
};

const markSvg = `<svg version="1.1" id="captainscorch_logo" xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 56.2 46.5" xml:space="preserve"><style>.white{fill:#fff}</style><g id="XMLID_536_"><path id="XMLID_547_" class="white" d="M13.2 32.1c0 3.2 2.6 5.7 5.7 5.7h18.3c3.2 0 5.7-2.6 5.7-5.7V23H36v4c0 2.2-1.8 4-4 4H13.2v1.1z"/><path id="XMLID_541_" class="white" d="M42.9 13.8c0-3.1-2.6-5.7-5.7-5.7H19c-3.2 0-5.7 2.6-5.7 5.7v9.1h6.9v-4c0-2.2 1.8-4 4-4H43v-1.1z"/><path id="XMLID_540_" class="white" d="M25.8 19.7c-.5 0-.9.4-.9.9v4.6c0 .5.4.9.9.9h4.6c.5 0 .9-.4.9-.9v-4.6c0-.5-.4-.9-.9-.9h-4.6z"/><path id="XMLID_539_" class="white" d="M47.9 33.8c0 5.1-4.2 9.3-9.3 9.3H17.5c-5.1 0-9.3-4.2-9.3-9.3V30.9H5.6v2.9c0 6.6 5.3 11.9 11.9 11.9h21.2c6.6 0 11.9-5.3 11.9-11.9V22.9H48v10.9z"/><path id="XMLID_537_" class="white" d="M38.7.7H17.5C10.9.7 5.6 6 5.6 12.6v10.3h2.6V12.6c0-5.1 4.2-9.3 9.3-9.3h21.2c5.1 0 9.3 4.2 9.3 9.3V15h2.6v-2.3c0-6.6-5.4-12-11.9-12z"/></g></svg>`;
</script>

<template>
    <CommandDialog :open="isOpen" @update:open="isOpen = $event">
        <CommandInput :placeholder="t('commandMenu.placeholder')" />
        <CommandList class="max-h-[300px] overflow-x-hidden overflow-y-auto">
            <CommandEmpty>{{ t('commandMenu.noResults') }}</CommandEmpty>
            <CommandGroup :heading="t('commandMenu.groups.pages')">
                <CommandItem value="home" @select="navigateTo('home')" class="group cmd-item--home">
                    <lord-icon
                        src="/icons/system-regular-41-home-hover-pinch.json"
                        trigger="morph"
                        target=".cmd-item--home"
                        style="width: 18px; height: 18px"
                        class="current-color mr-1.5 ml-0.5 opacity-60 group-hover:opacity-100"
                    >
                    </lord-icon>
                    <span>{{ t('commandMenu.pages.home') }}</span>
                </CommandItem>
                <CommandItem value="about" @select="navigateTo('about')" class="group cmd-item--about">
                    <lord-icon
                        src="/icons/system-regular-8-account-hover-pinch.json"
                        trigger="morph"
                        target=".cmd-item--about"
                        style="width: 18px; height: 18px"
                        class="current-color mr-1.5 ml-0.5 opacity-60 group-hover:opacity-100"
                    >
                    </lord-icon>
                    <span>{{ t('commandMenu.pages.about') }}</span>
                </CommandItem>
                <CommandItem value="portfolio" @select="navigateTo('portfolio')" class="group cmd-item--portfolio">
                    <lord-icon
                        src="/icons/system-regular-178-work-hover-work.json"
                        trigger="morph"
                        target=".cmd-item--portfolio"
                        style="width: 18px; height: 18px"
                        class="current-color mr-1.5 ml-0.5 opacity-60 group-hover:opacity-100"
                    >
                    </lord-icon>
                    <span>{{ t('commandMenu.pages.portfolio') }}</span>
                </CommandItem>
                <CommandItem value="terminal" @select="runCommand(openTerminal)" class="group cmd-item--terminal">
                    <lord-icon
                        src="/icons/system-regular-34-code-hover-code.json"
                        trigger="morph"
                        target=".cmd-item--terminal"
                        style="width: 18px; height: 18px"
                        class="current-color mr-1.5 ml-0.5 opacity-60 group-hover:opacity-100"
                    >
                    </lord-icon>
                    <span>{{ t('commandMenu.pages.terminal') }}</span>
                </CommandItem>
            </CommandGroup>
            <CommandSeparator />
            <CommandGroup :heading="t('commandMenu.groups.social')">
                <CommandItem value="instagram" @select="openLink('https://www.instagram.com/captainscorch')" class="group">
                    <FontAwesomeIcon icon="fa-brands fa-instagram" class="mr-1.5 ml-0.5 h-4 w-4 opacity-60 group-hover:opacity-100" />
                    <span>{{ t('commandMenu.social.instagram') }}</span>
                </CommandItem>
                <CommandItem value="twitter" @select="openLink('https://x.com/captainscorch')" class="group">
                    <FontAwesomeIcon icon="fa-brands fa-x-twitter" class="mr-1.5 ml-0.5 h-4 w-4 opacity-60 group-hover:opacity-100" />
                    <span>{{ t('commandMenu.social.twitter') }}</span>
                </CommandItem>
                <CommandItem value="linkedin" @select="openLink('https://www.linkedin.com/in/captainscorch')" class="group">
                    <FontAwesomeIcon icon="fa-brands fa-linkedin" class="mr-1.5 ml-0.5 h-4 w-4 opacity-60 group-hover:opacity-100" />
                    <span>{{ t('commandMenu.social.linkedin') }}</span>
                </CommandItem>
                <CommandItem value="github" @select="openLink('https://github.com/captainscorch')" class="group">
                    <FontAwesomeIcon icon="fa-brands fa-github" class="mr-1.5 ml-0.5 h-4 w-4 opacity-60 group-hover:opacity-100" />
                    <span>{{ t('commandMenu.social.github') }}</span>
                </CommandItem>
                <CommandItem value="email" @select="openLink('mailto:hi@captainscor.ch')" class="group cmd-item--email">
                    <lord-icon
                        src="/icons/system-regular-59-email-hover-email.json"
                        trigger="morph"
                        target=".cmd-item--email"
                        style="width: 18px; height: 18px"
                        class="current-color mr-1.5 ml-0.5 opacity-60 group-hover:opacity-100"
                    >
                    </lord-icon>
                    <span>{{ t('commandMenu.social.email') }}</span>
                </CommandItem>
            </CommandGroup>
            <CommandSeparator />
            <CommandGroup :heading="t('commandMenu.groups.brandAssets')">
                <CommandItem value="copy mark as svg" @select="copyToClipboard(markSvg, 'Mark')" class="group">
                    <svg
                        version="1.1"
                        id="Ebene_1"
                        xmlns="http://www.w3.org/2000/svg"
                        x="0"
                        y="0"
                        viewBox="0 0 56.2 46.5"
                        xml:space="preserve"
                        class="mr-1.5 ml-[3px] h-4 w-4 opacity-60 group-hover:opacity-100"
                    >
                        <g id="XMLID_536_">
                            <path
                                id="XMLID_547_"
                                class="fill-neutral-900 dark:fill-white"
                                d="M13.2 32.1c0 3.2 2.6 5.7 5.7 5.7h18.3c3.2 0 5.7-2.6 5.7-5.7V23H36v4c0 2.2-1.8 4-4 4H13.2v1.1z"
                            />
                            <path
                                id="XMLID_541_"
                                class="fill-neutral-900 dark:fill-white"
                                d="M42.9 13.8c0-3.1-2.6-5.7-5.7-5.7H19c-3.2 0-5.7 2.6-5.7 5.7v9.1h6.9v-4c0-2.2 1.8-4 4-4H43v-1.1z"
                            />
                            <path
                                id="XMLID_540_"
                                class="fill-neutral-900 dark:fill-white"
                                d="M25.8 19.7c-.5 0-.9.4-.9.9v4.6c0 .5.4.9.9.9h4.6c.5 0 .9-.4.9-.9v-4.6c0-.5-.4-.9-.9-.9h-4.6z"
                            />
                            <path
                                id="XMLID_539_"
                                class="fill-neutral-900 dark:fill-white"
                                d="M47.9 33.8c0 5.1-4.2 9.3-9.3 9.3H17.5c-5.1 0-9.3-4.2-9.3-9.3V30.9H5.6v2.9c0 6.6 5.3 11.9 11.9 11.9h21.2c6.6 0 11.9-5.3 11.9-11.9V22.9H48v10.9z"
                            />
                            <path
                                id="XMLID_537_"
                                class="fill-neutral-900 dark:fill-white"
                                d="M38.7.7H17.5C10.9.7 5.6 6 5.6 12.6v10.3h2.6V12.6c0-5.1 4.2-9.3 9.3-9.3h21.2c5.1 0 9.3 4.2 9.3 9.3V15h2.6v-2.3c0-6.6-5.4-12-11.9-12z"
                            />
                        </g>
                    </svg>
                    <span>{{ t('commandMenu.brandAssets.copyMarkAsSvg') }}</span>
                </CommandItem>
            </CommandGroup>
            <CommandSeparator />
            <CommandGroup :heading="t('commandMenu.groups.settings')">
                <CommandItem value="light theme mode CMD + L" @select="isDark = false" class="group">
                    <FontAwesomeIcon icon="fa-sharp fa-light fa-sun" class="mr-1.5 ml-0.5 h-4 w-4 opacity-60 group-hover:opacity-100" />
                    <span>{{ t('commandMenu.settings.light') }}</span>
                    <div class="ml-auto flex items-center justify-end">
                        <FontAwesomeIcon v-if="!isDark" icon="fa-sharp fa-light fa-check" class="mr-4 h-4 w-4 opacity-60 group-hover:opacity-100" />
                        <div class="flex items-center gap-1">
                            <div
                                class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-sm text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                            >
                                <span class="leading-none">⌘</span>
                            </div>
                            <span class="text-sm text-neutral-500 dark:text-neutral-400">+</span>
                            <div
                                class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-[10px] text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                            >
                                <span class="leading-none">L</span>
                            </div>
                        </div>
                    </div>
                </CommandItem>
                <CommandItem value="dark theme mode CMD + D" @select="isDark = true" class="group">
                    <FontAwesomeIcon icon="fa-sharp fa-light fa-moon" class="mr-1.5 ml-0.5 h-4 w-4 opacity-60 group-hover:opacity-100" />
                    <span>{{ t('commandMenu.settings.dark') }}</span>
                    <div class="ml-auto flex items-center justify-end">
                        <FontAwesomeIcon v-if="isDark" icon="fa-sharp fa-light fa-check" class="mr-4 h-4 w-4 opacity-60 group-hover:opacity-100" />
                        <div class="flex items-center gap-1">
                            <div
                                class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-sm text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                            >
                                <span class="leading-none">⌘</span>
                            </div>
                            <span class="text-sm text-neutral-500 dark:text-neutral-400">+</span>
                            <div
                                class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 text-[10px] text-neutral-500 dark:border-white/10 dark:bg-neutral-800/80 dark:text-neutral-400"
                            >
                                <span class="leading-none">D</span>
                            </div>
                        </div>
                    </div>
                </CommandItem>
                <CommandItem value="language" @select="() => switchLanguage('en')" class="group">
                    <div class="flex flex-1 items-center justify-start">
                        <div
                            class="mr-3 flex h-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 px-1 dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <span class="font-mono text-[10px] leading-none text-neutral-500 dark:text-neutral-400">EN</span>
                        </div>
                        <span>{{ t('commandMenu.settings.english') }}</span>
                    </div>
                    <div class="ml-auto flex items-center justify-end">
                        <FontAwesomeIcon
                            v-if="locale === 'en'"
                            icon="fa-sharp fa-light fa-check"
                            class="h-4 w-4 opacity-60 group-hover:opacity-100"
                        />
                    </div>
                </CommandItem>
                <CommandItem value="language" @select="() => switchLanguage('de')" class="group">
                    <div class="flex flex-1 items-center justify-start">
                        <div
                            class="mr-3 flex h-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 px-1 dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <span class="font-mono text-[10px] leading-none text-neutral-500 dark:text-neutral-400">DE</span>
                        </div>
                        <span>{{ t('commandMenu.settings.deutsch') }}</span>
                    </div>
                    <div class="ml-auto flex items-center justify-end">
                        <FontAwesomeIcon
                            v-if="locale === 'de'"
                            icon="fa-sharp fa-light fa-check"
                            class="h-4 w-4 opacity-60 group-hover:opacity-100"
                        />
                    </div>
                </CommandItem>
            </CommandGroup>
        </CommandList>
        <div class="border-t border-neutral-200 bg-white/70 px-3 py-2 dark:border-neutral-800 dark:bg-neutral-900/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-neutral-700 dark:text-neutral-200">
                    <span>&copy; {{ year }} Daniel Schmier</span>
                </div>
                <div class="flex items-center gap-4 text-[10px] text-neutral-400 dark:text-neutral-400">
                    <div class="flex items-center gap-1">
                        <span class="text-neutral-500 dark:text-neutral-400">{{ t('commandMenu.footer.goToPage') }}</span>
                        <div
                            class="flex size-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <CornerDownLeft class="size-2.5 text-neutral-500 dark:text-neutral-400" />
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="text-neutral-500 dark:text-neutral-400">{{ t('commandMenu.footer.exit') }}</span>
                        <div
                            class="flex h-5 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-100 px-1 dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <span class="font-mono text-[10px] leading-none text-neutral-500 dark:text-neutral-400">Esc</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CommandDialog>
    <Terminal v-if="isTerminalOpen" @close="closeTerminal" initial-command="neofetch">
        <template #initial-output>
            <div class="flex flex-col gap-4 md:flex-row">
                <div class="hidden text-[#2ab193] md:block">
                    <pre class="font-mono text-xs leading-none select-none">
   _____                     _     
  / ____|                   | |    
 | (___   ___ ___  _ __ ___| |__  
  \___ \ / __/ _ \| '__/ __| '_ \ 
  ____) | (_| (_) | | | (__| | | |
 |_____/ \___\___/|_|  \___|_| |_|
</pre
                    >
                </div>
                <div class="flex flex-col justify-center font-mono text-sm">
                    <div class="mb-2">
                        <span class="font-bold text-[#2ab193]">daniel</span>@<span class="font-bold text-[#2ab193]">scorchOS</span>
                    </div>
                    <div class="mb-2 text-[#64748b]">----------------</div>
                    <div class="grid grid-cols-[100px_1fr] gap-x-2">
                        <span class="font-bold text-[#2ab193]">OS</span>
                        <span class="text-[#ccfbf1]">ScorchOS x86_64</span>

                        <span class="font-bold text-[#2ab193]">Host</span>
                        <span class="text-[#ccfbf1]">MacBook Pro</span>

                        <span class="font-bold text-[#2ab193]">Kernel</span>
                        <span class="text-[#ccfbf1]">24.1.0</span>

                        <span class="font-bold text-[#2ab193]">Uptime</span>
                        <span class="text-[#ccfbf1]">42 days, 6 hours, 9 mins</span>

                        <span class="font-bold text-[#2ab193]">Shell</span>
                        <span class="text-[#ccfbf1]">zsh 5.9</span>

                        <span class="font-bold text-[#2ab193]">Resolution</span>
                        <span class="text-[#ccfbf1]">3024x1964</span>

                        <span class="font-bold text-[#2ab193]">DE</span>
                        <span class="text-[#ccfbf1]">Aqua</span>

                        <span class="font-bold text-[#2ab193]">CPU</span>
                        <span class="text-[#ccfbf1]">Apple M1 Max</span>

                        <span class="font-bold text-[#2ab193]">Memory</span>
                        <span class="text-[#ccfbf1]">64GB</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-[#64748b]">{{ t('commandMenu.terminal.helpHint', { command: 'help' }) }}</div>
        </template>
    </Terminal>
</template>
