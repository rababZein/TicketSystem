import { API } from './index'

// tasks end point
export default {
    get: params => API.get('/status/getAll'),
}