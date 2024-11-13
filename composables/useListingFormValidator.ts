export function useListingFormValidator() {
    const formErrors = ref(
        {
            fileError: false,
            titleError: false,
            priceError: false,
            locationError: false,
            descriptionError: false,
            propertyStatusError: false,
            propertyTypeError: false
        }
    )
    const valid =
    {
        title: false,
        file: false,
        price: false,
        location: false,
        description: false,
        property_status: false,
        property_type: false
    }

    function validation(title: string, description: string, property_type: string, price: string | number, property_status: string, location: string, total: number): boolean {
        const strPrice = price.toString()
        if (title === '') {
            formErrors.value.titleError = true
        } else {
            valid.title = true
        }
        if (description === '') {
            formErrors.value.descriptionError = true
        } else {
            valid.description = true
        }
        if (property_type === '') {
            formErrors.value.propertyTypeError = true
        } else {
            valid.property_type = true
        }
        if (location === '') {
            formErrors.value.locationError = true
        } else {
            valid.location = true
        }
        if (property_status === '') {
            formErrors.value.propertyStatusError = true
        } else {
            valid.property_status = true
        }
        if (isNaN(parseInt(strPrice))) {

            formErrors.value.priceError = true

        } else {
            valid.price = true
        }
        if (total === 0) {
            formErrors.value.fileError = true
        } else {
            valid.file = true
        }


        if (valid.description && valid.location && valid.price && valid.title && valid.property_type && valid.property_status && valid.file) {
            return true
        } else {
            setTimeout(() => {
                formErrors.value.descriptionError = false
                formErrors.value.fileError = false
                formErrors.value.locationError = false
                formErrors.value.priceError = false
                formErrors.value.titleError = false
                formErrors.value.propertyTypeError = false
                formErrors.value.propertyStatusError = false
            }, 5000)
            return false
        }

    }
    return { formErrors, validation }
}