import axios from 'axios'

// The Base Route
const API = axios.create({
    baseURL: ''
})

const token = localStorage.getItem('token');

API.defaults.headers.common['Authorization'] = token ? `Bearer ${token}` : '';

// users end point
const users = {
    get: params => API.get('/users/list', {params}),
    post: params => API.post('/users', { params }),
}
// roles end point
const roles = {
    get: params => API.get('/roles/list', {params}),
    post: params => API.post('/roles', {params}),
    put: params => API.put('/roles', {params}),
    delete: params => API.delete('/roles/'+params),
}
// permissions end point
const permissions = {
    get: params => API.get('/permissions/list', {params}),
    getAll: params => API.get('permissions/getall', {params}),
    delete: params => API.delete('/permissions/'+params),
}

export default {
    users,
    roles,
    permissions
}