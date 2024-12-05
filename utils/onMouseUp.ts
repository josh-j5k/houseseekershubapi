export default function (e: MouseEvent) {
    const button = e.currentTarget as HTMLButtonElement
    button.animate([{ transform: 'scale(0.85)' }, { transform: 'scale(1)' }], {
        duration: 50,
        easing: 'ease',
        iterations: 1,
        fill: 'forwards'

    })
}