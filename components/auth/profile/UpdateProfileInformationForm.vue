<script setup lang="ts">
import type { user } from '~/types/user';

defineProps<{
    class: string
}>()
const user = useState('user').value as user
const show = ref(false)
const closeable = ref(false)
const form = reactive({
    name: user.user.name,
    email: user.user.email,
});

const avatarForm = reactive({
    avatar: <File[]>[]
})
function changeAvatar() {

    document.getElementById('avatar')?.click()
}
function closeModal() {
    show.value = false
}
function updateAvatar() {
    show.value = true
}
function formSubmit() {
    const fileInput = document.getElementById('avatar') as HTMLInputElement
    const btn = document.getElementById('profile-update') as HTMLButtonElement
    if (fileInput?.files !== null) {
        const file = fileInput.files[0] as File
        avatarForm.avatar.push(file)
    }

    btn.click()
}
function submit() {
    // if (avatarForm.avatar.length > 0) {
    //     avatarForm.post(route('user.profile.update.avatar', user.id), {
    //         onSuccess: () => location.reload()
    //     })

    // } else {
    //     form.patch(route('user.profile.update', user.id))
    // }
}
onMounted(() => {
    const input = <HTMLInputElement>document.getElementById('avatar')
    input.addEventListener('change', formSubmit)
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


                <!-- <InputError class="mt-2" :message="form.errors.name" /> -->
            </div>

            <div>
                <label for="email">
                    Email
                    <input v-model="form.email" required autocomplete="username" class="input" type="email" name="email"
                        id="email">
                </label>


                <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
            </div>
            <div>
                <label for="avatar">Update Avatar</label>

                <div class="mt-3">
                    <input hidden type="file" name="avatar" id="avatar" accept=".jpg, .jpeg, .png, .webp">
                    <button aria-labelledby="button" @click="updateAvatar"
                        class="w-20 h-20 rounded-full bg-gray-200 cursor-pointer">
                        <img v-if="user.user.avatar" :src="user.user.avatar" alt="avatar"
                            class="w-20 h-20 rounded-full">
                        <span v-else class="w-20 h-20 flex justify-center items-center">
                            <i class="fas fa-user text-2xl"></i>
                        </span>
                    </button>
                </div>
            </div>

            <!-- <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </div>
            </div> -->

            <div class="flex items-center gap-4">
                <PrimaryButton id="profile-update">Save</PrimaryButton>

                <!-- <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition> -->

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
                    <img v-if="user.user.avatar" :src="user.user.avatar" alt="avatar" class="w-40 h-40 rounded-full">
                    <span v-else class="w-40 h-40 rounded-full bg-slate-100 flex justify-center items-center">
                        <i class="fas fa-user text-6xl"></i>
                    </span>

                </div>
                <hr class="w-full h-[1px] bg-slate-300">
                <div class="px-8 flex justify-between pt-4">
                    <button @click="changeAvatar" type="button" title="change avatar"
                        class="capitalize flex items-center flex-col">
                        <span>
                            <i class="fas fa-pencil"></i>
                        </span>
                        <span>
                            change avatar
                        </span>
                    </button>
                    <button type="button" title="remove avatars" class="capitalize items-center flex flex-col">
                        <span>
                            <i class="fas fa-trash-can"></i>
                        </span>
                        <span>
                            delete
                        </span>
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
