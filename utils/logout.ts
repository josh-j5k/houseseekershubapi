export default async function () {
    const { handleRequest } = useBackend()
    const { error, data } = await handleRequest('post', '/logout')

    if (!error) {
        if (useRoute().meta.middleware !== undefined) {
            navigateTo('/login')
        } else {

            location.reload()
        }
        localStorage.removeItem('user')

        clearNuxtState(['user', 'listings', "userListings"])

        toastNotification("Success", "Logout Successful")
    } else {
        console.log(data);
        toastNotification("Error", data.message)
    }
}