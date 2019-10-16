import Vue from 'vue';
import Vuex from 'vuex';
import project from './modules/project/index';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    modules: {
        project,
    },
    strict: debug,
})