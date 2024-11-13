<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        id?: string,
        loading?: boolean
    }>(),
    {
        loading: false
    }
);
</script>

<template>
    <button :id="id" :disabled="loading"
        class="flex gap-2 items-center px-4 py-2 bg-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-accent-hover focus:bg-accent-hover active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        :class="loading && 'opacity-75'">
        <span v-if="loading">
            <i class="fa-solid fa-spinner spinner"></i>
        </span>
        <slot />
    </button>
</template>

<style scoped>
.spinner {
    animation: spin 1000ms linear infinite forwards;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>