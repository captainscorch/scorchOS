<script setup lang="ts">
import gsap from 'gsap';
import { onMounted, ref } from 'vue';

defineProps<{
    show?: boolean;
}>();

const logoRef = ref<SVGElement | null>(null);

onMounted(() => {
    if (logoRef.value) {
        const paths = logoRef.value.querySelectorAll('path');

        gsap.set(paths, { opacity: 0, scale: 0.8, transformOrigin: 'center center' });

        const tl = gsap.timeline({ repeat: -1 });

        tl.to(paths, {
            opacity: 1,
            scale: 1,
            duration: 0.8,
            stagger: 0.1,
            ease: 'back.out(1.7)',
        }).to(paths, {
            opacity: 0,
            scale: 1.1,
            duration: 0.6,
            stagger: 0.05,
            ease: 'power2.in',
            delay: 0.5,
        });
    }
});
</script>

<template>
    <Transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-500 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show" class="fixed inset-0 z-[1000] flex items-center justify-center bg-white dark:bg-black">
            <div class="relative flex flex-col items-center gap-8">
                <svg
                    ref="logoRef"
                    version="1.1"
                    id="logo"
                    xmlns="http://www.w3.org/2000/svg"
                    x="0"
                    y="0"
                    viewBox="0 0 56.2 46.5"
                    xml:space="preserve"
                    class="h-24 w-auto"
                >
                    <g>
                        <path
                            class="fill-teal-black dark:fill-white"
                            d="M13.2 32.1c0 3.2 2.6 5.7 5.7 5.7h18.3c3.2 0 5.7-2.6 5.7-5.7V23H36v4c0 2.2-1.8 4-4 4H13.2v1.1z"
                        />
                        <path
                            class="fill-teal-black dark:fill-white"
                            d="M42.9 13.8c0-3.1-2.6-5.7-5.7-5.7H19c-3.2 0-5.7 2.6-5.7 5.7v9.1h6.9v-4c0-2.2 1.8-4 4-4H43v-1.1z"
                        />
                        <path
                            class="fill-teal-black dark:fill-white"
                            d="M25.8 19.7c-.5 0-.9.4-.9.9v4.6c0 .5.4.9.9.9h4.6c.5 0 .9-.4.9-.9v-4.6c0-.5-.4-.9-.9-.9h-4.6z"
                        />
                        <path
                            class="fill-teal-black dark:fill-white"
                            d="M47.9 33.8c0 5.1-4.2 9.3-9.3 9.3H17.5c-5.1 0-9.3-4.2-9.3-9.3V30.9H5.6v2.9c0 6.6 5.3 11.9 11.9 11.9h21.2c6.6 0 11.9-5.3 11.9-11.9V22.9H48v10.9z"
                        />
                        <path
                            class="fill-teal-black dark:fill-white"
                            d="M38.7.7H17.5C10.9.7 5.6 6 5.6 12.6v10.3h2.6V12.6c0-5.1 4.2-9.3 9.3-9.3h21.2c5.1 0 9.3 4.2 9.3 9.3V15h2.6v-2.3c0-6.6-5.4-12-11.9-12z"
                        />
                    </g>
                </svg>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
#logo {
    filter: drop-shadow(0 0 20px rgba(42, 177, 147, 0.1));
}

.dark #logo {
    filter: drop-shadow(0 0 20px rgba(42, 177, 147, 0.2));
}
</style>
