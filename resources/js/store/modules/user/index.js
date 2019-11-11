import users from '../../../api/users';

export default {
    namespaced: true,
    state: {
        items: {},
        singleUser: {}
    },
    getters: {
        activeSingleUser(state) {
            return state.singleUser;
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
        }
    }
}