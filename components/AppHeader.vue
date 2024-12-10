<script setup lang="ts">
import type { user } from '~/types/user';


const user = computed(() => useState('user').value as user)
const toggled = ref(false)
const dropdownToggled = ref(false)
const activeLinkClass = "text-accent font-bold lg:before:content-[''] lg:before:absolute lg:before:w-full lg:before:h-0.5 lg:before:bottom-0 lg:before:bg-accent"

const hover = "relative hover:after:content-[''] hover:after:absolute hover:after:w-full hover:after:h-0.5 hover:after:bottom-0 hover:after:bg-accent hover:after:left-0"
function navToggle() {
    toggled.value = !toggled.value

    if (toggled.value === true) {
        document.body.classList.add('overflow-y-hidden')
        setTimeout(() => {
            document.documentElement.addEventListener('click', closeDropdown)
        }, 10);
    } else {
        document.documentElement.removeEventListener('click', closeDropdown)
        document.body.classList.remove('overflow-y-hidden')

    }
}

function closeDropdown(ev: MouseEvent) {
    const element = ev.target as HTMLElement

    if (dropdownToggled.value === true && !element.closest('#dashboard_dropdown-toggle')) {
        dropdownToggled.value = false

    }
    if (element.id !== 'nav-container' && !element.closest('#dashboard_dropdown-toggle')) {
        toggled.value = false
        document.documentElement.removeEventListener('click', closeDropdown)
        document.body.classList.remove('overflow-y-hidden')
    }
}


