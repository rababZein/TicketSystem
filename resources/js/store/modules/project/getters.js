export default {
    activeProjects(state) {
        return state.items;
    },
    allProjects(state) {
        return state.allProjectList;
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
    },
    projectByOwners(state) {
        return Object.values(state.items);
    }
}