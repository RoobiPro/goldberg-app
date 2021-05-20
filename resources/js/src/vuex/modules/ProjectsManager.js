import APIService from '@/vuex/API/ProjectsAPI';
import Notifications from '@/vuex/modules/NotificationsManager';


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
    return APIService.projectusers(params)
      .then((project) => { commit('SET_FILTEREDUSERS', project); });
  },

  selectedProject({commit, dispatch}, params) {
    commit('SET_PROJECT', params);
  },

  getAll({commit, dispatch}, params) {
    return APIService.getAll(params)
      .then(({list, meta}) => {
        commit('SET_LIST', list);
      });
  },

  show({commit, dispatch}, params) {
    return APIService.show(params)
      .then((project) => { commit('SET_PROJECT', project); });
  },

  create({commit, dispatch}, params) {
    return APIService.add(params)
      .then((project) => { commit('SET_RESOURCE', project); });
  },

  update({commit, dispatch}, params) {
    return APIService.update(params)
      .then((project) => { commit('SET_RESOURCE', project); });
  },

  destroy({commit, dispatch}, params) {
    return APIService.destroy(params);
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
