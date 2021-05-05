// =========================================================
// * Vuetify Material Dashboard - v2.1.0
// =========================================================
//
// * Product Page: https://www.creative-tim.com/product/vuetify-material-dashboard
// * Copyright 2019 Creative Tim (https://www.creative-tim.com)
//
// * Coded by Creative Tim
//
// =========================================================
//
// * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
// require('./bootstrap');
import axios from 'axios'
import Vue from 'vue'
import App from './views/App.vue'
import router from './vue-router/router'
import store from './vuex/store'
import './plugins/base'
import './plugins/chartist'
// import './plugins/vee-validate'
import vuetify from './plugins/vuetify'
import i18n from './vue-i18n/i18n'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

if ( process.env.NODE_ENV == 'production' ) {
    Vue.config.silent = true;
    Vue.config.productionTip = false;
    Vue.config.devtools = false;
} else {
}

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://goldberg.local/'

Vue.config.productionTip = false

Vue.use(vuetify);

store.dispatch('auth/refresh').then(() => {
  new Vue({
    // components: {
    //   ValidationProvider
    // },
    router,
    store,
    vuetify,
    i18n,
    render: h => h(App),
  }).$mount('#app')
})
//
// new Vue({
//   el: '#app',
//   router,
//   vuetify: new Vuetify(),
//   store,
//   components: { App},
//   template: "<App/>"
// })

// .$mount('#app')

  // render: h => h(App),
// components: { App },
// template: '<App/>'
// const app = new Vue({
//     el: '#app',
//     router,
//     store,
//     components: { AdminApp },
//     template: '<AdminApp/>'
// })

// antialiased''
