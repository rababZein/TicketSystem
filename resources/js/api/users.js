import { API } from './index'

export default {
    get: () => API.get('/owner/getall')
}