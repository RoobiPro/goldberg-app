import Vue from 'vue'
import Vuex from 'vuex'

import AuthManager from './modules/AuthManager'
import LayoutManager from './modules/LayoutManager'
import UsersManager from './modules/UsersManager'
import ProjectsManager from './modules/ProjectsManager'
import CampaignManager from './modules/CampaignManager'
import NotificationsManager from './modules/NotificationsManager'


Vue.use(Vuex)


const store = new Vuex.Store({
  modules: {
    AuthManager,
    LayoutManager,
    UsersManager,
    ProjectsManager,
    CampaignManager,
    NotificationsManager
  },
});

export default store
