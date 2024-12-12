
export default defineNuxtRouteMiddleware(async (to, from) => {
    if (to.path.endsWith('/') && to.path.length > 2) {
        return navigateTo({ path: to.matched[0].path, query: to.query }, { redirectCode: 301 })
    }

})


