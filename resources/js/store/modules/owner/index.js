import users from '../../../api/users';

export default {
    namespaced: true,
    state: {
        items: [],
    },
    getters: {
        activeOwners(state) {
            return state.items;
        }
    },
    actions: {
        getOwners({ commit }) {
            return new Promise((resolve, reject) => {
                users.get().then(response => {
                    commit('setOwners', response.data.data)
                    resolve(response);
                })
                    .catch(error => {
                        reject(error);
                    })
            });
        }
    },
    mutations: {
        setOwners(state, owners) {
            state.items = _.map(owners, function (key) {
                return { id: key.id, name: key.name };
            });;
        }
    }
}