export default {
    setProjects(state, projects) {
        state.items = Object.assign({}, projects);
    },
    setAllProjects(state, projects) {
        state.allProjectList = projects;
    },
    createProject(state, project) {
        const projectObj = {
            id: project.id,
            name: project.name,
            description: project.description,
            project_assign: project.project_assign,
            owner: project.owner,
            task_rate: project.task_rate,
            budget_hours: project.budget_hours
        };
        state.items.data.unshift(projectObj);

    },
    editProject(state, project) {
        if (state.items.data) {
            const projectObj = state.items.data.find(items => items.id == project.id);
            Object.assign(projectObj, project);
        }
    },
    deleteProject(state, project) {
        state.items.data = state.items.data.filter(items => items.id != project.id);
    },
    setProjectsOwners(state, owners) {
        state.owners = _.map(owners, function (key, value) {
            return { id: key.id, name: key.name };
        });;
    },
    setSingleProject(state, project) {
        state.singleProject = project;
    },
    setProjectByOwners(state, projects) {
        state.items = _.map(projects, function (key) {
            return { id: key.id, name: key.name, owner: key.owner, tickets: key.tickets };
        });
    }
}