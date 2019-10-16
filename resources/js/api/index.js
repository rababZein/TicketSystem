import axios from 'axios'

// share a common base URL and configuration
export const API = axios.create({
    baseURL: 'v-api/'
})