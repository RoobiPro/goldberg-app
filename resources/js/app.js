require('./bootstrap');

// require('alpinejs');

import Vue from 'vue'
import App from './App.vue'
import router from './router'
// import vuetify from './plugins/vuetify'
import Vuetify from 'vuetify'
import './plugins'
import store from './store'
import { sync } from 'vuex-router-sync'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

Vue.config.productionTip = false

sync(store, router)

// Vue.use(vuetify)

// export const app = new Vue({
//   // el: '#app',
//   router,
//   vuetify: new Vuetify(),
//   store,
//   render: h => h(App),
// }).$mount('#app')

Vue.use(Vuetify);

new Vue({
  el: '#app',
  router,
  vuetify: new Vuetify(),
  store,
  components: { App},
  template: "<App/>"
})

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
