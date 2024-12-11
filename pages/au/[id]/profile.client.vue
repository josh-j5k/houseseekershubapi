<script setup lang="ts">

// setPageLayout('authenticated')
definePageMeta({
    layout: 'authenticated',
    middleware: 'auth'
})
</script>

<template>

    <Head title="Profile" />



    <h2 class="font-semibold text-xl text-gray-800 leading-tight px-8">Profile</h2>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <AuthProfileUpdateProfileInformationForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <AuthProfileUpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <AuthProfileDeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </div>

</template>