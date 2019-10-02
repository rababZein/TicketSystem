import axios from 'axios'

// The Base Route
const API = axios.create({
    baseURL: ''
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
    getAll: params => API.get('permissions/getall', {params}),
    delete: params => API.delete('/permissions/'+params),
}

// tickets end point
const tickets = {
    getAll: params => API.get('ticket/getall'),
    delete: params => API.delete('/ticket/'+params),
}

// projects end point
const projects = {
    getAll: params => API.get('project/getall'),
}

export default {
    users,
    roles,
    permissions,
    tickets,
    projects
}