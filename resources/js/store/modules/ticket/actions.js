import tickets from '../../../api/tickets';
import projects from '../../../api/projects';

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
    getProjectsByOwner({ commit }, ownerId) {
        return new Promise((resolve, reject) => {
            projects.getAllByOwner(ownerId).then(response => {
                commit('setProjectByOwners', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    createTicket({ commit }, data) {
        return new Promise((resolve, reject) => {
            tickets.createTicket(data).then(response => {
                // commit('createTicket', response.data.data);
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
    }

}