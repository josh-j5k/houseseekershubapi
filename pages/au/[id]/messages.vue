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
const messageInput = ref()
const users = ref(<any>[])
const authUser = useState('user').value as user | undefined
const colors = ['bg-orange-500', 'bg-orange-800', 'bg-blue-700', 'bg-green-500', 'bg-rose-600', 'bg-emerald-500', "bg-pink-600"]

const inboxLoading = ref(true)
const chatsLoading = ref(false)
async function sendMessage() {
    if (messageInput.value === null || messageInput.value.length == 0) {
        return
    }
    const value = messageInput.value
    messageInput.value = null
    const params = { date: 'Today', content: <{ message: string, from: string, image: string | null }>{ message: value, from: authUser?.user.ref } }
    imgSrc.value.length > 0 ? params.content.image = imgSrc.value[0] : ''
    if (chats.value.messages['Today']) {
        chats.value.messages["Today"] = [...chats.value.messages["Today"], params]
    }
    else {
        chats.value.messages["Today"] = [params]
    }
    imgSrc.value.length = 0
    const { data, error } = await handleRequest('post', '/messages', { message: value, receivers_ref: activeChat.value?.ref })
    chats.value.messages["Today"].at(-1).content.time = data.data
    getInboxMessages()
    resetInput(document.getElementById('file_upload') as HTMLInputElement)
}
async function getChats(inbox: { id: number, ref: string }) {
    if (Object.keys(chats.value).length === 0 || chats.value.recipient.ref !== inbox.ref) {
        chatsLoading.value = true
        const { data, error } = await handleRequest('get', '/messages/'.concat(inbox.ref))
        if (!error) {
            chats.value = data.data
        }
        chatsLoading.value = false
        activeChat.value = inbox
        setTimeout(() => {
            const chatArea = document.getElementById('chat_area') as HTMLDivElement
            chatArea.scrollTop = chatArea.scrollHeight
        }, 1);
    }

}
function uploadPicture(e: MouseEvent) {
    const dropbox = e.currentTarget as HTMLDivElement
    const input = dropbox.querySelector('#file_upload') as HTMLInputElement
    assignFiles(input)
    input.click()
}
async function getUsers() {
    const { data, error } = await handleRequest('get', '/messages/users')
    users.value = data.data
}
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
    <section class="grid grid-cols-[35%_65%] ">
        <div class="bg-white lg:h-[calc(100vh-65px)] lg:overflow-y-hidden custom-shadow">
            <div class="flex relative px-4 h-24 justify-between items-center">
                <h1 class="text-2xl text-blue-500">
                    Messages
                </h1>
                <button @click="getUsers" class="bg-gray-100 w-8 h-8 rounded-full">
                    <i class="fas fa-search text-gray-700"></i>
                </button>
                <!-- <div class="absolute top-4 w-full shadow-md bg-red-50">
                    <ul>
                        <template v-for="item in users">
                            <li v-if="item.ref != authUser?.user.ref" role="button" @click="setMessage(item)">{{
                                item.name }}</li>
                        </template>
</ul>
</div> -->
            </div>
            <hr>
            <div class="overflow-y-auto h-[calc(100%-96px)] overflow-x-clip px-4" role="list">
                <template v-if="inboxLoading">
                    <div class="flex justify-center pt-20">
                        <Spinner class="text-4xl" />
                    </div>
                </template>
                <template v-else v-for="(inbox, index) in inbox">
                    <div class="py-3" role="listitem">
                        <div class="w-full flex gap-3 cursor-pointer  pl-2" role="button"
                            :class="activeChat === inbox.id ? 'border-l-4 border-accent' : ''"
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
                                        <span class="text-gray-600">
                                            {{ inbox.message }}
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
        <div class="bg-gray-100 lg:h-[calc(100vh-65px)] lg:overflow-y-hidden relative">
            <template v-if="chatsLoading">
                <div class="flex justify-center pt-20">
                    <Spinner class="text-4xl" />
                </div>
            </template>
            <template v-if="Object.keys(chats).length > 0 && !chatsLoading">
                <div class="flex px-4 h-24 bg-white justify-between items-center">
                    <div class="flex gap-2">
                        <span v-if="chats.recipient.avatar == null"
                            class="capitalize text-2xl flex justify-center items-center bg-orange-500 text-white h-10 w-10 border rounded-full">
                            {{ chats.recipient.name.charAt(0) }}
                        </span>
                        <span v-else
                            class="capitalize text-2xl flex justify-center items-center text-white h-14 w-14 aspect-square border rounded-full">
                            <img class="h-full w-full rounded-full aspect-square" :src="chats.recipient.avatar"
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
                <div id="chat_area" class="h-[calc(100%-160px)] p-4 pb-8 w-full overflow-y-auto">
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
                                            <img class="w-52" v-if="chat.content.image" :src="chat.content.image"
                                                alt="">
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
                                            <img class="w-52" v-if="chat.content.image" :src="chat.content.image"
                                                alt="">
                                            {{ chat.content.message }}

                                        </span>
                                    </li>
                                </template>
                            </ul>
                        </div>

                    </template>

                </div>
                <div class="bg-white w-full flex justify-center items-center absolute p-4 bottom-0 h-20">

                    <template v-if="imgSrc.length > 0">
                        <div class="absolute bottom-16 right-8">
                            <img :src="imgSrc[0]" alt="" class="w-64 aspect-auto object-cover rounded">

                        </div>
                    </template>

                    <div class="h-full w-full flex gap-4 items-center rounded-3xl px-8 bg-gray-100">
                        <button>
                            <i class="fas fa-microphone text-gray-700"></i>
                        </button>
                        <input v-model="messageInput" type="text"
                            :placeholder="imgSrc.length > 0 ? 'Add a caption' : 'Type a message'" name="message"
                            id="message"
                            class="bg-transparent border-none focus-visible:outline-none caret-blue-600 flex-1 placeholder:text-sm">
                        <div class="flex gap-4 text-gray-600 items-center">
                            <button @click="uploadPicture">
                                <input type="file" class="hidden" name="file_upload" accept=".jpg, .jpeg, .png, .webp"
                                    id="file_upload">
                                <i class="fa-regular fa-image"></i>
                            </button>
                            <button>
                                <i class="fa-regular fa-face-smile"></i>
                            </button>
                            <button @click="sendMessage">
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