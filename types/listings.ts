export type Query = {
    location: string | null,
    status: "any" | "rent" | "sale",
    price: {
        min: string | null,
        max: string | null
    },
    property_type: string[]
}


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