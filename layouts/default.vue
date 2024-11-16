<script setup lang="ts">
if (import.meta.client) {
    const user = localStorage.getItem('user')
    if (user && useState('user').value == undefined) {
        useState('user', () => JSON.parse(user))
    }
}
</script>
<template>
    <AppHeader />
    <slot />
    <AppFooter />
</template>