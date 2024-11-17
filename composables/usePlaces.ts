import axios from "axios"

type suggestions = Array<{
    placePrediction: { text: { matches: string[], text: string } }
}>
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

    return {
        suggestions,
        handleRequest
    }
}