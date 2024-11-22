export type suggestions = Array<{
    placePrediction: { text: { matches: string[], text: string } }
}>

export type Listing = {

    description: string,
    ref: string,
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
    data: Array<Listing>,
    hasMorePages: boolean,

}
export type form = {
    location?: string,
    status?: string,
    price?: string,
    property_type: string,
}