import Vue from 'vue'
import Vuex from 'vuex'
// import createPersistedState from "vuex-persistedstate";
import auth from './auth'
import hs from './hs'
// import alerts from './modules/alerts-module';
import usersmod from './modules/users-module'
import projectsmod from './modules/projects-module'

// import Cookies from 'js-cookie';

Vue.use(Vuex)



// Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,
    hs,
    // alerts,
    usersmod,
    projectsmod
  },
  // plugins: [createPersistedState({
  //   storage: {
  //     getItem: key => Cookies.get(key),
  //     setItem: (key, value) => Cookies.set(key, value, { expires: 3, secure: true }),
  //     removeItem: key => Cookies.remove(key)
  //   }
  // })],
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
