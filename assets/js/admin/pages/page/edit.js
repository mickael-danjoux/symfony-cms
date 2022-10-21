import '../../main'
import {createApp, ref, onMounted} from "vue";
import {initConfirmDeleteButtons} from "../../Components/ConfirmDeleteComponent";


createApp({
    compilerOptions: {
        delimiters: ["${", "}"]
    },
    components: {

    },
    setup() {
        const formData = ref(null)
        const formHasChanged = ref(false)
        onMounted(() => {
            initConfirmDeleteButtons()
            handleFormChange()
            onSelectPageTypeChange()
        })
        const handleFormChange = () => {
            document.querySelectorAll('.js-change').forEach((el) =>{
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
            document.querySelectorAll('.js-disabled-on-change').forEach((el)=> {
                el.classList.add('disabled')
            })
        }

        const preview = () => {
            if(! formHasChanged.value){
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
