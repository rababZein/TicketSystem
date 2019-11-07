import { API } from './index'

export default {
    // permissions end point
    get: params => API.get('/permissions/list', { params }),
    getAll: params => API.get('/permissions/getall', { params }),
    delete: params => API.delete('/permissions/' + params),
}