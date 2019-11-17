import { API } from "./index";

export default {
    postForClient: params => API.post("/clientComments", params),
    postForTask: params => API.post("/taskComments", params),
    getPerClient: params => API.get("/commentsPerClient/" + params),
    getPerTask: params => API.get("/commentsPerTask/" + params)
};
