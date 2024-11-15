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



async function submit(name: string, value: LocationQueryValue | LocationQueryValue[]) {
    const encoded = encodeURIComponent(value?.toString()!)
    const route = useRoute()
    if (Object.keys(route.query).length == 0) {

        await navigateTo({
            path: '/listings',
            query: { [name]: encoded }
        })
        return
    }
    const newQuery = { ...route.query }

    newQuery[name] = encoded
    await navigateTo({
        path: '/listings',
        query: newQuery
    })
}
export function useListingFilter() {

    function setDefaultValues() {
        if (Object.keys(useRoute().query).length == 0) {
            return
        }


        for (const key in useRoute().query) {
            const value = useRoute().query[key]
            const decoded = decodeURIComponent(value?.toString()!)
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

        let property = ''
        propertyType.value.forEach((item, index) => {
            if (index === 0) {
                property = item
                submit('property_type', property)
            } else {
                property += '|'.concat(item)
                submit('property_type', property)
            }

        })
    }

    return {

        location,
        errors: readonly(errors),
        price, status, propertyType,
        statusSubmit,
        priceSubmit, propertySubmit,
        locationSubmit,
        setDefaultValues

    }
}
