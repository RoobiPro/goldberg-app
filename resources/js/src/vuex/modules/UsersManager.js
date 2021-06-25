import Notifications from '@/vuex/modules/NotificationsManager';
import APIService from '@/vuex/API/UsersAPI';

const state = {
  sessions: {},
  users: {},
  user: {},
  projects:{},
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
  SET_USERS: (state, users) => {
    state.users = users;
  },
  SET_RESOURCE: (state, user) => {
    state.user = user;
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
  },
  SET_USERSESSIONS: (state, sessions) => {
      state.sessions = sessions
    }
};



const actions = {
  deleteusersessions({commit, dispatch}, params) {
    return APIService.deleteUserSessions(params)
      .then((response) => {
        this.dispatch('NotificationsManager/setNotificationStatus', {type: response.type, text: response.message});
      });
  },
  deleteallsessions({commit, dispatch}) {
    return APIService.deleteAllSessions()
    .then((response) => {
        this.dispatch('NotificationsManager/setNotificationStatus', {type: response.type, text: response.message});
       });
  },
  usersessions({commit, dispatch}, params) {
      return APIService.getUserSessions(params)
        .then((sessions) => {
          commit('SET_USERSESSIONS', sessions);
          // this.dispatch('NotificationsManager/setNotificationStatus', message);
        });
  },

  clientprojects({commit, dispatch}, params) {
    return APIService.getClientProjects(params)
      .then((project) => {
        commit('SET_USERPROJECTS', project);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  userprojects({commit, dispatch}, params) {
    return APIService.getUserProjects(params)
      .then((project) => {
        commit('SET_USERPROJECTS', project);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  getAll({commit, dispatch}, params) {
    return APIService.getAll(params)
      .then((users) => {
        commit('SET_USERS', users);
      });
  },

  getusers({commit, dispatch}, params) {
    return APIService.listUsers(params)
      .then((list) => {
        commit('SET_USERSROLE', list);
      });
  },

  getclients({commit, dispatch}, params) {
    return APIService.listClients(params)
      .then((list) => {
        commit('SET_CLIENTSROLE', list);
      });
  },

  get({commit, dispatch}, params) {
    return APIService.get(params)
      .then((user) => { commit('SET_RESOURCE', user); });
  },

  add({commit, dispatch}, user) {
    return APIService.add(user)
    .then((response) => {
        this.dispatch('NotificationsManager/setNotificationStatus', {type: response.type, text: response.msg});
       });
  },

  update({commit, dispatch}, params) {
    return APIService.update(params)
      .then((response) => {
        commit('SET_RESOURCE', response.user);
        if(response.data.success){
          this.dispatch('NotificationsManager/setNotificationStatus', {type: 'green', text: response.data.msg});
        }
        else{
          this.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: response.data.msg});
        }
       });
  },

  destroy({commit, dispatch}, params) {
    return APIService.destroy(params)
        .then((response) => {
            this.dispatch('NotificationsManager/setNotificationStatus', {type: response.type, text: response.msg});
           });
  }

};

const getters = {
  users: state => state.users,
  user: state => state.user,
  response: state => state.response,
  users_role: state => state.users_role,
  clients_role: state => state.clients_role,
  admins_role: state => state.admins_role,
  projects: state => state.projects,
  sessions: state => state.sessions

};

const users = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default users;
