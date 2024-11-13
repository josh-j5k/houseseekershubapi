<script setup lang="ts">

import { type Query, type Listings, type metaLinks } from '@/types/listings'



// const { usePlaces, inputValue } = useGoogleMaps()

const { locations, form, setInputsValues } = useListingFilter()


const listings = <Listings>{}
const per_page = ref('16')


const sidebarToggled = ref(false)
const activeGrid = ref('grid')


const filter = computed(() => {
    const filArr = Object.values(form)
    const arr = <string[]>[]
    filArr.forEach((item: any | string) => {
        if (item.includes('|')) {
            const ele = item.split('|')
            ele.forEach((element: any) => {
                arr.push(element)
            })
        } else {
            arr.push(item)
        }
    })
    return arr
})
function removeFilter(e: any): void {

    const item = e.currentTarget.textContent as string;
    for (const [key, value] of Object.entries(form)) {
        const newKey = key as keyof typeof form
        if (key === 'location') {
            // inputValue.value = ''
            locations.value = ''
        }
        if (item === value) {
            delete form[newKey]
            // router.get('/listings', form)
        } else if (value.includes(item)) {
            const val = form[newKey] as string
            const indexofStr = val.indexOf(item)
            if (indexofStr === 0) {
                const str = item + "|"
                const newValue = val.replace(str, '')
                form[newKey] = newValue
                // router.get('/listings', form)

            } else {
                const str = "|" + item
                const newValue = val.replace(str, '')
                form[newKey] = newValue
                // router.get('/listings', form)
            }

        }
    }


}
// const metaLinks = computed((): metaLinks[] => {
//     const arr = ref(<metaLinks[]>[])
//     const links = listings.meta.links
//     let index = ref(1)
//     while (index.value < links.length - 1) {
//         const element = links[index.value] as metaLinks
//         arr.value.push(element)
//         index.value++
//     }

//     return arr.value
// })




// const search = useRoute().params
// const pageInclude = search.includes('page=')
// const startQuery = search.startsWith('?page=')
// const pageIndex = search.indexOf('&page')
// const pageReplace = search.slice(pageIndex)
// const newSearch = search.replace(pageReplace, '')
// function pageQery(pageNumber: string) {
//     if (search.length > 0 && startQuery) {
//         // router.get('/listings?' + 'page='.concat(pageNumber))
//     } else if (search.length > 0 && !pageInclude) {
//         // router.get('/listings' + search + '&page='.concat(pageNumber))
//     } else if (search.length > 0 && pageInclude && !startQuery) {
//         // router.get('/listings' + newSearch + '&page='.concat(pageNumber))
//     }
//     else {
//         // router.get('/listings?' + 'page='.concat(pageNumber))
//     }
// }
function paginatePrev() {
    // const prevPage = listings.meta.current_page - 1
    // pageQery(prevPage.toString())
}
function paginateNext() {
    // const nextPage = listings.meta.current_page + 1
    // pageQery(nextPage.toString())
}
function specificPage(ev: MouseEvent) {
    // const button = ev.target as HTMLButtonElement
    // const page = button.textContent!
    // pageQery(page)
}
// watch(inputValue, (newVal) => {

//     locations.value = newVal
// })

// onMounted(() => {
//     const locationInput = <HTMLInputElement>document.getElementById('location')
//     usePlaces(locationInput, locations.value)
//     setInputsValues(query?.location, query?.status, query.price, query.property_type)
// })
onUnmounted(() => {
    locations.value = ''
    for (const [key, value] of Object.entries(form)) {
        const newKey = key as keyof typeof form
        if (key) {
            delete form[newKey]
        }
    }
})


</script>

