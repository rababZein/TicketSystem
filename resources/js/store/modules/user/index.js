import users from '../../../api/users';

export default {
    state: {
        users: {},
    },
    // getters: {
    //     activeOwners(state) {
    //         return state.users;
    //     }
    // },
    // actions: {
    //     getOwners({ commit }){
    //         return new Promise((resolve, reject) => {
    //             users.get().then(response => {
    //                 commit('setOwners', response.data.data)
    //                 resolve(response);
    //             })
    //             .catch(error => {
    //                 reject(error);
    //             })
    //         });
    //     }
    // },
    // mutations: {
    //     setOwners() {
    //         state.users = _.map(owners, function (key, value) {
    //             return { id: key.id, name: key.name };
    //         });;
    //     }
    // }
}