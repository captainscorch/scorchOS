import { defineAsyncComponent } from 'vue';

/**
 * Auto-discover blog demo components.
 * Add new demo by creating a .vue file in this folder and using
 * ::interactive[ComponentName] in the post (name = filename without .vue).
 */
const demoModules = import.meta.glob<{ default: object }>('./*.vue');

export type DemoComponent = ReturnType<typeof defineAsyncComponent>;

export const demoRegistry: Record<string, DemoComponent> = {};

for (const path of Object.keys(demoModules)) {
    const name = path.replace(/^\.\/(.*)\.vue$/, '$1');
    demoRegistry[name] = defineAsyncComponent(demoModules[path] as () => Promise<{ default: object }>);
}

/**
 * Demos that also exist on the Playground page — show "View in Playground" link.
 * Add entry here when the same demo is also in Playground.vue.
 */
export const COMPONENT_TO_PLAYGROUND_ID: Record<string, string> = {
    ConcentricDemo: 'concentric-radius',
};
