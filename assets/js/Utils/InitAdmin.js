import initSidebar from "./InitSidebar";
import initValidateFormButtons from "./InitValidateFormButtons";
import initDropFile from "./InitDropFiles";
import { initConfirmButtons } from "../Components/ConfirmComponent";


export default () => {

    initSidebar()
    initValidateFormButtons()
    initDropFile()

// Initialisation des boutons de confirmation standard (avec href)
    initConfirmButtons()
}