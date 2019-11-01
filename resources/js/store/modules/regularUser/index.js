import users from '../../../api/users';

export default {
    namespaced: true,
    state: {
        items: [],
    },
    getters: {
        activeRegularUser(state) {
            return state.items;
        }
    },
    actions: {
        getRegularUser({ commit }) {
            return new Promise((resolve, reject) => {
                users.getRegularUsers().then(response => {
                    commit('setRegularUsers', response.data.data)
                    resolve(response);
                })
                    .catch(error => {
                        reject(error);
                    })
            });
        }
    },
    mutations: {
        setRegularUsers(state, users) {
            state.items = _.map(users, function (key) {
                return { id: key.id, name: key.name };
            });;
        }
    }
}