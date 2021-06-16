import ImportsAPI from '@/vuex/API/ImportsAPI';

const state = {
  imports:[]
};

const getters = {
  imports (state) {
    return state.imports
  }
};

const mutations = {
  SET_IMPORTS (state, value) {
    state.imports = value
  }
};

const actions = {
  get ({commit, dispatch}, params) {
    return ImportsAPI.get(params).then((response) => {
       commit('SET_IMPORTS', response)
     })
   },

   destroy({commit, dispatch}, project) {
     return ImportsAPI.revert(project)
       .then((message) => {
         if(message.success){
           console.log(message)
           this.dispatch('NotificationsManager/setNotificationStatus', message);
           return message
         }
     });
   }

};

const imports = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default imports;
