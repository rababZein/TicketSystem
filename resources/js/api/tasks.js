import { API } from './index'

// tasks end point
export default {
    get: params => API.get('/tasks/', { params }),
    getById: params => API.get('/tasks/' + params),
    getAll: params => API.get('/tasks/getall'),
    createTask: params => API.post('/tasks', params),
    editTask: params => API.put('/tasks/' + params.id, params),
    deleteTask: params => API.delete('/tasks/' + params),
    getStatus: params => API.get('/status/getAll'),
}