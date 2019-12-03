import Vue from 'vue';
import Vuex from 'vuex';
import project from './modules/project/index';
import ticket from './modules/ticket/index';
import task from './modules/task/index';
import regularUser from './modules/regularUser/index';
import owner from './modules/owner/index';
import user from './modules/user/index';
import activity from './modules/activity/index';
import comment from './modules/comment/index';
import track from './modules/track/index';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    modules: {
        project,
        ticket,
        task,
        regularUser,
        owner,
        user,
        activity,
        comment,
        track
    },
    strict: debug,
})