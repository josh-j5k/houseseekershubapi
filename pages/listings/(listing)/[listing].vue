<script setup lang="ts">
import { type Listing } from '@/types/listings'
import type { user } from '~/types/user';
definePageMeta({
    layout: 'full-page'
})
type response = { status: string, message: string, data: Listing }
const showMessage = ref(false)
const messageInput = ref('')
const authorized = ref(<string | null>null)
const data = ref(<response>{
})

const isAuthorized = ref(<{ bookmark: boolean, ref: string | null }>{
    bookmark: false,
    ref: null
})
const { handleRequest } = useBackend()
const { assignFiles, imgSrc } = useFileUpload()

const chatLoading = ref(true)
const bookmarkLoading = ref(false)
const currentIndex = ref(0)
const chats = ref(<null | any>null)
const authUser = ref(<user | undefined>undefined)
const closeMessage = ref(false)

const response = await useFetch('/api/listings/'.concat(useRoute().params.listing.toString()))

if (response.error.value == null) {
    data.value = response.data.value as response

    useHead({
        title: data.value.data.title
    })
    if (import.meta.client) {
        const { data, error } = await handleRequest('get', '/authorized/'.concat(useRoute().params.listing.toString()))
        if (!error) {
            isAuthorized.value = data.data

        }
    }
}

function share() {
    navigator.clipboard.writeText(location.href)
    toastNotification('Success', 'Linked copied to clipboard')
}
// Send a message
async function sendMessage() {
    const form = <HTMLFormElement>document.getElementById('form')
    const formData = new FormData(form)
    const file = formData.getAll('file_upload')

    if (messageInput.value.length == 0 && file.length == 0) {
        return
    }
    const value = messageInput.value
    messageInput.value = ''
    const params = { date: 'Today', content: <{ message: string, from: string, message_pictures: { url: string } | null }>{ message: value, from: authUser.value?.user.ref, message_pictures: imgSrc.value.length > 0 ? { url: imgSrc.value[0] } : null } }

    if (chats.value.messages['Today']) {
        chats.value.messages["Today"] = [...chats.value.messages["Today"], params]
    }
    else {
        chats.value.messages["Today"] = [params]
    }
    imgSrc.value.length = 0

    const payload = <{ message: string, receivers_ref: string, message_pictures: any | undefined }>{ message: value, receivers_ref: isAuthorized.value.ref }
    if (file != null) {
        payload.message_pictures = file
    }
    const { data, error } = await handleRequest('post', '/messages', payload, 'multpartForm')
    chats.value.messages["Today"].at(-1).content.time = data.data

}

function closeMessageBox() {
    closeMessage.value = true
    document.documentElement.style.overflow = 'auto'
    setTimeout(() => {
        showMessage.value = false
    }, 600);
}
// Fetch chat messages
async function getChats() {
    showMessage.value = true
    if (closeMessage.value) closeMessage.value = false
    if (chats.value == null) {
        const { data, error } = await handleRequest('get', '/messages/'.concat(isAuthorized.value.ref!))
        if (!error) {

            chats.value = data.data
            setTimeout(() => {
                const chatArea = document.getElementById('chat_area') as HTMLDivElement
                chatArea.scrollTop = chatArea.scrollHeight
                const input = document.querySelector('#file_upload') as HTMLInputElement
                assignFiles(input)
            }, 1);

        }
        chatLoading.value = false
    }
    document.documentElement.style.overflow = 'clip'
}
function uploadPicture() {
    const input = document.querySelector('#file_upload') as HTMLInputElement
    input.click()
}
async function setBookmark() {
    bookmarkLoading.value = true
    const { error } = await handleRequest('post', '/bookmark/' + data.value.data.id)
    if (!error) {

        if (isAuthorized.value.bookmark) {
            toastNotification('Success', 'Bookmark removed')
        } else {
            toastNotification('Success', 'Bookmark added')
        }
        isAuthorized.value.bookmark = !isAuthorized.value.bookmark
    }
    bookmarkLoading.value = false
}
function prevPic() {
    currentIndex.value--
    if (currentIndex.value < 0) {
        currentIndex.value = data.value.data.images?.length - 1

    }
}


