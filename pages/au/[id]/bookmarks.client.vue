<script setup lang="ts">
import type { Listing } from '~/types/listings';


definePageMeta({
    layout: 'authenticated',
    middleware: 'auth'
})
const bookmarkLoading = ref(false)
const listings = ref(<{
    id: string;
    description: string;
    slug: string;
    location: string;
    price: number;
    property_status: string;
    property_type: string;
    title: string;
    images: string
}[]>[])
const { handleRequest, loading } = useBackend()

async function unBookmark(index: number) {
    bookmarkLoading.value = true
    const { error } = await handleRequest('post', '/bookmark/' + listings.value[index].id)
    if (!error) {
        const res = await handleRequest('get', '/bookmark')
        listings.value = res.data.data
        toastNotification('Success', 'Bookmark removed')
    }
    bookmarkLoading.value = false
}

async function fetch() {
    const { error, data } = await handleRequest('get', '/bookmark')
    if (!error) {
        listings.value = data.data


    }
}
fetch()
</script>

<template>
    <section>
        <div>
            <template v-if="loading">
                <div class="flex justify-center h-72 items-center bg-white pt-20">
                    <Spinner class="text-4xl" />
                </div>
            </template>
            <template v-else>
                <div class="grid grid-cols-2 -md:grid-cols-1 gap-4 p-8 -md:p-4">
                    <template v-for="(listing, index) in listings">

                        <Card role="button"
                            @click="$router.push({ name: 'listings-listing', params: { listing: listing.slug } })"
                            class="bg-blue-50 flex gap-4 md:max-w-md relative cursor-pointer">

                            <img :src="listing.images" alt="listing image"
                                class="max-w-[200px] object-cover aspect-square -md:max-w-[150px]">

                            <div class="p-4">
                                <p class="font-bold flex gap-1 mb-3 text-sm text-accent">
                                    <span>{{ listing.price.toLocaleString('en-US', {
                                        style: 'currency',
                                        currency: 'XAF'
                                    }) }}{{ listing.property_status == 'rent' ? '/Month' : '' }}</span>
                                </p>
                                <div>

                                    <div>

                                        <p class="font-bold mb-1">
                                            {{ listing.title.slice(0, 35) }}
                                        </p>
                                    </div>
                                </div>
                                <p class="text-sm opacity-75 mb-3 capitalize">
                                    {{ listing.property_type }}
                                </p>
                                <hr class="w-full h-[1px] bg-slate-100 mb-3">

                                <div class="flex gap-2 text-sm mb-3">
                                    <span>
                                        <i class="fas fa-location-dot text-accent"></i>
                                    </span>

                                    <p class="">{{ listing.location.slice(0, 30) }}</p>
                                </div>
                                <span class="capitalize rounded py-1 px-2 text-white text-sm cursor-default"
                                    :class="[listing.property_status === 'rent' ? 'bg-green-500' : 'bg-orange-500']">
                                    for {{ listing.property_status }}
                                </span>
                            </div>
                            <button :disabled="bookmarkLoading" @click="unBookmark(index)"
                                class="absolute top-2 left-3 z-30">
                                <i
                                    class="fa-solid fa-bookmark text-3xl text-secondary transition-colors duration-150 hover:text-blue-600 "></i>
                            </button>
                        </Card>

                    </template>
                </div>
            </template>
        </div>
    </section>
</template>