type typeOfMessage = 'Success' | 'Error'

export default function (typeOfMessage: typeOfMessage, message: string) {
    const timeout = 5000;
    const div = document.createElement("div");
    const progressBar = document.createElement("div");
    const p = document.createElement("p");
    div.className = "toast py-2 px-4 shadow-lg bg-gray-100 text-slate-700";
    p.textContent = message;
    div.append(p, progressBar);
    document.documentElement.appendChild(div);
    if (typeOfMessage === "Success") {
        div.classList.add("toast_success");
    } else {
        div.classList.add("toast_error");
    }
    div.animate(
        [
            { transform: "translateX(100%)" },
            { transform: "translateX(0)", easing: "ease-in-out" },
        ],
        {
            duration: 500,
            iterations: 1,
        }
    );
    progressBar.animate([{ width: "100%" }, { width: 0 }], {
        duration: timeout + 1,
        iterations: 1,
    });
    setTimeout(() => div.remove(), timeout);

}
