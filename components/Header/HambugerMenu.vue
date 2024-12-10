<script setup lang="ts">
import type { user } from '~/types/user';


defineProps<{
    dropdownToggled: boolean,
    user: undefined | user
}>()

</script>

<template>
    <div v-if="dropdownToggled"
        class="absolute -lg:hidden bg-white shadow py-4 px-8 top-20 right-4  flex-col items-start z-[9998] gap-2 transition-opacity duration-500 ease-in-out"
        id="dashboard_dropdown">
        <ul v-if="user != undefined" class="flex flex-col items-center gap-3">
            <li class="capitalize">
                <NuxtLink :to="{ name: 'au-id', params: { id: user.user.ref } }">dashboard</NuxtLink>
            </li>
            <li class="capitalize">
                <NuxtLink :to="{ name: 'au-id-profile', params: { id: user.user.ref } }">profile</NuxtLink>
            </li>
            <button @click="logout" class="capitalize">
                logout
            </button>
        </ul>
    </div>
</template>