import { type user } from "~/types/user";
function isAuthenticated(): boolean {


    const user = localStorage.getItem('user')
    if (user) {
        useState('user', () => JSON.parse(user))
        return true
    }
    return false

}

// ---cut---
export default defineNuxtRouteMiddleware((to, from) => {
    if (import.meta.server) return
    if (isAuthenticated()) {
        const user = useState('user').value as user
        return navigateTo({ name: 'au-id', params: { id: user.user.ref } })
    }
})

