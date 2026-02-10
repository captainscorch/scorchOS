<script setup lang="ts"></script>

<template>
    <div class="interactive-demo overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50 dark:border-white/10 dark:bg-neutral-900">
        <div class="grid md:grid-cols-2">
            <!-- Messy Prompts -->
            <div class="flex flex-col border-b border-neutral-200 md:border-r md:border-b-0 dark:border-white/10">
                <div class="relative flex h-[300px] w-full flex-col items-center justify-center overflow-hidden p-8">
                    <!-- Stacked Messy Bubbles -->
                    <div class="relative ml-10 flex h-full w-full items-center justify-center">
                        <div
                            class="absolute top-[20%] left-[15%] rotate-[-6deg] rounded-full border border-neutral-200 bg-neutral-950 px-5 py-2.5 text-sm whitespace-nowrap text-neutral-400 shadow-lg ring-1 ring-white/5 dark:border-white/10 dark:bg-black"
                        >
                            Fix this
                        </div>
                        <div
                            class="absolute top-[32%] right-[25%] rotate-[3deg] rounded-full border border-neutral-200 bg-neutral-950 px-5 py-2.5 text-sm whitespace-nowrap text-neutral-400 shadow-lg ring-1 ring-white/5 dark:border-white/10 dark:bg-black"
                        >
                            Also that
                        </div>
                        <div
                            class="absolute top-[45%] left-[5%] rotate-[-2deg] rounded-full border border-neutral-200 bg-neutral-950 px-5 py-2.5 text-sm whitespace-nowrap text-neutral-400 shadow-lg ring-1 ring-white/5 dark:border-white/10 dark:bg-black"
                        >
                            One more thing
                        </div>
                        <div
                            class="absolute top-[60%] right-[20%] rotate-[4deg] rounded-full border border-neutral-200 bg-neutral-950 px-5 py-2.5 text-sm whitespace-nowrap text-neutral-400 shadow-lg ring-1 ring-white/5 dark:border-white/10 dark:bg-black"
                        >
                            Forgot to mention
                        </div>
                        <div
                            class="absolute bottom-[5%] left-[10%] rotate-[-3deg] rounded-full border border-neutral-200 bg-neutral-950 px-5 py-2.5 text-sm whitespace-nowrap text-neutral-400 shadow-lg ring-1 ring-white/5 dark:border-white/10 dark:bg-black"
                        >
                            This one's broken
                        </div>
                    </div>
                </div>

                <div class="flex justify-center border-t border-neutral-200 bg-white py-6 dark:border-white/10 dark:bg-neutral-950">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow-lg shadow-red-500/20">
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

            <!-- Structured Workflow -->
            <div class="flex flex-col">
                <div class="flex h-[300px] flex-col items-center justify-center p-8">
                    <div class="flex flex-col items-center gap-4">
                        <div
                            class="rounded-full border border-neutral-200 bg-neutral-950 px-6 py-3 text-sm font-medium whitespace-nowrap text-neutral-300 shadow-lg ring-1 ring-white/5 dark:border-white/10 dark:bg-black"
                        >
                            First step
                        </div>

                        <div class="relative flex h-16 flex-col items-center justify-center">
                            <div class="h-full w-px bg-neutral-200 dark:bg-neutral-800"></div>
                            <div
                                class="absolute rounded-full border border-neutral-200 bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-500 dark:border-neutral-800 dark:bg-neutral-900"
                            >
                                then
                            </div>
                        </div>

                        <div
                            class="rounded-full border border-neutral-200 bg-neutral-950 px-6 py-3 text-sm font-medium whitespace-nowrap text-neutral-300 shadow-lg ring-1 ring-white/5 dark:border-white/10 dark:bg-black"
                        >
                            Next step
                        </div>
                    </div>
                </div>

                <div class="flex justify-center border-t border-neutral-200 bg-white py-6 dark:border-white/10 dark:bg-neutral-950">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
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
