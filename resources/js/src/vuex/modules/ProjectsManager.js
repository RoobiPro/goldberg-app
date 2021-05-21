import APIService from '@/vuex/API/ProjectsAPI';
import Notifications from '@/vuex/modules/NotificationsManager';


const state = {
  projects: {},
  filteredusers: {},
  project: {},
};

const getters = {
  projects: state => state.projects,
  project: state => state.project,
  filteredusers: state => state.filteredusers

};

const mutations = {
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
      // console.log(message)
      this.dispatch('NotificationsManager/setNotificationStatus', message); });
  },

  assignUser({commit, dispatch}, project){
    return APIService.assignUser(project)
    .then((message) => {
      // console.log(message)
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

  getAll({commit, dispatch}, params) {
    return APIService.getAll(params)
      .then((projects) => {
        // console.log(projects)
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
        // console.log(message)
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
    // console.log(project)
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
