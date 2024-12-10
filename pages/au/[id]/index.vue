<script setup lang="ts">
import { type Listings, type Listing } from '~/types/listings';
import toast from '~/utils/toastNotification';

definePageMeta({
    layout: "authenticated",
    middleware: 'auth'

})
const listings = ref(<Listings>{})
const page = ref(1)
const currentIndex = ref(0)
const loading = ref(true)
const loadmore = ref(false)
const updateLoading = ref(false)
const deleteLoading = ref(false)
const { validation } = useListingFormValidator()
const { deleteFile, imgSrc, total, assignFiles, dragenter, dragover, drop, filesArr } = useFileUpload()

const { handleRequest, btnLoading } = useBackend()


const storeUserListings = <Ref<Listings | undefined>>useState('userListings', () => undefined)

const form = ref({
    title: '',
    description: '',
    price: <string | number>'',
    location: '',
    property_status: '',
    property_type: '',
    deletedImages: <string[]>[],
    inputFiles: <File[]>[]
})
function resetFields() {
    form.value.title = ""
    form.value.description = ""
    form.value.inputFiles = <any>[]
    form.value.location = ""
    form.value.property_status = 'rent'
    form.value.price = ''
    form.value.property_type = ''
    form.value.deletedImages = <string[]>[]
}
const options = {
    rootMargin: "0px",
    threshold: 1.0,
}
const mainImage = ref(0)
const show = ref(false)
const show_delete_warning = ref(false)
const show_edit_modal = ref(false)
const closeable = ref(true)

function intersectionCallback(e: IntersectionObserverEntry[]) {
    const intersection = e[0]

    if (intersection.isIntersecting && (listings.value.hasMorePages || storeUserListings.value?.hasMorePages)) {
        page.value++
        moreListing()
    }
}

if (storeUserListings.value) {
    listings.value = storeUserListings.value
    loading.value = false
    const target = document.getElementById("loadmore")!;

    const observer = new IntersectionObserver(intersectionCallback, options);
    observer.observe(target)
} else {
    (async function () {
        const { data, error } = await handleRequest('get', '/listings/user/listings')
        if (!error) {
            listings.value.listings = data.data.listings
            listings.value.hasMorePages = data.data.hasMorePages
            storeUserListings.value = listings.value
            if (data.data.listings.length > 0) {

                setTimeout(() => {
                    const target = document.getElementById("loadmore")!;

                    const observer = new IntersectionObserver(intersectionCallback, options);
                    observer.observe(target)
                }, 10);
            }
        }
        loading.value = false
    })()
}

function closeModal() {
    show.value = false
}

function closeDeleteWarning() {
    show_delete_warning.value = false
}

function showDeleteModal() {
    show.value = false
    show_delete_warning.value = true
}

function closeEditModal() {
    function dropEnter(ev: any) {
        drop(ev, file_upload)
    }
    const file_upload = document.getElementById('file_upload') as HTMLInputElement
    const dropbox = document.getElementById('dropbox') as HTMLDivElement
    dropbox.removeEventListener("dragenter", dragenter, false);
    dropbox.removeEventListener("dragover", dragover, false);
    dropbox.removeEventListener("drop", dropEnter, false);
    total.value = 0
    if (form.value.deletedImages.length > 0) {
        listings.value.listings[currentIndex.value].images.push(...form.value.deletedImages)
    }

    show.value = false
    show_edit_modal.value = false

}

function showEditModal() {
    show.value = false
    show_edit_modal.value = true
    form.value.title = listings.value.listings[currentIndex.value].title
    form.value.location = listings.value.listings[currentIndex.value].location
    form.value.price = listings.value.listings[currentIndex.value].price
    form.value.description = listings.value.listings[currentIndex.value].description
    form.value.property_status = listings.value.listings[currentIndex.value].propertyStatus
    form.value.property_type = listings.value.listings[currentIndex.value].propertyType
    setTimeout(() => {

        function dropEnter(ev: any) {
            drop(ev, file_upload)
        }
        const dropbox = document.getElementById('dropbox') as HTMLDivElement
        const file_upload = document.getElementById('file_upload') as HTMLInputElement

        assignFiles(file_upload,)
        dropbox.addEventListener("dragenter", dragenter, false);
        dropbox.addEventListener("dragover", dragover, false);
        dropbox.addEventListener("drop", dropEnter, false);
        total.value = listings.value.listings[currentIndex.value].images.length
    }, 100)
}

function fileUpload(e: MouseEvent) {
    const dropbox = e.currentTarget as HTMLDivElement
    const input = dropbox.querySelector('#file_upload') as HTMLInputElement
    input.click()

}

