import tickets from '../../../api/tickets';
import owners from '../../../api/users';
import projects from '../../../api/projects';

export default {
    getTickets({ commit }) {
        return new Promise((resolve, reject) => {
            tickets.get().then(response => {
                commit('setTickets', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getProjectsByOwner({ commit }) {
        return new Promise((resolve, reject) => {
            projects.getAllByOwner().then(response => {
                commit('setTicketsOwners', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    createTickets({ commit }, data) {
        return new Promise((resolve, reject) => {
            tickets.createTickets(data).then(response => {
                commit('createTickets', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },


}