<script setup lang="ts">
import type { user } from '~/types/user';


definePageMeta({
    layout: 'authenticated',
    middleware: 'auth'
})
const { assignFiles, imgSrc, filesArr, resetInput } = useFileUpload()
const { handleRequest } = useBackend()
const chats = ref(<any>{})
const activeChat = ref(<{
    "id": number,
    ref: string
} | null>null)
const inbox = ref(<any>[

])
const mobileActiveChat = ref(false)
const messageInput = ref('')
const users = ref(<any>[])
const authUser = useState('user').value as user | undefined
const colors = ['bg-orange-500', 'bg-orange-800', 'bg-blue-700', 'bg-green-500', 'bg-rose-600', 'bg-emerald-500', "bg-pink-600"]

const inboxLoading = ref(true)
const chatsLoading = ref(false)

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
    const params = { date: 'Today', content: <{ message: string, from: string, message_pictures: { url: string } | null }>{ message: value, from: authUser?.user.ref, message_pictures: imgSrc.value.length > 0 ? { url: imgSrc.value[0] } : null } }

    if (chats.value.messages['Today']) {
        chats.value.messages["Today"] = [...chats.value.messages["Today"], params]
    }
    else {
        chats.value.messages["Today"] = [params]
    }
    imgSrc.value.length = 0

    const payload = <{ message: string, receivers_ref: string, message_pictures: any | undefined }>{ message: value, receivers_ref: activeChat.value?.ref }
    if (file != null) {
        payload.message_pictures = file
    }
    const { data, error } = await handleRequest('post', '/messages', payload, 'multpartForm')
    chats.value.messages["Today"].at(-1).content.time = data.data
    if (!error) {
        getInboxMessages()
    }
    resetInput(document.getElementById('file_upload') as HTMLInputElement)
    file.length = 0
}


// Fetch chat messages
async function getChats(inbox: { id: number, ref: string }) {
    mobileActiveChat.value = true
    if (Object.keys(chats.value).length === 0 || chats.value.recipient.ref !== inbox.ref) {
        chatsLoading.value = true
        activeChat.value = inbox
        const { data, error } = await handleRequest('get', '/messages/'.concat(inbox.ref))
        if (!error) {
            chats.value = data.data
            setTimeout(() => {
                const chatArea = document.getElementById('chat_area') as HTMLDivElement
                chatArea.scrollTop = chatArea.scrollHeight
                const input = document.querySelector('#file_upload') as HTMLInputElement
                assignFiles(input)
            }, 1);

        }

        chatsLoading.value = false

    }

}
function uploadPicture() {
    const input = document.querySelector('#file_upload') as HTMLInputElement
    input.click()
}
function resetActiveChat() {
    mobileActiveChat.value = false
}
// Get messges for the inbox
async function getInboxMessages() {
    const { data, error } = await handleRequest('get', '/messages')
    if (!error) {
        inbox.value = data.data
    }
    inboxLoading.value = false
}
getInboxMessages()

</script>

