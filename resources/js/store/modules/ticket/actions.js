import tickets from '../../../api/tickets';
import projects from '../../../api/projects';
import status from '../../../api/status';

export default {
    getTickets({ commit }, page) {
        return new Promise((resolve, reject) => {
            tickets.get({page: page}).then(response => {
                commit('setTickets', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getOwners({ commit }){
        return new Promise((resolve, reject) => {
            projects.getOwners().then(response => {
                commit('setTicketsOwners', response.data.data)
                resolve(response);
            })
            .catch(error => {
                reject(error);
            })
        });
    },
    
    createTicket({ commit }, data) {
        return new Promise((resolve, reject) => {
            tickets.createTicket(data).then(response => {
                commit('createTicket', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    editTicket({ commit }, data) {
        return new Promise((resolve, reject) => {
            tickets.editTicket(data).then(response => {
                commit('editTicket', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    deleteTicket({commit}, id) {
        return new Promise((resolve, reject) => {
            tickets.delete(id).then(response => {
                commit('deleteTicket', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getTicketById({commit}, id) {
        return new Promise((resolve, reject) => {
            tickets.show(id).then(response => {
                commit('getTicketById', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getTicketsByProjectId({commit}, params) {
        return new Promise((resolve, reject) => {
            tickets.getByProjectId(params).then(response => {
                commit('setTickets', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getTicketCountPerClient({commit}, userId) {
        return new Promise((resolve, reject) => {
            tickets.getTicketCountPerClient(userId).then(response => {
                commit('user/getTicketCountPerClient', response.data.data, { root: true });
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getTicketsPerClient({commit}, params) {
        return new Promise((resolve, reject) => {
            tickets.getTicketsPerClient(params).then(response => {
                commit('user/getTicketsPerClient', response.data.data, { root: true });
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getStatus({commit}) {
        return new Promise((resolve, reject) => {
            status.get().then(response => {
                commit('setStatus', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    }

}