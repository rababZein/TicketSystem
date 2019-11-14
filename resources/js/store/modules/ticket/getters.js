export default {
    activeTickets(state) {
        return state.items;
    },
    ticketsOwners(state) {
        return state.owners;
    },
    activeTicket(state) {
        return state.singleTicket;
    }
}