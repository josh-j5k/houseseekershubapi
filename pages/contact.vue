<script setup lang="ts">
const { handleRequest } = useBackend()
const formError = reactive({
    full_name: false,
    email: false,
    category: false,
    description: false
})
const form = reactive({
    name: '',
    email: '',
    category: '',
    description: ''
})

const limit = ref(255)
const charactersRemaining = computed(() => limit.value - form.description.length)
function validate() {
    if (form.name.length == 0) {
        formError.full_name = true
        setTimeout(() => {
            formError.full_name = false
        }, 4000);
        return false
    }
    if (form.email.length == 0) {
        formError.email = true
        setTimeout(() => {
            formError.email = false
        }, 4000);
        return false
    }
    if (form.category.length == 0) {
        formError.category = true
        setTimeout(() => {
            formError.category = false
        }, 4000);
        return false
    }
    if (form.description.length == 0) {
        formError.description = true
        setTimeout(() => {
            formError.description = false
        }, 4000);
        return false
    }
    return true
}
async function submit() {
    if (validate()) {
        const { error, data } = await handleRequest('post', '/contact', form)

        if (!error) {
            toastNotification('Success', data.message)
        } else {
            toastNotification('Error', data.message)
        }
    }
}
const title = 'Get In Touch With Us'
const description = "If you’re interested in more information or arranging a viewing for any property listed with us. Contact us"
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
    ogUrl: 'https://houseseekershub.com/contact',
    twitterCard: "summary_large_image",
    twitterImage: '/og image.png',
    twitterTitle: title,
    twitterDescription: description,
    title: title,
    description: description
})
</script>
<template>

    <Head>
        <title>Get in touch with us</title>
    </Head>

    <section class="p-10 pt-12 lg:pb-52 ">
        <div class="grid md:grid-cols-[60%_40%] grid-cols-1 gap-16">
            <div>
                <h1 class="capitalize text-4xl font-bold mb-12">
                    contact us
                </h1>

                <form @submit.prevent="submit">
                    <div class="flex flex-col gap-8">
                        <div class="flex gap-4 -md:flex-col">
                            <div class="flex flex-col gap-1 w-full">
                                <label for="full_name" class="capitalize">
                                    name
                                </label>
                                <input v-model="form.name" placeholder="Enter your name" type="text" name="full_name"
                                    id="full_name" class="input" :class="[formError.full_name ? 'border-red-500' : '']">
                                <p v-if="formError.full_name" class="text-red-500">
                                    This field is required
                                </p>
                            </div>
                            <div class="flex flex-col gap-1 w-full">
                                <label for="email" class="capitalize">
                                    email
                                </label>
                                <input v-model="form.email" placeholder="Enter your email" type="email" name="email"
                                    id="email" class="input" :class="[formError.email ? 'border-red-500' : '']">
                                <p v-if="formError.email" class="text-red-500">
                                    This field is required
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <label for="category" class="capitalize">category</label>
                            <select v-model="form.category" name="category" id="category"
                                class="bg-gray-200 p-1 focus-visible:outline-blue-500 rounded-md border border-blue-300 ring-blue-100"
                                :class="[formError.category ? 'border-red-500' : '']">
                                <option value="" disabled>Please select</option>
                                <option value="ideas_improvements">
                                    Ideas/improvements
                                </option>
                                <option value="report a problem with location">
                                    Report a problem with a location
                                </option>
                                <option value="report problem with functionality">
                                    Report a problem with functionality
                                </option>
                                <option value="other">
                                    Other
                                </option>
                            </select>
                            <p v-if="formError.category" class="text-red-500">
                                This field is required
                            </p>
                        </div>
                        <div class="flex flex-col">
                            <label for="description">
                                Message
                            </label>
                            <textarea v-model="form.description" name="description" id="description" rows="5"
                                placeholder="Please enter any useful details" class="input"
                                :class="[formError.description ? 'border-red-500' : '']"></textarea>
                            <p class="text-sm text-gray-600">Characters remaining: {{ charactersRemaining }}</p>
                            <p v-if="formError.description" class="text-red-500">
                                This field is required
                            </p>
                        </div>
                        <div>
                            If you’re interested in more information or arranging a viewing for any property listed
                            with us, please contact the agent or new home developer directly for the relevant
                            property rather than using this form.
                        </div>
                        <button type="submit"
                            class="flex py-4 justify-center items-center bg-accent rounded hover:bg-accent-hover text-white text-lg font-bold">
                            Send message
                        </button>
                    </div>
                </form>
            </div>
            <div>
                <div class="sticky top-0 md:p-4">
                    <h2 class="font-bold text-lg">
                        Registered office address:
                    </h2>
                    <p class="mb-4">
                        RGRG+VG5, Yaoundé, Cameroon 00237
                    </p>
                    <p>
                        Please note that we do not accept direct advertising from private sellers or landlords.
                    </p>
                </div>
            </div>

        </div>
    </section>
</template>