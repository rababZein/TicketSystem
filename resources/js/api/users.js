import { API } from './index'

export default {
    // all users api
    get: params => API.get('/users', { params }),
    post: params => API.post('/users', { params }),
    show: params => API.get('/users/' + params),
    delete: params => API.delete('/users/' + params),

    // get clients
    getOwners: () => API.get('/owner/getall'),
        
    // get regular-user
    getRegularUsers : () => API.get('/user/getAllResponsibles'),
}