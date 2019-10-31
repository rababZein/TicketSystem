import Vue from 'vue';
import Vuex from 'vuex';
import project from './modules/project/index';
import ticket from './modules/ticket/index';
import owner from './modules/owner/index';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    modules: {
        project,
        ticket,
        owner
    },
    strict: debug,
})