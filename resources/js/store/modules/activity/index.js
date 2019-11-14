import activity from '../../../api/activity';

export default {
    namespaced: true,
    state: {
        items: [],
    },
    getters: {
        activityList(state) {
            return state.items;
        }
    },
    actions: {
        getlogActivityListByClientId({ commit }, id) {
            return new Promise((resolve, reject) => {
                activity.get(id).then(response => {
                    commit('setActivities', response.data.data)
                    resolve(response);
                })
                    .catch(error => {
                        reject(error);
                    })
            });
        }
    },
    mutations: {
        setActivities(state, activities) {
            state.items = activities
        }
    }
}