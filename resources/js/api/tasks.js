import { API } from './index'

// tasks end point
export default {
    get: params => API.get('/tasks/', { params }),
    show: params => API.get('/tasks/' + params),
    createTask: params => API.post('/tasks', params),
    editTask: params => API.put('/tasks/' + params.id, params),
    deleteTask: params => API.delete('/tasks/' + params),
    getTasksByTicketId: params => API.get('/tickets/' + params.id + '/tasks', {params}),
    getTaskCountPerClient: params => API.get('/clients/'+ params +'/tasksNumber'),
    getTasksPerClient: params => API.get('/clients/'+ params.id +'/tasks', {params}),
}