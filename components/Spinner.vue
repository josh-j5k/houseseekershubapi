<script setup lang="ts">

defineProps<{

    class?: string

}>()

</script>

<template>
    <div :class="class">
        <span class="spinner">
            <i class="fa-solid fa-spinner spinner"></i>
        </span>
    </div>

</template>

<style scoped>
.spinner {
    animation: spin 1200ms linear infinite forwards;
}

@keyframes spin {
    0% {
        color: rgb(0, 102, 255);
        scale: 1;
        rotate: 0;
    }

    25% {
        color: rgb(0, 140, 255);
        scale: 0.85;
    }

    50% {
        color: rgb(0, 204, 255);
        scale: 0.75;
    }

    75% {
        color: rgb(0, 140, 255);
        scale: 0.85;
    }

    100% {
        color: rgb(0, 102, 255);
        scale: 1;
        rotate: 360deg;
    }


}
</style>