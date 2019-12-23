import { API } from './index'

// projects end point
export default {
        get: params => API.get('/projects', { params }),
        createProject: params => API.post('/projects', {
                name: params.name,
                description: params.description,
                owner_id: params.owner_id,
                task_rate: params.task_rate,
                budget_hours: params.budget_hours,
                project_assign: params.project_assign
        }),
        editProject: params => API.put('/projects/' + params.id, {
                name: params.name,
                description: params.description,
                owner_id: params.owner_id,
                task_rate: params.task_rate,
                budget_hours: params.budget_hours,
                project_assign: params.project_assign
        }),
        getAllProjects: () => API.get('/projects/list'),
        getAllByOwner: params => API.get('/project/getAllByOwner/' + params),
        delete: params => API.delete('/projects/' + params),
        show: params => API.get('/projects/' + params),
        getOwners: () => API.get('/owner/getall'),
        
        getProjectCountPerClient: params => API.get('/clients/'+ params +'/projectsNumber'),
        getProjectPerClient: params => API.get('/clients/'+ params.id +'/projects', {params}),
        search: params => API.get('/project/search/'+ params),
}