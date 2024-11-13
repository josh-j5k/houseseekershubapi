<script setup lang="ts">

defineProps<{
    class: string
}>()
const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = reactive({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    // form.delete(route('user.profile.destroy'), {
    //     preserveScroll: true,
    //     onSuccess: () => closeModal(),
    //     onError: () => passwordInput.value?.focus(),
    //     onFinish: () => {
    //         form.reset();
    //     },
    // });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    // form.reset();
};
</script>

<template>
    <section :class="class" class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
                your account, please download any data or information that you wish to retain.
            </p>
        </header>

        <button @click="confirmUserDeletion">Delete Account</button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <label for="password">
                        Password
                        <input class="input" v-model="form.password" type="password" name="password" id="password"
                            placeholder="Password" @keyup.enter="deleteUser">
                    </label>


                    <!-- <InputError :message="form.errors.password" class="mt-2" /> -->
                </div>

                <div class="mt-6 flex justify-end">
                    <button @click="closeModal"> Cancel </button>

                    <button class="ml-3" @click="deleteUser">
                        Delete Account
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
