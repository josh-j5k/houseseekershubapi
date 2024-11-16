import type { user } from "~/types/user";

function isAuthenticated(): boolean {


    const user = localStorage.getItem('user')
    if (user) {
        useState('user', () => JSON.parse(user))
        return true
    }
    return false

    // const user = localStorage.getItem('user')


}
// ---cut---
export default defineNuxtRouteMiddleware((to, from) => {

    if (import.meta.server) return
    // isAuthenticated() is an example method verifying if a user is authenticated
    if (isAuthenticated() === false) {
        return navigateTo('/login')
    }


})


