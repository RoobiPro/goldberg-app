import service from '@/vuex/api/users-service';
import alerts from '@/vuex/modules/alerts-module';

const state = {
  list: {},
  user: {},
  meta: {},
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
};

const actions = {

  list({commit, dispatch}, params) {
    return service.list(params)
      .then(({list, meta}) => {
        commit('SET_LIST', list);
        commit('SET_META', meta);
      });
  },

  getusers({commit, dispatch}, params) {
    return service.listUsers(params)
      .then(({list, meta}) => {
        commit('SET_USERSROLE', list);
      });
  },

  getclients({commit, dispatch}, params) {
    return service.listClients(params)
      .then(({list, meta}) => {
        commit('SET_CLIENTSROLE', list);
      });
  },

  get({commit, dispatch}, params) {
    return service.get(params)
      .then((user) => { commit('SET_RESOURCE', user); });
  },

  add({commit, dispatch}, params) {
    console.log(params);
    return service.add(params)
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
    return service.update(params)
      .then((user) => { commit('SET_RESOURCE', user); });
  },

  destroy({commit, dispatch}, params) {
    // dispatch('alerts/setNotificationStatus', null, {root:true})
    // alerts.setNotificationStatus();
    return service.destroy(params);
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

};

const users = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default users;
