<script setup lang="ts">

import type { LocationQuery, LocationQueryValue } from 'vue-router';
import type { Listing } from '@/types/listings'

const { decodeQuery, propertyType, location, status, price, priceValidate, locationValidate, removeQueryParams, resetFields } = useListing()
const { handleRequest } = useBackend()
const { storedListings } = useStoredListings()

const listings = ref(<Listing[]>Array(0))
const loading = ref(true)
const hasMorePages = ref(false)
const page = ref(1)
const per_page = ref('16')
const sidebarToggled = ref(false)
const activeGrid = ref('grid')
const loadmore = ref(false)
const query = decodeQuery()

const filter = computed(() => {
    let arr = <string[]>[]
    for (const key in useRoute().query) {
        const value = useRoute().query[key] as string
        const decoded = decodeURIComponent(value?.toString()!)
        if (decoded.includes('|')) {
            arr = [...arr, ...decoded.split('|')]
        } else {
            arr = [...arr, decoded]
        }
    }
    return arr
})
async function init() {
    const { data, error } = await handleRequest('get', '/listings', query)
    if (!error) {

        if (Object.keys(query).length == 0) {
            useState('listings').value = data.data
            listings.value = data.data.listings
            hasMorePages.value = data.data.hasMorePages
        } else {

            listings.value = data.data.listings
            hasMorePages.value = data.data.hasMorePages
        }


        loading.value = false
    } else {
        loading.value = false

    }
}
if (storedListings == undefined) {
    init()
} else {
    listings.value = storedListings.listings
}

async function removeFilter(e: string) {
    const { decodedQuery, newQuery } = removeQueryParams(e)
    navigateTo({
        path: '/listings',
        query: newQuery
    })
    loading.value = true
    const { data, error } = await handleRequest('get', '/listings', decodedQuery)
    if (!error) {
        listings.value = data.data.listings
        hasMorePages.value = data.data.hasMorePages
    }
    loading.value = false

}

async function submit(name: string, value: LocationQueryValue | LocationQueryValue[]) {
    const encoded = encodeURIComponent(value?.toString()!)
    const q = useRoute().query
    const newQuery = { ...q }
    newQuery[name] = encoded
    navigateTo({
        path: '/listings',
        query: newQuery
    })
    const decoded = <LocationQuery>{}
    for (const [key, value] of Object.entries(newQuery)) {
        decoded[key] = decodeURIComponent(value as string)
    }
    loading.value = true
    const { data, error } = await handleRequest('get', '/listings', decoded)
    if (!error) {
        listings.value = data.data.listings
        hasMorePages.value = data.data.hasMorePages
    }
    loading.value = false
}

function submitLocation() {
    if (locationValidate()) {
        submit('location', location.value)
    }
}
function submitPrice() {
    if (priceValidate()) {
        if (price.value.min && price.value.max) {
            submit('price', 'over'.concat(price.value.min) + '|' + 'under'.concat(price.value.max))
            return
        }
        if (price.value.min) {
            submit('price', 'over'.concat(price.value.min))
            return
        }
        if (price.value.max) {
            submit('price', 'under'.concat(price.value.max))
            return
        }
    }
}
function submitProperty() {
    submit('property_type', propertyType.value.join('|'))
}
function submitStatus() {
    submit('status', status.value)
}

onMounted(() => {
    const target = document.getElementById("loadmore")!;
    const options = {
        rootMargin: "0px",
        threshold: 1.0,
    };
    async function moreListing() {
        loadmore.value = true
        const decodedQuery = decodeQuery()
        decodedQuery['page'] = page.value.toString()
        const { data, error } = await handleRequest('get', '/listings', decodedQuery)
        if (!error) {
            listings.value = [...listings.value, ...data.data.listings]
            hasMorePages.value = data.data.hasMorePages
        }
        loadmore.value = false
    }
    const observer = new IntersectionObserver(function (e) {
        const intersection = e[0]

        if (intersection.isIntersecting && (hasMorePages.value || storedListings?.hasMorePages)) {
            page.value++
            moreListing()
        }

    }, options);
    observer.observe(target)
})
onUnmounted(() => {
    if (Object.keys(useRoute().query).length > 0) {
        clearNuxtState('listings')
        resetFields()
    }
})
const title = 'Houses For Sale And For Rent'
const description = "View all the available houses for rent and for sale. You are one step further in finding the perfect house"
useHead({
    title
})

