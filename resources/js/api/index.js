import axios from "axios";
import router from "../routes";

// share a common base URL and configuration
export const API = axios.create({
    baseURL: "/v-api/"
});

// will check if the global handler should be used or not (by default is true)
// Example: API.get('/v-api/api-endpoint', { handlerEnabled: false })
const isHandlerEnabled = (config = {}) => {
    return config.hasOwnProperty("handlerEnabled") && !config.handlerEnabled ? false : true;
};


const errorHandler = error => {
    if (isHandlerEnabled(error.config)) {
        // check if unauthorized error returned
        if (error.response.status === 401) {
            window.location.href = '/login';
        }
        if (error.response.status == 403) {
            Toast.fire({
                type: "error",
                title: "This action is unauthorized"
            });
        }
        if (error.response.status === 404) {
            router.push('/404')
        }
        if (error.response.status == 500) {
            Toast.fire({
                type: "error",
                title: "Server error!"
            });
        }
    }
    return Promise.reject({ ...error });
};

const successHandler = response => {
    if (isHandlerEnabled(response.config)) {
    }
    return response;
};

API.interceptors.response.use(
    response => successHandler(response),
    error => errorHandler(error)
);
