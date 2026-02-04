<script setup lang="ts">
interface Props {
    src: string;
    alt?: string;
    caption?: string;
    class?: string;
}

withDefaults(defineProps<Props>(), {
    alt: '',
    caption: '',
    class: '',
});
</script>

<template>
    <figure :class="['markdown-image group relative my-12', $props.class]">
        <div
            class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-100 transition-all duration-500 hover:border-neutral-300 hover:shadow-2xl dark:border-white/10 dark:bg-neutral-900 dark:hover:border-white/20"
        >
            <img :src="src" :alt="alt" class="h-auto w-full object-cover transition-transform duration-700 group-hover:scale-[1.02]" loading="lazy" />
        </div>

        <figcaption v-if="caption" class="mx-auto mt-4 max-w-lg px-2 text-center">
            <span class="inline-block text-sm font-medium text-neutral-500 dark:text-neutral-500"> // {{ caption }} </span>
        </figcaption>
    </figure>
</template>

<style scoped>
.markdown-image {
    clear: both;
}
</style>
