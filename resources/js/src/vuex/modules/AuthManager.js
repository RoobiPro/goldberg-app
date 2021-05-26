import AuthAPI from '@/vuex/API/AuthAPI'
import Notifications from '@/vuex/modules/NotificationsManager'

// We need to remove Axios here


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
    });
      return AuthAPI.login(credentials).then((response) => {
        commit('SET_AUTHENTICATED', true)
        commit('SET_USER', response.data.user)
      }).catch(() => {
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)
        this.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: 'Invalid login credentials!'});


      })


  },
   signOut ({ commit }) {
     return AuthAPI.logout().then((response) => {
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)
      })
    },

    update({commit, dispatch}, params) {
      return AuthAPI.update(params)
        .then((response) => {
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
        if(response.data.success==true){
          commit('SET_AUTHENTICATED', true)
          commit('SET_USER', response.data.user)
        }
        else{
          commit('SET_AUTHENTICATED', false)
          commit('SET_USER', null)
        }

      }).catch(() => {
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
