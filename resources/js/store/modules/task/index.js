import tasks from '../../../api/tasks';
import status from '../../../api/status';

export default {
    namespaced: true,

    state: {
        items: {},
        singleTask: {},
        status: [],
        tickets: [],
    },
    actions: {
        getTasks({ commit }, params) {
            return new Promise((resolve, reject) => {
                tasks.get(params).then(response => {
                    commit('setTasks', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        getTaskById({ commit }, id) {
            return new Promise((resolve, reject) => {
                tasks.show(id).then(response => {
                    commit('setTaskById', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        getStatus({ commit }) {
            return new Promise((resolve, reject) => {
                status.get().then(response => {
                    commit('setStatus', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        createTask({commit}, task) {
            return new Promise((resolve, reject) => {
                tasks.createTask(task).then(response => {
                    commit('setNewTask', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        editTask({commit}, task) {
            return new Promise((resolve, reject) => {
                tasks.editTask(task).then(response => {
                    commit('editTask', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        deleteTask({commit}, id) {
            return new Promise((resolve, reject) => {
                tasks.deleteTask(id).then(response => {
                    commit('deleteTask', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        getTasksByTicketId({commit}, params) {
            return new Promise((resolve, reject) => {
                tasks.getTasksByTicketId(params).then(response => {
                    commit('setTasks', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        getTaskCountPerClient({commit}, userId) {
            return new Promise((resolve, reject) => {
                tasks.getTaskCountPerClient(userId).then(response => {
                    commit('user/setTaskCountPerClient', response.data.data, {root: true});
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        getTasksPerClient({commit}, params) {
            return new Promise((resolve, reject) => {
                tasks.getTasksPerClient(params).then(response => {
                    commit('user/setTasksPerClient', response.data.data, {root: true});
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        }
    },
    mutations: {
        setTasks(state, tasks) {
            state.items = Object.assign({}, tasks);
        },
        setTaskById(state, task) {
            state.singleTask = task;
        },
        setNewTask(state, task) {
            const taskObj = task;
            state.items.data.unshift(taskObj);
        },
        setStatus(state, status) {
            state.status = _.map(status, function (key) {
                return { id: key.id, name: key.name };
            });
        },
        editTask(state, task) {
            if (state.items.data) {
                const taskObj = state.items.data.find(items => items.id == task.id);
                Object.assign(taskObj, task);    
            }
        },
        deleteTask(state, task) {
            state.items.data = state.items.data.filter(items => items.id != task.id);
        }
    },
    getters: {
        activeTasks(state) {
            return state.items;
        },
        activeTask(state) {
            return state.singleTask;
        },
        activeStatus(state) {
            return state.status;
        }
    }
}