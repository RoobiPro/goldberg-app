import APIService from '@/vuex/API/ProjectsAPI';
import Notifications from '@/vuex/modules/NotificationsManager';


const state = {
  spatials: {},
  projects: {},
  filteredusers: {},
  project: {},
  projectdata:{},
  projectdrillings:{}
};

const getters = {
  spatials: state => state.spatials,
  projects: state => state.projects,
  project: state => state.project,
  filteredusers: state => state.filteredusers,
  projectdata: state => state.projectdata,
  projectdrillings: state => state.projectdrillings
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
  SET_PROJECTDATA: (state, projectdata) => {
    state.projectdata = projectdata;
  },
  SET_PROJECTDRILLINGS: (state, projectdrillings) => {
    state.projectdrillings = projectdrillings;
  },

};


const actions = {
  getProjectDrillings({commit, dispatch}, params) {
    return APIService.getprojectdrillings(params)
      .then((projectdrillings) => {
        console.log(projectdrillings)
        commit('SET_PROJECTDRILLINGS', projectdrillings);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  getProjectData({commit, dispatch}, params) {
    return APIService.getprojectdata(params)
      .then((projectdata) => {
        commit('SET_PROJECTDATA', projectdata);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

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
