<script setup lang="ts">
import type { user } from '~/types/user';

defineProps<{
    class: string
}>()
const user = useState('user') as Ref<user>
const { handleRequest, btnLoading } = useBackend()
const show = ref(false)
const confirmingUserEmailChange = ref(false)
const closeable = ref(false)
const uploadPicture = ref(<string | null>null)
const avatar = ref(<FormDataEntryValue | null>null)
const form = reactive(<{ name: string, email: string, password?: string }>{
    name: user.value.user.name,
    email: user.value.user.email,
});

function changeAvatar() {

    document.getElementById('avatar')?.click()
}
function closeModal() {
    show.value = false
    uploadPicture.value = null
    avatar.value = null
}
function closeEmailChangeModal() {
    confirmingUserEmailChange.value = false
    form.email = user.value.user.email
}
function updateAvatar() {
    show.value = true
}
function getAvatar() {
    const fileInput = document.getElementById('avatar') as HTMLInputElement
    const avatarForm = document.getElementById('avatar-form') as HTMLFormElement
    const formData = new FormData(avatarForm)
    const file = formData.get('avatar')
    if (file !== null) {
        avatar.value = file

        let src = ''
        const reader = new FileReader();
        if (fileInput.files) {
            reader.readAsDataURL(fileInput.files[0]);

            reader.onload = (e: any) => {
                src = e.target.result;
                uploadPicture.value = src

            };
        }

    }

}
function passwordAdded() {
    confirmingUserEmailChange.value = false
    submit()
}
async function submit() {
    if (avatar.value) {
        const { data, error } = await handleRequest('post', '/profile/update/'.concat(user.value.user.ref).concat('/avatar'), { avatar: avatar.value }, 'multpartForm')
        if (!error) {
            user.value.user.picture = data.data
            localStorage.removeItem('user')
            localStorage.setItem('user', JSON.stringify(user.value))
            toastNotification('Success', data.message)
        } else {
            toastNotification('Error', data.message)
        }

    } else {
        if (form.email === user.value.user.email && form.name === user.value.user.name) return
        if (form.email !== user.value.user.email && form.password == undefined) {
            confirmingUserEmailChange.value = true
            return
        }

        const { data, error } = await handleRequest('post', '/profile/update/'.concat(user.value.user.ref).concat('/user'), form)
        if (!error) {
            user.value.user.name = data.data
            localStorage.removeItem('user')
            localStorage.setItem('user', JSON.stringify(user.value))
            toastNotification('Success', data.message)
        } else {
            toastNotification('Error', data.message)

        }
        if (form.password) {
            delete form.password
        }
    }
}
onMounted(() => {
    const input = <HTMLInputElement>document.getElementById('avatar')
    input.addEventListener('change', getAvatar)
})
</script>

<template>
    <section :class="class">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6" enctype="multipart/form-data" method="post">
            <div>
                <label for="name">
                    Name
                    <input v-model="form.name" required autofocus class="input" type="text" name="name" id="name">
                </label>
            </div>

            <div>
                <label for="email">
                    Email
                    <input v-model="form.email" required autocomplete="username" class="input" type="email" name="email"
                        id="email">
                </label>
            </div>
            <div>
                <label for="avatar">Update Avatar</label>

                <div class="mt-3">
                    <form id="avatar-form">
                        <input hidden type="file" name="avatar" id="avatar" accept=".jpg, .jpeg, .png, .webp">
                    </form>

                    <button @click="updateAvatar" class="w-20 h-20 rounded-full bg-gray-200 cursor-pointer">
                        <img v-if="user.user.picture" :src="user.user.picture" alt="avatar"
                            class="w-20 h-20 rounded-full">
                        <span v-else class="w-20 h-20 flex justify-center items-center">
                            <i class="fas fa-user text-2xl"></i>
                        </span>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton type="submit" :loading="btnLoading" id="profile-update"><span v-if="!btnLoading">
                        Save
                    </span> <span v-else>Saving</span></PrimaryButton>
            </div>
        </form>
        <Modal @close="closeModal" :show="show" max-width="lg" :closeable="closeable">
            <div class="py-4">
                <div class="flex px-8 justify-between">
                    <h2>Avatar</h2>
                    <button type="button" title="close modal" @click="closeModal"
                        class="w-8 aspect-square bg-gray-200 rounded-full">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
                <div class="flex justify-center mb-8">
                    <img v-if="uploadPicture || user.user.picture" :src="uploadPicture || user.user.picture"
                        alt="avatar" class="w-40 h-40 rounded-full">
                    <span v-else class="w-40 h-40 rounded-full bg-slate-100 flex justify-center items-center">
                        <i class="fas fa-user text-6xl"></i>
                    </span>

                </div>
                <hr class="w-full h-[1px] bg-slate-300">
                <div class="px-8 flex gap-3 items-center justify-between pt-4">
                    <div class="flex gap-4">
                        <PrimaryButton class="text-sm" :loading="btnLoading" v-if="avatar" @click="submit">
                            Submit
                        </PrimaryButton>
                        <button @click="changeAvatar" type="button" title="change avatar"
                            class="uppercase flex gap-2 px-4 py-[7px] text-sm items-center bg-secondary rounded text-white">
                            <span>
                                <i class="fas fa-pencil"></i>
                            </span>
                            <span>
                                change avatar
                            </span>
                        </button>

                    </div>
                    <div>
                        <button type="button" title="remove avatar"
                            class="uppercase flex gap-2 px-4 py-[7px] text-sm items-center bg-red-600 rounded text-white">
                            <span>
                                <i class="fas fa-trash-can"></i>
                            </span>
                            <span>
                                delete
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </Modal>
        <Modal :show="confirmingUserEmailChange" @close="closeEmailChangeModal">
            <div class="p-6">
                <p class="mt-1 text-sm text-gray-600">
                    Please
                    enter your password to confirm you would like to change the email linked to your account.
                </p>

                <div class="mt-6">
                    <label for="password">
                        Password
                        <input class="input" v-model="form.password" type="password" name="password" id="password"
                            placeholder="Password" @keyup.enter="submit">
                    </label>


                    <!-- <InputError :message="form.errors.password" class="mt-2" /> -->
                </div>

                <div class="mt-6 flex gap-4 justify-end">
                    <button class="bg-gray-500 py-1 px-3 rounded text-white" @click="closeEmailChangeModal"> Cancel
                    </button>
                    <PrimaryButton @click="passwordAdded">
                        Submit
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
