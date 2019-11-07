import { API } from './index'

export default {
    // receipts end point
    list: params => API.get('/receipts/list'),
    getAll: params => API.get('/receipts/getall'),
    delete: params => API.delete('/receipts/' + params),
}