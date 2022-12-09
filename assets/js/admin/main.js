import 'bootstrap';
import '../Components/Datatable/datatable'
import { initConfirmDeleteButtons } from '../Components/ConfirmDeleteComponent'
import { initAdminPage } from "../Utils/InitAdminPage";


initAdminPage()

// Initialisation des boutons de confirmation de suppression standard (avec data-url PHP)
initConfirmDeleteButtons()


