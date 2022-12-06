import axios from "axios"
import { editorEndpoints } from "../Config/endpoints";

export const WebpackService = {
    getEntrypoint: () => axios.get(editorEndpoints.webpack.entrypoint)
}