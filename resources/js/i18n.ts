import { createI18n } from 'vue-i18n';
import de from '../../lang/de.json';
import en from '../../lang/en.json';

// Get initial locale from localStorage or browser settings
const getInitialLocale = () => {
    // First try localStorage
    const storedLocale = localStorage.getItem('preferred-locale');
    if (storedLocale && ['en', 'de'].includes(storedLocale)) {
        return storedLocale;
    }

    // Then try browser language
    const browserLang = navigator.language.split('-')[0];
    if (['en', 'de'].includes(browserLang)) {
        return browserLang;
    }

    // Default to English
    return 'en';
};

// Create i18n instance
export const i18n = createI18n({
    legacy: false,
    locale: getInitialLocale(),
    fallbackLocale: 'en',
    messages: {
        en,
        de,
    },
    silentTranslationWarn: true,
    missingWarn: false,
    fallbackWarn: false,
});

// Helper function to change locale
export function setLocale(locale: string | 'en' | 'de') {
    if (!['en', 'de'].includes(locale)) {
        return;
    }

    i18n.global.locale.value = locale as 'en' | 'de';
    localStorage.setItem('preferred-locale', locale);
    document.documentElement.lang = locale;
}
