
export function useFileUpload() {
    const filesArr = ref(<File[]>[])
    const imgSrc = ref(<string[]>[])
    const total = ref(0)
    function updateFilesDisplayImages(fileInput: HTMLInputElement) {
        const newDt = new DataTransfer()
        filesArr.value.forEach(file => newDt.items.add(file))
        fileInput.files = newDt.files
        total.value = fileInput.files.length

    }
    function assignFiles(fileInput: HTMLInputElement) {
        fileInput.addEventListener('change', (ev: Event) => {
            const inputTarget = ev.target as HTMLInputElement
            if (inputTarget.files !== null) {
                for (let index = 0; index < inputTarget.files.length; index++) {
                    const file = inputTarget.files[index] as File
                    if (!file.type.startsWith("image/")) {
                        return
                    }

                    handleFiles(file)
                    filesArr.value = [...filesArr.value, file]
                }
            }
            updateFilesDisplayImages(fileInput)
        })
    }

    function dragenter(e: MouseEvent) {
        e.stopPropagation();
        e.preventDefault();
    }

    function dragover(e: MouseEvent) {
        e.stopPropagation();
        e.preventDefault();
    }
    function drop(e: any, fileInput: HTMLInputElement) {
        e.stopPropagation();
        e.preventDefault();

        const dt = e.dataTransfer;

        for (let index = 0; index < dt.files.length; index++) {
            const file = dt.files[index] as File;
            if (!file.type.startsWith("image/")) {
                return
            }
            handleFiles(file)
            filesArr.value = [...filesArr.value, file];
        }
        updateFilesDisplayImages(fileInput)
    }

    function deleteFile(fileInput: HTMLInputElement, btnIndex: number) {
        const dt = new DataTransfer()
        filesArr.value.splice(btnIndex, 1)
        imgSrc.value.splice(btnIndex, 1)
        filesArr.value.forEach(file => dt.items.add(file))
        fileInput.files = dt.files
        total.value = fileInput.files.length

    }
    function handleFiles(file: File) {
        const src = ref('')
        const reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onload = (e: any) => {
            src.value = e.target.result;
            imgSrc.value = [...imgSrc.value, src.value]

        };
    }

    return {
        drop, dragenter, dragover, deleteFile, assignFiles, total, imgSrc, filesArr,
    }


}