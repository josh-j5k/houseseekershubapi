<script setup lang="ts">

defineProps < {
    dropdownToggled: boolean,
    user: null
} > ()
</script>

<template>
    <div class="absolute -lg:hidden bg-white shadow py-4 px-8 top-20 right-4  flex-col items-start z-[9998] gap-2 transition-opacity duration-500 ease-in-out"
        id="dashboard_dropdown " :class="[dropdownToggled ? 'flex opacity-100' : 'hidden opacity-0']">
        <ul v-if="user" class="flex flex-col items-center gap-3">
            <li class="capitalize">
                <NuxtLink to="dashboard">dashboard</NuxtLink>
            </li>
            <li class="capitalize">
                <NuxtLink to="dashboard/profile">profile</NuxtLink>
            </li>
            <li @click="console.log('logout')" class="capitalize">
                logout
            </li>
        </ul>
    </div>
</template>