import tasks from '../../../api/tasks';

export default {
    namespaced: true,

    state: {
        items: {},
    },
    actions: {
        getTasksAsCards({ commit }, page) {
            return new Promise((resolve, reject) => {
                tasks.getTasksAsCards().then(response => {
                    commit('setTasks', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        }
    },
    mutations: {
        // setTasks(state, tasks) {
        //     state.items = Object.assign({}, tasks);
        // }
    },
    getters: {
        // activeTasks(state) {
        //     return state.items;
        // }
    }
}