<script setup lang="ts">
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

gsap.registerPlugin(ScrollTrigger);

const props = withDefaults(
    defineProps<{
        text: string;
        tag?: string;
        delay?: number;
        duration?: number;
        stagger?: number;
        class?: string;
        // ScrollTrigger options
        scrollTrigger?: boolean; // Enable scroll trigger
        scrub?: boolean | number; // Link animation to scroll position
        start?: string; // e.g., "top 80%"
        end?: string; // e.g., "bottom 20%"
        markers?: boolean; // For debugging
    }>(),
    {
        tag: 'span',
        delay: 0,
        duration: 0.8,
        stagger: 0.03,
        class: '',
        scrollTrigger: false,
        scrub: false,
        start: 'top 85%',
        end: 'bottom center',
        markers: false,
    },
);

const container = ref<HTMLElement | null>(null);
let animation: gsap.core.Tween | gsap.core.Timeline | null = null;

const animate = () => {
    if (!container.value) return;

    // Kill existing animation/trigger if re-running
    if (animation) {
        animation.scrollTrigger?.kill();
        animation.kill();
    }

    const chars = container.value.querySelectorAll('.char');

    // Reset initial state
    gsap.set(chars, {
        opacity: 0,
        color: 'oklch(70.8% 0 0)', // neutral-400
        filter: 'blur(4px)',
        y: 8,
        x: 2,
        willChange: 'transform, opacity, filter',
        transformStyle: 'preserve-3d',
        backfaceVisibility: 'hidden',
    });

    const animationConfig: gsap.TweenVars = {
        opacity: 1,
        color: 'inherit',
        filter: 'blur(0px)',
        y: 0,
        x: 0,
        duration: props.duration,
        stagger: props.stagger,
        delay: props.delay,
        ease: 'power2.out',
        clearProps: 'willChange,transformStyle,backfaceVisibility,filter',
    };

    if (props.scrollTrigger) {
        animation = gsap.to(chars, {
            ...animationConfig,
            scrollTrigger: {
                trigger: container.value,
                start: props.start,
                end: props.end,
                scrub: props.scrub,
                markers: props.markers,
                // If not scrubbing, play it once when entering
                toggleActions: props.scrub ? undefined : 'play none none none',
            },
        });
    } else {
        animation = gsap.to(chars, animationConfig);
    }
};

onMounted(async () => {
    await nextTick();
    animate();
});

onUnmounted(() => {
    if (animation) {
        animation.scrollTrigger?.kill();
        animation.kill();
    }
    // Clean up ScrollTrigger instances
    ScrollTrigger.getAll().forEach((t) => {
        if (t.trigger === container.value) t.kill();
    });
});

watch(
    () => props.text,
    async () => {
        await nextTick();
        animate();
    },
);

const words = computed(() => {
    return props.text.split(' ');
});
</script>

<template>
    <component :is="tag" ref="container" :class="['inline', props.class]" aria-label="text">
        <span class="sr-only">{{ text }}</span>
        <template v-for="(word, index) in words" :key="index">
            <span class="inline-block whitespace-nowrap">
                <span v-for="(char, charIndex) in word" :key="charIndex" class="char inline-block" v-html="char"></span>
            </span>
            <span v-if="index < words.length - 1" class="whitespace-normal">{{ ' ' }}</span>
        </template>
    </component>
</template>
