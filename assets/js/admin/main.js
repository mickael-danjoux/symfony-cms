import 'bootstrap';
import "@fortawesome/fontawesome-free/js/all.js";
import '../Components/Datatable/datatable'
import { initConfirmDeleteButtons } from '../Components/ConfirmDeleteComponent'
import initSidebar from '../Utils/InitSidebar'
import initValidateFormButtons from '../Utils/InitValidateFormButtons'
import initDropFile from '../Utils/InitDropFiles'

initSidebar()
initValidateFormButtons()
initDropFile()

// Initialisation des boutons de confirmation de suppression standard (avec data-url PHP)
initConfirmDeleteButtons()

