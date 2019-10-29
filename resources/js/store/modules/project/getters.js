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
    ownerOfProject(state) {
        return state.singleProject.owner;
    },
    activeTicketsForProject(state) {
        if (state.singleProject.tickets !== undefined) {
            return Object.assign({}, state.singleProject.tickets);
        } else {
            return {}
        }
    }
}