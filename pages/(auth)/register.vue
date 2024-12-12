<script setup lang="ts">
import type { user } from '~/types/user';

definePageMeta({
    middleware: 'auth'
})
const { handleRequest, btnLoading } = useBackend()
const status = ref({
    error: false,
    message: null
})
const authUser = <Ref<user | undefined>>useState('user')
const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = async () => {
    const { error, data } = await handleRequest('post', '/register', form)
    if (error) {
        status.value.error = true
        status.value.message = data.message
        setTimeout(() => {
            status.value.message = null
            status.value.error = false
        }, 4000);
        return
    }
    localStorage.setItem('user', JSON.stringify({ access_token: data.access_token, user: data.user }))
    let user = { user: data.user, access_token: data.access_token }
    authUser.value = user

    navigateTo({ name: 'au-id', params: { id: data.user.ref } })
};
const title = 'Signup'
const description = "Join thousands of others to start searching the perfect house or start listing."
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
    ogUrl: 'https://houseseekershub.com/register',
    twitterCard: "summary_large_image",
    twitterImage: '/og image.png',
    twitterTitle: title,
    twitterDescription: description,
    title: title,
    description: description
})
</script>

<template>

    <Head title="Register" />
    <div class="min-h-screen flex flex-col sm:justify-center items-center py-8 sm:pt-0 bg-gray-100 -sm:pt-24">

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div v-if="status.message" class="mb-4 font-medium text-center text-sm "
                :class="status.error ? 'text-red-600' : 'text-green-600'">
                {{ status.message }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <label for="name">
                        Name
                        <input class="input" required v-model="form.name" type="text" name="name" id="name">
                    </label>

                    <!-- <InputError class="mt-2" :message="form.errors.name" /> -->
                </div>

                <div class="mt-4">
                    <label for="email">
                        Email
                        <input class="input" required v-model="form.email" type="email" name="email" id="email">
                    </label>
                    <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
                </div>

                <div class="mt-4">
                    <label for="password">
                        Password
                        <input class="input" required v-model="form.password" type="password" name="password"
                            id="password">
                    </label>


                    <!-- <InputError class="mt-2" :message="form.errors.password" /> -->
                </div>

                <div class="mt-4">
                    <label for="password_confirmation">
                        Confirm Password
                        <input class="input" required v-model="form.password_confirmation" type="password"
                            name="password_confirmation" id="password_confirmation">
                    </label>


                    <!-- <InputError class="mt-2" :message="form.errors.password_confirmation" /> -->
                </div>

                <div class="flex items-center justify-between mt-4">
                    <NuxtLink to="/login"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Already registered?
                    </NuxtLink>

                    <PrimaryButton type="submit" :loading="btnLoading" class="ml-4">
                        Register
                    </PrimaryButton>
                </div>
            </form>
            <LoginWithSocials google_name="Signup" />
        </div>
    </div>
</template>
