import { API } from './index'

export default {
    // tracking tasks
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
    checkTrackingInProgress:  params => API.get('/tracking/checkTrackingInProgress/' + params, { handlerEnabled: false }),
    getHistory: params => API.get('/tracking/history/' + params),
    timeReporting: params => API.post('/tracking/timeReporting', params),
}