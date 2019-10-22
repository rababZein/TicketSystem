import axios from 'axios'

// The Base Route
const API = axios.create({
    baseURL: '/v-api'
})

// const token = localStorage.getItem('token');

// API.defaults.headers.common['Authorization'] = token ? `Bearer ${token}` : '';

// users end point
const users = {
    get: params => API.get('/users/list', { params }),
    post: params => API.post('/users', { params }),
    delete: params => API.delete('/users/' + params),
}
// roles end point
const roles = {
    get: params => API.get('/roles/list', { params }),
    getAll: params => API.get('/roles/getall', { params }),
    post: params => API.post('/roles', { params }),
    put: params => API.put('/roles', { params }),
    delete: params => API.delete('/roles/' + params),
}
// permissions end point
const permissions = {
    get: params => API.get('/permissions/list', { params }),
    getAll: params => API.get('/permissions/getall', { params }),
    delete: params => API.delete('/permissions/' + params),
}
// projects end point
const projects = {
    get: params => API.get('/projects/list', { params }),
    post: params => API.post('/projects', { params }),
    getAllByOwner: params => API.get('/project/getAllByOwner/' + params),
    delete: params => API.delete('/projects/' + params),
    show: params => API.get('/projects/' + params),
}

// tickets end point
const tickets = {
    get: params => API.get('/tickets/list'),
    getAll: params => API.get('/tickets/getall'),
    delete: params => API.delete('/tickets/' + params),
    show: params => API.get('/tickets/' + params)
}

// owners end point
const owners = {
    getAll: params => API.get('/owner/getall'),
}

// responsibles end point
const responsibles = {
    getAll: params => API.get('/user/getAllResponsibles'),
}

// tasks end point
const tasks = {
    get: params => API.get('/tasks/list/' + params),
    getById: params => API.get('/tasks/' + params),
    getAll: params => API.get('/tasks/getall'),
    delete: params => API.delete('/tasks/' + params),
}

// tracking tasks
const track = {
    get: params => API.get('/tracking/' + params),
    post: params => API.post('/tracking/' + params.task_id, {
        comment: params.comment,
        start_at: params.start_at,
        task_id: params.task_id
    }),
    put: params => API.patch('/tracking/' + params.task_id + '/' + params.track_id, { 
        end_at: params.end_at,
        task_id: params.task_id
    }),
    delete: params => API.delete('/tracking/' + params.task_id + '/' + params.track_id),
    countDuration: params => API.get('/tracking/' + params),
    checkTrackingInProgress: params => API.get('/tracking/checkTrackingInProgress/' + params),
    getHistory: params => API.get('/tracking/history/' + params),
}

// receipts end point
const receipts = {
    list: params => API.get('/receipts/list'),
    getAll: params => API.get('/receipts/getall'),
    delete: params => API.delete('/receipts/' + params),
}

// status end point
const status = {
    getAll: params => API.get('/status/getAll'),
}

export default {
    users,
    roles,
    permissions,
    tickets,
    owners,
    projects,
    tasks,
    responsibles,
    receipts,
    track,
    status
}