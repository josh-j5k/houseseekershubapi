export type suggestions = Array<{
    placePrediction: { text: { matches: string[], text: string } }
}>

export type Listing = {
    id: string
    description: string,
    slug: string,
    location: string,
    price: number,
    propertyStatus: string,
    propertyType: string,
    title: string,
    images: string[]
}
export type SingleListing = {
    listing: Listing
}
export type Listings = {
    listings: Array<Listing>,
    hasMorePages: boolean,

}
export type form = {
    title: string,
    description: string,
    price: string | number,
    location: string,
    property_status: string,
    property_type: string,
    deletedImages: string[],
    inputFiles: File[]
}
export type ListingsResponse = { status: string, message: string, data: { listings: Listing[], hasMorePages: boolean } }