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
// users end point
const roles = {
    get: params => API.get('/roles', {params}),
    post: params => API.post('/roles', {params}),
}

export default {
    users,
    roles
}