<template>
    <section class="grid lg:grid-cols-[35%_65%] grid-col-1">
        <div class="bg-white lg:h-[calc(100vh-65px)] lg:overflow-y-hidden custom-shadow">
            <div class="flex relative px-4 h-24 justify-between items-center">
                <h1 class="text-2xl text-blue-500">
                    Messages
                </h1>
                <button @mousedown="onMouseDown" @mouseup="onMouseUp" class="bg-gray-100 w-8 h-8 rounded-full">
                    <i class="fas fa-search text-gray-700"></i>
                </button>

            </div>
            <hr>
            <div class="overflow-y-auto h-[calc(100%-96px)] overflow-x-clip" role="list">
                <template v-if="inboxLoading">
                    <div class="flex justify-center pt-20">
                        <Spinner class="text-4xl" />
                    </div>
                </template>
                <template v-else v-for="(inbox, index) in inbox">
                    <div class="py-3 px-2" role="listitem"
                        :class="activeChat?.id === inbox.id ? 'bg-gradient-to-l from-blue-800/10 to-blue-300/30 ' : ''">
                        <div class="w-full flex gap-3 cursor-pointer" role="button"
                            @click="getChats({ id: inbox.id, ref: inbox.recipient.ref })">
                            <span v-if="inbox.recipient.avatar === null"
                                class="capitalize text-2xl flex justify-center items-center text-white h-14 w-14 aspect-square border rounded-full"
                                :class="index ? colors[index] : 'bg-orange-600'">
                                {{ inbox.recipient.name.charAt(0) }}
                            </span>
                            <span v-else
                                class="capitalize text-2xl flex justify-center items-center text-white h-14 w-14 aspect-square border rounded-full">
                                <img class="h-full w-full rounded-full aspect-square" :src="inbox.recipient.avatar"
                                    alt="picture">
                            </span>
                            <div class=" flex flex-col gap-1 w-full">
                                <div class="flex justify-between">
                                    <span class="font-bold capitalize">
                                        {{ inbox.recipient.name }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ inbox.time }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <div class="flex gap-1">
                                        <span v-if="inbox.latest_message_sender == authUser?.user.ref">
                                            <i class="fa-solid fa-check-double text-gray-500"></i>
                                        </span>
                                        <span v-if="inbox.message" class="text-gray-600">
                                            {{ inbox.message }}
                                        </span>
                                        <span v-else>
                                            <i class="fa-regular fa-image"></i>
                                        </span>
                                    </div>
                                    <span v-if="inbox.latest_message_sender !== authUser?.user.ref && inbox.unred > 0"
                                        class="rounded-full bg-blue-500 text-white text-[12px] flex justify-center items-center w-5 h-5  aspect-square">
                                        {{ inbox.unread }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="bg-gray-50">
                </template>
            </div>

        </div>
        <div class="bg-gray-100 lg:h-[calc(100vh-65px)] -md:transition-transform -md:duration-300 -md:ease-in -md:w-full md -md:absolute -lg:z-20 lg:overflow-y-hidden"
            :class="[mobileActiveChat ? '-md:translate-x-0' : '-md:translate-x-full']">
            <template v-if="chatsLoading">
                <div class="flex justify-center -md:h-screen bg-white pt-20">
                    <Spinner class="text-4xl" />
                </div>
            </template>
            <!-- chat -->
            <template v-if="Object.keys(chats).length > 0 && !chatsLoading">
                <div class="flex px-4 h-24 bg-white gap-4 items-center">
                    <button class="md:hidden" @click="resetActiveChat">
                        <i class="fa-solid fa-arrow-left text-lg font-bold"> </i>
                    </button>
                    <div class="flex gap-2">
                        <span v-if="chats.recipient.picture == null"
                            class="capitalize text-2xl flex justify-center items-center bg-orange-500 text-white h-10 w-10 border rounded-full">
                            {{ chats.recipient.name.charAt(0) }}
                        </span>
                        <span v-else
                            class="capitalize text-2xl flex justify-center items-center text-white h-14 w-14 aspect-square border rounded-full">
                            <img class="h-full w-full rounded-full aspect-square" :src="chats.recipient.picture"
                                alt="picture">
                        </span>
                        <div class="flex flex-col">
                            <span class="capitalize text-lg">
                                {{ chats.recipient.name }}
                            </span>
                            <span class="text-[13px] -mt-1 text-gray-500">
                                Offline
                            </span>
                        </div>
                    </div>

                </div>
                <!-- chat area -->
                <div id="chat_area" class="h-[calc(100vh-161px)] p-4 pb-8 -md:pb-24 w-full overflow-y-auto">
                    <template v-for="key in Object.keys(chats.messages)">
                        <div>
                            <div class="flex justify-center py-3">
                                <span class="font-bold cursor-default bg-blue-900/30 px-3 py-0.5 rounded-lg">
                                    {{ chats.messages[key][0].date }}
                                </span>
                            </div>
                            <ul>
                                <template v-for="chat in chats.messages[key]">
                                    <li class="rounded flex mb-1 w-full"
                                        :class="chat.content.from === authUser?.user.ref ? ' justify-end' : 'justify-start'">
                                        <span v-if="chat.content.time"
                                            class="w-fit p-2 rounded-[10px] relative flex flex-col"
                                            :class="chat.content.from === authUser?.user.ref ? ' bg-blue-600 text-white rounded-br-none' : 'bg-white rounded-bl-none'">
                                            <img class="w-52" v-if="chat.content.message_pictures"
                                                :src="chat.content.message_pictures.url" alt="">
                                            {{ chat.content.message }}
                                            <span class="text-[12px] text-right"
                                                :class="chat.content.from === authUser?.user.ref ? ' text-blue-100' : 'text-gray-500'">
                                                {{ chat.content.time }}
                                                <span v-if="chat.content.from === authUser?.user.ref">
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
                                :placeholder="imgSrc.length > 0 ? 'Add a caption' : 'Type a message'" name="message"
                                id="message"
                                class="bg-transparent border-none focus-visible:outline-none caret-blue-600 flex-1 placeholder:text-sm w-full">
                            <input multiple type="file" class="hidden" name="file_upload"
                                accept=".jpg, .jpeg, .png, .webp" id="file_upload">
                        </form>
                        <div class="flex gap-4 text-gray-600 items-center">
                            <button @mousedown="onMouseDown" @mouseup="onMouseUp" type="button" @click="uploadPicture">
                                <i class="fa-regular fa-image"></i>
                            </button>
                            <button @mousedown="onMouseDown" @mouseup="onMouseUp" type="button"> <i
                                    class="fa-regular fa-face-smile"></i>
                            </button>
                            <button @mousedown="onMouseDown" @mouseup="onMouseUp" type="button" @click="sendMessage">
                                <i class="fa-regular fa-paper-plane"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </template>
        </div>
    </section>
</template>

<style scoped>
.custom-shadow {
    box-shadow: inset -1px 0px rgba(0, 0, 0, 0.144);
}
</style>