import axios from "axios"
import { editorEndpoints } from "../Config/endpoints";

export const AssetManagerService = {
    remove: url => axios.delete(editorEndpoints.image.item(url))
}