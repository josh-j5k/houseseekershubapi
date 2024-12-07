
export default defineEventHandler((event) => {
    const config = useRuntimeConfig()
    const query = getQuery(event)
    const url = new URL('/api/listings', config.apiUrl)
    if (query.limit == null || query.limit == undefined) {
        url.searchParams.append('limit', '12')
    }
    if (Object.keys(query).length > 0) {
        for (const [key, value] of Object.entries(query)) {
            url.searchParams.append(key, value?.toString()!)
        }
    }
    const data = fetch(url)
    return data
})