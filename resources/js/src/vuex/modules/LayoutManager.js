// Theme JS  for Interacting with the Layout/UI
//
// ####################################
// By Rob & Schahin
// #Tim du bistn HRSN
// Peilst du?
// ####################################
//
//
import Notifications from '@/vuex/modules/NotificationsManager';

const state = {
  barColor: 'rgba(0, 0, 0, .8), rgba(0, 0, 0, .8)',
  showBarImage: !localStorage.getItem("showBarImage") ? false : (localStorage.getItem("showBarImage") == 'true' ? true : false),
  barImage: !localStorage.getItem("showBarImage") ? '' : (localStorage.getItem("showBarImage") == 'true' ? localStorage.getItem("barImage") : ''),
  drawer: null,
  dark: null
};


const getters = {
  drawer: (state) => { return state.drawer  }
};


const mutations = {
  SET_BAR_IMAGE (state, payload) {
    console.log(payload)
    if(state.showBarImage == false){
      localStorage.setItem('barImage', '')
      state.barImage = ''
    }
    else{
      localStorage.setItem('barImage', payload)
      state.barImage = payload
    }

  },
  SET_DRAWER (state, payload) {
    state.drawer = payload
  },
  SET_SHOWBARIMAGE(state,payload){

    if(payload){
      localStorage.setItem("showBarImage", 'true')
      state.showBarImage = payload
    }
    else{
      localStorage.setItem("showBarImage", 'false')
      state.showBarImage = payload
    }


  }
};

const actions = {
  setDrawer({commit, dispatch}, params) {
      commit('SET_DRAWER', params);
  },
};



const layout = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default layout
