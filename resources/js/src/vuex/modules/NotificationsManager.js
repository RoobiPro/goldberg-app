// import Swal from "sweetalert2";

const state = {
  showNotification: {
    status: false,
    type: '',
    text: ''
  }
};

const mutations = {
  SET_NOTIFICATIONSTATUS: (state, showNotification) => {
    state.showNotification.status = showNotification;
  },
  SET_TYPE: (state, type) => {
    state.showNotification.type = type;
  },
  SET_TEXT: (state, text) => {
    state.showNotification.text = text;
  },
};

const actions = {
  setNotificationStatus({commit, dispatch}, payload) {

    // commit('SET_NOTIFICATIONSTATUS', false)
    commit('SET_NOTIFICATIONSTATUS', true)
    commit('SET_TYPE', payload.type)
    console.log(payload.text)
    commit('SET_TEXT', payload.text)
    setTimeout(() => {  commit('SET_NOTIFICATIONSTATUS', false); }, 3500);
  }
};

const getters = {
  getNotificationStatus: (state) => { return state.showNotification   }
};

const notifications = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default notifications;
