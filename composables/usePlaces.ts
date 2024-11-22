import axios from "axios"
import type { suggestions } from "~/types/listings"


const url = import.meta.env.VITE_GOOGLE_PLACES
const apiKey = import.meta.env.VITE_GOOGLE_PLACES_API_KEY
const includedRegionCodes = ["cm"]
const suggestions = ref(<suggestions>[])
export default function usePlaces() {
    const headers = { 'Content-Type': 'application/json', 'X-Goog-Api-Key': apiKey }
    async function handleRequest(input: string) {
        const data = { input, includedRegionCodes }
        try {
            const response = await axios.post(url, data, { headers })
            suggestions.value = response.data.suggestions
        } catch (error: any) {
            console.log(error.response);

        }
    }
    function closeSuggestion() {
        suggestions.value = <suggestions>[]
    }
    return {
        suggestions: readonly(suggestions),
        handleRequest,
        closeSuggestion
    }
}