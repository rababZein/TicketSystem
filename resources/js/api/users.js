import { API } from './index'

export default {
    // get clients
    getOwners: () => API.get('/owner/getall'),
        
    // get regular-user
    getRegularUsers : () => API.get('/user/getAllResponsibles')
}