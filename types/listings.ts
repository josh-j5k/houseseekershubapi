export type Query = {
    location: string | null,
    status: "any" | "rent" | "sale",
    price: {
        min: string | null,
        max: string | null
    },
    property_type: string[]
}
type Links = {
    first: string,
    last: string,
    next: string | null,
    prev: string | null
}
export type metaLinks = {
    url: string,
    label: string,
    active: boolean
}
type Meta = {
    links: Array<metaLinks>,
    current_page: number,
    last_page: number,
    path: string,
    per_page: number,
    to: number,
    total: number
}
export type Listing = {
    id: string,
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
    links: Links,
    meta: Meta,

}
export type form = {
    location?: string,
    status?: string,
    price?: string,
    property_type: string,
}