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
            if (typeof state.items[0].data !="undefined") {
                return state.items[0];
            } else {
                return {};
            }
        },
        activeTotalTime(state) {
            if (state.items[1]) {
                return state.items[1][0].time_summation;
            } else {
                return null;
            }
        }
    }
};
