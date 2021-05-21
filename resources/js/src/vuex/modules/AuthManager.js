import AuthAPI from '@/vuex/API/AuthAPI'
import Notifications from '@/vuex/modules/NotificationsManager'

// We need to remove Axios here
import axios from 'axios'


const state =  {
    authenticated: false,
    user: false
}

const getters = {
    authenticated (state) {
      return state.authenticated
    },

    user (state) {
      return state.user
    },

}

// axios.interceptors.response.use(function (response) {
//   return response;
// }, function (error) {
//   return Promise.reject(error);
// });

const mutations = {
    SET_AUTHENTICATED (state, value) {
      state.authenticated = value
    },

    SET_USER (state, value) {
      state.user = value
    },

    SET_USERAVATAR (state, avatar) {
      state.user.avatar = avatar

    }
}

const actions =  {
    signIn ({ commit }, credentials) {
    AuthAPI.csrfToken().then((response) => {
      // console.log(response);
    });
      return AuthAPI.login(credentials).then((response) => {
        commit('SET_AUTHENTICATED', true)
        // console.log(response.data.user)
        commit('SET_USER', response.data.user)
      }).catch(() => {
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)
        // console.log(this.dis)
        //Sending Notifications from here is possible.
        this.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: 'Invalid login credentials!'});


      })


  },
   signOut ({ commit }) {
     return AuthAPI.logout().then((response) => {
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)
        // self.router.push('/login')
        // this.$router.replace({ path: '/login' })
      })
      // return dispatch('me')
    },

    update({commit, dispatch}, params) {
      return AuthAPI.update(params)
        .then((response) => {
          // console.log(response)
          // commit('SET_RESOURCE', response.user);
          if(response.data.success){
            commit('SET_USER', response.data.user);

            this.dispatch('NotificationsManager/setNotificationStatus', {type: 'green', text: response.data.msg});
          }
          else{
            this.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: response.data.msg});
          }
         });
    },

    refresh ({ commit }) {
      return AuthAPI.getAuthStatus().then((response) => {
        // console.log(response)
        if(response.data.success==true){
          commit('SET_AUTHENTICATED', true)
          commit('SET_USER', response.data.user)
        }
        else{
          commit('SET_AUTHENTICATED', false)
          commit('SET_USER', null)
        }

      }).catch(() => {
        // console.log("401 error")
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)
      })
    }

}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
