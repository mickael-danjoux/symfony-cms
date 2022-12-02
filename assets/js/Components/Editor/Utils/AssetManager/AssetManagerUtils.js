import { Toast } from "../../../Toast";

export const handleAssetRemove = () => {
    const btnDelete = document.querySelectorAll("[data-toggle='asset-remove']");

    btnDelete.forEach(el => el.addEventListener('click', (e) => {
        console.log(btnDelete)
        // récupération des images utilisées dans le canvas
        const imagesElements = document.querySelector("iframe").contentDocument.body.querySelectorAll("[data-gjs-type='image']")

        const urlImagesRendered = []
        imagesElements.forEach(img => {
            console.log(img.attributes.src.value)
            if (img.attributes.src.value.startsWith('/uploads/images')) {
                urlImagesRendered.push(img.attributes.src.value)
            }
        })

        const childs = e.target.parentElement.childNodes

        if (urlImagesRendered.includes('/uploads/images/' + childs[3].firstElementChild.innerText)) {
            e.stopPropagation()
            Toast.warning('Cette image est actuellement utilisée dans la page, impossible de la supprimer.')
        }

    }))
}