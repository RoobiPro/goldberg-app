// =========================================================
// Project Goldberg
// =========================================================
//
//  By Rob & Schahin  @03/2021-05/2021
//
// =========================================================

// ------------- should be in Bootstrap---------------
//bootstrap the most important configs/axios

require('./bootstrap.default.js');

import Vue from 'vue'
import App from './views/App.vue'
import router from './vue-router/router'
import store from './vuex/store'

import './vendors/base'
import vuetify from './vendors/vuetify'
Vue.use(vuetify);

if ( process.env.APP_ENV == 'production' ) {
    Vue.config.silent = true;
    Vue.config.productionTip = false;
    Vue.config.devtools = false;
    axios.defaults.baseURL = 'https://app.goldbergresources.com'
} else {
  axios.defaults.baseURL = ''
}

axios.defaults.withCredentials = true

let api_url = process.env.APP_ENV;

const app = new Vue({
    router,
    store,
    vuetify,
    // i18n,
    render: h => h(App),
    created: function () {
      window.addEventListener('beforeunload', function (event) {
        console.log("calling goodbye-function")
        axios.post('/api/goodbye')
        }, false)
    },

  }).$mount('#app')
