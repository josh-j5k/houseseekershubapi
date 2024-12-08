export default async function () {
    const { handleRequest } = useBackend()

    const { data, error } = await handleRequest('post', '/logout')

    if (!error) {
        if (useRoute().meta.middleware !== undefined) {
            navigateTo('/login')
        }
        localStorage.removeItem('user')
        clearNuxtState(['user', 'listings', 'userListings'])
        toastNotification("Success", "Logout Successful")
    } else {
        toastNotification("Error", data.message)
    }
}