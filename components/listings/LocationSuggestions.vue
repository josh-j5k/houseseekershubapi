<script setup lang="ts">


const { suggestions, closeSuggestion } = usePlaces()
const emit = defineEmits(['setLocation'])

function setLocation(e: string) {
    emit('setLocation', e)
}

if (import.meta.client) {
    document.documentElement.onclick = (ev) => {
        const target = <HTMLElement>ev.target
        if (target.id !== 'location-suggestion') {
            closeSuggestion()
        }
    }
}
</script>

<template>
    <div id="location-suggestion" class="absolute bg-blue-100 w-full min-h-16 p-4 top-10 shadow">
        <ul>
            <template v-for="suggestion in suggestions">
                <li role="button" class="py-1 hover:text-blue-400 text-gray-800 cursor-pointer"
                    @click="setLocation(suggestion.placePrediction.text.text)">
                    {{ suggestion.placePrediction.text.text.length > 25 ? suggestion.placePrediction.text.text.slice(0,
                        25).concat('...') : suggestion.placePrediction.text.text }}
                </li>
            </template>
        </ul>
    </div>
</template>