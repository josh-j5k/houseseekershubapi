<script setup lang="ts">
defineProps < {
    show: boolean,
    closeable: boolean
        images: string[]
        title: string,
    location: string,
    description: string,
    price: string | number
} > ()
const emit = defineEmits(['closeModal', "showEditModal", "showDeleteModal"])
function closeModal() {
    emit('closeModal')
}
function showEditModal() {
    emit('showEditModal')
}
function showDeleteModal() {
    emit('showDeleteModal')
}
</script>
<template>
    <Modal :show="show" :closeable="closeable" @close="closeModal">
        <div class="p-8 relative">
            <div>
                <img v-if="images" :src="images[0]" alt="listing image">

            </div>
            <div>
                <h2 class="font-bold text-2xl mt-6">
                    {{ title }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ location }}
                </p>
                <p class="text-accent mt-2 mb-6">
                    {{ price }}
                </p>
                <h3 class="font-bold text-lg mb-3">Description</h3>
                <p>
                    {{ description }}
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
            <button @click="closeModal"
                class="w-8 rounded-full aspect-square bg-gray-600 text-white absolute top-3 right-3">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
    </Modal>
</template>