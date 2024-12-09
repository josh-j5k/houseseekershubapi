export default defineEventHandler(async (event) => {
    const param = getRouterParam(event, 'listing')
    const config = useRuntimeConfig()

    const url = new URL('/api/listings/'.concat(param?.toString()!), config.public.apiUrl)
    const data = await $fetch(url.toString())


    return data
})