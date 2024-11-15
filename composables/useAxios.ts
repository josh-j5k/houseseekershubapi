import axios from "axios"
import type { LocationQuery } from "vue-router"

type method = 'get' | 'post' | 'put' | 'delete'
type endpoint = string
type query = undefined | any

type contentType = 'json' | 'multpartForm'
type handleRequestReturnValue = {
    error: boolean,
    status: number,
    data: any,

}

const xsrfUrl = import.meta.env.VITE_API_XSRF_URL as string
export default function useAxios() {
    const loading = ref(true)
    const btnLoading = ref(false)
    async function handleRequest(method: method, endpoint: endpoint, query: query = undefined, constentType: contentType = 'json') {
        const baseApiUrl = new URL(endpoint, import.meta.env.VITE_API_URL)

        btnLoading.value = true
        try {

            if (method == 'get') {
                if (query) {
                    for (const key in query) {
                        const value = query[key] as string;
                        baseApiUrl.searchParams.append(key, value)
                    }

                }
                const res = await axios.get(baseApiUrl.toString())
                return { error: false, data: res.data }
            }
            if (method == 'post') {
                await axios.get(xsrfUrl)
                const res = await axios.post(baseApiUrl.toString(), query, {
                    withCredentials: true,
                    withXSRFToken: true,
                    headers: {
                        Accept: constentType == 'json' ? 'application/json' : 'application/x-www-form-urlencoded',
                        "Content-Type": constentType == 'json' ? 'application/json' : 'multipart/form-data',
                    }
                })


                return { error: false, data: res.data }
            }
            if (method == 'put') {
                await axios.get(xsrfUrl)
                const res = await axios.put(baseApiUrl.toString(), query, {
                    withCredentials: true,
                    withXSRFToken: true,
                    headers: {
                        Accept: constentType == 'json' ? 'application/json' : 'application/x-www-form-urlencoded',
                        "Content-Type": constentType == 'json' ? 'application/json' : 'multipart/form-data',
                    }
                })
                return { error: false, data: res.data }
            }
            await axios.get(xsrfUrl)
            const res = await axios.delete(baseApiUrl.toString())
            return { error: false, data: res.data }
        } catch (error: any) {
            return { error: true, data: error.response.data }
        } finally {
            loading.value = false
            btnLoading.value = false
        }
    }
    return { handleRequest, loading, btnLoading }
}