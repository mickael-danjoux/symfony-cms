const baseURL = '/api/editor'
export const editorEndpoints = {
    image: {
        item: url => `${baseURL}/image/${url}`,
        collection: `${baseURL}/image`
    },
    page: {
        item: id => `${baseURL}/page/${id}`,
        load: id => `${baseURL}/page/load/${id}`
    },
    webpack: {
        entrypoint: `${baseURL}/webpack/entrypoint`
    }
}