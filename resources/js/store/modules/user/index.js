import users from '../../../api/users';

export default {
    namespaced: true,
    state: {
        items: {},
        singleUser: {
        },
        projectsPerUser: {},
        ticketsPerUser: {},
        tasksPerUser: {},
        projectCountPerUser: null,
        ticketCountPerUser: null,
        openTaskCountPerUser: null,
        closedTaskCountPerUser: null
    },
    getters: {
        activeUsers(state) {
            return state.items;
        },
        activeSingleUser(state) {
            return state.singleUser;
        },
        ProjectCountPerClient(state) {
            return state.projectCountPerUser;
        },
        TicketCountPerClient(state) {
            return state.ticketCountPerUser;
        },
        OpenTaskCountPerClient(state) {
            return state.openTaskCountPerUser;
        },
        ClosedTaskCountPerClient(state) {
            return state.closedTaskCountPerUser;
        },
        TotlaTaskCountPerClient(state) {
            return state.openTaskCountPerUser + state.closedTaskCountPerUser;
        },
        ProjectPerClient(state) {
            return state.projectsPerUser;
        },
        TicketsPerClient(state) {
            return state.ticketsPerUser;
        },
        TasksPerClient(state) {
            return state.tasksPerUser;
        }
    },
    actions: {
        getUsers({ commit }, params) {
            return new Promise((resolve, reject) => {
                users.get(params).then(response => {
                    commit('setUsers', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        getUserById({ commit }, id) {
            return new Promise((resolve, reject) => {
                users.show(id).then(response => {
                    commit('setSingleUser', response.data.data)
                    resolve(response);
                })
                    .catch(error => {
                        reject(error);
                    })
            });
        },
        editUser({commit}, data) {
            return new Promise((resolve, reject) => {
                users.edit(data).then(response => {
                    commit('setSingleUser', response.data.data)
                    resolve(response);
                })
                    .catch(error => {
                        reject(error);
                    })
            });
        },
        createMetadata({commit}, data) {
            return new Promise((resolve, reject) => {
                users.createMetadata(data).then(response => {
                    commit('setMetaDate', response.data.data)
                    resolve(response);
                })
                    .catch(error => {
                        reject(error);
                    })
            });
        },
        editMetadata({commit}, data) {
            return new Promise((resolve, reject) => {
                users.editMetadata(data).then(response => {
                    commit('setMetaDate', response.data.data)
                    resolve(response);
                })
                    .catch(error => {
                        reject(error);
                    })
            });
        }
    },
    mutations: {
        setUsers(state, users) {
            state.items = Object.assign({}, users);
        },
        setSingleUser(state, user) {
            state.singleUser = user;
        },
        setProjectCountPerUser(state, count) {
            state.projectCountPerUser = count.projectsNumber;
        },
        getTicketCountPerClient(state, count) {
            state.ticketCountPerUser = count.ticketsNumber;
        },
        setTaskCountPerClient(state, taskCount) {
            // status_id of closed task = 4
            let openTasksCount = taskCount.filter( (item) => item.status_id !== 4 );
            let closedTasksCount = taskCount.filter(o => [4].includes(o.status_id) );

            Array.prototype.sum = function (prop) {
                var total = 0
                for ( var i = 0, _len = this.length; i < _len; i++ ) {
                    total += this[i][prop]
                }
                return total
            }
            
            state.openTaskCountPerUser = openTasksCount.sum("count");
            state.closedTaskCountPerUser = closedTasksCount.sum("count");
        },
        setProjectPerUser(state, projects) {
            state.projectsPerUser = projects;
        },
        getTicketsPerClient(state, tickets) {
            state.ticketsPerUser = tickets;
        },
        setTasksPerClient(state, tasks) {
            state.tasksPerUser = tasks;
        },
        setMetaDate(state, metadata) {
            state.singleUser.metadata = metadata;
        }
    }
}