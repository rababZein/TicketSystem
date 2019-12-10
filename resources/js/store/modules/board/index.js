import tasks from "../../../api/tasks";

export default {
    namespaced: true,

    state: {
        name: "workshop",
        columns: [
            {
                name: "open",
                tasks: [
                    {
                        description: "",
                        name: "New Alferp Bug when trying to close a ticket",
                        id: 1,
                        userAssigned: null
                    },
                    {
                        description: "",
                        name: "New Alferp Bug when trying to ticket",
                        id: 2,
                        userAssigned: null
                    },
                    {
                        description: "",
                        name: "New Alferp Bug when trying to close a ticket",
                        id: 3,
                        userAssigned: null
                    }
                ]
            },
            {
                name: "pending",
                tasks: [
                    {
                        description: "",
                        name: "first task",
                        id: 6,
                        userAssigned: null
                    }
                ]
            },
            {
                name: "in-progress",
                tasks: [
                    {
                        description: "",
                        name: "first task",
                        id: 4,
                        userAssigned: null
                    }
                ]
            },
            {
                name: "done",
                tasks: [
                    {
                        description: "",
                        name: "first task",
                        id: 5,
                        userAssigned: null
                    }
                ]
            }
        ]
    },
    actions: {},
    mutations: {
        MOVE_TASK(state, { fromTasks, toTasks, taskIndex }) {
            const taskToMove = fromTasks.splice(taskIndex, 1)[0];
            toTasks.push(taskToMove);
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
