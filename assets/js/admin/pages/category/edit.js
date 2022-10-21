const selectTypeElement = document.getElementById('category_type');

const blockSelectPageElement = document.getElementById('form-select-page');
const selectPageElement = document.getElementById('category_page');

const blockCustomLinkElement = document.getElementById('form-custom-link');
const inputUrlElement = document.getElementById('category_url');

const dynamicFields = () => {

    // récupération de l'option sélectionnée au chargement de la page en mode edit
    const selectedOptionValue = document.querySelector('#category_type option[selected="selected"]').value
    selectedOptionValue !== 0 ? toggleClasses(selectedOptionValue) : '';

    selectTypeElement.addEventListener('change', (e) => {
        toggleClasses(+e.target.value)
    })
}


/**
 * La valeur récupérée correspond à l'index de l'élément dans le tableau de choix
 * donc la valeur récupérée est nombre entier
 * Correspondance VALEUR => LABEL :
 * O => Element du menu = aucun champ n'est à afficher lorsque ce choix est sélectionné. On cache les autres blocks.
 * 1 => Lien interne = affichage du select permettant de choisir une page. On cache le block #form-custom-link.
 * 2 => Lien personnalisé = affichage du block contenant un input text et une checkbox. On cache le block #form-select-page.
 */
const toggleClasses = (index) => {
    switch (+index) {
        case 0:
            blockSelectPageElement.classList.add('d-none');
            blockCustomLinkElement.classList.add('d-none');
            toggleRequiredAttributes()
            break;
        case 1:
            blockSelectPageElement.classList.remove('d-none');
            blockCustomLinkElement.classList.add('d-none');
            toggleRequiredAttributes('select')
            break;
        case 2:
            blockCustomLinkElement.classList.remove('d-none');
            blockSelectPageElement.classList.add('d-none');
            toggleRequiredAttributes('input-checkbox')
            break;
        default:
            return;
    }
}

const toggleRequiredAttributes = (field) => {
    switch (field) {
        case 'select':
            selectPageElement.setAttribute('required', 'required');
            inputUrlElement.removeAttribute('required');
            break;
        case 'input-checkbox':
            inputUrlElement.setAttribute('required', 'required');
            selectPageElement.removeAttribute('required');
            break;
        default:
            inputUrlElement.removeAttribute('required');
            selectPageElement.removeAttribute('required');
    }


}

selectTypeElement ? dynamicFields() : '';
