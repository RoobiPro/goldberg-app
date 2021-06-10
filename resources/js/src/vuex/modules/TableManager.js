import TableAPI from '@/vuex/API/TableAPI';

const state = {
  headers:[]
};

const getters = {
  headers (state) {
    return state.headers
  }
};

const mutations = {
  SET_HEADERS (state, value) {
    state.headers = value
  }
};

const actions = {
  get ({commit, dispatch}, params) {
    return TableAPI.get(params).then((response) => {
       commit('SET_HEADERS', response)
     })
   },
};

const headers = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default headers;
