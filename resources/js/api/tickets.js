import { API } from './index'

export default {
    get: params => API.get('/tickets', { params }),
    getAll: params => API.get('/tickets/getall'),
    createTicket: params => API.post('/tickets', params),
    editTicket: params => API.put('/tickets/' + params.id, params),
    delete: params => API.delete('/tickets/' + params),
    show: params => API.get('/tickets/' + params),
    getByProjectId: params => API.get('/projects/' + params.id + '/tickets/' , {params}),
    getTicketCountPerClient: params => API.get('/clients/'+ params +'/ticketsNumber'),
    getTicketsPerClient: params => API.get('/clients/'+ params +'/tickets')
}