<script setup lang="ts">

definePageMeta({
    layout: 'authenticated',
    middleware: 'auth'
})
const messages = [
    { message: 'hello', from: 'you' },
    { message: 'testing one two three', from: 'you' },
    { message: 'welcome', from: 'me' },
    { message: 'thank you, how is it going', from: 'you' },
    { message: 'all good and you', from: 'me' },
    { message: 'I\'m fine', from: 'you' },
    { message: 'So what is in the menu', from: 'you' },
    { message: 'Nothing', from: 'me' },
    { message: 'Have ideas?', from: 'me' },
    { message: 'No. But I\'ll check out somethig', from: 'you' },
    { message: 'Alright let me know when you ready', from: 'me' },
    { message: 'We better get this on the road', from: 'you' },
    { message: 'sure', from: 'me' },
    { message: 'alright', from: 'you' },
]

const userMessages = [
    {
        name: 'Mary Doe',
        lastMassage: 'alrigt',
        from: 'you'
    },
    {
        name: 'William Bax',
        lastMassage: 'On the way',
        from: 'you',
        unread: 2
    },
    {
        name: 'Liz smiith',
        lastMassage: 'I can\'t find it',
        from: 'me',

    },
    {
        name: 'Peter Pan',
        lastMassage: 'It is not going',
        from: 'me'
    },
    {
        name: 'Baba Riz',
        lastMassage: 'Well done! Good',
        from: 'you',
        unread: 4
    },
    {
        name: 'Golden',
        lastMassage: 'alrigt',
        from: 'you',
        unread: 5
    },
    {
        name: 'Alvino ',
        lastMassage: 'Good job',
        from: 'you'
    },
]
const colors = ['bg-orange-500', 'bg-orange-800', 'bg-blue-700', 'bg-green-500', 'bg-rose-600', 'bg-emerald-500', "bg-pink-600"]
const name = "Mary Doe"
</script>

<template>
    <section class="grid grid-cols-[35%_65%] ">
        <div class="bg-white lg:h-[calc(100vh-65px)] lg:overflow-y-hidden custom-shadow">
            <div class="flex px-4 h-24 justify-between items-center">
                <h1 class="text-2xl text-blue-500">
                    Messages
                </h1>
                <button class="bg-gray-100 w-8 h-8 rounded-full">
                    <i class="fas fa-search text-gray-700"></i>
                </button>
            </div>
            <hr>
            <div class="overflow-y-auto h-[calc(100%-96px)] overflow-x-clip px-4">
                <template v-for="(inbox, index) in userMessages">
                    <div class="flex gap-3 py-3">
                        <span
                            class="capitalize text-2xl flex justify-center items-center text-white h-14 w-14 aspect-square border rounded-full"
                            :class="index ? colors[index] : 'bg-orange-600'">
                            {{ inbox.name.charAt(0) }}
                        </span>
                        <div class=" flex flex-col gap-1 w-full">
                            <div class="flex justify-between">
                                <span class="font-bold capitalize">
                                    {{ inbox.name }}
                                </span>
                                <span v-if="false" class="text-sm text-gray-500">
                                    11:00
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    {{ inbox.lastMassage }}
                                </span>
                                <span v-if="inbox.unread"
                                    class="rounded-full bg-blue-500 text-white text-[12px] flex justify-center items-center w-5 h-5  aspect-square">
                                    {{ inbox.unread }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr class="bg-gray-50">
                </template>
            </div>
        </div>
        <div class="bg-gray-100 lg:h-[calc(100vh-65px)] lg:overflow-y-hidden relative">
            <div class="flex px-4 h-24 bg-white justify-between items-center">
                <div class="flex gap-2">
                    <span
                        class="capitalize text-2xl flex justify-center items-center bg-orange-500 text-white h-10 w-10 border rounded-full">
                        {{ name.charAt(0) }}
                    </span>
                    <div class="flex flex-col">
                        <span class="capitalize text-lg">
                            {{ name }}
                        </span>
                        <span class="text-[13px] -mt-1 text-gray-500">
                            Offline
                        </span>
                    </div>
                </div>

            </div>
            <div class="h-[calc(100%-160px)] p-4 pb-8 w-full overflow-y-auto">
                <ul>
                    <template v-for="message in messages">

                        <li class="rounded flex mb-1 w-full"
                            :class="message.from == 'me' ? ' justify-end' : 'justify-start'">
                            <span class="w-fit p-2 rounded-[10px] relative"
                                :class="message.from == 'me' ? ' bg-blue-600 text-white rounded-br-none' : 'bg-white rounded-bl-none'">
                                {{ message.message }}
                            </span>
                        </li>
                    </template>
                </ul>

            </div>
            <div class="bg-white w-full flex justify-center items-center absolute p-4 bottom-0 h-20">
                <div class="h-full w-full flex gap-4 items-center rounded-3xl px-8 bg-gray-100">
                    <button>
                        <i class="fas fa-microphone text-gray-700"></i>
                    </button>
                    <input type="text" placeholder="Type a message" name="message" id="message"
                        class="bg-transparent border-none focus-visible:outline-none caret-blue-600 flex-1 placeholder:text-sm">
                    <div class="flex gap-4 text-gray-600 items-center">
                        <button>
                            <i class="fa-regular fa-image"></i>
                        </button>
                        <button>
                            <i class="fa-regular fa-face-smile"></i>
                        </button>
                        <button>
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.custom-shadow {
    box-shadow: inset -1px 0px rgba(0, 0, 0, 0.144);
}
</style>