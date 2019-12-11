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
        }
    },
    mutations: {
        MOVE_TASK(state, { fromTasks, toTasks, taskIndex }) {
            const taskToMove = fromTasks.splice(taskIndex, 1)[0];
            toTasks.push(taskToMove);
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
