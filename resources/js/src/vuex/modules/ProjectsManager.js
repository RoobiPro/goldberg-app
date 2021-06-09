import APIService from '@/vuex/API/ProjectsAPI';
import Notifications from '@/vuex/modules/NotificationsManager';


const state = {
  spatials: {},
  projects: {},
  filteredusers: {},
  project: {},
};

const getters = {
  spatials: state => state.spatials,
  projects: state => state.projects,
  project: state => state.project,
  filteredusers: state => state.filteredusers

};

const mutations = {
  SET_SPATIALS: (state, spatials) => {
    state.spatials = spatials;
  },
  SET_PROJECTS: (state, projects) => {
    state.projects = projects;
  },
  SET_FILTEREDUSERS: (state, filteredusers) => {
    state.filteredusers = filteredusers;
  },
  SET_PROJECT: (state, project) => {
    state.project = project;
  },

};


const actions = {
  unassignUser({commit, dispatch}, project){
    return APIService.unassignUser(project)
      .then( (message) => {
        this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  reassignUser({commit, dispatch}, project){
    return APIService.reassignUser(project)
      .then( (message) => {
        this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  assignClient({commit, dispatch}, project){
    return APIService.assignClient(project)
    .then((message) => {
      this.dispatch('NotificationsManager/setNotificationStatus', message); });
  },

  assignUser({commit, dispatch}, project){
    return APIService.assignUser(project)
    .then((message) => {
      this.dispatch('NotificationsManager/setNotificationStatus', message);
    });
  },

  filterUsers({commit, dispatch}, params) {
    return APIService.projectusers(params)
      .then((project) => { commit('SET_FILTEREDUSERS', project); });
  },

  selectedProject({commit, dispatch}, params) {
    commit('SET_PROJECT', params);
  },

  getSpatials({commit, dispatch}, params) {
    return APIService.projectspatials(params)
      .then((spatials) => {
        commit('SET_SPATIALS', spatials);
      });
  },


  getAll({commit, dispatch}, params) {
    return APIService.getAll(params)
      .then((projects) => {
        commit('SET_PROJECTS', projects);
      });
  },

  show({commit, dispatch}, params) {
    return APIService.show(params)
      .then((project) => { commit('SET_PROJECT', project); });
  },

  create({commit, dispatch}, project) {
    return APIService.create(project)
      .then((message) => {
        this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  update({commit, dispatch}, project) {
    return APIService.update(project)
      .then((message) => {
        this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  destroy({commit, dispatch}, project) {
    return APIService.destroy(project)
      .then((message) => {
        this.dispatch('NotificationsManager/setNotificationStatus', message);
    });
  }
};



const projects = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default projects;
