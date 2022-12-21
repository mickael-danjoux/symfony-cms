import { createApp, ref, onMounted } from "vue";
import slugger from 'slugger'
import Editor from '../../Components/Editor/Editor'
import initAdmin from "../../Utils/InitAdmin";

createApp({
    compilerOptions: {
        delimiters: ["${", "}"]
    },
    components: { Editor },
    setup() {
        const formHasChanged = ref(false)
        const showEditor = ref(true)
        onMounted(() => {
            initAdmin()
            handleFormChange()
            onSelectPageTypeChange()
            manageUrlField()
        })
        const manageUrlField = () => {
            const titleField = document.getElementById('page_title')
            const pathField = document.getElementById('page_path')
            const pathPreview = document.getElementById('path-preview')
            const customPathField = document.getElementById('page_customPath')
            const editPathButton = document.getElementById('editPath')
            const reloadPathButton = document.getElementById('reloadPath')

            if (customPathField.checked) {
                pathField.classList.remove('d-none')
                pathPreview.classList.add('d-none')
                editPathButton.classList.add('d-none')
                reloadPathButton.classList.remove('d-none')
            }
            titleField.addEventListener('change', (event) => {
                if (!customPathField.checked) {
                    generateSlug()
                }
            })

            editPathButton.addEventListener('click', () => {
                pathField.classList.remove('d-none')
                pathPreview.classList.add('d-none')
                customPathField.checked = true
            })
            reloadPathButton.addEventListener('click', () => {
                reloadPathButton.classList.add('d-none')
                pathField.classList.add('d-none')
                editPathButton.classList.remove('d-none')
                pathPreview.classList.remove('d-none')
                customPathField.checked = false
                generateSlug()
            })
            function generateSlug(){
                const slug = slugger(titleField.value)
                pathField.value = slug
                pathPreview.innerText = slug
            }
        }

        const handleFormChange = () => {
            document.querySelectorAll('.js-change').forEach((el) => {
                el.addEventListener('change', () => {
                    disabledPreview()
                })
            })
        }
        const updateContent = () => {
            disabledPreview()
        }

        const disabledPreview = () => {
            formHasChanged.value = true
            document.querySelectorAll('.js-disabled-on-change').forEach((el) => {
                el.classList.add('disabled')
            })
        }

        const preview = () => {
            if (!formHasChanged.value) {
                storeEditorContent()
                const path = document.getElementById('page_path').value
                window.open('/' + path + '?preview=true', '_blank').focus();
            }
        }

        const onSelectPageTypeChange = () => {
            const selectElement = document.getElementById('page_type');
            const INTERNAL_PAGE = 'INTERNAL_PAGE';
            const CUSTOM_PAGE = 'CUSTOM_PAGE';

            // si l'élément est défini, alors l'utilisateur est un SA
            if (selectElement) {
                const blockInputControllerElement = document.getElementById('form-page-controller');
                const blockInputRouteElement = document.getElementById('form-page-route');
                const inputControllerElement = document.getElementById('page_controller');
                const pageTypeValue = document.getElementById('page_type').value;

                // sert uniquement au chargement de la page
                if (pageTypeValue === INTERNAL_PAGE) {
                    blockInputControllerElement.classList.remove('d-none')
                    blockInputRouteElement.classList.remove('d-none')
                    inputControllerElement.setAttribute('required', 'required')
                    showEditor.value = false
                }

                selectElement.addEventListener('change', (e) => {

                    const selectedValue = e.target.value

                    if (selectedValue === INTERNAL_PAGE) {
                        blockInputControllerElement.classList.remove('d-none')
                        blockInputRouteElement.classList.remove('d-none')
                        inputControllerElement.value = ''
                        inputControllerElement.setAttribute('required', 'required')
                        showEditor.value = false

                    } else if (selectedValue === CUSTOM_PAGE) {
                        blockInputControllerElement.classList.add('d-none')
                        blockInputRouteElement.classList.add('d-none')
                        inputControllerElement.removeAttribute('required')
                        inputControllerElement.value = ''
                        showEditor.value = true
                    }

                })
            }
            // élément non défini (=user role ADMIN)
            else {
                // l'élément #pageType est un input:hidden pour récupérer le type de la page
                // sert uniquement au chargement de la page
                const pageTypeValue = document.getElementById('pageType').value;
                if (pageTypeValue === INTERNAL_PAGE) showEditor.value = false
            }

        }

        const storeEditorContent = () => window.dispatchEvent(new CustomEvent("storeEditorContent"))

        return {
            formHasChanged,
            updateContent,
            preview,
            storeEditorContent,
            showEditor
        }

    }
}).mount('main')
