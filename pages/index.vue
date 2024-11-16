<script setup lang="ts">
import type { Listings, Listing } from '~/types/listings';

const { handleRequest } = useAxios();
const loading = ref(false)
const listings = ref(<Listings>{})

const storeListings = useState('listings').value as Listings | undefined
storeListings !== undefined ? listings.value.data = storeListings.data.slice(0, 4) : ''
const options = {
    componentRestrictions: { country: "cm" },
    strictBounds: false,
};
// const loader = new Loader({
//     apiKey: "AIzaSyAj3t8m1tT8R9LuME3pcNedY9IK6aUjsu4",
//     version: "weekly",
//     libraries: ['places'],
// });
// const { usePlaces } = useGoogleMaps()
const input = ref('input')
const form = reactive({
    status: 'any',
    location: '',
    property_type: ''
})
const formError = ref(false)
function validate(): boolean {
    if (form.location.length === 0 || form.property_type.length === 0) {
        formError.value = true
        return false
    } else {
        return true
    }
}
function submit() {
    if (validate()) {
        console.log('validated')
    }
}
(async function () {
    if (storeListings == undefined) {
        loading.value = true
        const { data, error } = await handleRequest('get', 'listings?limit=4')
        if (!error) {
            listings.value.data = data.data.listings
            loading.value = false
            return
        }
        loading.value = false
    }
})()

onMounted(() => {
    const locationInput = <HTMLInputElement>document.getElementById('location')
    // usePlaces(locationInput, form.location)
    // loader.importLibrary('places').then(res => {

    //     const autocomplete = new res.Autocomplete(locationInput, options)

    //     autocomplete.addListener('place_changed', () => {
    //         const place = autocomplete.getPlace()
    //         form.location = place.formatted_address

    //     })

    // })

})


