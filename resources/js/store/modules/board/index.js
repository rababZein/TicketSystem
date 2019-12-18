import tasks from "../../../api/tasks";

export default {
    namespaced: true,

    state: {
        board: {}
    },
    actions: {
        getTasksForBoard({ commit }, projectId) {
            return new Promise((resolve, reject) => {
                tasks.getTasksForBoard({ project_id: projectId })
                    .then(response => {
                        commit("SET_BOARD", response.data.data);
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        moveTask({ commit }, { fromColumnIndex, toColumnIndex, taskId }) {
            return new Promise((resolve, reject) => {
                if (fromColumnIndex != toColumnIndex) {
                    tasks.editTask({ id: taskId, status_id: toColumnIndex })
                        .then(response => {
                            commit("MOVE_TASK", { fromColumnIndex, toColumnIndex, taskId });
                            resolve(response);
                        })
                        .catch(error => {
                            reject(error);
                        });
                }
            });
        },
        createTask({ commit }, { columnIndex, projectId, title }) {
            return new Promise((resolve, reject) => {
                tasks.createTask({ project_id: projectId, name: title, status_id: columnIndex })
                    .then(response => {
                        commit("CREATE_TASK", response.data.data);
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        editTask({commit}, task) {
            return new Promise((resolve, reject) => {
                tasks.editTask(task).then(response => {
                    commit('EDIT_TASK', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
    },
    mutations: {
        EDIT_TASK(state, task) {
            const columnTasks = state.board.columns[task.status.id - 1].tasks;
            console.log(columnTasks);
            const taskObj = columnTasks.find(items => items.id == task.id);
            Object.assign(taskObj, task);    
        },
        CREATE_TASK(state, task) {
            let toColumnTasks = state.board.columns[task.status.id - 1].tasks;
            toColumnTasks.push(task)
        },
        MOVE_TASK(state, { fromColumnIndex, toColumnIndex, taskId }) {
            let fromColumnTasks = state.board.columns[fromColumnIndex - 1].tasks;
            const toColumnTasks = state.board.columns[toColumnIndex - 1].tasks;
            const taskToMove = fromColumnTasks.find(items => items.id == taskId);
            if (fromColumnIndex != toColumnIndex) {
                state.board.columns[fromColumnIndex - 1].tasks = fromColumnTasks.filter(items => items.id != taskId);
                toColumnTasks.push(taskToMove);
            }

        },
        SET_BOARD(state, data) {
            state.board = data;
        }
    },
    getters: {
        getTask(state) {
            return id => {
                for (const column of state.board.columns) {
                    for (const task of column.tasks) {
                        if (task.id === id) {
                            return task;
                        }
                    }
                }
            };
        }
    }
};
