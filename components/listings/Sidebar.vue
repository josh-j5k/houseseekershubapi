<script setup lang="ts">


defineProps<{
    sidebarToggled: boolean,
}>()

const { location, locationSubmit, price, errors, status, propertyType, priceSubmit, propertySubmit, statusSubmit } = useListing()

const { closeSuggestion, handleRequest, suggestions } = usePlaces()


async function autocompleteLocation() {
    if (location.value && location.value.length > 0) {
        await handleRequest(location.value)

    }
}
function setLocation(e: string) {
    location.value = e
    closeSuggestion()
}
function submitPrice(ev: KeyboardEvent) {

    if (ev.code === 'Enter' || ev.code === 'NumpadEnter') {
        priceSubmit()
    }
}
function submitLocation(ev: KeyboardEvent) {

    if (ev.code === 'Enter' || ev.code === 'NumpadEnter') {
        locationSubmit()
    }
}
</script>

<template>
    <div class="-lg:fixed z-40 top-0 left-0 -lg:h-screen -lg:w-5/6 shadow-md bg-white px-8 pt-8 -lg:pt-28 pb-8 -lg:overflow-y-auto transition-transform "
        :class="[sidebarToggled ? '-lg:translate-x-0' : '-lg:-translate-x-full']">
        <div class="">
            <h2 class="capitalize font-bold text-2xl mb-4">location</h2>
            <label for="location" class="sr-only">location</label>
            <div class="flex gap-3 relative">
                <input @keydown="submitLocation" @change="autocompleteLocation" v-model="location"
                    :class="errors.locationError && 'border-red-500'" class="input bg-transparent" type="text" name=""
                    id="location" placeholder="Type your town, region">
                <button @click="locationSubmit" type="button" title="submit location"><i
                        class="fas fa-chevron-right fa-lg"></i></button>
                <template v-if="suggestions.length > 0">
                    <ListingsLocationSuggestions @set-location="setLocation" />
                </template>
            </div>
            <p v-if="errors.locationError" class="text-red-500">Please enter a location</p>
        </div>
        <hr class="w-4/5 bg-slate-300 my-8">
        <div>
            <h2 class="capitalize font-bold text-2xl mb-4">status</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex gap-3 items-center h-full">

                    <label for="status-all" class="capitalize">
                        <input id="status-all" @change="statusSubmit" v-model="status" type="radio" value="any"
                            class="accent-accent">
                        any
                    </label>
                </div>
                <div class="flex gap-3 items-center h-full">
                    <label for="status-rent" class="capitalize">
                        <input id="status-rent" @change="statusSubmit" type="radio" value="rent" v-model="status"
                            class="accent-accent">
                        rent
                    </label>
                </div>
                <div class="flex gap-3 items-center h-full">
                    <label for="status-sale" class="capitalize">
                        <input id="status-sale" @change="statusSubmit" type="radio" value="sale" v-model="status"
                            class="accent-accent">
                        sale
                    </label>
                </div>
            </div>
        </div>
        <hr class="w-4/5 bg-slate-300 my-8">
        <div>
            <h2 class="capitalize font-bold text-2xl mb-4">Price</h2>
            <div class="flex gap-3 items-center">
                <input @keydown="submitPrice" :class="errors.priceError && 'border-red-500'"
                    class="input bg-transparent" v-model="price.min" placeholder="min" type="text" size="7" name="min"
                    id="price-min">
                <label for="price-min" class="sr-only"> Minimum price</label>
                <span>To</span>
                <input @keydown="submitPrice" :class="errors.priceError && 'border-red-500'"
                    class="input bg-transparent" v-model="price.max" placeholder="max" size="7" type="text" name="max"
                    id="price-max">
                <label for="price-max" class="sr-only"> Maximum price</label>
                <button @click="priceSubmit" title="submit price" type="button"><i
                        class="fas fa-chevron-right fa-lg"></i></button>
            </div>
            <p v-if="errors.priceError" class="text-red-500">Please enter a valid price range</p>
        </div>
        <hr class="w-4/5 bg-slate-300 my-8">
        <div>
            <h2 class="capitalize font-bold text-2xl mb-4">Property type</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex gap-2 items-center">
                    <input @change="propertySubmit" type="checkbox" name="room" id="property-room"
                        class="checkbox accent-accent" value="room" v-model="propertyType">
                    <label for="property-room" class="capitalize">room</label>
                </div>
                <div class="flex gap-2 items-center">
                    <input @change="propertySubmit" type="checkbox" name="studio" id="property-studio"
                        class="checkbox accent-accent" value="studio" v-model="propertyType">
                    <label for="property-studio" class="capitalize">studio</label>
                </div>
                <div class="flex gap-2 items-center">
                    <input @change="propertySubmit" type="checkbox" name="appartment" id="property-appartment"
                        class="checkbox accent-accent" value="appartment" v-model="propertyType">
                    <label for="property-appartment" class="capitalize">appartment</label>
                </div>
                <div class="flex gap-2 items-center">
                    <input @change="propertySubmit" type="checkbox" name="duplex" id="property-duplex"
                        class="checkbox accent-accent" value="duplex" v-model="propertyType">
                    <label for="property-duplex" class="capitalize">duplex</label>
                </div>
            </div>
        </div>
    </div>
</template>