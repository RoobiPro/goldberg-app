import axios from 'axios'

export default {
  namespaced: true,

  state: {
    authenticated: false,
    user: false
  },

  getters: {
    authenticated (state) {
      return state.authenticated
    },

    user (state) {
      return state.user
    },
  },

  mutations: {
    SET_AUTHENTICATED (state, value) {
      state.authenticated = value
    },

    SET_USER (state, value) {
      state.user = value
      console.log(state.user)
    },

    SET_USERAVATAR (state, avatar) {
      console.log(avatar)
      state.user.avatar = avatar

    }
  },

  actions: {
    async signIn ({ commit }, credentials) {
    await axios.get('/sanctum/csrf-cookie')
    console.log('sanctum csrf');
    // await axios.post('/login', credentials)
    await axios.post('/login', credentials).then((response) => {
      commit('SET_AUTHENTICATED', true)
      commit('SET_USER', response.data.user)

      // this.$router.replace({ path: '/dashboard' })
    }).catch(() => {
      commit('SET_AUTHENTICATED', false)
      commit('SET_USER', null)
    })
    },

    async signOut ({ commit }) {
      await axios.post('/logout').then((response) => {
        console.log(response.data)
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)
        axios.get('/api/users').then((response) => {
          console.log(response.data)
        })
      })

      // return dispatch('me')
    },

    refresh ({ commit }) {
      return axios.get('/api/refresh').then((response) => {
        // console.log(response.data)
        commit('SET_AUTHENTICATED', true)
        commit('SET_USER', response.data.user)
      }).catch(() => {
        commit('SET_AUTHENTICATED', false)
        commit('SET_USER', null)
      })
    }
  }
}
