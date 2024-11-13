<script setup lang="ts">

const year = new Date().getFullYear()
const pagesToShowFooterBanner = ['index', 'contact', 'about']
</script>

<template>
    <footer class="lg:px-12 lg:pt-32 bg-slate-950 text-white relative">
        <div v-if="pagesToShowFooterBanner.includes(useRoute.name)"
            class="lg:w-[80%] w-full mx-auto lg:absolute z-50 left-1/2 lg:-translate-x-1/2 -top-1/3 grid lg:grid-cols-2 grid-cols-1 gap-4 lg:gap-16 items-center lg:px-16 p-8 lg:py-2 lg:translate-y-20 bg-accent-hover lg:rounded-lg">
            <div class="-lg:text-center">
                <p class="text-2xl mb-4">
                    Start listing or buying a property with House Seeker's Hub
                </p>
                <NuxtLink to="listings/create" class="capitalize bg-secondary text-white py-2 px-4 rounded">
                    get started
                </NuxtLink>
            </div>
            <div class="flex justify-center">
                <House />
            </div>
        </div>
        <div class="w-[90%] mx-auto grid py-20 grid-cols-1 lg:grid-cols-[30%_20%_20%_30%] gap-8">
            <div>
                <h1>House seekers hub</h1>
                <p class="text-sm mt-4">
                    Every property you see advertised with us is on the market with full-service estate or letting
                    agents
                    who are experts in their local area.

                </p>
                <h3 class="mt-4 font-bold">Socials</h3>
                <div class="flex gap-4 text-accent-hover">
                    <span><i class="fa-brands fa-instagram"></i></span>
                    <span><i class="fa-brands fa-facebook"></i></span>
                    <span><i class="fa-brands fa-twitter"></i></span>
                </div>
            </div>
            <div class="flex flex-col">
                <h2 class="text-2xl font-bold mb-3 text-slate-100">
                    Top Pages
                </h2>
                <div class="flex flex-col gap-2 text-sm">
                    <nav>
                        <ul>
                            <li class=" hover:text-accent-hover">
                                <NuxtLink to="/">
                                    Home
                                </NuxtLink>
                            </li>
                            <li class=" hover:text-accent-hover">
                                <NuxtLink to="/about">
                                    About Us
                                </NuxtLink>
                            </li>
                            <li class=" hover:text-accent-hover">
                                <NuxtLink to="/contact">
                                    Contact Us
                                </NuxtLink>
                            </li>
                            <li class=" hover:text-accent-hover">
                                <NuxtLink to="#">
                                    Terms & Conditions
                                </NuxtLink>
                            </li>
                            <li class=" hover:text-accent-hover">
                                <NuxtLink to="#">
                                    User's Guide
                                </NuxtLink>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
            <div class="flex flex-col">
                <h2 class="text-2xl font-bold mb-3 text-slate-100">
                    Popular Searches
                </h2>
                <div class="flex flex-col gap-2 text-sm">
                    <a href="#" class=" hover:text-accent-hover">
                        Appartment for rent
                    </a>
                    <a href="#" class=" hover:text-accent-hover">
                        House Low to High Price
                    </a>
                    <a href="#" class=" hover:text-accent-hover">
                        Office to Buy
                    </a>
                    <a href="#" class=" hover:text-accent-hover">
                        Modern Room
                    </a>
                </div>
            </div>
            <div class="flex flex-col items-start">
                <h2 class="text-2xl  font-bold mb-3 text-slate-100">
                    Newsletter
                </h2>
                <form action="">
                    <label for="newsletter" class="">Subscribe to our newsletter</label>
                    <div class="flex mt-2">
                        <input type="text" name="" id="newsletter" placeholder="Enter your email"
                            class="w-full rounded-l-lg h-10 mb-4 bg-slate-900 placeholder:text-slate-200 pl-4">
                        <button type="submit" title="submit" class="h-10 rounded-r-lg px-6 bg-accent text-white">
                            subscribe
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <div class="text-center px-8 pb-4 text-sm">
            <hr class="w-[90%] h-[1px] mx-auto bg-slate-900 mb-4 opacity-20">
            <p>
                &copy; <span>{{ year }}</span> . <span class="capitalize">all rights reserve</span>. <span
                    class="capitalize"> designed by achumanzeh</span>
            </p>
        </div>
    </footer>
</template>