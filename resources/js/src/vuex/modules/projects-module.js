import service from '@/vuex/services/projects-service';

const state = {
  list: {},
  project: {},
  meta: {}
};

const mutations = {
  SET_LIST: (state, list) => {
    state.list = list;
  },
  SET_RESOURCE: (state, project) => {
    state.project = project;
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
      .then((project) => { commit('SET_RESOURCE', project); });
  },

  add({commit, dispatch}, params) {
    return service.add(params)
      .then((project) => { commit('SET_RESOURCE', project); });
  },

  update({commit, dispatch}, params) {
    return service.update(params)
      .then((project) => { commit('SET_RESOURCE', project); });
  },

  destroy({commit, dispatch}, params) {
    return service.destroy(params);
  }
};

const getters = {
  list: state => state.list,
  listTotal: state => state.meta.page.total,
  project: state => state.project,
  meta: state => state.meta

};

const projects = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default projects;
