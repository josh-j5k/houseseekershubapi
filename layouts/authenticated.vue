<script setup lang="ts">
import type { user } from '~/types/user';

const authUser = useState('user').value as user
const id = useRoute().params.id

const dropdownToggled = ref(false)

function closeDropdown(ev: MouseEvent) {
    const element = ev.target as HTMLElement
    if (dropdownToggled.value === true && !element.closest('#dashboard_dropdown-toggle')) {
        dropdownToggled.value = false

    }
}


onMounted(() => {
    document.documentElement.addEventListener('click', closeDropdown)

})
onUnmounted(() => {
    document.removeEventListener('click', closeDropdown)
})
</script>

<template>
    <ClientOnly fallback-tag="span">
        <template v-if="id && id !== authUser.user.ref">
            <div>
                No resource found
            </div>
        </template>
        <template v-else>
            <section class="lg:h-screen lg:w-screen">
                <div class="lg:grid lg:grid-cols-[15%_85%]">
                    <div
                        class="lg:border-l lg:min-h-screen -lg:w-full -lg:fixed z-10 bottom-0 shadow lg:p-2 bg-gradient-to-br from-slate-100 to-blue-300 flex lg:flex-col bg- lg:gap-4 -lg:justify-evenly">
                        <div class="lg:mb-20 pt-8 flex justify-center">
                            <ApplicationLogo class="w-36 -md:hidden" type="dark" />
                        </div>
                        <NuxtLink :to="{ name: 'au-id', params: { id: authUser.user.ref } }"
                            class=" py-2 px-3 flex -lg:flex-col lg:gap-3 rounded"
                            :class="[$route.name == 'au-id' ? 'bg-slate-100 shadow-sm text-black font-bold' : 'text-gray-600']">
                            <span class="-lg:text-2xl text-center">
                                <i class="fas fa-home"></i>
                            </span>
                            <span>
                                Home
                            </span>
                        </NuxtLink>
                        <NuxtLink :to="{ name: 'au-id-messages', params: { id: authUser.user.ref } }"
                            class="py-2 px-3 flex -lg:flex-col lg:gap-3 rounded"
                            :class="[$route.name == 'au-id-messages' ? 'bg-slate-100 shadow-sm text-black font-bold' : 'text-gray-600']">
                            <span class="-lg:text-2xl text-center">
                                <i class="fas fa-message"></i>
                            </span>
                            <span>
                                Messages
                            </span>
                        </NuxtLink>
                        <NuxtLink :to="{ name: 'au-id-bookmarks', params: { id: authUser.user.ref } }"
                            class="py-2 px-3 flex -lg:flex-col lg:gap-3 rounded"
                            :class="[$route.name == 'au-id-bookmarks' ? 'bg-slate-100 shadow-sm text-black font-bold' : 'text-gray-600']">
                            <span class="-lg:text-2xl text-center">
                                <i class="fas fa-bookmark"></i>
                            </span>
                            <span>
                                Bookmarks
                            </span>
                        </NuxtLink>
                        <NuxtLink :to="{ name: 'au-id-profile', params: { id: authUser.user.ref } }"
                            class="py-2 px-3 flex -lg:flex-col lg:gap-3 rounded"
                            :class="[$route.name == 'au-id-profile' ? 'bg-slate-100 shadow-sm text-black font-bold' : 'text-gray-600']">
                            <span class="-lg:text-2xl text-center">
                                <i class="fas fa-user"></i>
                            </span>
                            <span>
                                Profile
                            </span>
                        </NuxtLink>
                        <NuxtLink @click="logout" class="py-2 px-3 text-gray-600 flex -lg:flex-col lg:gap-3 rounded">
                            <span class="-lg:text-2xl text-center">
                                <i class="fas fa-right-from-bracket"></i>
                            </span>
                            <span>
                                Logout
                            </span>
                        </NuxtLink>
                    </div>
                    <div>
                        <div class="border-b w-full flex justify-between items-center px-8 py-4">
                            <div>
                                <ApplicationLogo class="w-24 md:hidden" type="dark" />
                                <div class="flex items-center gap-1 font-bold -md:hidden">
                                    <span>

                                        Hi,
                                    </span>

                                    <span>
                                        {{ authUser.user.name.split(" ")[0] }}
                                    </span>
                                    <span class="-rotate-45 text-orange-200">
                                        <i class="fas fa-hand"></i>
                                    </span>
                                </div>

                            </div>
                            <div class="flex gap-2 items-center">

                                <button type="button" title="New listing" @click=" $router.push('/listings/create')"
                                    class="text-white -md:text-accent -md:text-2xl md:hover:bg-accent-hover md:bg-accent flex items-center justify-center gap-2 h-8 w-28 -md:fit -md:aspect-square -md:h-6 -md:rounded-full rounded-md text-sm ">
                                    <span class="">
                                        <i class="fas fa-circle-plus aspect-square "></i>
                                    </span>
                                    <span class="capitalize text-sm">
                                        new listing
                                    </span>
                                </button>

                                <div class=" relative">

                                    <button @click="dropdownToggled = !dropdownToggled" id="dashboard_dropdown-toggle"
                                        type="button" class="flex gap-2.5 items-center dashboard_dropdown-toggle">
                                        <span v-if="authUser.user.avatar">

                                            <img :src="authUser.user.avatar" alt="user avatar"
                                                class="w-8 aspect-square rounded-full">
                                        </span>
                                        <span v-else
                                            class="w-8 aspect-square flex justify-center items-center bg-slate-900 rounded-full text-white border border-accent">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <!-- <span id="user_name">
                                            {{ authUser.user.name.split(" ")[0] }}
                                        </span> -->
                                        <span id="dropdown-icon" class="text-sm">
                                            <i class="fas fa-caret-down"></i>
                                        </span>
                                    </button>
                                    <div class="absolute bg-white shadow py-4 px-8 z-50 top-10 -right-4  flex-col items-start gap-2 transition-opacity duration-500 ease-in-out"
                                        id="dashboard_dropdown "
                                        :class="[dropdownToggled ? 'flex opacity-100' : 'hidden opacity-0']">

                                        <NuxtLink :to="{ name: 'au-id', params: { id: authUser.user.ref } }"
                                            class="capitalize">
                                            dashboard
                                        </NuxtLink>
                                        <NuxtLink :to="{ name: 'au-id-profile', params: { id: authUser.user.ref } }"
                                            class="capitalize">
                                            profile
                                        </NuxtLink>
                                        <button @click="logout" as="button" class="capitalize cursor-pointer">
                                            logout
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <main>
                            <slot />
                        </main>
                    </div>
                </div>

            </section>
        </template>
        <!-- SSR fallbck -->
        <template #fallback>
            <p>Loading...</p>
        </template>
    </ClientOnly>
</template>
