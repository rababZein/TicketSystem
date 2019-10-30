export default {
    activeTickets(state) {
        return state.items;
    },
    ticketsOwners(state) {
        return state.owners;
    },
    projectByOwners(state) {
        return state.projects;
    },
    activeTicket(state) {
        return state.singleTicket;
    },
}