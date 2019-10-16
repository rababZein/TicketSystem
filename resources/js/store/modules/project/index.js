import projects from '../../../api/projects';

const state = {
    Projects: [],
};

const getters = {
    activeProjects(state) {
        return state.Projects;
    }
};

const actions = {
    getProjects({ commit }) {
        return new Promise((resolve, reject) => {
            projects.get().then(response => {
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
};

const mutations = {

    setProjects(state, Projects) {
        state.Projects = Projects;
    },
    createProject(state, project) {
        const projectObj = {
            id: project.id,
            name: project.name,
            description: project.description,
            owner_id: project.owner_id,
            task_rate: project.task_rate,
            budget_hours: project.budget_hours
        };
        state.Projects.push(projectObj);
    },


};

export default {
    state,
    getters,
    actions,
    mutations
};