<template>

    <Head title="Listings" />

    <section class="min-h-screen w-full overflow-x-hidden relative "
        :class="[sidebarToggled ? 'sidebar' : '', listings.data && listings.data.length > 0 ? 'bg-gray-200' : 'bg-white']">
        <div class="grid lg:grid-cols-[25%_75%] min-h-screen grid-cols-1 -md:gap-4">
            <ListingsSidebar :sidebar-toggled="sidebarToggled" :form="form" />
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
                                        <template v-for="item in 9">
                                            <span class="w-1.5 aspect-square rounded-full group-hover:bg-blue-500"
                                                :class="[activeGrid === 'grid' ? 'bg-accent' : 'bg-dark-blue']"></span>
                                        </template>
                                    </span>
                                </button>
                                <button @click="activeGrid = 'tiles'" type="button" title="Display each item in tiles"
                                    class="rounded w-7 h-7 group">
                                    <span
                                        class="grid grid-cols-[25%_75%] grid-rows-3 h-full w-full items-center justify-center">
                                        <template v-for="item in 6">
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
                                <button type="button" title="cancel filter" @click="removeFilter"
                                    class="flex items-center text-sm gap-2 rounded-xl bg-blue-500 text-white h-8 px-4">
                                    <span class="">
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

                    <template v-if="listings.data && listings.data.length > 0">
                        <div class="mt-8 w-[90%] mx-auto grid pb-8 transition-all gap-3"
                            :class="[activeGrid === 'grid' ? 'grid-cols-4 -md:grid-cols-2 -sm:grid-cols-1 ' : 'grid-cols-1']">
                            <!-- <template v-for="(listing) in listings.data">
                                    <NuxtLink :to="listing/{ref}">
                                    <Card class="bg-white relative" :class="activeGrid === 'tiles' ? 'flex gap-4' : ''">
                                        <div>
                                            <img v-if="listing.images?.length > 0" :src="listing.images[0]" alt=""
                                                class="md:aspect-square object-cover"
                                                :class="[activeGrid === 'tiles' ? 'max-w-[200px] -md:max-w-[150px] rounded-l-lg  -md:h-full' : 'w-full aspect-square rounded-tr-lg rounded-tl-lg']">
                                            <img v-else src="/Images/no_image_placeholder.jpg" alt=""
                                                :class="[activeGrid === 'tiles' ? 'max-w-[200px] md:aspect-square  -md:h-full object-cover -md:max-w-[150px]' : '']">
                                        </div>
                                        <div class="p-4">
                                            <p class="font-bold flex gap-1 mb-3 text-sm text-accent">

                                                <span>

                                                    <span v-if="listing.propertyStatus === 'rent'">
                                                        <span>{{
                                                            listing.price.toLocaleString('en-US', {
                                                                style: 'currency',
                                                            currency: 'XAF'
                                                            }) }}</span>/Month
                                                    </span>
                                                    <span v-else>
                                                        {{ listing.price.toLocaleString('en-US', {
                                                            style: 'currency',
                                                            currency: 'XAF'
                                                        }) }}
                                                    </span>
                                                </span>
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
                                </template> -->

                            <!-- <template v-for="cards in 24">
                                                        <div class="w-full h-80 bg-slate-300 shadow">
                                                            <SkeletonLoader class="w-full h-5/6 bg-slate-300" />
                                                            <SkeletonLoader class="w-5/6 h-8 mx-auto bg-slate-400" />
                                                        </div>
                                                    </template> -->
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
                </div>
                <!-- <div v-if="metaLinks && metaLinks.length > 1"
                    class="flex justify-between md:px-12 px-4 mt-8 bg-white h-32">
                    <div class="flex items-center">
                        <button @click="paginatePrev" :disabled="listings.links.prev === null"
                            class="capitalize group border border-accent rounded-full aspect-square w-10"
                            :class="[listings && listings.links.prev === null ? 'opacity-70' : '']">
                            <i class="fas fa-chevron-left transition-transform duration-200 ease-out"
                                :class="[listings.links.prev !== null ? 'group-hover:-translate-x-1' : '']"></i>
                        </button>
                    </div>
                    <div class="flex gap-2 items-center">
                        <template v-for="(item, index) in metaLinks">
                            <button @click="specificPage"
                                class="border rounded-full hover:bg-accent-hover hover:text-white transition-colors aspect-square w-10"
                                :class="[item.active ? 'bg-accent text-white' : '']">
                                {{ index + 1 }}
                            </button>
                        </template>
                    </div>
                    <div class="flex items-center">
                        <button @click="paginateNext" :disabled="listings.links.next === null"
                            class="capitalize group border border-accent  rounded-full aspect-square w-10"
                            :class="[listings && listings.links.next === null ? 'opacity-70' : '']">
                            <i class="fas fa-chevron-right transition-transform duration-200 ease-out"
                                :class="[listings && listings.links.next !== null ? 'group-hover:translate-x-1' : '']"></i>
                        </button>
                    </div>
                </div> -->
            </div>
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