export type user = {
    user: {
        ref: string,
        email: string,
        name: string,
        avatar: string | null
    },
    access_token: string
}