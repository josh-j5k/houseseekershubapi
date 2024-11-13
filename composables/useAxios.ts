import axios from "axios"

type method = 'get' | 'post' | 'put' | 'delete'
type endpoint = string
type data = object | null
type contentType = 'json' | 'multpartForm'
type handleRequestReturnValue = {
    error: boolean,
    status: number,
    data: any
}
const baseApiUrl = import.meta.env.VITE_API_URL as string
const xsrfUrl = import.meta.env.VITE_API_XSRF_URL as string
export default function useAxios() {
    const loading = ref(false)
    async function handleRequest(method: method, endpoint: endpoint, data: data = null, constentType: contentType = 'json') {
        loading.value = true
        try {

            if (method == 'get') {
                const res = await axios.get(baseApiUrl.concat(endpoint))
                return { error: false, data: res.data }
            }
            if (method == 'post') {
                await axios.get(xsrfUrl)
                const res = await axios.post(baseApiUrl.concat(endpoint), data, {
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
                const res = await axios.put(baseApiUrl.concat(endpoint), data, {
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
            const res = await axios.delete(baseApiUrl.concat(endpoint))
            return { error: false, data: res.data }
        } catch (error: any) {
            return { error: true, data: error.response.data }
        } finally {
            loading.value = false
        }
    }
    return { handleRequest, loading }
}