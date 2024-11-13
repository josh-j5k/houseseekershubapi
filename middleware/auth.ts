function isAuthenticated(): boolean {
    if (useState('user').value) return true;
    return false
}
// ---cut---
export default defineNuxtRouteMiddleware((to, from) => {
    // isAuthenticated() is an example method verifying if a user is authenticated
    if (isAuthenticated() === false) {
        return navigateTo('/login')
    }
})


