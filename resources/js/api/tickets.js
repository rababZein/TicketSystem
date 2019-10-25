import { API } from './index'

export default {
    get: params => API.get('/tickets/list'),
    getAll: params => API.get('/tickets/getall'),
    delete: params => API.delete('/tickets/' + params),
    show: params => API.get('/tickets/' + params)
}