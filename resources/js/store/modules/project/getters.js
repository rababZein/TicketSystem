export default {
    activeProjects(state, getters, rootState) {
        return state.items;
    },
    projectsOwners(state) {
        return state.owners;
    },
    activeSingleProject(state) {
        return state.singleProject;
    },
    activeTicketsForProject(state) {
        if (state.singleProject.tickets !== undefined) {
            return Object.assign({}, state.singleProject.tickets);
        } else {
            return {}
        }
    }
}