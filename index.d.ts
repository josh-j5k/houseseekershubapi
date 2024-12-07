declare module 'nuxt/schema' {
    interface RuntimeConfig {
        apiUrl: string,
        frontendUrl: string,
        googleClientId: string,
        googleClientSecret: string,
        googleOauth2Endpoint: string,
        googleOauth2Token: string,
        googleOauth2CallbackUrl: string,
        googlePlaces: string,
        googlePlacesApiKey: string
    }
    interface PublicRuntimeConfig {
    }
}
// It is always important to ensure you import/export something when augmenting a type
export { }