function nextPic() {
    currentIndex.value++
    if (currentIndex.value > data.value.data.images?.length - 1) {
        currentIndex.value = 0
    }
}
onUnmounted(() => {
    if (document.documentElement.style.overflow === 'clip') {
        document.documentElement.style.overflow = 'auto'
    }
})
const regex = /[.@!$%#^*;]/g
const title = data.value.data.title.replaceAll(regex, '')
useSeoMeta({
    ogImage: {
        url: `https://api.houseseekershub.com/og-image.png/${data.value.data.images[0]}`,
        secureUrl: `https://api.houseseekershub.com/og-image.png/${data.value.data.images[0]}`
    },
    ogDescription: data.value.data.description,
    ogTitle: title,
    ogType: 'website',
    ogUrl: 'https://houseseekershub.com/listings/create',
    twitterCard: "summary_large_image",
    twitterImage: `https://api.houseseekershub.com/og-image.png/${data.value.data.images[0]}`,
    twitterTitle: title,
    twitterDescription: data.value.data.description,
    title: title,
    description: data.value.data.description
})
</script>
<template>


    <template v-if="Object.keys(data).length === 0">
        <div class="flex justify-center items-center text-3xl pt-16 font-bold">
            No listing found
        </div>
    </template>
    <template v-else>
        <section class="md:h-screen min-h-screen w-full overflow-x-hidden">
            <CloseButton route-name="/listings" position="top-8 left-8" />
            <div class="grid lg:grid-cols-[75%_25%] grid-cols-1">
                <div
                    class="min-h-screen isolate relative before:content-emptystring before:absolute before:w-full before:h-full before:inset-0 before:bg-[rgba(0,_0,_0,_0.2)] overflow-clip before:-z-10 flex justify-center items-center bg-slate-500">
                    <!-- backdrop background -->
                    <div class="w-full h-full absolute">
                        <img :src="data.data.images[currentIndex]" alt="listing image"
                            class="w-full h-full object-cover absolute inset-0 blur-lg -z-20">
                    </div>
                    <!-- listing image -->
                    <div>
                        <a :href="data.data.images[currentIndex]" target="_blank">
                            <img :src="data.data.images[currentIndex]" alt="listing image" class="max-w-lg">
                        </a>
                    </div>
                    <button @click="prevPic" type="button" title="click to get previous image"
                        class="w-12 aspect-square rounded-full bg-white absolute left-4 top-1/2 -translate-y-1/2">
                        <span>
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </button>
                    <button @click="nextPic" type="button" title="click to get next image"
                        class="w-12 aspect-square rounded-full bg-white absolute right-4 top-1/2 -translate-y-1/2">
                        <span>
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </button>
                    <div v-if="data.data.images.length > 0"
                        class="absolute flex gap-2 overflow-x-auto overflow-y-hidden py-1 mx-auto bottom-2 z-10 bg-[rgba(255,_255,_255,_0.1)] justify-center w-full h-16">
                        <template v-for="(item, index) in data.data.images">
                            <img @click="currentIndex = index" :src="item" alt=""
                                class="w-20 aspect-square object-cover cursor-pointer"
                                :class="[index === currentIndex ? 'border-2 border-blue-500' : '']">
                        </template>
                    </div>
                </div>
                <div class="bg-white py-12 px-8 shadow md:h-screen relative overflow-clip">
                    <div>
                        <h1 class="capitalize font-bold text-3xl mb-3">
                            {{ data.data.title }}
                        </h1>
                        <p class="font-bold flex gap-1 text-sm text-accent">
                            <span>
                                <span>{{ data.data.price.toLocaleString('en-US', {
                                    style: 'currency',
                                    currency: 'XAF'
                                }) }} {{ data.data.propertyStatus === 'rent' ? '/Month' : '' }}</span>
                            </span>

                        </p>
                        <p class=" mb-6 text-sm text-gray-600 uppercase">
                            <span>
                                Property for
                            </span>
                            <span>
                                {{ data.data.propertyStatus }}
                            </span>
                        </p>

                        <div class="flex gap-4 relative">

                            <ClientOnly>
                                <button :disabled="isAuthorized.ref == null || isAuthorized.ref === authUser?.user.ref"
                                    @mousedown="onMouseDown" @mouseup="onMouseUp" @click="getChats" type="button"
                                    title="message" class="flex gap-3 bg-secondary text-white py-1 px-3 rounded-lg"
                                    :class="[isAuthorized.ref == null || isAuthorized.ref === authUser?.user.ref ? 'opacity-60 cursor-not-allowed' : '']">
                                    <span>
                                        <i class="fa-regular fa-comments"></i>
                                    </span>
                                    <span class="capitalize">
                                        message
                                    </span>
                                </button>
                                <button
                                    :disabled="isAuthorized.ref == null || isAuthorized.ref === authUser?.user.ref || bookmarkLoading"
                                    @mousedown="onMouseDown" @mouseup="onMouseUp" @click="setBookmark" type="button"
                                    title="bookmark" class="bg-secondary py-1 px-3 rounded-lg"
                                    :class="[isAuthorized.ref == null || isAuthorized.ref === authUser?.user.ref || bookmarkLoading ? 'opacity-60 cursor-not-allowed' : '']">
                                    <span class="flex gap-3 items-center text-blue-300" v-if="isAuthorized.bookmark">
                                        <i class="fa-solid fa-bookmark "></i>
                                        Bookmark
                                    </span>
                                    <span class="flex items-center gap-3 text-white" v-else>
                                        <i class="fa-regular fa-bookmark "></i>
                                        Bookmark
                                    </span>

                                </button>
                                <template #fallback>
                                    <button disabled type="button" title="message"
                                        class="flex gap-3 bg-secondary text-white py-1 px-3 rounded-lg opacity-60 cursor-not-allowed">
                                        <span>
                                            <i class="fa-regular fa-comments"></i>
                                        </span>
                                        <span class="capitalize">
                                            message
                                        </span>
                                    </button>
                                    <button disabled type="button" title="bookmark"
                                        class="flex gap-3 bg-secondary  py-1 px-3 rounded-lg opacity-60 cursor-not-allowed">

                                        <span>
                                            <i class="fa-regular fa-bookmark text-white"></i>
                                        </span>

                                    </button>
                                </template>
                            </ClientOnly>

                            <button @click="share" @mousedown="onMouseDown" @mouseup="onMouseUp" type="button"
                                title="share" class="flex gap-3 bg-secondary text-white py-1 px-3 rounded-lg">
                                <span>
                                    <i class="fa-solid fa-share"></i>
                                </span>
                                Share
                            </button>

                        </div>
                    </div>
                    <hr class="w-full bg-slate-300 my-4">
                    <div class="pb-4">
                        <p class="font-bold text-lg mb-1"> Location</p>
                        <p class="text-sm">{{ data.data.location }}</p>
                    </div>
                    <hr class="w-full bg-slate-300 my-4">
                    <h2 class="font-bold text-lg mb-4">
                        Description
                    </h2>
                    <div class="overflow-y-auto">
                        <p>{{ data.data.description }}</p>
                    </div>

                    <!-- message box -->
                    <div v-if="showMessage"
                        class="w-full shadow-xl min-h-[75%] -md:h-screen bg-blue-100 z-30 absolute -md:fixed bottom-0 left-0"
                        :class="[closeMessage ? ' animate-slide_out_to_bottom' : ' animate-slide_in_from_bottom']">
                        <template v-if="chatLoading">
                            <div class="flex justify-center -md:h-screen bg-blue-100 pt-20">
                                <Spinner class="text-4xl" />
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex px-4 h-16 bg-accent gap-4 items-center">
                                <button class="text-white" @click="closeMessageBox">
                                    <i class="fa-solid fa-arrow-left text-lg font-bold"> </i>
                                </button>
                                <div class="flex gap-2 items-center">
                                    <span v-if="chats.recipient.picture == null"
                                        class="capitalize text-2xl flex justify-center items-center bg-orange-500 text-white h-8 w-8 border rounded-full">
                                        {{ chats.recipient.name.charAt(0) }}
                                    </span>
                                    <span v-else
                                        class="flex justify-center items-center text-white h-10 w-10 aspect-square border rounded-full">
                                        <img class="h-full w-full rounded-full aspect-square"
                                            :src="chats.recipient.picture" alt="picture">
                                    </span>
                                    <div class="flex flex-col">
                                        <span class="capitalize text-white">
                                            {{ chats.recipient.name }}
                                        </span>
                                        <span class="text-[12px] -mt-1 text-gray-300">
                                            Offline
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <!-- chat area -->
                            <div id="chat_area" class="h-[calc(100%-75px)] p-4 pb-8 -md:pb-24 w-full overflow-y-auto">
                                <template v-for="key in Object.keys(chats.messages)">
                                    <div>
                                        <div class="flex justify-center py-3">
                                            <span
                                                class="font-bold cursor-default bg-blue-900/30 px-3 py-0.5 rounded-lg">
                                                {{ chats.messages[key][0].date }}
                                            </span>
                                        </div>
                                        <ul>
                                            <template v-for="chat in chats.messages[key]">
                                                <li class="rounded flex mb-1 w-full"
                                                    :class="chat.content.from !== chats.recipient.ref ? ' justify-end' : 'justify-start'">
                                                    <span v-if="chat.content.time"
                                                        class="w-fit p-2 rounded-[10px] relative flex flex-col"
                                                        :class="chat.content.from !== chats.recipient.ref ? ' bg-blue-600 text-white rounded-br-none' : 'bg-white rounded-bl-none'">
                                                        <img class="w-52" v-if="chat.content.message_pictures"
                                                            :src="chat.content.message_pictures.url" alt="">
                                                        {{ chat.content.message }}
                                                        <span class="text-[12px] text-right"
                                                            :class="chat.content.from !== chats.recipient.ref ? ' text-blue-100' : 'text-gray-500'">
                                                            {{ chat.content.time }}
                                                            <span v-if="chat.content.from !== chats.recipient.ref">
                                                                <i class="fa-solid fa-check"></i>
                                                            </span>
                                                        </span>
                                                    </span>
                                                    <span v-else
                                                        class="w-fit p-2 rounded-[10px] relative flex flex-col bg-blue-600 text-white rounded-br-none ">
                                                        <img class="w-52" v-if="chat.content.message_pictures"
                                                            :src="chat.content.message_pictures.url" alt="">
                                                        {{ chat.content.message }}

                                                    </span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>

                                </template>

                            </div>
                            <!-- input field -->
                            <div class="bg-white w-full flex justify-center items-center absolute p-4 bottom-0 h-20">
                                <template v-if="imgSrc.length > 0">
                                    <div class="absolute bottom-16 right-8">
                                        <img :src="imgSrc[0]" alt="" class="w-64 aspect-auto object-cover rounded">

                                    </div>
                                </template>

                                <div class="h-full w-full flex gap-4 items-center rounded-3xl px-8 bg-gray-100">
                                    <button @mousedown="onMouseDown" @mouseup="onMouseUp">
                                        <i class="fas fa-microphone text-gray-700"></i>
                                    </button>
                                    <form class="w-full" name="form" id="form">
                                        <input v-model="messageInput" type="text"
                                            :placeholder="imgSrc.length > 0 ? 'Add a caption' : 'Type a message'"
                                            name="message" id="message"
                                            class="bg-transparent border-none focus-visible:outline-none caret-blue-600 flex-1 placeholder:text-sm w-full">
                                        <input multiple type="file" class="hidden" name="file_upload"
                                            accept=".jpg, .jpeg, .png, .webp" id="file_upload">
                                    </form>
                                    <div class="flex gap-4 text-gray-600 items-center">
                                        <button @mousedown="onMouseDown" @mouseup="onMouseUp" type="button"
                                            @click="uploadPicture">
                                            <i class="fa-regular fa-image"></i>
                                        </button>
                                        <button @mousedown="onMouseDown" @mouseup="onMouseUp" type="button"> <i
                                                class="fa-regular fa-face-smile"></i>
                                        </button>
                                        <button @mousedown="onMouseDown" @mouseup="onMouseUp" type="button"
                                            @click="sendMessage">
                                            <i class="fa-regular fa-paper-plane"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>
    </template>
</template>