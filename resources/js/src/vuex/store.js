import Vue from 'vue'
import Vuex from 'vuex'

import AuthManager from './modules/AuthManager'
import LayoutManager from './modules/LayoutManager'
import UsersManager from './modules/UsersManager'
import ProjectsManager from './modules/ProjectsManager'
import CampaignManager from './modules/CampaignManager'
import TableManager from './modules/TableManager'
import ImportManager from './modules/ImportManager'
import NotificationsManager from './modules/NotificationsManager'


Vue.use(Vuex)


const store = new Vuex.Store({
  modules: {
    AuthManager,
    LayoutManager,
    UsersManager,
    ProjectsManager,
    CampaignManager,
    TableManager,
    ImportManager,
    NotificationsManager
  },
});

export default store
