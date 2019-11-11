import projects from '../../../api/projects';

export default {
    getProjects({ commit }, page) {
        return new Promise((resolve, reject) => {
            projects.get({ page: page }).then(response => {
                commit('setProjects', response.data.data);
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
                commit('setProjectCoutPerUser', response.data.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    }
}