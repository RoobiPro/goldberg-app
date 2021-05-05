import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth'
import hs from './hs'

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
  state: {
    barColor: 'rgba(0, 0, 0, .8), rgba(0, 0, 0, .8)',
    barImage: 'https://demos.creative-tim.com/material-dashboard/assets/img/sidebar-1.jpg',
    drawer: null,
  },
  mutations: {
    SET_BAR_IMAGE (state, payload) {
      state.barImage = payload
    },
    SET_DRAWER (state, payload) {
      state.drawer = payload
    },
  },
});

export default store
