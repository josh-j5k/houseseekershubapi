import type { LocationQuery, LocationQueryValue } from 'vue-router';
import type { Listings } from '~/types/listings';


const loading = ref(true)
const listings = ref(<Listings>{})
const { handleRequest } = useAxios()
const location = ref(<string | null>null)
const errors = reactive({
    locationError: false,
    priceError: false
})
const perPage = ref('8')
const status = ref(<string>'any')
const propertyType = ref(<string[]>[])

const price = ref(<{
    min: string | null,
    max: string | null
}>{
        min: null,
        max: null
    })


async function getListings(query: any) {
    loading.value = true
    const { data, error } = await handleRequest('get', 'listings', query)
    if (!error) {
        listings.value.data = data.data.listings
        listings.value.hasMorePages = data.data.hasMorePages
        useState('listings', () => listings.value)
    }
    loading.value = false
}
async function submit(name: string, value: LocationQueryValue | LocationQueryValue[]) {
    const encoded = encodeURIComponent(value?.toString()!)
    const query = useRoute().query
    const newQuery = { ...query }

    newQuery[name] = encoded
    await navigateTo({
        path: '/listings',
        query: newQuery
    })
    const decodedQuery = <LocationQuery>{}
    for (const key in newQuery) {
        const value = decodeURIComponent(newQuery[key] as string);
        decodedQuery[key] = value
    }
    decodedQuery['limit'] = perPage.value
    getListings(decodedQuery)
}


export function useListing() {

    function init() {
        const query = useRoute().query
        const decodedQuery = <LocationQuery>{}

        for (const key in query) {
            const value = query[key]
            const decoded = decodeURIComponent(value?.toString()!)
            decodedQuery[key] = decoded
            if (key == 'status') {
                status.value = decoded
            }
            if (key == 'location') {
                location.value = decoded
            }
            if (key == 'price') {
                const arr = decoded.split('|')
                arr?.forEach(item => {
                    if (item.startsWith('over')) {
                        price.value.min = item.substring(4)
                    } else {
                        price.value.max = item.substring(4)
                    }
                })
            }
            if (key == 'property_type') {
                propertyType.value = decoded.split('|')!
            }
        }
        decodedQuery['limit'] = perPage.value
        getListings(decodedQuery)
    }
    function statusSubmit() {
        submit('status', status.value)
    }
    function locationSubmit() {
        if (location.value === null || location.value.length === 0) {
            errors.locationError = true
            setTimeout(() => errors.locationError = false, 4000)
            return
        }
        submit('location', location.value)
    }
    function setPriceError() {
        errors.priceError = true
        setTimeout(() => errors.priceError = false, 4000)
        return
    }
    function priceValidate() {

        const regex = /^\d+$/

        if ((price.value.max?.length === 0 || price.value.max == null) && (price.value.min?.length === 0 || price.value.min == null)) {
            setPriceError()
        }
        if (price.value.max && !regex.test(price.value.max)) {
            setPriceError()
        }
        if (price.value.min && !regex.test(price.value.min)) {
            setPriceError()
        }
        return true
    }

    function priceSubmit() {

        if (priceValidate()) {
            if (price.value.min && price.value.max) {
                submit('price', 'over'.concat(price.value.min) + '|' + 'under'.concat(price.value.max))
                return
            }
            if (price.value.min) {
                submit('price', 'over'.concat(price.value.min))
                return
            }
            if (price.value.max) {
                submit('price', 'under'.concat(price.value.max))
                return
            }
        }
    }
    function propertySubmit() {
        submit('property_type', propertyType.value.join('|'))
    }
    function removeFilter(e: string): void {
        const newQuery = { ...useRoute().query }
        const decodedQuery = <LocationQuery>{}
        for (const key in newQuery) {
            const value = newQuery[key] as string
            const decoded = decodeURIComponent(value)
            if (decoded.includes(e)) {
                if (key == 'location' || key == 'status') {
                    delete newQuery[key]
                    key == 'location' ? location.value = null : status.value = 'any'
                } else {
                    const arr = decoded.split('|')
                    if (key == 'price') {
                        if (arr.length == 1) {
                            delete newQuery[key]
                            e.includes('over') ? price.value.min = null : price.value.max = null
                        } else {
                            arr.forEach(item => {
                                if (item === e) {
                                    e.includes('over') ? price.value.min = null : price.value.max = null
                                } else {
                                    newQuery[key] = item
                                }

                            })
                        }
                    }
                    if (key == 'property_type') {

                        if (arr.length == 1) {
                            delete newQuery[key]
                            propertyType.value = <string[]>[]
                        } else {
                            let newArr = <string[]>[]
                            arr.forEach(item => {
                                if (item !== e) {
                                    newArr.push(item)
                                }
                            })
                            propertyType.value = newArr
                            newQuery[key] = newArr.join('|')
                        }
                    }
                }


            } else {
                decodedQuery[key] = decoded
            }
        }
        navigateTo({
            path: '/listings',
            query: newQuery
        })
        decodedQuery['limit'] = perPage.value
        getListings(decodedQuery)
    }

    return {
        loading: readonly(loading),
        listings,
        location,
        errors: readonly(errors),
        price, status, propertyType,
        statusSubmit,
        priceSubmit, propertySubmit,
        locationSubmit,
        removeFilter,
        init
    }
}
