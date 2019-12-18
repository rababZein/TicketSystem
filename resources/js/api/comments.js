import { API } from "./index";

export default {
    postForClient: params => API.post("/clientComments", params),
    postForTask: params => API.post("/taskComments", params),
    postForTicket: params => API.post("/ticketComments", params),
    getPerClient: params => API.get("/commentsPerClient/" + params),
    getPerTask: params => API.get("/commentsPerTask/" + params),
    getPerTicket: params => API.get("/commentsPerTicket/" + params),
};
