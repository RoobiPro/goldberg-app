import APIService from '@/vuex/API/ProjectsAPI';
import Notifications from '@/vuex/modules/NotificationsManager';


const state = {
  spatials: {},
  projects: {},
  filteredusers: {},
  project: {},
  projectdata:{},
  projectdrillings:{},
  projecthandsamples:{},
  projectwells:{},
  projectdrillingsamplelist:{},
  projecthandsamplesamplelist:{}
};

const getters = {
  spatials: state => state.spatials,
  projects: state => state.projects,
  project: state => state.project,
  filteredusers: state => state.filteredusers,
  projectdata: state => state.projectdata,
  projectdrillings: state => state.projectdrillings,
  projecthandsamples: state => state.projecthandsamples,
  projectwells: state => state.projectwells,
  projectdrillingsamplelist: state => state.projectdrillingsamplelist,
  projectwellsamplelist: state => state.projectwellsamplelist,
  projecthandsamplesamplelist: state => state.projecthandsamplesamplelist
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
  SET_PROJECTHANDSAMPLES: (state, projecthandsamples) => {
    state.projecthandsamples = projecthandsamples;
  },
  SET_PROJECTWELLS: (state, projectwells) => {
    state.projectwells = projectwells;
  },
  SET_PROJECTDRILLINGSAMPLELIST: (state, projectdrillingsamplelist) => {
    state.projectdrillingsamplelist = projectdrillingsamplelist;
  },
  SET_PROJECTWELLSAMPLELIST: (state, projectwellsamplelist) => {
    state.projectwellsamplelist = projectwellsamplelist;
  },
  SET_PROJECTHANDSAMPLESAMPLELIST: (state, projecthandsamplesamplelist) => {
    state.projecthandsamplesamplelist = projecthandsamplesamplelist;
  },

};


const actions = {
  getProjectHandSampleSampleList({commit, dispatch}, params) {
    return APIService.handsamplesamples(params)
      .then((projectwellsamplelist) => {
        commit('SET_PROJECTHANDSAMPLESAMPLELIST', projectwellsamplelist);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  getProjectWellSampleList({commit, dispatch}, params) {
    return APIService.drillingsamples(params)
      .then((projectwellsamplelist) => {
        commit('SET_PROJECTWELLSAMPLELIST', projectwellsamplelist);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  getProjectDrillingSampleList({commit, dispatch}, params) {
    return APIService.drillingsamples(params)
      .then((projectdrillingsamplelist) => {
        commit('SET_PROJECTDRILLINGSAMPLELIST', projectdrillingsamplelist);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  getProjectWells({commit, dispatch}, params) {
    return APIService.getprojectwells(params)
      .then((projectwells) => {
        commit('SET_PROJECTWELLS', projectwells);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  getProjectHandsamples({commit, dispatch}, params) {
    return APIService.getprojecthandsamples(params)
      .then((projecthandsamples) => {
        commit('SET_PROJECTHANDSAMPLES', projecthandsamples);
        // this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  getProjectDrillings({commit, dispatch}, params) {
    return APIService.getprojectdrillings(params)
      .then((projectdrillings) => {
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
