import 'bootstrap';
//import "@fortawesome/fontawesome-free/js/all.js";
import '../Components/Datatable/datatable'
import { initConfirmDeleteButtons } from '../Components/ConfirmDeleteComponent'
import { initAdminPage } from "../Utils/InitAdminPage";


initAdminPage()

// Initialisation des boutons de confirmation de suppression standard (avec data-url PHP)
initConfirmDeleteButtons()


