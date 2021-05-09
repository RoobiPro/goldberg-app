// Creative Tim der HRSN
const state = {
  barColor: 'rgba(0, 0, 0, .8), rgba(0, 0, 0, .8)',
  barImage: localStorage.getItem("barImage"),
  drawer: null,
  dark: null
};

const mutations = {
  SET_BAR_IMAGE (state, payload) {
    localStorage.setItem('barImage', payload)
    state.barImage = payload
  },
  SET_DRAWER (state, payload) {
    state.drawer = payload
  },
};

const actions = {
  setDrawer({commit, dispatch}, params) {
      commit('SET_DRAWER', params);
  },
};

const getters = {


};

const hs = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default hs
