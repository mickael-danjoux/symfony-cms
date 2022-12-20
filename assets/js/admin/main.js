import 'bootstrap';
import "@fortawesome/fontawesome-free/js/all.js";
import '../Components/Datatable/datatable'
import { initConfirmButtons } from '../Components/ConfirmComponent'
import initSidebar from '../Utils/InitSidebar'
import initValidateFormButtons from '../Utils/InitValidateFormButtons'
import initDropFile from '../Utils/InitDropFiles'

initSidebar()
initValidateFormButtons()
initDropFile()

// Initialisation des boutons de confirmation standard (avec data-url PHP)
initConfirmButtons()

