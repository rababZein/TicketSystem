import axios from 'axios'

// The Base Route
const API = axios.create({
    baseURL: '/api'
})

// users end point
const users = {
    get: params => API.get('/users', {params}),
    post: params => API.post('/users', { params }),
}
// roles end point
const roles = {
    get: params => API.get('/roles', {params}),
    post: params => API.post('/roles', {params}),
    put: params => API.put('/roles', {params}),
    delete: params => API.delete('/roles/'+params),
}
// permissions end point
const permissions = {
    get: params => API.get('/permissions', {params}),
    delete: params => API.delete('/permissions/'+params),
}

export default {
    users,
    roles,
    permissions
}