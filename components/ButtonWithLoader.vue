<script setup lang="ts">
withDefaults(
    defineProps<{
        id?: string,
        loading?: boolean,
        color?: string,
        hover_color?: string,
        type?: 'submit' | 'reset' | 'button'
    }>(),
    {
        loading: false,
        color: 'bg-accent',
        hover_color: 'accent-hover',
        type: 'button'
    }
);
</script>

<template>
    <button :type :id :disabled="loading"
        class="flex gap-2 justify-center items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        :class="[loading && 'opacity-75', `${color} hover:bg-${hover_color} focus:bg-${hover_color}`]">
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