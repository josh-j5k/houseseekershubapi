import type { user } from "~/types/user"

function isAuthenticated(user: user | undefined): boolean {
    if (user) return true
    return false
}
// ---cut---
export default defineNuxtRouteMiddleware(async (to, from) => {


    if (import.meta.client) {
        const authUser = <Ref<user | undefined>>useState('user')
        const user = localStorage.getItem('user')
        if (user) {
            authUser.value = JSON.parse(user)
        }

        if (to.name?.toString().includes('au-id') && isAuthenticated(authUser.value) === false) {
            return navigateTo('/login')
        }
        if ((to.name == 'login' || to.name == 'register') && isAuthenticated(authUser.value)) {
            return navigateTo({ name: 'au-id', params: { id: authUser.value?.user.ref } })
        }
    }
})


