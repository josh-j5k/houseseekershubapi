export default defineEventHandler((event) => {
    const param = getRouterParam(event, 'listing')
    const config = useRuntimeConfig()
    // if (param.typeof 'string') {
    //     throw createError({
    //         statusCode: 400,
    //         statusMessage: 'ID should be a string',
    //     })
    // }
    const url = new URL('/api/listings/'.concat(param?.toString()!), config.apiUrl)
    const data = fetch(url)

    return data
})