<script setup lang="ts">
const badPrompt = 'Make the submit button show loading.';
const goodPrompt =
    'Disable the button and show a spinner inside it while the request is in flight. Re-enable it and hide the spinner when the response comes back, or after 10 seconds as a fallback.';
</script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <div class="grid gap-6 p-8 md:grid-cols-2">
            <!-- Bad Prompt -->
            <div class="flex flex-col gap-4">
                <div class="rounded-2xl bg-neutral-950 p-6 text-sm leading-relaxed text-neutral-400 shadow-xl dark:bg-black">
                    <p>{{ badPrompt }}</p>

                    <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                        <div class="flex items-center gap-2">
                            <div
                                class="flex size-8 items-center justify-center rounded-full bg-neutral-800/50 p-0 text-xs font-medium text-neutral-400 md:h-8 md:w-fit md:flex-row md:gap-1.5 md:px-3"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-sparkles"
                                >
                                    <path
                                        d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"
                                    />
                                </svg>
                                <span class="hidden md:inline">Claude Opus 4.5</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-neutral-600">
                            <!-- At Sign -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-at-sign hover:text-neutral-400"
                            >
                                <circle cx="12" cy="12" r="4" />
                                <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94" />
                            </svg>
                            <!-- Globe -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-globe hover:text-neutral-400"
                            >
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                <path d="M2 12h20" />
                            </svg>
                            <!-- Image -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-image hover:text-neutral-400"
                            >
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                <circle cx="9" cy="9" r="2" />
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                            </svg>

                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-neutral-800">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="12"
                                    height="12"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-arrow-up text-neutral-400"
                                >
                                    <path d="m5 12 7-7 7 7" />
                                    <path d="M12 19V5" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-x"
                        >
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Good Prompt -->
            <div class="flex flex-col gap-4">
                <div class="rounded-2xl bg-neutral-950 p-6 text-sm leading-relaxed text-neutral-400 shadow-xl dark:bg-black">
                    <p>{{ goodPrompt }}</p>

                    <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                        <div class="flex items-center gap-2">
                            <div
                                class="flex size-8 items-center justify-center rounded-full bg-neutral-800/50 p-0 text-xs font-medium text-neutral-400 md:h-8 md:w-fit md:flex-row md:gap-1.5 md:px-3"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-sparkles"
                                >
                                    <path
                                        d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"
                                    />
                                </svg>
                                <span class="hidden md:inline">Claude Opus 4.5</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-neutral-600">
                            <!-- At Sign -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-at-sign hover:text-neutral-400"
                            >
                                <circle cx="12" cy="12" r="4" />
                                <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94" />
                            </svg>
                            <!-- Globe -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-globe hover:text-neutral-400"
                            >
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                <path d="M2 12h20" />
                            </svg>
                            <!-- Image -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-image hover:text-neutral-400"
                            >
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                <circle cx="9" cy="9" r="2" />
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                            </svg>

                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-neutral-800">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="12"
                                    height="12"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-arrow-up text-neutral-400"
                                >
                                    <path d="m5 12 7-7 7 7" />
                                    <path d="M12 19V5" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-white">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-check"
                        >
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
