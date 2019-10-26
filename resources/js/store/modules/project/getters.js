export default {
    activeProjects(state, getters, rootState) {
        return state.items;
    },
    projectsOwners(state) {
        return state.owners;
    }
}