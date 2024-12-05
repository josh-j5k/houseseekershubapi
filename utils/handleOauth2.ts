export default function () {
    const array = new Uint8Array(8)
    crypto.getRandomValues(array)
    const security_token = "security_token ".concat(array.join(" ").concat(" login"))
    localStorage.setItem('state', security_token)
    const url = new URL(import.meta.env.VITE_GOOGLE_OAUTH2_ENDPOINT)
    const params = {
        response_type: 'code',
        client_id: import.meta.env.VITE_GOOGLE_CLIENT_ID,
        scope: "openid email profile",
        redirect_uri: import.meta.env.VITE_GOOGLE_OAUTH2_CALLBACK_URL,
        state: security_token,
        access_type: 'offline'
    }

    for (const [key, value] of Object.entries(params)) {
        url.searchParams.append(key, value)
    }
    open(url)
}