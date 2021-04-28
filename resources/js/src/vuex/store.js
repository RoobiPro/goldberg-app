import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";
import auth from './auth'
import hs from './hs'
// import Cookies from 'js-cookie';

Vue.use(Vuex)



// Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,
    hs
  },
  // plugins: [createPersistedState({
  //   storage: {
  //     getItem: key => Cookies.get(key),
  //     setItem: (key, value) => Cookies.set(key, value, { expires: 3, secure: true }),
  //     removeItem: key => Cookies.remove(key)
  //   }
  // })],
});

export default store
