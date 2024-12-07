import type { Listings } from "~/types/listings"

export function useStoredListings() {
    const storedListings = <Listings | undefined>useState('listings').value
    function setStoredListings(listings: Listings) {
        useState('listings').value = listings
    }

    return { setStoredListings, storedListings: storedListings != undefined ? readonly(storedListings) : undefined }
} 