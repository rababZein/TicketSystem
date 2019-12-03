import tracks from '../../../api/tracks';

export default {
    namespaced: true,

    state: {
        items: {}
    },
    actions: {
        reporting({ commit }, data) {
            return new Promise((resolve, reject) => {
                tracks.timeReporting(data)
                    .then(response => {
                        commit("setNewTimeReport", response.data.data);
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        }
    },
    mutations: {
        setNewTimeReport(state, data) {
            state.items = Object.assign({}, data);
        }
    },
    getters: {
        activeTimeReport(state) {
            return state.items;
        }
    }
};
