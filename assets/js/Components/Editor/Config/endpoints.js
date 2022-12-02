const baseURL = '/api/editor'
export const editorEndpoints = {
    image: {
        item: id => `${baseURL}/image/${id}`,
        collection: `${baseURL}/image`
    },
    page: {
        item: id => `${baseURL}/page/${id}`,
        load: id => `${baseURL}/page/load/${id}`
    }
}