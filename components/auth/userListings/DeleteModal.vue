<script setup lang="ts">
defineProps<{
    show_delete_warning: boolean
}>()
const emit = defineEmits(['closeDeleteWarning', 'deleteConfirmed'])
function deleteConfirmed() {
    emit("deleteConfirmed")
}
function closeDeleteWarning() {
    emit("closeDeleteWarning")
}
</script>
<template>
    <Modal :show="show_delete_warning" @close="closeDeleteWarning" max-width="sm">
        <div class="p-8">
            <p class="flex justify-center items-center gap-3 mb-4">
                <span class="w-8 h-8 flex justify-center items-center rounded-full bg-gray-200 text-xl">
                    <i class="fas fa-exclamation"></i>
                </span>
                <span>
                    Are you sure you want to delete listing?
                </span>
            </p>
            <div class="flex justify-between">
                <button @click="deleteConfirmed" class="flex gap-2 py-1 px-3 rounded  bg-red-500 text-white">
                    <span>
                        <i class="fas fa-check"></i>
                    </span>
                    <span>
                        Yes
                    </span>
                </button>
                <button @click="closeDeleteWarning" class="flex gap-2 py-1 px-3 rounded bg-gray-500 text-white">
                    <span>
                        <i class="fas fa-xmark"></i>
                    </span>
                    <span>
                        Cancel
                    </span>
                </button>
            </div>
        </div>
    </Modal>
</template>