<script setup lang="ts">

const user = computed(() => null)
const userNameArr = ref(<string[]>[])
const userName = ref('')
const toggled = ref(false)
const dropdownToggled = ref(false)
const activeLinkClass = "text-accent font-bold lg:border-b-2 lg:pb-[1px] lg:border-accent"
// if (user.value) {
//     userNameArr.value = user.value.name.split(' ')
//     userName.value = userNameArr.value[0]
// }
function navToggle() {
    toggled.value = !toggled.value
    if (toggled.value === true) {
        document.body.classList.add('overflow-y-hidden')
    } else {

        document.body.classList.remove('overflow-y-hidden')

    }
}

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
    if (toggled.value === true) {
        document.body.classList.remove('overflow-y-hidden')
    }
    document.removeEventListener('click', closeDropdown)
})
</script>
<template>
    <header class="h-24 w-full flex flex-col z-[9990] justify-center lg:px-16 px-8 overflow-x-hidden"
        :class="useRoute().name === 'index' ? 'absolute inset-0 bg-transparent text-white' : 'bg-white text-black relative shadow'">
        <div class=" lg:grid lg:grid-cols-[20%_80%] grid-cols-2 -lg:flex -lg:justify-between items-center">
            <h1>
                House seekers hub
            </h1>
            <button id="nav-toggle" type="button" @click="navToggle" class="lg:hidden z-[990] relative "
                :class="toggled ? 'text-black' : 'text-white'" title="nav toggle" aria-controls="primary-nav"
                :aria-expanded="toggled" aria-haspopup="true">
                <span :class="toggled ? 'opacity-0' : 'opacity-100'"><i
                        class="fas fa-bars fa-2xl text-accent"></i></span>
                <span :class="toggled ? 'opacity-100' : 'opacity-0'"><i class="fas fa-xmark fa-xl"></i></span>
            </button>
            <div
                class="flex lg:items-center lg:justify-between -lg:fixed inset-0 -lg:bg-white -lg:shadow  -lg:text-gray-800 transition-transform duration-500 -lg:flex-col -lg:p-4 -lg:translate-x-full">
                <nav id="primary-nav">
                    <ul class="flex gap-4 h-full -lg:flex-col -lg:pt-16 -lg:px-8 text-sm -lg:text-xl -lg:font-bold">
                        <li :class="[useRoute().name === 'index' && activeLinkClass]"
                            class="hover:border-b-2 border-accent">
                            <NuxtLink to="/">Home</NuxtLink>
                        </li>
                        <li :class="[useRoute().name === 'listings' && activeLinkClass]"
                            class="hover:border-b-2 border-accent">
                            <NuxtLink to="/listings">Listings</NuxtLink>
                        </li>
                        <li class="hover:border-b-2 border-accent">
                            <NuxtLink to="#">New Homes</NuxtLink>
                        </li>
                        <li class="hover:border-b-2 border-accent">
                            <NuxtLink to="#">Commercial</NuxtLink>
                        </li>
                        <li :class="[useRoute().name === 'contact' && activeLinkClass]"
                            class="hover:border-b-2 border-accent">
                            <NuxtLink to="/contact">Contact Us</NuxtLink>
                        </li>
                        <li :class="[useRoute().name === 'about' && activeLinkClass]"
                            class="hover:border-b-2 border-accent">
                            <NuxtLink to="/about">About Us</NuxtLink>
                        </li>
                    </ul>

                </nav>
                <div class="flex gap-6 -lg:justify-between -lg:pt-8 -lg:border-t">

                    <div class="flex items-center gap-2 h-12">
                        <button type="button" title="New listing" @click="useRouter().push('/listings/create')"
                            class="text-white hover:bg-accent-hover bg-accent flex items-center justify-center gap-2 h-9 w-32 rounded-md ">
                            <span>
                                <i class="fas fa-circle-plus"></i>
                            </span>
                            <span class="capitalize">
                                new listing
                            </span>
                        </button>
                    </div>
                    <div class="flex items-center relative">
                        <button v-if="!user" type="button" title="login or register" @click="useRouter().push('/login')"
                            class="flex items-center gap-2 h-12 ">
                            <span
                                class="w-8 aspect-square rounded-full flex justify-center items-end border-2 border-accent overflow-hidden">
                                <i class="fa-regular fa-user text-2xl text-accent translate-y-1.5"></i>
                            </span>
                            <span>
                                Sign in
                            </span>
                        </button>

                        <button @click="dropdownToggled = !dropdownToggled" id="dashboard_dropdown-toggle" v-else
                            type="button" class="flex gap-2.5 items-center dashboard_dropdown-toggle">
                            <span v-if="user" id="avatar">
                                <img :src="user" alt="user avatar" class="w-8 aspect-square rounded-full">
                            </span>
                            <span id="avatar" v-else
                                class="w-8 aspect-square flex justify-center items-center bg-slate-900 rounded-full text-white border border-accent">
                                <i class="fas fa-user"></i>
                            </span>
                            <span id="user_name">
                                {{ userName }}
                            </span>
                            <span id="dropdown-icon" class="text-sm">
                                <i class="fas fa-caret-down"></i>
                            </span>
                        </button>
                        <div v-if="user"
                            class="absolute lg:hidden bg-gray-50 shadow py-4 px-8 -top-[450%] right-0 flex-col items-start gap-2 transition-opacity duration-500 ease-in-out"
                            id="dashboard_dropdown "
                            :class="[dropdownToggled ? 'flex opacity-100' : 'hidden opacity-0']">
                            <nav>
                                <ul>
                                    <li class="capitalize">
                                        <NuxtLink :to="{ name: 'au-id', params: { id: 1 } }">dashboard</NuxtLink>
                                    </li>
                                    <li class="capitalize">
                                        <NuxtLink :to="{ name: 'au-id-profile', params: { id: 1 } }">profile</NuxtLink>
                                    </li>
                                    <li @click="console.log('logout')" class="capitalize">
                                        logout
                                    </li>
                                </ul>
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="w-[80%] h-[1px] mx-auto bg-white opacity-20 mt-4 -lg:hidden"
            :class="[useRoute().name === 'index' ? '' : 'hidden']">
    </header>


    <!-- Mobile hamburger menu -->

    <HeaderHambugerMenu :dropdown-toggled :user />
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