<script setup lang="ts">
withDefaults(
    defineProps<{
        class?: string;
        type?: 'white' | 'blue' | 'indigo' | 'dark';
    }>(),
    {
        type: 'indigo',
    }
)
</script>

<template>
    <NuxtLink to="/">
        <img :class="class"
            :src="type == 'blue' ? '/logo blue.png' : type == 'white' ? '/logo white.png' : type == 'dark' ? '/logo dark.png' : '/logo.png'"
            alt="App logo">
    </NuxtLink>
</template>