onMounted(() => {

})
onUnmounted(() => {
    if (toggled.value === true) {
        document.body.classList.remove('overflow-y-hidden')
    }
    document.removeEventListener('click', closeDropdown)
})
</script>
<template>
    <header class="h-24 w-full flex flex-col z-[9990] justify-center lg:px-16 px-8 overflow-x-hidden"
        :class="$route.name === 'index' ? 'absolute inset-0 bg-transparent text-white' : 'bg-white text-black relative shadow'">
        <div class=" lg:grid lg:grid-cols-[20%_80%] grid-cols-2 -lg:flex -lg:justify-between items-center">
            <ApplicationLogo class=" w-44" :type="$route.name === 'index' ? 'white' : 'indigo'" />
            <button id="nav-toggle" type="button" @click="navToggle" class="lg:hidden z-[990] relative "
                :class="toggled ? 'text-black' : 'text-white'" title="nav toggle" aria-controls="primary-nav"
                :aria-expanded="toggled" aria-haspopup="true">
                <span :class="toggled ? 'opacity-0' : 'opacity-100'"><i
                        class="fas fa-bars fa-2xl text-accent"></i></span>
                <span :class="toggled ? 'opacity-100' : 'opacity-0'"><i class="fas fa-xmark fa-xl"></i></span>
            </button>
            <div id="nav-container"
                class="flex lg:items-center lg:justify-between -lg:fixed inset-0 -lg:bg-white -lg:shadow  -lg:text-gray-800 transition-transform duration-500 -lg:flex-col -lg:p-4 -lg:translate-x-full">
                <nav id="primary-nav">
                    <ul class="flex gap-4 h-full -lg:flex-col -lg:pt-16 -lg:px-8 text-sm -lg:text-xl -lg:font-bold">
                        <li :class="[$route.name === 'index' && activeLinkClass, `${hover}`]">
                            <NuxtLink to="/">Home</NuxtLink>
                        </li>
                        <li :class="[$route.name === 'listings' && activeLinkClass, `${hover}`]">
                            <NuxtLink to="/listings">Listings</NuxtLink>
                        </li>
                        <li :class="[$route.name === 'new-homes' && activeLinkClass, `${hover}`]">
                            <NuxtLink to="#">New Homes</NuxtLink>
                        </li>
                        <li :class="[$route.name === 'commercial' && activeLinkClass, `${hover}`]">
                            <NuxtLink to="#">Commercial</NuxtLink>
                        </li>
                        <li :class="[$route.name === 'contact' && activeLinkClass, `${hover}`]">
                            <NuxtLink to="/contact">Contact Us</NuxtLink>
                        </li>
                        <li :class="[$route.name === 'about' && activeLinkClass, `${hover}`]">
                            <NuxtLink to="/about">About Us</NuxtLink>
                        </li>
                    </ul>

                </nav>
                <div class="flex gap-6 -lg:justify-between -lg:pt-8 -lg:border-t">

                    <div class="flex items-center gap-2 h-12">
                        <ul>
                            <li>
                                <NuxtLink :to="{ name: 'listings-create' }"
                                    class="text-white hover:bg-accent-hover bg-accent flex items-center justify-center gap-2 h-9 w-32 rounded-md ">
                                    <span>
                                        <i class="fas fa-circle-plus"></i>
                                    </span>
                                    <span class="capitalize ">
                                        new listing
                                    </span>
                                </NuxtLink>
                            </li>
                        </ul>
                    </div>

                    <ClientOnly>
                        <div class="flex items-center relative">
                            <button v-if="user == undefined" type="button" title="login or register"
                                @click="$router.push('/login')" class="flex items-center gap-2 h-12 ">
                                <span
                                    class="w-8 aspect-square rounded-full flex justify-center items-end border-2 border-accent overflow-hidden">
                                    <i class="fa-regular fa-user text-2xl text-accent translate-y-1.5"></i>
                                </span>
                                <span>
                                    Sign in
                                </span>
                            </button>

                            <div v-else class="relative">
                                <button @click="dropdownToggled = !dropdownToggled" id="dashboard_dropdown-toggle"
                                    type="button" class="flex gap-2.5 items-center dashboard_dropdown-toggle">
                                    <span v-if="user.user.avatar" id="avatar">
                                        <img :src="user.user.avatar" alt="user avatar"
                                            class="w-8 aspect-square rounded-full">
                                    </span>
                                    <span id="avatar" v-else
                                        class="w-8 aspect-square flex justify-center items-center bg-slate-900 rounded-full text-white border border-accent">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <span id="user_name">
                                        {{ user.user.name.split(' ')[0] }}
                                    </span>
                                    <span id="dropdown-icon" class="text-sm">
                                        <i class="fas fa-caret-down"></i>
                                    </span>
                                </button>
                                <div class="absolute -md:top-10 lg:hidden bg-gray-50 shadow py-4 w-36 right-0 flex-col items-center text-lg transition-opacity duration-500 ease-in-out"
                                    id="dashboard_dropdown "
                                    :class="[dropdownToggled ? 'flex opacity-100' : 'hidden opacity-0']">
                                    <nav>
                                        <ul class="flex flex-col gap-4 ">
                                            <li class="capitalize">
                                                <NuxtLink :to="{ name: 'au-id', params: { id: user.user.ref } }">
                                                    dashboard
                                                </NuxtLink>
                                            </li>
                                            <li class="capitalize">
                                                <NuxtLink
                                                    :to="{ name: 'au-id-profile', params: { id: user.user.ref } }">
                                                    profile
                                                </NuxtLink>
                                            </li>
                                            <li role="button" @click="logout" class="capitalize cursor-pointer">
                                                logout
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>



                        </div>

                        <template #fallback>
                            <button title="login or register" class="flex items-center gap-2 h-12 ">
                                <span
                                    class="w-8 aspect-square rounded-full flex justify-center items-end border-2 border-accent overflow-hidden">
                                    <i class="fa-regular fa-user text-2xl text-accent translate-y-1.5"></i>
                                </span>
                                <span>
                                    Sign in
                                </span>
                            </button>
                        </template>
                    </ClientOnly>

                </div>
            </div>
        </div>
        <hr class="w-[80%] h-[1px] mx-auto bg-white opacity-20 mt-4 -lg:hidden"
            :class="[$route.name === 'index' ? '' : 'hidden']">
    </header>


    <ClientOnly>
        <!-- Mobile hamburger menu -->
        <HeaderHambugerMenu :dropdown-toggled :user="user" />
    </ClientOnly>
</template>

<style scoped>
@media (max-width: 1023px) {
    #nav-toggle {
        transition: transform 100ms ease-in;
    }

    #nav-toggle[aria-expanded="false"] {
        transform: translateX(0);
    }

    #nav-toggle[aria-expanded="true"] {
        transform: translateX(-20px);
    }

    #nav-toggle~div {
        transition: transform 300ms ease-in;
    }

    #nav-toggle[aria-expanded="false"]~div {
        transform: translateX(100%);
    }

    #nav-toggle[aria-expanded="true"]~div {
        transform: translateX(0);
    }
}
</style>