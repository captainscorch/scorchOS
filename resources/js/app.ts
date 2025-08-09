import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { createI18n } from 'vue-i18n';
import { ZiggyVue } from 'ziggy-js';

const i18n = createI18n({
    locale: 'en',
    fallbackLocale: 'en',
});

const appName = import.meta.env.VITE_APP_NAME || 'scorchOS';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#2ab193',
    },
});

console.log('Like what you see? Connect with me at https://captainscor.ch 🚀');

// Silence all console logs in production
// if (import.meta.env.PROD) {
//   console.log = () => {};
//   console.debug = () => {};
//   console.info = () => {};
//   console.warn = () => {};
//   console.error = () => {};
// }

// Designed & developed by captainscor.ch (https://captainscor.ch)
