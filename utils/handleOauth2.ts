export default function () {
    const config = useRuntimeConfig()
    const array = new Uint8Array(8)
    crypto.getRandomValues(array)
    const security_token = "security_token ".concat(array.join(" ").concat(" login"))
    localStorage.setItem('state', security_token)
    const url = new URL(config.public.googleOauth2Endpoint)
    const params = {
        response_type: 'code',
        client_id: config.public.googleClientId,
        scope: "openid email profile",
        redirect_uri: config.public.frontendUrl.concat('/login'),
        state: security_token,
        access_type: 'offline'
    }

    for (const [key, value] of Object.entries(params)) {
        url.searchParams.append(key, value)
    }
    open(url)
}