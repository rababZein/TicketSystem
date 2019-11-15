import { API } from "./index";

export default {
    post: params => API.post("/clientComments", params),
    getPerClient: params => API.get("/commentsPerClient/" + params)
};
