import comments from '../../../api/comments';

export default {
    namespaced: true,

    state: {
        items: {},
    },
    actions: {
        createCommentForUser({ commit }, data) {
            return new Promise((resolve, reject) => {
                comments.postForClient(data).then(response => {
                    commit('setComment', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        },
        createCommentForTask({ commit }, data) {
            return new Promise((resolve, reject) => {
                comments.postForTask(data).then(response => {
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
        getCommentsPerTask({commit}, params) {
            return new Promise((resolve, reject) => {
                comments.getPerTask(params).then(response => {
                    commit('setCommentsPerTask', response.data.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            });
        }
    },
    mutations: {
        setCommentsPerClient(state, comments) {
            state.items = comments;
        },
        setCommentsPerTask(state, comments) {
            state.items = comments;
        },
        setComment(state, comment) {
            state.items.data.push(comment);
        },
        
    },
    getters: {
        activeComments(state) {
            return state.items;
        }
    }
}