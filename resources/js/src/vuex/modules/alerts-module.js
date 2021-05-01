import Swal from "sweetalert2";

const state = {};

const mutations = {};

const actions = {
  // success({commit, dispatch}, message) {
  //   this.$app.$notify({
  //     timeout: 2500,
  //     message: message,
  //     horizontalAlign: "right",
  //     verticalAlign: "top",
  //     icon: "add_alert",
  //     type: "success"
  //   });
  success(){
    Swal.fire({
      title: 'Error!',
      text: 'Do you want to continue',
      icon: 'error',
      confirmButtonText: 'Cool'
    })
  },

  error({commit, dispatch}, message) {
    this.$app.$notify({
      timeout: 2500,
      message: message,
      horizontalAlign: "right",
      verticalAlign: "top",
      icon: "add_alert",
      type: "warning"
    });
  }
};

const getters = {};

const alerts = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default alerts;
