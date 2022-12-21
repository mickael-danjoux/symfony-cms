import initSidebar from "./InitSidebar";
import initValidateFormButtons from "./InitValidateFormButtons";
import initDropFile from "./InitDropFiles";
import { initConfirmButtons } from "../Components/ConfirmComponent";


export default () => {

// Initialisation des boutons de confirmation de suppression standard (avec data-url PHP)
    initSidebar()
    initValidateFormButtons()
    initDropFile()

// Initialisation des boutons de confirmation standard (avec data-url PHP)
    initConfirmButtons()
}