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
            console.log("Starting signIn process...");

            // Get CSRF token
            console.log("Requesting CSRF token...");
            const csrfResponse = await AuthAPI.csrfToken();
            console.log("CSRF token response:", csrfResponse);

            // Attempt login with credentials
            console.log("Attempting login with credentials:", credentials);
            const loginResponse = await AuthAPI.login(credentials);
            console.log("Login response:", loginResponse);

            // Check if login was successful
            // if (loginResponse.data && loginResponse.data.user) {
            //     console.log("Login successful. User data:", loginResponse.data.user);
            //     commit('SET_AUTHENTICATED', true);
            //     commit('SET_USER', loginResponse.data.user);
            // }
            
            // Check if login was successful
            if (loginResponse.data && loginResponse.data.user) {
                console.log("Login response: ", loginResponse)
                console.log("Login successful. User data:", loginResponse.data.user);
                commit('SET_AUTHENTICATED', true);
                commit('SET_USER', loginResponse.data.user);
                
                // Set the session string as a cookie
                if (loginResponse.data.session_string) {
                    const cookieName = "goldberg_session";
                    const cookieValue = loginResponse.data.session_string;
                    const cookieExpirationDays = 7; // Set cookie to expire in 7 days
                    const date = new Date();
                    date.setTime(date.getTime() + (cookieExpirationDays * 24 * 60 * 60 * 1000));
                    const expires = "expires=" + date.toUTCString();
                    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
                    console.log("Session string set as cookie:", document.cookie);
                }
            }

            else {
                console.log("Login failed. Invalid credentials.");
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
            console.log('Calling AuthAPI.getAuthStatus...');
            const response = await AuthAPI.getAuthStatus();
            console.log('REFRESH response:', response);
            if (response.data.success) {
                console.log('Authentication successful. Setting authenticated state and user.');
                commit('SET_AUTHENTICATED', true);
                commit('SET_USER', response.data.user);
            } else {
                console.log('Authentication failed. Setting authenticated state to false and user to null.');
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