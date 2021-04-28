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
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './plugins/base'
import './plugins/chartist'
import './plugins/vee-validate'
import vuetify from './plugins/vuetify'
import i18n from './i18n'
// import 'vuetify/dist/vuetifymin.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

Vue.config.productionTip = false

Vue.use(vuetify);

new Vue({
  router,
  store,
  vuetify,
  i18n,
  render: h => h(App),
}).$mount('#app')

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
