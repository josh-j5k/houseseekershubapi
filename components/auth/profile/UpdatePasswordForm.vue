<script setup lang="ts">

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);
defineProps<{
    class: string
}>()
const form = reactive({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    // form.put(route('password.update'), {
    //     preserveScroll: true,
    //     onSuccess: () => {
    //         form.reset();
    //     },
    //     onError: () => {
    //         if (form.errors.password) {
    //             form.reset('password', 'password_confirmation');
    //             passwordInput.value?.focus();
    //         }
    //         if (form.errors.current_password) {
    //             form.reset('current_password');
    //             currentPasswordInput.value?.focus();
    //         }
    //     },
    // });
};
</script>

<template>
    <section :class="class">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>

                <label for="current_password">
                    Current Password
                    <input class="input" type="password" name="current_password" id="current_password"
                        ref="currentPasswordInput" v-model="form.current_password" autocomplete="current-password">
                </label>


                <!-- <InputError :message="form.errors.current_password" class="mt-2" /> -->
            </div>

            <div>
                <label for="password">
                    New Password
                    <input class="input" type="password" name="password" id="password" v-model="form.password"
                        autocomplete="new-password">
                </label>




                <!-- <InputError :message="form.errors.password" class="mt-2" /> -->
            </div>

            <div>

                <label for="password_confirmation">
                    Confirm Password
                    <input class="input" type="password_confirmation" name="password_confirmation"
                        id="password_confirmation" v-model="form.password_confirmation" autocomplete="new-password">
                </label>


                <!-- <InputError :message="form.errors.password_confirmation" class="mt-2" /> -->
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton>Save</PrimaryButton>

                <!-- <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition> -->
            </div>
        </form>
    </section>

</template>
