import Notifications from '@/vuex/modules/NotificationsManager';
import APIService from '@/vuex/API/UsersAPI';

const state = {
  list: {},
  user: {},
  meta: {},
  projects:{},
  response: {},
  users_role: {},
  clients_role: {},
  admins_role: {},
  roleList: [
    'Viewer',
    'Editor',
    'Admin'
  ]
};

const mutations = {
  SET_LIST: (state, list) => {
    state.list = list;
  },
  SET_RESOURCE: (state, user) => {
    state.user = user;
  },
  SET_META: (state, meta) => {
    state.meta = meta;
  },
  SET_RESPONSE: (state, resp) => {
    state.response = resp;
  },
  SET_USERSROLE: (state, usersrole) => {
    state.users_role = usersrole;
  },
  SET_CLIENTSROLE: (state, clientsrole) => {
    state.clients_role = clientsrole;
  },
  SET_ADMINSROLE: (state, adminsrole) => {
    state.admins_role = adminsrole;
  },
  SET_USERPROJECTS: (state, projects) => {
    state.projects = projects
  }
};

const actions = {

  userprojects({commit, dispatch}, params) {
    return APIService.getUserProjects(params)
      .then((project) => {
        commit('SET_USERPROJECTS', project);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  list({commit, dispatch}, params) {
    return APIService.list(params)
      .then(({list, meta}) => {
        commit('SET_LIST', list);
        commit('SET_META', meta);
      });
  },

  getusers({commit, dispatch}, params) {
    return APIService.listUsers(params)
      .then(({list, meta}) => {
        commit('SET_USERSROLE', list);
      });
  },

  getclients({commit, dispatch}, params) {
    return APIService.listClients(params)
      .then(({list, meta}) => {
        commit('SET_CLIENTSROLE', list);
      });
  },

  get({commit, dispatch}, params) {
    return APIService.get(params)
      .then((user) => { commit('SET_RESOURCE', user); });
  },

  add({commit, dispatch}, params) {
    console.log(params);
    return APIService.add(params)
      .then((response) => {
        console.log(response),
        commit('SET_RESPONSE', response);
      })
      .catch(error => {
        if (error.response.status != 200){
          console.log(error);
          commit('SET_RESPONSE', error.response);
        }
      });
  },

  update({commit, dispatch}, params) {
    return APIService.update(params)
      .then((response) => {
        console.log(response)
        commit('SET_RESOURCE', response.user);
        if(response.data.success){
          // this.commit('AuthManager/SET_USER', response.data.user);

          this.dispatch('NotificationsManager/setNotificationStatus', {type: 'green', text: response.data.msg});
        }
        else{
          this.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: response.data.msg});
        }
       });
  },

  destroy({commit, dispatch}, params) {
    return APIService.destroy(params);
  }

};

const getters = {
  list: state => state.list,
  user: state => state.user,
  meta: state => state.meta,
  response: state => state.response,
  users_role: state => state.users_role,
  clients_role: state => state.clients_role,
  admins_role: state => state.admins_role,
  projects: state => state.projects

};

const users = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default users;
