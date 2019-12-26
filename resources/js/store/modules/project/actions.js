import projects from '../../../api/projects';

export default {
    getProjects({ commit }, queryParams) {
        return new Promise((resolve, reject) => {
            projects.get({ queryParams: queryParams, page: queryParams.page }).then(response => {
                commit('setProjects', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getAllProjects({commit}) {
        return new Promise((resolve, reject) => {
            projects.getAllProjects().then(response => {
                commit('setAllProjects', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    createProject({ commit }, data) {
        return new Promise((resolve, reject) => {
            projects.createProject(data).then(response => {
                commit('createProject', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    editProject({ commit }, project) {
        return new Promise((resolve, reject) => {
            projects.editProject(project).then(response => {
                commit('editProject', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    deleteProject({ commit }, id) {
        return new Promise((resolve, reject) => {
            projects.delete(id).then(response => {
                commit('deleteProject', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getOwners({ commit }) {
        return new Promise((resolve, reject) => {
            projects.getOwners().then(response => {
                commit('setProjectsOwners', response.data.data)
                resolve(response);
            })
                .catch(error => {
                    reject(error);
                })
        });
    },
    getProjectById({ commit }, id) {
        return new Promise((resolve, reject) => {
            projects.show(id)
                .then(response => {
                    commit('setSingleProject', response.data.data)
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
    getProjectCountPerClient({ commit }, clientId) {
        return new Promise((resolve, reject) => {
            projects.getProjectCountPerClient(clientId).then(response => {
                commit('user/setProjectCountPerUser', response.data.data, { root: true });
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },
    getProjectPerClient({commit}, params) {
        return new Promise((resolve, reject) => {
            projects.getProjectPerClient(params).then(response => {
                commit('user/setProjectPerUser', response.data.data, { root: true });
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    }
}