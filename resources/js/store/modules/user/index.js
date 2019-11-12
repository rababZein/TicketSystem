import users from '../../../api/users';

export default {
    namespaced: true,
    state: {
        items: {},
        singleUser: {},
        projectCountPerUser: null,
        ticketCountPerUser: null,
        openTaskCountPerUser: null,
        closedTaskCountPerUser: null
    },
    getters: {
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
        }
    },
    actions: {
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
        }
    },
    mutations: {
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
            
            let openTasksCount = taskCount.filter(o => [1,2,3].includes(o.status_id) );
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
        }
    }
}