function removePhoto(ev: MouseEvent) {
    const fileInput = document.querySelector('#file_upload') as HTMLInputElement
    const btn = ev.currentTarget as HTMLButtonElement
    const btnIndex = parseInt(btn.value)
    deleteFile(fileInput, btnIndex)
}
function deletePhoto(ev: MouseEvent) {
    const btn = ev.currentTarget as HTMLButtonElement
    const btnIndex = parseInt(btn.value)
    form.value.deletedImages.push(listings.value.listings[currentIndex.value].images[btnIndex])
    listings.value.listings[currentIndex.value].images.splice(btnIndex, 1)
}

async function submit() {
    if (filesArr.value.length > 0) {
        form.value.inputFiles = filesArr.value
    }
    if (validation(form.value, total.value)) {
        const input = <HTMLInputElement>document.getElementById('file_upload')
        updateLoading.value = true
        const { error, data } = await handleRequest('post', '/listings/update/' + listings.value.listings[currentIndex.value].id, form.value, 'multpartForm')
        if (!error) {
            toastNotification('Success', data.message)
            show_edit_modal.value = false
            resetFields()
            filesArr.value.length = 0
            imgSrc.value = []
            input.files = null
            input.value = ''
        }
        else {
            toastNotification('Error', data.message)
        }
        updateLoading.value = false
    }


}

async function deleteConfirmed() {
    deleteLoading.value = true
    const { data, error } = await handleRequest('delete', '/listings/delete/'.concat(listings.value.listings[currentIndex.value].id))

    if (!error) {
        const res = await handleRequest('get', '/listings/user/listings')
        if (!res.error) {
            listings.value.listings = res.data.data.listings
            listings.value.hasMorePages = res.data.data.hasMorePages
            useState('userListings').value = res.data
        }
        currentIndex.value = 0
        show_delete_warning.value = false
        toast('Success', 'Listing was deleted successfully!')
    } else {
        toast('Error', data.message)
    }
    deleteLoading.value = false
}
async function moreListing() {
    loadmore.value = true
    const query = <{ [x: string]: string }>{}
    query['page'] = page.value.toString()
    const { data, error } = await handleRequest('get', '/listings/user/listings', query)
    if (!error) {
        listings.value.listings = [...listings.value.listings, ...data.data.listings]
        listings.value.hasMorePages = data.data.hasMorePages
    }
    loadmore.value = false
}

</script>

