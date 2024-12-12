export type user = {
    user: {
        ref: string,
        email: string,
        name: string,
        picture: string | null
    },
    access_token: string
}