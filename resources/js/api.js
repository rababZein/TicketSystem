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
    delete: params => API.delete('/users/'+params),
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
// projects end point
const projects = {
    get: params => API.get('/projects', {params}),
    post: params => API.post('/projects', {params}),
} 

// tickets end point
const tickets = {
    getAll: params => API.get('ticket/getall'),
    delete: params => API.delete('/ticket/'+params),
}

// projects end point
const projects = {
    getAllByOwner: params => API.get('project/getAllByOwner/'+params),
}

// owners end point
const owners = {
    getAll: params => API.get('owner/getall'),
}

export default {
    users,
    roles,
    permissions,
    tickets,
    projects,
    owners
}