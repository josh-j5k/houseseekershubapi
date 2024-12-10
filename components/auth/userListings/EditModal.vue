<script setup lang="ts">
import type { form } from '~/types/listings';

const { imgSrc, total } = useFileUpload()
const { formErrors } = useListingFormValidator()
const emit = defineEmits(['closeEditModal', 'fileUpload', 'removePhoto', 'deletePhoto', 'submit'])
defineProps<{
    show_edit_modal: boolean,
    images: string[],
    form: form,
    loading: boolean
}>()
function fileUpload(e: MouseEvent) {
    emit('fileUpload', e)
}
function closeEditModal() {
    emit('closeEditModal')
}
function removePhoto(ev: MouseEvent) {
    emit('removePhoto', ev)
}
function deletePhoto(ev: MouseEvent) {
    emit('deletePhoto', ev)
}
function submit() {
    emit('submit')
}
</script>

<template>
    <Modal :show="show_edit_modal" @close="closeEditModal">
        <div class="p-8">
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="flex flex-col gap-4">
                    <div class="relative max-h-[190px]  bg-gray-100  "
                        :class="[images ? 'grid lg:grid-cols-3 grid-cols-2 gap-2 overflow-x-hidden overflow-y-auto p-4' : total > 0 ? 'grid lg:grid-cols-3 grid-cols-2 gap-2 overflow-x-hidden overflow-y-auto p-4' : 'justify-center items-center overflow-hidden']">
                        <template v-for="(item, index) in imgSrc">
                            <div
                                class="relative before:content-emptystring before:absolute before:w-full before:h-full before:inset-0 before:bg-[rgba(255,_255,_255,_0.1)] before:z-10">
                                <img :src="item" alt="" class="w-full aspect-square object-cover rounded">
                                <button @click="removePhoto" :value="index" type="button"
                                    class="w-6 aspect-square bg-white rounded-full absolute top-2 right-4 z-20">
                                    <span>
                                        <i class="fas fa-xmark"></i>
                                    </span>
                                </button>
                            </div>
                        </template>
                        <template v-for="(item, index) in images">
                            <div v-if="images.length > 0"
                                class="relative before:content-emptystring before:absolute before:w-full before:h-full before:inset-0 before:bg-[rgba(255,_255,_255,_0.1)] before:z-10">
                                <img :src="item" alt="" class="w-full aspect-square object-cover rounded">
                                <button @click="deletePhoto" :value="index" type="button"
                                    class="w-6 aspect-square bg-white rounded-full absolute top-2 right-4 z-20">
                                    <span>
                                        <i class="fas fa-xmark"></i>
                                    </span>
                                </button>
                            </div>
                        </template>
                        <FileUpload @file-upload="fileUpload" file_type="image" :file-error="formErrors.fileError"
                            width="100%" accept=".jpg, .jpeg, .png, .webp" />
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
                                placeholder="Enter the price per month" class="input"
                                :class="[formErrors.priceError ? 'border-red-500' : '']">
                            <span class="font-bold absolute right-2 top-1/2 text-secondary -translate-y-1/2">FCFA</span>
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
                    <ButtonWithLoader type="submit" :loading>
                        Update
                    </ButtonWithLoader>
                </div>
            </form>
        </div>
    </Modal>
</template>