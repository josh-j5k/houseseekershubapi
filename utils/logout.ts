export default async function () {
    const { handleRequest } = useBackend()
    const { error, data } = await handleRequest('post', '/logout')

    if (!error) {
        if (useRoute().meta.middleware !== undefined) {
            navigateTo('/login')
        }
        localStorage.removeItem('user')
        clearNuxtState()
        toastNotification("Success", "Logout Successful")
    } else {
        toastNotification("Error", data.message)
    }
}