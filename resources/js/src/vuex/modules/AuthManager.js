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
      console.log(response);
      
      AuthAPI.login(credentials).then((response) => {
        commit('SET_AUTHENTICATED', true)
        console.log(response.data.user)
        commit('SET_USER', response.data.user)
      }).catch(() => {
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)

        //Sending Notifications from here is possible.
        this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: 'Invalid login credentials!'});

      })
    });

  },
   signOut ({ commit }) {
     axios.post('/logout').then((response) => {
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)
        // this.$router.replace({ path: '/login' })
      })
      // return dispatch('me')
    },

    refresh ({ commit }) {
      return axios.get('/api/refresh').then((response) => {
        commit('SET_AUTHENTICATED', true)
        commit('SET_USER', response.data.user)
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
