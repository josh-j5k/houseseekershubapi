export default async function () {
    const { handleRequest } = useAxios()
    const { error } = await handleRequest('post', '/logout')

    const routes = ['index', 'listings', 'contact', 'about']
    if (!error) {
        navigateTo('/')
    } else {
        console.log(error);

    }
}