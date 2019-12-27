export default {
    setTickets(state, tickets) {
        state.items = Object.assign({}, tickets);
    },


    deleteTicket(state, ticket) {
        if (state.items.data) {
            state.items.data = state.items.data.filter(items => items.id != ticket.id);
        }
    },
    editTicket(state, ticket) {
        if (state.items.data) {
            const ticketObj = state.items.data.find(items => items.id == ticket.id);
            Object.assign(ticketObj, ticket);
        }
    },
    createTicket(state, ticket) {
        const ticketObj = {
            id: ticket.id,
            number: ticket.number,
            status: {name: ticket.status.name},
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
    },
    setStatus(state, status) {
        state.status = _.map(status, function (key) {
            return { id: key.id, name: key.name };
        });
    },
}