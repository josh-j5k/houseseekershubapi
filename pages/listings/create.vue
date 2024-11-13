<script setup lang="ts">
import toast from '~/utils/toastNotification';



const { drop, dragenter, dragover, assignFiles, total, imgSrc, deleteFile, filesArr } = useFileUpload()
const { formErrors, validation } = useListingFormValidator()


const form = reactive({
    title: '',
    property_status: 'rent',
    price: '',
    location: '',
    description: '',
    property_type: '',
    inputFiles: <File[]>[]

})


const currentIndex = ref(0)

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
function prevPic() {
    currentIndex.value--
    if (currentIndex.value < 0) {
        currentIndex.value = imgSrc.value.length - 1

    }
}


function nextPic() {
    currentIndex.value++
    if (currentIndex.value > imgSrc.value.length - 1) {
        currentIndex.value = 0
    }
}

// function submit() {
//     form.title.trim()
//     form.description.trim()
//     form.inputFiles = filesArr.value


//     if (user.value === null) {
//         return toast('Error', 'You need to be logged in to perform this action')
//     } else if (validation(form.title, form.description, form.property_type, form.price, form.property_status, form.location, total.value)) {

//         form.post(route('listings.index'), {
//             preserveScroll: true,
//             onSuccess: () => {
//                 toast('Success', 'Listing added successfully')
//                 form.reset('description', 'inputFiles', 'location', 'price', 'property_status', 'property_type', 'title')
//                 const file_upload = document.getElementById('file_upload') as HTMLInputElement
//                 const newDt = new DataTransfer()
//                 file_upload.files = newDt.files
//                 imgSrc.value = []
//                 filesArr.value = <File[]>[]
//                 total.value = 0

//             },
//         })
//     }
// }

onMounted(() => {
    const titleInput = document.getElementById('listing_title') as HTMLInputElement
    titleInput.addEventListener('change', () => {

        const arr = <string[]>[]
        const convertToCamelCase = form.title.split(' ')
        convertToCamelCase.forEach((word) => {
            const uppercaseFirstLetter = word[0].toUpperCase()
            const firstLetter = word[0]


            const newWord = word.replace(firstLetter, uppercaseFirstLetter)
            arr.push(newWord)
        })
        form.title = arr.join(' ')
    })
    function dropEnter(ev: any) {
        drop(ev, file_upload)
    }
    const dropbox = document.getElementById('dropbox') as HTMLDivElement
    const file_upload = document.getElementById('file_upload') as HTMLInputElement

    assignFiles(file_upload,)
    dropbox.addEventListener("dragenter", dragenter, false);
    dropbox.addEventListener("dragover", dragover, false);
    dropbox.addEventListener("drop", dropEnter, false);

    const locationInput = document.getElementById('property_location') as HTMLInputElement
    // usePlaces(locationInput, locationInput.value)

})
onUnmounted(() => {
    document.removeEventListener('dragenter', dragenter)
    document.removeEventListener('dragover', dragover)

})
</script>


