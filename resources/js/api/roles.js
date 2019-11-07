import { API } from './index'

export default {
    // roles end point
    get: params => API.get('/roles/list', { params }),
    getAll: params => API.get('/roles/getall', { params }),
    post: params => API.post('/roles', { params }),
    put: params => API.put('/roles', { params }),
    delete: params => API.delete('/roles/' + params),
}