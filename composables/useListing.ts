import type { LocationQuery, LocationQueryValue } from 'vue-router';

const location = ref(<string | null>null)
const errors = reactive({
    locationError: false,
    priceError: false
})

const status = ref(<string>'any')
const propertyType = ref(<string[]>[])

const price = ref(<{
    min: string | null,
    max: string | null
}>{
        min: null,
        max: null
    })


export function useListing() {
    function resetFields() {
        location.value = null
        price.value = { max: null, min: null }
        status.value = 'any'
        propertyType.value = <string[]>[]
    }
    function encodeQuery() {
        const query = useRoute().query
        const encodedQuery = <LocationQuery>{}

        for (const key in query) {
            const value = query[key]
            const decoded = decodeURIComponent(value?.toString()!)
            encodedQuery[key] = decoded
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
        return encodedQuery
    }
    function decodeQuery() {
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
        return decodedQuery
    }


    function locationValidate(): boolean {
        if (location.value === null || location.value.length === 0) {
            errors.locationError = true
            setTimeout(() => errors.locationError = false, 4000)
            return false
        }
        return true
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

    function removeQueryParams(e: string) {
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
        return { decodedQuery, newQuery }
    }

    return {

        location,
        errors: readonly(errors),
        price, status, propertyType,
        locationValidate,
        priceValidate,
        decodeQuery,
        removeQueryParams,
        encodeQuery,
        resetFields
    }
}
