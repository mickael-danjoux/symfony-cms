import axios from "axios"
import { editorEndpoints } from "../Config/endpoints";

export const AssetManagerService = {
    remove: id => axios.delete(editorEndpoints.image.item(id))
}