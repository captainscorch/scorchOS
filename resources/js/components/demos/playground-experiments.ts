import { demoRegistry } from './registry';

export interface PlaygroundExperimentConfig {
    componentName: string;
    id: string;
    title: string;
    description: string;
    category: string;
    tags: string[];
    code: string;
    language: string;
}

/**
 * Playground experiment metadata. Component is resolved from the auto-discovered demo registry.
 * Add new entry here when adding a demo to the Playground.
 */
const CONFIG: PlaygroundExperimentConfig[] = [
    {
        componentName: 'ConcentricDemo',
        id: 'concentric-radius',
        title: 'Concentric Border Radius',
        description: 'Maintaining geometric continuity in nested corners through mathematical offsets.',
        category: 'UI Logic',
        tags: ['CSS', 'Geometry'],
        code: `.card {
  --inner-radius: 12px;
  --gap: 16px;

  padding: var(--gap);
  border-radius: calc(var(--inner-radius) + var(--gap));
}

.card-content {
  border-radius: var(--inner-radius);
}`,
        language: 'css',
    },
];

export interface PlaygroundExperiment extends PlaygroundExperimentConfig {
    component: (typeof demoRegistry)[string];
}

export const playgroundExperiments: PlaygroundExperiment[] = CONFIG.map((entry) => ({
    ...entry,
    component: demoRegistry[entry.componentName],
})).filter((exp): exp is PlaygroundExperiment => !!exp.component);
