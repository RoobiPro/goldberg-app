import service from '@/vuex/services/users-service';
import alerts from '@/vuex/modules/alerts-module';

const state = {
  list: {},
  user: {},
  meta: {}
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
  }
};

const actions = {
  list({commit, dispatch}, params) {
    // console.log(params);
    // console.log(params2);

    return service.list(params)
      .then(({list, meta}) => {
        commit('SET_LIST', list);
        commit('SET_META', meta);
      });
  },

  get({commit, dispatch}, params) {
    return service.get(params)
      .then((user) => { commit('SET_RESOURCE', user); });
  },

  add({commit, dispatch}, params) {
    console.log(params);
    return service.add(params)
      .then((user) => { commit('SET_RESOURCE', user); });
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
  meta: state => state.meta

};

const users = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default users;
