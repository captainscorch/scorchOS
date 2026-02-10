<script setup lang="ts"></script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 p-8 dark:border-white/10 dark:bg-neutral-900">
        <div class="mx-auto max-w-lg">
            <!-- Command Input -->
            <div class="mb-4 flex">
                <div class="rounded-full border border-amber-500/50 bg-neutral-950 px-4 py-1.5 text-sm text-amber-500 dark:bg-black">/ deslop</div>
            </div>

            <!-- Response Card -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-white/10 dark:bg-neutral-950">
                <div
                    class="border-b border-neutral-200 bg-neutral-50 px-4 py-3 font-medium text-neutral-900 dark:border-white/10 dark:bg-white/5 dark:text-white"
                >
                    # Remove AI code slop
                </div>
                <div class="p-4 text-sm leading-relaxed text-neutral-600 dark:text-neutral-400">
                    Check the diff against main, and remove all AI generated slop introduced in this branch. This includes:
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        <li>Extra comments that a human wouldn't add</li>
                        <li>Extra defensive checks or try/catch blocks</li>
                        <li>Casts to any to get around type issues</li>
                        <li>Any other style that is inconsistent</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
