import { API } from './index'

export default {
    get: params => API.get('/logActivityListsByClientId/' + params),
}