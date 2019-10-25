export default {
    setProjects(state, projects) {
        state.items = projects;
    },
    createProject(state, project) {
        const projectObj = {
            id: project.id,
            name: project.name,
            description: project.description,
            // TODO:owner didn't return after create project
            owner: project.owner_id,
            task_rate: project.task_rate,
            budget_hours: project.budget_hours
        };
        
        state.Projects.push(projectObj);
    },
    editProject(state, project) {
        const projectObj = state.Projects.find(Projects => Projects.id == project.id);
        Vue.set(projectObj, 'name', project.name);
    },
    deleteProject(state, project) {
        state.Projects = state.Projects.filter(Projects => Projects.id != project.id);
    },
    setOwners(state, owners) {
        state.owners = _.map(owners, function (key, value) {
            return { id: key.id, name: key.name };
        });;
    }
}