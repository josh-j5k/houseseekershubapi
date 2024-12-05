export default function (e: MouseEvent) {
    const button = e.currentTarget as HTMLButtonElement
    button.animate([{ transform: 'scale(1)' }, { transform: 'scale(0.85)' }], {
        duration: 50,
        easing: 'ease',
        iterations: 1,
        fill: 'forwards'

    })
}