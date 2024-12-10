export default async function () {
    const config = useRuntimeConfig()
    const url = new URL('/sanctum/csrf-cookie', config.public.apiUrl)
    await fetch(url.toString(), {
        credentials: 'include'
    })
}