</script>
<template>

    <Head>
        <title>House, properties to rent and for sale</title>
    </Head>

    <section class="relative h-screen w-full isolate flex flex-col items-center justify-center px-2">
        <div
            class="w-full h-screen absolute inset-0 -z-10 before:content-emptystring before:absolute before:inset-0 before:bg-black-opacity-50 before:w-full before:h-full">
            <img src="/Images/house-with-balcony-sky-background.jpg" alt="background-img"
                class="w-full h-screen object-cover">
        </div>
        <h2 class="md:text-5xl text-4xl text-white font-bold max-w-2xl text-center mb-12">Lets find a home that is
            perfect for you
        </h2>

        <form @submit.prevent="submit">

            <div class="text-white flex justify-center gap-4">
                <label tabindex="0" for="any-status"
                    class="w-24 h-10 focus:outline-accent flex cursor-pointer items-center justify-center rounded-tl-md rounded-tr-md "
                    :class="form.status === 'any' ? 'bg-white text-black border-t-2 border-accent' : 'bg-secondary text-white'">
                    Any Status
                </label>
                <input class="hidden" type="radio" value="any" v-model="form.status" name="any-status" id="any-status">
                <label tabindex="0" for="rent"
                    class="w-24 h-10 focus:outline-accent cursor-pointer flex items-center justify-center rounded-tl-md rounded-tr-md"
                    :class="form.status === 'rent' ? 'bg-white text-black border-t-2 border-accent' : 'bg-secondary text-white'">
                    Rent
                </label>
                <input class="hidden" type="radio" value="rent" v-model="form.status" name="rent" id="rent">
                <label tabindex="0" for="sale"
                    class="w-24 h-10 cursor-pointer focus:outline-accent flex items-center justify-center rounded-tl-md rounded-tr-md "
                    :class="form.status === 'sale' ? 'bg-white text-black border-t-2 border-accent' : 'bg-secondary text-white'">
                    Sale
                </label>
                <input class="hidden" type="radio" value="sale" v-model="form.status" name="sale" id="sale">
            </div>
            <!-- Form Inputs -->
            <div
                class="md:w-[672px] w-[90vw] md:h-20 bg-white rounded-lg flex flex-col md:flex-row md:gap-4 items-center md:pl-8 overflow-hidden ">
                <div class="flex flex-col md:min-w-[240px] w-full py-4 -md:px-8">
                    <label for="location" class="font-bold">
                        Location
                    </label>
                    <div class="flex items-center gap-4 w-full relative">
                        <input required v-model="form.location" type="text" placeholder="Type your town, region"
                            id="location" class="input">
                        <span class="absolute right-2"><i class="fas fa-map"></i></span>
                    </div>
                </div>
                <div class="md:w-[1px] w-full md:h-14 h-[1px] bg-slate-300">&nbsp;</div>
                <div class="flex flex-col md:min-w-[240px] w-full py-4 -md:px-8">
                    <label for="property-type" class="font-bold ">
                        Property Type
                    </label>
                    <div class="flex items-center gap-4 w-full relative">
                        <select required v-model="form.property_type" id="property-type" class="input appearance-none">
                            <option value="" disabled>Please select an option</option>
                            <option value="room">
                                Room
                            </option>
                            <option value="studio">
                                Studio
                            </option>
                            <option value="appartment">
                                Appartment
                            </option>
                            <option value="duplex">
                                Duplex
                            </option>
                        </select>
                        <span class="absolute right-2"><i class="fas fa-caret-down"></i></span>
                    </div>
                </div>
                <button title="search" type="submit"
                    class="bg-accent hover:bg-accent-hover md:h-full h-16 w-full  text-white">
                    <span><i class="fas fa-search fa-xl"></i></span>
                </button>
            </div>
            <p v-if="formError" class="text-red-500 capitalize text-center font-bold">please fill all fields</p>
        </form>
    </section>
    <section class="py-8 w-full lg:min-h-screen grid">
        <div class="grid lg:grid-cols-2 grid-cols w-5/6 gap-4 mx-auto items-center">
            <div>
                <h2 class="font-bold text-3xl mb-6">When are you looking to market your property?</h2>
                <p>
                    The simple, free, no-obligation way to request an accurate
                    valuation of your home from estate and letting agents who are
                    experts in your local area. Watch our TV advert now.
                </p>
                <button title="start valuation" type="button"
                    class="capitalize px-4 py-3  bg-accent hover:bg-accent-hover text-white mt-6">
                    start
                    instant
                    valuation</button>
            </div>
            <div class="-lg:row-start-1 -lg:row-end-1 -lg:pt-16">
                <img src="/Images/high-view-hands-stationery-items.jpg" alt="house valuation" class="">
            </div>
        </div>
    </section>
    <section class="min-h-32 bg-secondary py-12 mb-8 px-8 grid items-center text-white">
        <div class="flex md:justify-between -md:flex-col -md:gap-6 w-5/6 mx-auto">
            <h2 class="md:text-3xl text-2xl font-bold text-center">
                Your property search just got easier
            </h2>
            <button type="button" class="border border-white capitalize p-2"> find a house</button>
        </div>
    </section>
    <section class="p-8 pt-12 ">
        <div class="md:w-5/6 w-full mx-auto md:bg-blue-100 rounded-lg">
            <div class="flex md:justify-between -md:flex-col  md:px-16 py-8">
                <h3 class="md:text-2xl text-xl font-bold text-center">
                    Houses near you
                </h3>
                <NuxtLink to="/listings" class="text-accent flex justify-center mt-3">
                    <span class="capitalize mr-2">
                        show more houses
                    </span>
                    <span>
                        <i class="fa-regular fa-circle-right"></i>
                    </span>
                </NuxtLink>
            </div>
            <div class="px-8 grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-3 md:translate-y-[5%]">
                <template v-if="storeListings == undefined ? loading : false">
                    <template v-for="(_, index) in 4">
                        <Card class="bg-white relative">
                            <div class="flex flex-col items-center gap-3">
                                <Skeleton class="aspect-square w-full" />
                                <Skeleton class=" h-8 w-full" />
                                <Skeleton class=" h-4 w-3/4" />
                                <Skeleton class=" h-8 w-full" />
                                <hr class="w-full h-[1px] bg-slate-100 mb-3">

                                <Skeleton class=" h-6 w-full" />

                            </div>
                        </Card>
                    </template>
                </template>
                <template v-else>
                    <template v-for="(listing, index) in listings.data">
                        <Card class="bg-white relative">
                            <div>
                                <img lazy v-if="listing.images?.length > 0" :src="listing.images[0]" alt="listing image"
                                    class="w-full aspect-square">
                                <img v-else src="/Images/no_image_placeholder.jpg" alt="">
                            </div>
                            <div class="p-4">
                                <p class="font-bold flex gap-1 mb-3 text-sm text-accent">



                                    <span v-if="listing.propertyStatus === 'rent'">
                                        <span>{{ listing.price.toLocaleString('en-US', {
                                            style: 'currency',
                                            currency: 'XAF'
                                        }) }}</span>/Month
                                    </span>
                                    <span v-else>
                                        {{ listing.price.toLocaleString('en-US', {
                                            style: 'currency',
                                            currency: 'XAF'
                                        }) }}
                                    </span>

                                </p>
                                <div>
                                    <p class="font-bold mb-1">
                                        {{ listing.title.slice(0, 35) }}
                                    </p>


                                </div>
                                <p class="text-sm opacity-75 mb-3 capitalize">
                                    {{ listing.propertyType }}
                                </p>
                                <hr class="w-full h-[1px] bg-slate-100 mb-3">
                                <div class="flex gap-2 text-sm">
                                    <span>
                                        <i class="fas fa-location-dot text-accent"></i>
                                    </span>
                                    <p>{{ listing.location.slice(0, 40) }}</p>
                                </div>

                            </div>
                            <span
                                class="capitalize rounded py-1 px-2 absolute top-3 left-3 text-white text-sm cursor-default"
                                :class="[listing.propertyStatus === 'rent' ? 'bg-green-500' : 'bg-orange-500']">
                                for {{ listing.propertyStatus }}
                            </span>
                        </Card>
                    </template>
                </template>
            </div>
        </div>
    </section>
    <section class="lg:h-[90vh] p-12 mt-16">
        <div class="grid lg:grid-cols-[40%_60%] grid-cols-1 items-center justify-center lg:w-5/6 mx-auto gap-8">
            <div>

                <h2 class="lg:text-3xl text-2xl font-bold mb-6">
                    Your property search just got easier
                </h2>
                <p class="mb-4">
                    We feature thousands of new properties every month, 24 hours or more before they’re advertised
                    on
                    Rightmove or Zoopla. Find out more about Only With Us properties here.
                </p>
                <NuxtLink href="#" class="text-accent ">
                    <span class="capitalize">
                        start your search
                    </span>
                    <span>
                        <i class="fa-regular fa-circle-right"></i>
                    </span>
                </NuxtLink>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 lg:grid-rows-2 lg:gap-2 gap-6">
                <div class="w-full relative">
                    <img src="/Images/355280246_2871413009661347_7662648592867538430_n.jpg" alt="house interior"
                        class="lg:max-w-[250px] w-full aspect-square object-cover rounded-lg">
                    <span class="bg-secondary text-white py-1 px-2.5 rounded absolute top-12 -left-8">
                        04:70:00
                    </span>
                </div>
                <div class=" w-full lg:row-start-1 lg:row-end-3 lg:col-start-2 lg:col-end-3">
                    <div class="grid items-end h-full w-full relative">
                        <img src="/Images/385808252_849018929958297_1588544965478260459_n.jpg" alt="house exterior"
                            class="lg:max-w-[250px] w-full aspect-square object-cover rounded-lg">
                        <span
                            class="bg-secondary text-white py-1 px-2.5 rounded absolute lg:bottom-1/3 -lg:top-12 -left-8">
                            15:30:90
                        </span>
                    </div>
                </div>
                <div
                    class="w-full relative tablet:col-start-1 tablet:col-end-3 tablet:justify-center tablet:w-1/2 tablet:aspect-square tablet:mx-auto">
                    <img src="/Images/385909430_1003122750912505_3112270827831116516_n.jpg" alt="house interior"
                        class="lg:max-w-[250px] w-full aspect-square object-cover rounded-lg">
                    <span class="bg-secondary text-white py-1 px-2.5 rounded absolute top-12 -left-8">
                        22:10:20
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="lg:min-h-screen p-12 bg-slate-100">
        <div class="md:w-5/6 mx-auto grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 ">
            <Card class="p-8 w-full bg-white">
                <div class="flex gap-4">
                    <span>
                        <i class="fa-solid fa-hand-holding-hand text-3xl"></i>
                    </span>
                    <h3 class="mb-6 text-2xl font-bold">
                        Help me choose
                    </h3>
                </div>
                <p>
                    We can help you find your dream home by guiding you through a few simple steps and matching you
                    with
                    tailor-made property listings.
                </p>
            </Card>
            <Card class="p-8 w-full bg-blue-900 text-white">
                <div class="flex gap-4 ">
                    <span>
                        <i class="fa-regular fa-heart text-3xl"></i>
                    </span>
                    <h3 class="mb-6 text-2xl font-bold">
                        Introducing wish list
                    </h3>
                </div>
                <p>
                    What are the things you’re really looking for in your next property? Create a Wish List of
                    features,
                    from deal-breakers to nice-to-haves, then we’ll sort your property search results accordingly.
                    It’s
                    that simple.
                </p>
            </Card>
            <Card class="p-8 w-full bg-secondary text-white">
                <div class="flex gap-4">
                    <span>
                        <i class="fa-regular fa-clock text-3xl"></i>
                    </span>
                    <h3 class="mb-6 text-2xl font-bold">
                        Travel Time Search
                    </h3>
                </div>
                <p>
                    Need to find a property near your workplace, children’s school or train station? We’ll help you
                    find
                    your dream property in the right location.
                </p>
            </Card>
            <Card class="p-8 w-full bg-slate-900 text-white">
                <div class="flex gap-4">
                    <span>
                        <i class="fa-solid fa-bolt text-3xl"></i>
                    </span>
                    <h3 class="mb-6 text-2xl font-bold">
                        Instant Valuation
                    </h3>
                </div>
                <p>
                    Our online valuation service gives you a free and instant estimate of your home’s current value
                    in
                    minutes.
                </p>
            </Card>

        </div>
    </section>

</template>

<style scoped>
select::-ms-expand {
    display: none;
}

#property-type {
    background-image: none;
}
</style>
