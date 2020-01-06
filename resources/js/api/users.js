import { API } from './index'

export default {
    // all users api
    get: params => API.get('/users', { params }),
    post: params => API.post('/users', params),
    edit: params => API.put('/users/' + params.id, params),
    show: params => API.get('/users/' + params),
    delete: params => API.delete('/users/' + params),

    // get clients
    getOwners: () => API.get('/owner/getall'),
    getClientsPaginated: params => API.get('/user/getClientsPaginated', { params }),
    getEmployeesPaginated: params => API.get('/user/getEmployeesPaginated', { params }),

    // get regular-user
    getRegularUsers: () => API.get('/user/getAllResponsibles'),

    // metadata
    createMetadata: params => API.post('/metadata', params),
    editMetadata: params => API.put('/metadata/' + params.id, params),
}