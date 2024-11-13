import { type user } from "~/types/user";
function isAuthenticated(): boolean {
    if (useState('user').value) return true;
    return false
}

// ---cut---
export default defineNuxtRouteMiddleware((to, from) => {
    // isAuthenticated() is an example method verifying if a user is authenticated
    if (isAuthenticated()) {
        const user = useState('user').value as user
        return navigateTo({ name: 'au-id', params: { id: user.ref } })
    }
})

