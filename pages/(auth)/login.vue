<script setup lang="ts">
definePageMeta({
    middleware: 'authenticated'
})
const { handleRequest, btnLoading } = useAxios()
const status = ref({
    error: false,
    message: null
})
const form = reactive({
    email: '',
    password: '',
    remember: false,
});


const submit = async () => {
    const { error, data } = await handleRequest('post', 'login', form)

    if (error) {
        status.value.message = data.message
        status.value.error = true
        setTimeout(() => {
            status.value.message = null
            status.value.error = false
        }, 4000);
        return
    }
    const user = useState('user', () => data.user)
    console.log(user.value)
    navigateTo('/au/'.concat(user.value.ref))
}
</script>

<template>

    <Head title="Log in" />

    <!-- <ApplicationLogo class="w-16 absolute top-8 left-8" /> -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center py-8 sm:pt-0 bg-gray-100 -sm:pt-24">
        <div>
            <h1>House Seeker's hub</h1>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

            <div v-if="status.message" class="mb-4 font-medium text-center text-sm "
                :class="status.error ? 'text-red-600' : 'text-green-600'">
                {{ status.message }}
            </div>


            <form @submit.prevent="submit">
                <div>
                    <label for="email">
                        Email
                        <input class="input" v-model="form.email" type="email" name="email" id="email" required>
                    </label>
                    <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
                </div>

                <div class="mt-4">
                    <label for="password">
                        Password
                        <input class="input" v-model="form.password" type="password" name="password" id="password"
                            required>
                    </label>


                    <!-- <InputError class="mt-2" :message="form.errors.password" /> -->
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <input class="accent-accent" type="checkbox" name="remember" v-model="form.remember" />
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <NuxtLink to="#"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Forgot your password?
                    </NuxtLink>
                    <PrimaryButton :loading="btnLoading" class="ml-4">
                        Log in
                    </PrimaryButton>
                </div>
            </form>

            <LoginWithSocials />
            <div class="grid grid-cols-[20%_60%_20%] items-center mb-4">
                <hr class="w-full bg-slate-200">
                <p class="flex justify-center capitalize">Don't have an account?</p>
                <hr class="w-full bg-slate-200">
            </div>
            <div class="pb-12">
                <button @click="$router.push('/register')" type="button" title="sign up"
                    class="w-40 mx-auto flex justify-center items-center h-8 rounded-3xl border border-secondary capitalize hover:border-none hover:bg-secondary hover:text-white transition ">
                    sign up
                </button>
            </div>
        </div>

    </div>
</template>
