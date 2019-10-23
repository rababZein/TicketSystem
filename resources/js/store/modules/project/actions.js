import projects from '../../../api/projects';

export default {
    getProjects({ commit }) {
        return new Promise((resolve, reject) => {
            projects.get().then(response => {
                commit('setProjects', response.data.data.data);
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
    getOwners({ commit }){
        return new Promise((resolve, reject) => {
            projects.getOwners().then(response => {
                commit('setOwners', response.data.data)
                resolve(response);
            })
            .catch(error => {
                reject(error);
            })
        });
    }
}