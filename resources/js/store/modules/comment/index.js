import comments from '../../../api/comments';

export default {
    namespaced: true,

    state: {
        items: {},
    },
    actions: {
        createComment({ commit }, data) {
            return new Promise((resolve, reject) => {
                comments.post(data).then(response => {
                    commit('setComment', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        getCommentsPerClient({ commit }, id) {
            return new Promise((resolve, reject) => {
                comments.getPerClient(id).then(response => {
                    commit('setCommentsPerClient', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
    },
    mutations: {
        setCommentsPerClient(state, comments) {
            state.items = comments;
        },
        setComment(state, comment) {
            state.items = Object.assign({}, comment);
        },
        
    },
    getters: {
        activeComments(state) {
            return state.items;
        }
    }
}