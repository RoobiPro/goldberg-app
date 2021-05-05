import service from '@/vuex/api/projects-service';

const state = {
  list: {},
  filteredusers: {},
  project: {},
};

const mutations = {
  SET_LIST: (state, list) => {
    state.list = list;
  },
  SET_FILTEREDUSERS: (state, filteredusers) => {
    state.filteredusers = filteredusers;
  },
  SET_PROJECT: (state, project) => {
    state.project = project;
  },

};

const actions = {
  filterUsers({commit, dispatch}, params) {
    return service.projectusers(params)
      .then((project) => { commit('SET_FILTEREDUSERS', project); });
  },

  getAll({commit, dispatch}, params) {
    return service.getAll(params)
      .then(({list, meta}) => {
        commit('SET_LIST', list);
      });
  },

  show({commit, dispatch}, params) {
    return service.get(params)
      .then((project) => { commit('SET_PROJECT', project); });
  },

  create({commit, dispatch}, params) {
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
  project: state => state.project,
  filteredusers: state => state.filteredusers

};

const projects = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default projects;