useSeoMeta({
    ogImage: {
        url: '/og image.png',
        type: 'image/png',
        height: 600,
        secureUrl: '/og image.png'
    },
    ogDescription: description,

    ogTitle: title,
    ogType: 'website',
    ogUrl: 'https://houseseekershub.com/listings',
    twitterCard: "summary_large_image",
    twitterImage: '/og image.png',
    twitterTitle: title,
    twitterDescription: description,
    title: title,
    description: description
})
</script>

<template>

    <section class="min-h-screen w-full overflow-x-hidden relative "
        :class="[sidebarToggled ? 'sidebar' : '', listings.length > 0 ? 'bg-gray-200' : 'bg-white']">
        <div class="grid lg:grid-cols-[25%_75%] min-h-screen grid-cols-1 -md:gap-4">
            <ListingsSidebar @location-submit="submitLocation" @price-submit="submitPrice"
                @property-submit="submitProperty" @status-submit="submitStatus" :sidebar-toggled="sidebarToggled" />
            <div>

                <div class="">
                    <div class=" md:w-[90%] mx-auto -md:px-8 bg-gray-100 mt-8 p-4">
                        <div class="flex relative -md:justify-end justify-between items-center">
                            <h3 class="capitalize font-bold -md:hidden">
                                applied filters
                            </h3>
                            <button @click="sidebarToggled = !sidebarToggled"
                                class=" bg-accent text-white w-12 h-8 rounded-3xl lg:hidden z-50 transition-transform"
                                :class="[sidebarToggled ? 'fixed right-4' : 'translate-x-0 absolute left-0']">
                                <span class="relative h-full w-full flex justify-center items-center">
                                    <span class="absolute" :class="[sidebarToggled ? 'opacity-0' : 'opacity-100']">
                                        <i class="fa-solid fa-bars-staggered "></i>
                                    </span>
                                    <span class="absolute" :class="[sidebarToggled ? 'opacity-100' : 'opacity-0']">
                                        <i class="fa-solid fa-xmark "></i>
                                    </span>

                                </span>
                            </button>
                            <div class="flex gap-3 items-center">
                                <div>
                                    <label for="per_page" class="text-sm">
                                        Per Page
                                    </label>
                                    <select v-model="per_page" name="per page" id="per_page"
                                        class="h-8 py-1 rounded w-12 px-1 bg-right">
                                        <option value="16">16</option>
                                        <option value="32">32</option>
                                        <option value="64">64</option>
                                    </select>
                                </div>


                                <button @click="activeGrid = 'grid'" type="button" title="Display each item in grid"
                                    class="rounded w-7 h-7 group">
                                    <span
                                        class="grid grid-cols-3 grid-rows-3 h-full w-full items-center justify-center">
                                        <template v-for="_ in 9">
                                            <span class="w-1.5 aspect-square rounded-full group-hover:bg-blue-500"
                                                :class="[activeGrid === 'grid' ? 'bg-accent' : 'bg-dark-blue']"></span>
                                        </template>
                                    </span>
                                </button>
                                <button @click="activeGrid = 'tiles'" type="button" title="Display each item in tiles"
                                    class="rounded w-7 h-7 group">
                                    <span
                                        class="grid grid-cols-[25%_75%] grid-rows-3 h-full w-full items-center justify-center">
                                        <template v-for="_ in 6">
                                            <span
                                                class="w-1 h-1 rounded-full even:w-5/6 even:h-0.5 group-hover:bg-blue-500"
                                                :class="[activeGrid === 'tiles' ? 'bg-blue-500' : 'bg-dark-blue']"></span>
                                        </template>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="flex  gap-3 mt-3">
                            <template v-for="item in filter">
                                <button type="button" title="cancel filter" @click="removeFilter(item)"
                                    class="flex items-center text-sm gap-2 rounded-xl bg-blue-500 text-white h-8 px-4">
                                    <span class="capitalize">
                                        {{ item.slice(0, 10) }}
                                    </span>
                                    <span
                                        class="h-1/2 aspect-square rounded-full bg-white text-blue-500 flex justify-center items-center">
                                        <i class=" fas fa-xmark fa-sm"></i>
                                    </span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <template v-if="storedListings == undefined ? loading : false">
                        <div
                            class="mt-8 w-[90%] mx-auto grid pb-8 transition-all gap-3 grid-cols-4 -md:grid-cols-2 -sm:grid-cols-1 ">
                            <template v-for="_ in 24">
                                <Card class="flex flex-col gap-2">
                                    <Skeleton class="aspect-square w-full" />
                                    <Skeleton class=" h-8 w-full" />
                                    <Skeleton class=" h-4 w-3/4" />
                                    <Skeleton class=" h-8 w-full" />
                                    <Skeleton class=" h-6 w-full" />
                                </Card>
                            </template>
                        </div>
                    </template>
                    <template v-else>

                        <template v-if="listings.length > 0">
                            <div class="mt-8 w-[90%] mx-auto grid pb-8 transition-all gap-3"
                                :class="[activeGrid === 'grid' ? 'grid-cols-4 -md:grid-cols-2 -sm:grid-cols-1 ' : 'grid-cols-1']">
                                <template v-for="(listing) in listings">
                                    <NuxtLink :to="{ name: 'listings-listing', params: { listing: listing.slug } }">
                                        <Card class="bg-white relative"
                                            :class="activeGrid === 'tiles' ? 'flex gap-4' : ''">

                                            <img :src="listing.images[0]" alt="" class="md:aspect-square object-cover"
                                                :class="[activeGrid === 'tiles' ? 'max-w-[200px] -md:max-w-[150px] rounded-l-lg  -md:h-full' : 'w-full aspect-square rounded-tr-lg rounded-tl-lg']">

                                            <div class="p-4">
                                                <p class="font-bold flex gap-1 mb-3 text-sm text-accent">
                                                    <span>{{
                                                        listing.price.toLocaleString('en-US', {
                                                            style: 'currency',
                                                            currency: 'XAF'
                                                        }) }}{{ listing.propertyStatus === 'rent' ? '/Month' : ''
                                                        }}</span>
                                                </p>
                                                <div>
                                                    <p v-if="activeGrid === 'grid'" class="font-bold mb-1">
                                                        {{ listing.title.slice(0, 35) }}
                                                    </p>
                                                    <div v-else>
                                                        <p class="font-bold mb-1 -md:hidden">
                                                            {{ listing.title }}
                                                        </p>
                                                        <p class="font-bold mb-1 md:hidden">
                                                            {{ listing.title.slice(0, 35) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <p class="text-sm opacity-75 mb-3 capitalize">
                                                    {{ listing.propertyType }}
                                                </p>
                                                <hr class="w-full h-[1px] bg-slate-100 mb-3">
                                                <div v-if="activeGrid === 'grid'" class="flex gap-2 text-sm">
                                                    <span>
                                                        <i class="fas fa-location-dot text-accent"></i>
                                                    </span>
                                                    <p>{{ listing.location.slice(0, 40) }}</p>
                                                </div>
                                                <div v-else class="flex gap-2 text-sm">
                                                    <span>
                                                        <i class="fas fa-location-dot text-accent"></i>
                                                    </span>
                                                    <p class="-md:hidden">{{ listing.location }}</p>
                                                    <p class="md:hidden">{{ listing.location.slice(0, 30) }}</p>
                                                </div>
                                            </div>
                                            <span
                                                class="capitalize rounded py-1 px-2 absolute top-3 left-3 text-white text-sm cursor-default"
                                                :class="[listing.propertyStatus === 'rent' ? 'bg-green-500' : 'bg-orange-500']">
                                                for {{ listing.propertyStatus }}
                                            </span>
                                        </Card>
                                    </NuxtLink>
                                </template>
                                <template v-if="loadmore" v-for="_ in 24">
                                    <Card class="flex flex-col gap-2">
                                        <Skeleton class="aspect-square w-full" />
                                        <Skeleton class=" h-8 w-full" />
                                        <Skeleton class=" h-4 w-3/4" />
                                        <Skeleton class=" h-8 w-full" />
                                        <Skeleton class=" h-6 w-full" />
                                    </Card>
                                </template>
                            </div>

                        </template>
                        <template v-else>

                            <div class="flex flex-col gap-3 justify-center items-center mt-16 w-[90%] mx-auto">
                                <span>
                                    <i class="fa-solid fa-triangle-exclamation text-4xl"></i>
                                </span>
                                <p class="text-8xl -md:text-6xl font-bold">
                                    OOPS!
                                </p>
                                <p class="text-6xl -md:text-4xl font-bold">
                                    NO LISTING
                                </p>
                                <span>
                                    <i class="fa-solid fa-triangle-exclamation text-4xl"></i>
                                </span>
                            </div>
                        </template>
                    </template>
                </div>

            </div>
        </div>
        <div id="loadmore">
        </div>
    </section>

</template>

<style scoped>
.sidebar::before {
    position: absolute;
    content: '';
    inset: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 30;

}
</style>