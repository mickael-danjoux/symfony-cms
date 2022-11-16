const selectTypeElement = document.getElementById('category_type');

const blockSelectPageElement = document.getElementById('form-select-page');
const selectPageElement = document.getElementById('category_page');

const blockCustomLinkElement = document.getElementById('form-custom-link');
const inputUrlElement = document.getElementById('category_url');

const CATEGORY_ITEM = 'ITEM';
const CATEGORY_INTERNAL_LINK = 'INTERNAL_LINK';
const CATEGORY_CUSTOM_LINK = 'CUSTOM_LINK';

const dynamicFields = () => {

    // récupération de l'option sélectionnée au chargement de la page en mode edit
    const selectedOptionValue = document.querySelector('#category_type option[selected="selected"]').value
    selectedOptionValue !== CATEGORY_ITEM ? toggleClasses(selectedOptionValue) : '';

    selectTypeElement.addEventListener('change', (e) => {
        toggleClasses(e.target.value)
    })
}


/**
 * Correspondance VALEUR => LABEL :
 * ITEM => Element du menu = aucun champ n'est à afficher lorsque ce choix est sélectionné. On cache les autres blocks.
 * INTERNAL_LINK => Lien interne = affichage du select permettant de choisir une page. On cache le block #form-custom-link.
 * CUSTOM_LINK => Lien personnalisé = affichage du block contenant un input text et une checkbox. On cache le block #form-select-page.
 */
const toggleClasses = (categoryType) => {
    switch (categoryType) {
        case CATEGORY_ITEM:
            blockSelectPageElement.classList.add('d-none');
            blockCustomLinkElement.classList.add('d-none');
            toggleRequiredAttributes()
            break;
        case CATEGORY_INTERNAL_LINK:
            blockSelectPageElement.classList.remove('d-none');
            blockCustomLinkElement.classList.add('d-none');
            toggleRequiredAttributes('select')
            break;
        case CATEGORY_CUSTOM_LINK:
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
