import type { Listings } from "~/types/listings"

export function useStoredListings() {
    const storedListings = <Listings | undefined>useState('listings').value

    return { storedListings: storedListings !== undefined ? storedListings : undefined }
} 