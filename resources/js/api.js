import axios from 'axios'

// The Base Route
const API = axios.create({
    baseURL: '/v-api'
})

// const token = localStorage.getItem('token');

// API.defaults.headers.common['Authorization'] = token ? `Bearer ${token}` : '';


// receipts end point
const receipts = {
    list: params => API.get('/receipts/list'),
    getAll: params => API.get('/receipts/getall'),
    delete: params => API.delete('/receipts/' + params),
}



export default {
    receipts,
}