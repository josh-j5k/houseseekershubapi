import axios from "axios"

import type { user } from "~/types/user"

type method = 'get' | 'post' | 'put' | 'delete'
type endpoint = string
type query = undefined | any
type contentType = 'json' | 'multpartForm'


const base = import.meta.env.VITE_API_URL

export default function useBackend() {
    const loading = ref(true)
    const btnLoading = ref(false)
    const headers = <{ [x: string]: string }>{
        Accept: 'application/json'
    }
    const user = ref(<user | undefined>undefined)
    if (import.meta.client) {
        const item = localStorage.getItem('user')
        item ? user.value = JSON.parse(item) : ''
    }

    async function handleRequest(method: method, endpoint: endpoint, query: query = undefined, contentType: contentType = 'json') {

        btnLoading.value = true
        const url = new URL("/api" + endpoint, base)

        if (contentType == 'json') {

            headers['Content-Type'] = 'application/json'
        } else {
            // headers['Accept'] = 'application/x-www-form-urlencoded'
            headers['Content-Type'] = 'multipart/form-data'
        }

        const options = <{
            headers: { [x: string]: string },
            withCredentials: boolean | undefined
            withXSRFToken: boolean | undefined
        }>{
                headers
            }

        if (user.value) {
            headers['Authorization'] = "Bearer " + user.value.access_token
        }


        if (method == 'get' && query) {
            for (const key in query) {
                const value = query[key] as string;
                url.searchParams.append(key, value)
            }
        } else {

        }
        try {
            let res = null
            if (method !== 'get') {
                options.withCredentials = true,
                    options.withXSRFToken = true
                await getToken()
            }
            switch (method) {
                case 'get':
                    res = await axios.get(url.toString(), options)
                    return { error: false, data: res.data }
                case 'post':
                    res = await axios.post(url.toString(), query, options)
                    return { error: false, data: res.data }
                case 'put':
                    res = await axios.put(url.toString(), query, options)
                    return { error: false, data: res.data }
                default:
                    res = await axios.delete(url.toString(), options)
                    return { error: false, data: res.data }
            }

        } catch (error: any) {
            return { error: true, data: error.response.data }
        } finally {
            loading.value = false
            btnLoading.value = false
        }
    }
    return { handleRequest, loading, btnLoading }
}