import '../css/app.css';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { i18n } from './i18n';

import GlobalLayout from '@/components/GlobalLayout.vue';
import { MotionPlugin } from '@vueuse/motion';
import { defineElement } from 'lord-icon-element';
import lottie from 'lottie-web';

defineElement(lottie.loadAnimation);

const appName = import.meta.env.VITE_APP_NAME || 'scorchOS';

createInertiaApp({
    title: (title) => (title ? `${title}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(GlobalLayout, null, { default: () => h(App, props) }) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .use(MotionPlugin)
            .mount(el);
    },
    defaults: {
        visitOptions: () => {
            return { viewTransition: true };
        },
    },
    progress: {
        color: '#2ab193',
    },
});

console.log('Like what you see? Connect with me at https://captainscor.ch 🚀');

// Silence all console logs in production
if (import.meta.env.PROD) {
    console.log = () => {};
    console.debug = () => {};
    console.info = () => {};
    console.warn = () => {};
    console.error = () => {};
}

// Text scramble effect
interface QueueItem {
    from: string;
    to: string;
    start: number;
    end: number;
    char?: string;
}

class TextScramble {
    el: HTMLElement;
    chars: string;
    initialText: string;
    queue: QueueItem[] = [];
    frame: number = 0;
    frameRequest: number | null = null;
    resolve?: () => void;

    constructor(el: HTMLElement) {
        this.el = el;
        this.chars = '*?><[]&@#)(.%$-_:/;?!';
        // this.chars = '*?><[]&@#)(.%$-_:/;?!AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
        this.initialText = el.innerText;
        this.update = this.update.bind(this);
    }
    setText(newText: string, initialText: string): Promise<void> {
        const oldText = this.el.innerText || initialText;
        const length = Math.max(oldText.length, newText.length);
        const promise = new Promise<void>((resolve) => (this.resolve = resolve));
        this.queue = [];
        for (let i = 0; i < length; i++) {
            const from = oldText[i] || '';
            const to = newText[i] || '';
            const start = Math.floor(Math.random() * 20);
            const end = start + Math.floor(Math.random() * 20);
            this.queue.push({
                from,
                to,
                start,
                end,
            });
        }
        if (this.frameRequest !== null) {
            cancelAnimationFrame(this.frameRequest);
        }
        this.frame = 0;
        this.update();
        return promise;
    }
    update(): void {
        let output = '';
        let complete = 0;
        for (let i = 0, n = this.queue.length; i < n; i++) {
            const { from, to, start, end } = this.queue[i];
            let char = this.queue[i].char;
            if (this.frame >= end) {
                complete++;
                output += to;
            } else if (this.frame >= start) {
                if (!char || Math.random() < 0.28) {
                    char = this.randomChar();
                    this.queue[i].char = char;
                }
                output += `<span class="scramble-effect">${char}</span>`;
            } else {
                output += from;
            }
        }
        this.el.innerHTML = output;
        if (complete === this.queue.length) {
            if (this.resolve) {
                this.resolve();
            }
        } else {
            this.frameRequest = requestAnimationFrame(this.update);
            this.frame++;
        }
    }
    randomChar(): string {
        return this.chars[Math.floor(Math.random() * this.chars.length)];
    }
}

const processedElements = new WeakSet();

const applyScrambleEffect = (element: HTMLElement): void => {
    if (processedElements.has(element) || !element.innerText.trim()) {
        return;
    }

    const fx = new TextScramble(element);
    const initialText = element.innerText;

    let isHovered = false;

    element.addEventListener('mouseover', () => {
        isHovered = true;
        fx.setText(initialText, initialText).then(() => {
            isHovered = false;
        });
    });

    element.addEventListener('mouseout', () => {
        isHovered = false;
    });

    if (element.tagName.toLowerCase() === 'a') {
        const targetId = element.getAttribute('href');
        if (targetId && targetId.startsWith('#')) {
            const targetSection = document.querySelector(targetId);
            if (targetSection) {
                element.addEventListener('click', (event: MouseEvent) => {
                    if (!isHovered) {
                        event.preventDefault();
                        targetSection.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }
        }
    }

    processedElements.add(element);
};

const initializeScrambleEffect = () => {
    const triggerElements = document.querySelectorAll('.scramble-trigger') as NodeListOf<HTMLElement>;
    triggerElements.forEach((element) => {
        applyScrambleEffect(element);
    });
};

initializeScrambleEffect();

router.on('navigate', () => {
    setTimeout(() => {
        initializeScrambleEffect();
    }, 100);
});

const observer = new MutationObserver((mutations: MutationRecord[]) => {
    mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
            if (node.nodeType === Node.ELEMENT_NODE) {
                const element = node as HTMLElement;
                if (element.classList && element.classList.contains('scramble-trigger')) {
                    applyScrambleEffect(element);
                }
                const nestedElements = element.querySelectorAll?.('.scramble-trigger') as NodeListOf<HTMLElement> | undefined;
                if (nestedElements) {
                    nestedElements.forEach((el) => applyScrambleEffect(el));
                }
            }
        });
    });
});

observer.observe(document.body, {
    childList: true,
    subtree: true,
});

// Prevent image download
document.addEventListener('contextmenu', (e: MouseEvent) => {
    if (e.target && (e.target as HTMLElement).tagName === 'IMG') {
        e.preventDefault();
    }
});

// Designed & developed by captainscor.ch (https://captainscor.ch)
