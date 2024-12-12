<script setup lang="ts">
defineProps<{
    class: string
}>()
</script>


<template>
    <div :class="class" class="skeleton"></div>


</template>

<style scoped>
.skeleton {
    background-image: linear-gradient(to right,
            #f1f1f1 0%,
            #ececec 10%,
            #dbdbdb 20%,
            #cfcfcf 100%);
    background-size: 200% 100%;

    animation: background_animate 1.2s linear infinite;
}

@keyframes background_animate {
    from {
        background-position: 50% 0;
    }

    to {
        background-position: -150% 0;
    }
}
</style>