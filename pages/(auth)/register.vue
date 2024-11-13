<script setup lang="ts">
definePageMeta({
    middleware: 'authenticated'
})

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// const submit = () => {
//     form.post(route('register'), {
//         onFinish: () => {
//             form.reset('password', 'password_confirmation');
//         },
//     });
// };
</script>

<template>

    <Head title="Register" />
    <div class="min-h-screen flex flex-col sm:justify-center items-center py-8 sm:pt-0 bg-gray-100 -sm:pt-24">

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">


            <form @submit.prevent="console.log('hee')">
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

                    <PrimaryButton class="ml-4">
                        Register
                    </PrimaryButton>
                </div>
            </form>
            <LoginWithSocials />
        </div>
    </div>
</template>
