export default function (value: string) {

    return value.trim().split(' ').map(item => item.charAt(0).toUpperCase().concat(item.substring(1))).join(' ')
}