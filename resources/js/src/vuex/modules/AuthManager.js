import AuthAPI from '@/vuex/API/AuthAPI'
import Notifications from '@/vuex/modules/NotificationsManager'

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
  async signIn({ commit }, credentials) {
      try {
          const csrfResponse = await AuthAPI.csrfToken();
          console.log('CSRF token set:', csrfResponse);

          const loginResponse = await AuthAPI.login(credentials);
          console.log('Login response:', loginResponse);

          if (loginResponse.data && loginResponse.data.user) {
              commit('SET_AUTHENTICATED', true);
              commit('SET_USER', loginResponse.data.user);
          } else {
              commit('SET_AUTHENTICATED', false);
              commit('SET_USER', null);
              this.dispatch('NotificationsManager/setNotificationStatus', { type: 'red', text: 'Invalid login credentials!' });
          }
      } catch (error) {
          console.error('Login error:', error);
          commit('SET_AUTHENTICATED', false);
          commit('SET_USER', null);
          this.dispatch('NotificationsManager/setNotificationStatus', { type: 'red', text: 'Invalid login credentials!' });
      }
  },

  async signOut({ commit }) {
      try {
          const response = await AuthAPI.logout();
          console.log('Logout response:', response);
          commit('SET_AUTHENTICATED', false);
          commit('SET_USER', null);
      } catch (error) {
          console.error('Logout error:', error);
      }
  },

  async update({ commit, dispatch }, params) {
      try {
          const response = await AuthAPI.update(params);
          console.log('Update response:', response);

          if (response.data.success) {
              commit('SET_USER', response.data.user);
              this.dispatch('NotificationsManager/setNotificationStatus', { type: 'green', text: response.data.msg });
          } else {
              this.dispatch('NotificationsManager/setNotificationStatus', { type: 'red', text: response.data.msg });
          }
      } catch (error) {
          console.error('Update error:', error);
      }
  },

  async refresh({ commit }) {
      try {
          const response = await AuthAPI.getAuthStatus();
          console.log('Auth status response:', response);

          if (response.data.success) {
              commit('SET_AUTHENTICATED', true);
              commit('SET_USER', response.data.user);
          } else {
              commit('SET_AUTHENTICATED', false);
              commit('SET_USER', null);
          }
      } catch (error) {
          console.error('Auth status error:', error);
          commit('SET_AUTHENTICATED', false);
          commit('SET_USER', null);
      }
  }
};

export default {
namespaced: true,
state,
getters,
mutations,
actions
};