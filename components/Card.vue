<script setup lang="ts">
defineProps<{
    class?: string,
}>()
</script>

<template>
    <div class="shadow rounded-lg" :class="class">
        <slot />
    </div>
</template>