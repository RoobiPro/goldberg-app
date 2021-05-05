import Vue from 'vue'
import Vuex from 'vuex'

import auth from './modules/auth'
import hs from './modules/hs'
import users from './modules/users-module'
import projects from './modules/projects-module'
import alerts from './modules/alerts-module'

Vue.use(Vuex)


const store = new Vuex.Store({
  modules: {
    auth,
    hs,
    alerts,
    users,
    projects
  },
});

export default store