<template>

    <Head>
        <title>Post a listing</title>
    </Head>
    <!-- <Preloader /> -->

    <section
        class="w-full min-h-screen bg-gray-100 grid grid-cols-[30%_70%] -lg:grid-cols-1 justify-center items-center p-8 pt-0 gap-4">
        <div
            class="lg:w-[30vw] -lg:h-full h-screen lg:overflow-y-auto lg:fixed lg:left-0 top-0 bg-white shadow px-8 py-12 lg:pt-28">
            <form @submit.prevent="console.log('yaay')" enctype="multipart/form-data">
                <div class="flex flex-col gap-4">
                    <div>
                        <p class="text-sm mb-2 flex items-center justify-center">
                            <span class="font-bold">
                                Photos <span class="">-></span> {{ total }}/50 -
                            </span>
                            <span>
                                You can add up to 50 photos
                            </span>
                        </p>
                        <div class="max-h-[190px]"
                            :class="[total > 0 ? 'grid grid-cols-2 gap-2 overflow-x-hidden overflow-y-auto' : '']">
                            <template v-for="(item, index) in imgSrc">
                                <div
                                    class="overflow-hidden relative before:content-emptystring before:absolute before:w-full before:h-full before:inset-0 before:bg-[rgba(255,_255,_255,_0.1)] before:z-10">
                                    <img :src="item" alt="" class="w-32 aspect-square object-cover rounded">
                                    <button @click="removePhoto" :value="index" type="button"
                                        class="w-6 aspect-square bg-white rounded-full absolute top-2 right-8 z-20">
                                        <span>
                                            <i class="fas fa-xmark"></i>
                                        </span>
                                    </button>
                                </div>
                            </template>
                            <FileUpload @file-upload="fileUpload" file_type="image" :file-error="formErrors.fileError"
                                width="100%" accept=".jpg, .jpeg, .png, .webp" />
                        </div>
                    </div>
                    <p v-if="formErrors.fileError" class="text-red-500"> Please upload at least one photo.</p>
                    <div class="flex flex-col">
                        <label for="listing_title" class="capitalize font-bold text-lg mb-3">listing title</label>
                        <input v-model="form.title" type="text" name="listing title" id="listing_title"
                            placeholder="Enter listing title" class="input"
                            :class="[formErrors.titleError ? 'border-red-500' : '']">
                    </div>
                    <p v-if="formErrors.titleError" class="text-red-500">
                        Please fill the title field.
                    </p>
                    <div class="flex flex-col">
                        <label for="property_status" class="capitalize font-bold text-lg mb-3">property
                            status</label>
                        <select v-model="form.property_status" name="property status" id="proptery-status"
                            class="input">
                            <option disabled> Choose property status</option>
                            <option value="rent">For Rent</option>
                            <option value="sale">For Sale</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label for="price_per_month" class="capitalize font-bold text-lg mb-3">price per
                            month</label>
                        <div class="relative">
                            <input v-model="form.price" type="text" name="listing price" id="price_per_month"
                                placeholder="Enter the price per month" class="input pl-12"
                                :class="[formErrors.priceError ? 'border-red-500' : '']">
                            <span class="font-bold absolute left-2 top-1/2 text-secondary -translate-y-1/2">XAF</span>
                        </div>
                        <p v-if="formErrors.priceError" class="text-red-500">
                            Please enter a valid price.
                        </p>
                    </div>
                    <div class="flex flex-col">
                        <label for="property_location" class="capitalize font-bold text-lg mb-3">property
                            location</label>
                        <input v-model="form.location" type="text" name="location location" id="property_location"
                            placeholder="Enter the property location" class="input"
                            :class="[formErrors.locationError ? 'border-red-500' : '']">
                    </div>
                    <p v-if="formErrors.locationError" class="text-red-500">
                        Please enter a valid location.
                    </p>
                    <div class="flex flex-col">
                        <label for="property_type" class="capitalize font-bold text-lg mb-3">property
                            type</label>
                        <select v-model="form.property_type" name="property type" id="proptery-type" class="input"
                            :class="[formErrors.propertyTypeError ? 'border-red-500' : '']">
                            <option disabled> Choose property type</option>
                            <option value="room">Room</option>
                            <option value="studio">Studio</option>
                            <option value="appartment">Appartment</option>
                            <option value="duplex">Duplex</option>
                        </select>
                    </div>
                    <p v-if="formErrors.propertyTypeError" class="text-red-500">
                        Please chose a property type
                    </p>
                    <div class="flex flex-col">
                        <label for="property_description" class="capitalize font-bold text-lg mb-3">property
                            description</label>
                        <textarea v-model="form.description" name="property_description" id="" cols="30" rows="10"
                            class="input" :class="[formErrors.descriptionError ? 'border-red-500' : '']"></textarea>
                    </div>
                    <p v-if="formErrors.descriptionError" class="text-red-500">
                        Please enter a description.
                    </p>
                    <button type="submit" class="bg-accent py-3 px-6 text-white">
                        Publish
                    </button>
                </div>
            </form>
        </div>
        <div class="lg:col-start-2 lg:col-end-3">
            <div class="w-full lg:h-[90vh] m-auto overflow-hidden bg-white shadow rounded-md  p-4 pb-11">
                <!-- <div v-if="user === null" class=" text-red-500 text center bg-white p-4">
                    <p class="text-center">
                        You need to be an authenticated user to
                        publish a
                        listing!
                    </p>
                </div> -->
                <h2 class="font-bold py-2 text-lg">Preview</h2>
                <div class="grid lg:grid-cols-[60%_40%] grid-cols-1 h-full w-full border rounded-md">
                    <div class="relative w-full h-[90%] overflow-hidden"
                        :class="[imgSrc.length > 0 ? 'bg-black' : 'bg-gray-200']">
                        <div v-if="imgSrc.length > 0">
                            <img :src="imgSrc[currentIndex]" alt="" class="max-w-sm h-5/6 object-cover mx-auto">
                            <button @click="prevPic" type="button" title="click to get previous image"
                                class="w-12 aspect-square rounded-full bg-white absolute left-4 top-1/2 -translate-y-1/2">
                                <span>
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </button>
                            <button @click="nextPic" type="button" title="click to get next image"
                                class="w-12 aspect-square rounded-full bg-white absolute right-4 top-1/2 -translate-y-1/2">
                                <span>
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </button>
                            <div
                                class="absolute flex gap-2 overflow-x-auto overflow-y-hidden py-1 mx-auto bottom-2 z-10 bg-[rgba(255,_255,_255,_0.1)] justify-center w-full h-16">
                                <template v-for="(item, index) in imgSrc">
                                    <img @click="currentIndex = index" :src="item" alt=""
                                        class="w-20 aspect-square object-cover cursor-pointer"
                                        :class="[index === currentIndex ? 'border-2 border-blue-500' : '']">
                                </template>
                            </div>
                        </div>
                        <div v-else class="flex flex-col text-gray-500 py-8 justify-center h-full items-center ">
                            <h2 class="font-bold text-xl capitalize mb-2">
                                your listing preview
                            </h2>
                            <p class="w-4/6 mx-auto text-center">
                                As you create your listing, you can preview it how it is going to appear.
                            </p>
                        </div>
                    </div>
                    <div class="p-8 overflow-y-auto">
                        <div>
                            <h2 v-if="form.title.length > 0" class="font-bold text-2xl mb-1">
                                {{ form.title }}
                            </h2>
                            <h2 v-else class="font-bold text-2xl mb-1">
                                Title
                            </h2>
                        </div>
                        <div>
                            <p v-if="form.price.length > 0" class="text-sm">
                                <span class="font-bold mr-2">XAF</span>
                                <span>{{ form.price }}</span>
                                <span v-if="form.property_status === 'rent'">/month</span>
                            </p>
                            <p v-else class="font-bold mb-3">price/month</p>
                        </div>

                        <hr class="w-full h-0.5 bg-slate-300 my-4">
                        <div class="pb-16">
                            <p class="font-bold text-lg mb-1">Property location</p>
                            <p class="text-sm">{{ form.location }}</p>
                        </div>
                        <hr class="w-full h-0.5 bg-slate-300 my-4">
                        <h2 class="font-bold text-lg mb-4">
                            Property Description
                        </h2>
                        <p>{{ form.description }}</p>
                    </div>
                </div>

            </div>
        </div>

    </section>
</template>