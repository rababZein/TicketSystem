export default {
    setTickets(state, tickets) {
        state.items = Object.assign({}, tickets);
    },
    setTicketsOwners(state, owners) {
        state.owners = _.map(owners, function (key, value) {
            return { id: key.id, name: key.name };
        });;
    },
    setProjectByOwners(state, projects) {
        state.projects = _.map(projects, function (key, value) {
            return { id: key.id, name: key.name, owner: key.owner };
        });;
    },
    deleteTicket(state, ticket) {
        state.items.data = state.items.data.filter(items => items.id != ticket.id);
    },
    editTicket(state, ticket) {
        const ticketObj = state.items.data.find(items => items.id == ticket.id);
        Object.assign(ticketObj, ticket);
    },
    createTicket(state, ticket) {
        const ticketObj = {
            id: ticket.id,
            name: ticket.name,
            description: ticket.description,
            project: ticket.project,
            read: ticket.read,
            create_at: ticket.created_at,
            updated_at: ticket.updated_at
        };
        state.items.data.unshift(ticketObj);
    },
    getTicketById(state, ticket) {
        state.singleTicket = ticket;
    }
}