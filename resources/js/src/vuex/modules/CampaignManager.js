import APIService from '@/vuex/API/CampaignsAPI';
import Notifications from '@/vuex/modules/NotificationsManager';

const state = {
  campaign: {},
  campaigns: {}
};

const getters = {
  campaigns: state => state.campaigns,
  campaign: state => state.campaign
};

const mutations = {
  SET_CAMPAIGNS: (state, campaigns) => {
    state.campaigns = campaigns;
  },
  SET_CAMPAIGN: (state, campaign) => {
    state.campaign = campaign;
  }
};

const actions = {
  async getcampaign({commit, dispatch}, id) {
    return await APIService.getCampaign(id)
      .then((campaign) => {
        commit('SET_CAMPAIGN', campaign);
      });
  },

async createDrilling({commit, dispatch}, drilling){
    return await APIService.createDrilling(drilling)
      .then( (message) => {
        this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },

  getprojectcamaigns({commit, dispatch}, id) {
    return APIService.getProjectCampaigns(id)
      .then((campaigns) => {
        commit('SET_CAMPAIGNS', campaigns);
      });
  },

  create({commit, dispatch}, campaign){
    return APIService.create(campaign)
      .then( (message) => {
        this.dispatch('NotificationsManager/setNotificationStatus', message);
      });
  },
};

const campaigns = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default campaigns;
