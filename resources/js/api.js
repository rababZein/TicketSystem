import axios from 'axios'

// The Base Route
const API = axios.create({
    baseURL: '/v-api'
})

// const token = localStorage.getItem('token');

// API.defaults.headers.common['Authorization'] = token ? `Bearer ${token}` : '';

// owners end point
const owners = {
    getAll: params => API.get('/owner/getall'),
}

// responsibles end point
const responsibles = {
    getAll: params => API.get('/user/getAllResponsibles'),
}

const tasks = {
    get: params => API.get('/tasks/list/'),
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
    owners,
    tasks,
    responsibles,
    receipts,
    track,
    status,
    tasks
}