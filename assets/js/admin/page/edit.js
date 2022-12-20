import '../main'
import {createApp, ref, onMounted} from "vue";
import {initConfirmButtons} from "../../Components/ConfirmComponent";
import slugger from 'slugger'

createApp({
    compilerOptions: {
        delimiters: ["${", "}"]
    },
    components: {},
    setup() {
        const formData = ref(null)
        const formHasChanged = ref(false)
        onMounted(() => {
            initConfirmButtons()
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
                const path = document.getElementById('page_path').value
                window.open('/' + path + '?preview=true', '_blank').focus();
            }
        }

        const onSelectPageTypeChange = () => {
            const selectElement = document.getElementById('page_type');
            const pageTypeValue = document.getElementById('pageType').value;

            // si l'élément est défini, alors l'utilisateur est un SA
            if (selectElement) {
                const blockInputControllerElement = document.getElementById('form-page-controller');
                const blockInputRouteElement = document.getElementById('form-page-route');
                const inputControllerElement = document.getElementById('page_controller');

                // sert uniquement au chargement de la page
                if (pageTypeValue === "INTERNAL_PAGE") {
                    blockInputControllerElement.classList.remove('d-none')
                    blockInputRouteElement.classList.remove('d-none')
                    inputControllerElement.setAttribute('required', 'required')
                    formData.value = false;
                }

                selectElement.addEventListener('change', (e) => {

                    if (+e.target.value === 0) {
                        blockInputControllerElement.classList.remove('d-none')
                        blockInputRouteElement.classList.remove('d-none')
                        inputControllerElement.value = ''
                        inputControllerElement.setAttribute('required', 'required')
                        formData.value = false;
                    } else if (+e.target.value === 1) {
                        blockInputControllerElement.classList.add('d-none')
                        blockInputRouteElement.classList.add('d-none')
                        inputControllerElement.removeAttribute('required')
                        inputControllerElement.value = ''
                        formData.value = JSON.parse(document.getElementById('formContent').value)
                    }

                })
            }
            // élément non défini
            else {
                // sert uniquement au chargement de la page
                if (pageTypeValue === "INTERNAL_PAGE") {
                    formData.value = false;
                }
            }

        }

        return {
            formData,
            formHasChanged,
            updateContent,
            preview
        }

    }
}).mount('main')