<template>

    <Head title="Dashboard" />
    <template v-if="loading">
        <div class="flex flex-col justify-center items-center h-72">
            <Spinner class="text-4xl" />
        </div>
    </template>
    <template v-else>
        <div class=" py-2 -md:pb-20 lg:h-[calc(100vh-65px)] lg:overflow-hidden">
            <div class="w-[98%] bg-white shadow-sm  h-full -md:w-full sm:rounded-lg mx-auto sm:px-6 lg:px-2">
                <div v-if="listings.listings && listings.listings.length == 0"
                    class="p-4 pt-8 text-center text-gray-900">
                    <h2 class="text-2xl mb-4 font-bold">
                        You have no listings
                    </h2>
                    <NuxtLink to="/listings/create" class="text-accent">
                        <span>
                            Create your first listing
                        </span>
                        <span>
                            <i class="fas fa-circle-arrow-right"></i>
                        </span>
                    </NuxtLink>

                </div>
                <div v-else class="p-2 h-full text-gray-900 gap-8 overflow-hidden lg:grid grid-cols-[45%_55%]">
                    <div class="flex overflow-auto flex-col gap-4">
                        <template v-for="(listing, index) in listings.listings">
                            <Card @click="currentIndex = index"
                                :class="currentIndex == index ? 'border-l-2 border-blue-600' : ''"
                                class="bg-white flex gap-4 cursor-pointer -lg:hidden">

                                <img :src="listing.images[0]" alt=""
                                    class="max-w-[200px] object-cover aspect-square -md:max-w-[150px]">

                                <div class="p-4">
                                    <p class="font-bold flex gap-1 mb-3 text-sm text-accent">
                                        <span>{{ listing.price.toLocaleString('en-US', {
                                            style: 'currency',
                                            currency: 'XAF'
                                        }) }}{{ listing.propertyStatus == 'rent' ? '/Month' : '' }}</span>
                                    </p>
                                    <div>

                                        <div>

                                            <p class="font-bold mb-1">
                                                {{ listing.title.slice(0, 35) }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-sm opacity-75 mb-3 capitalize">
                                        {{ listing.propertyType }}
                                    </p>
                                    <hr class="w-full h-[1px] bg-slate-100 mb-3">

                                    <div class="flex gap-2 text-sm">
                                        <span>
                                            <i class="fas fa-location-dot text-accent"></i>
                                        </span>

                                        <p class="">{{ listing.location.slice(0, 30) }}</p>
                                    </div>
                                </div>
                                <span
                                    class="capitalize rounded py-1 px-2 absolute top-3 left-3 text-white text-sm cursor-default"
                                    :class="[listing.propertyStatus === 'rent' ? 'bg-green-500' : 'bg-orange-500']">
                                    for {{ listing.propertyStatus }}
                                </span>
                            </Card>
                            <Card @click="function () {
                                currentIndex = index
                                show = true
                            }" class="bg-white relative flex gap-4 cursor-pointer lg:hidden">
                                <div>
                                    <img v-if="listing.images.length > 0" :src="listing.images[0]" alt=""
                                        class="max-w-[200px] aspect-square object-cover -md:max-w-[150px]">

                                </div>
                                <div class="p-4">
                                    <p class="font-bold flex gap-1 mb-3 text-sm text-accent">
                                        <span>
                                            XAF
                                        </span>
                                        <span>

                                            <span v-if="listing.propertyStatus === 'rent'">
                                                <span>{{ listing.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                                    ",").concat('.00') }}</span>/Month
                                            </span>
                                            <span v-else>
                                                {{ listing.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                                    ",").concat('.00') }}
                                            </span>
                                        </span>
                                    </p>
                                    <div>

                                        <div>

                                            <p class="font-bold mb-1">
                                                {{ listing.title.slice(0, 35) }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-sm opacity-75 mb-3 capitalize">
                                        {{ listing.propertyType }}
                                    </p>
                                    <hr class="w-full h-[1px] bg-slate-100 mb-3">

                                    <div class="flex gap-2 text-sm">
                                        <span>
                                            <i class="fas fa-location-dot text-accent"></i>
                                        </span>

                                        <p class="">{{ listing.location.slice(0, 30) }}</p>
                                    </div>
                                </div>
                                <span
                                    class="capitalize rounded py-1 px-2 absolute top-3 left-3 text-white text-sm cursor-default"
                                    :class="[listing.propertyStatus === 'rent' ? 'bg-green-500' : 'bg-orange-500']">
                                    for {{ listing.propertyStatus }}
                                </span>
                            </Card>
                        </template>
                        <div v-if="loadmore" class="flex flex-col items-center">
                            <Spinner class="text-2xl" />
                        </div>
                        <div class="h-40 w-full">

                        </div>
                        <div id="loadmore">
                        </div>
                    </div>
                    <div class="px-3 overflow-auto scrollbar_invisible -lg:hidden">
                        <h2 class="mb-4 text-2xl font-bold">{{ listings.listings[currentIndex].title }}</h2>
                        <div class="grid grid-cols-[70%_30%] overflow-hidden gap-3 h-[390px]">

                            <img :src="listings.listings[currentIndex].images[mainImage]" alt=""
                                class="row-span-full aspect-square">

                            <div class="flex flex-col gap-3 scrollbar_invisible overflow-y-auto">
                                <template v-for="(image, index) in listings.listings[currentIndex].images">
                                    <img @click="mainImage = index" :src="image" alt=""
                                        class="w-36 aspect-square object-cover rounded-xl cursor-pointer">
                                </template>

                            </div>
                        </div>
                        <p class="mt-3 text-gray-500 flex gap-3 ">
                            <span>
                                <i class="fas fa-location-dot"></i>
                            </span>
                            <span>
                                {{ listings.listings[currentIndex].location }}
                            </span>
                        </p>
                        <hr class="w-full h-[1px] bg-gray-200 my-4">
                        <p class="font-bold mb-2 ">
                            Property details
                        </p>
                        <p class="text-gray-700 ">
                            {{ listings.listings[currentIndex].description }}
                        </p>
                        <div class="flex justify-between mt-3">
                            <button @click="showEditModal" class="flex flex-col items-center">
                                <span class=" text-accent text-xl">
                                    <i class="fas fa-pen-to-square"></i>
                                </span>
                                <span class="uppercase">
                                    edit listing
                                </span>
                            </button>
                            <button @click="showDeleteModal" class="flex flex-col items-center">
                                <span class=" text-red-500 text-xl">
                                    <i class="fas fa-trash"></i>
                                </span>
                                <span class="uppercase">
                                    delete listing
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <template v-if="listings.listings && listings.listings.length > 0">
            <!-- edit modal -->
            <AuthUserListingsEditModal :loading="updateLoading" :show_edit_modal :form
                :images="listings.listings[currentIndex].images" @file-upload="fileUpload"
                @close-edit-modal="closeEditModal" @remove-photo="removePhoto" @delete-photo="deletePhoto"
                @submit="submit" />
            <!-- show delete modal -->
            <AuthUserListingsDeleteModal :loading="deleteLoading" :show_delete_warning
                @close-delete-warning="closeDeleteWarning" @delete-confirmed="deleteConfirmed" />
            <!-- show primary modal -->
            <AuthUserListingsPrimaryModal @close-modal="closeModal" @show-delete-modal="showDeleteModal"
                @show-edit-modal="showEditModal" :closeable :show :images="listings.listings[currentIndex].images"
                :title="listings.listings[currentIndex].title" :location="listings.listings[currentIndex].location"
                :description="listings.listings[currentIndex].description"
                :price="listings.listings[currentIndex].price" />
        </template>
    </template>

</template>