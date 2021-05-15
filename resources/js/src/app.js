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
import vuetify from './plugins/vuetify'
import i18n from './vue-i18n/i18n'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import 'leaflet/dist/leaflet.css';


if ( process.env.APP_ENV == 'production' ) {
    Vue.config.silent = true;
    Vue.config.productionTip = false;
    Vue.config.devtools = false;
    axios.defaults.baseURL = 'http://phplaravel-524047-1884759.cloudwaysapps.com'
} else {
  axios.defaults.baseURL = ''
}

axios.defaults.withCredentials = true

let api_url = process.env.APP_ENV;
console.log("my env variable:");
console.log(api_url);
console.log(process.env.APP_ENV)

import L from 'leaflet';
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png').default,
  iconUrl: require('leaflet/dist/images/marker-icon.png').default,
  shadowUrl: require('leaflet/dist/images/marker-shadow.png').default,
});

// Vue.config.productionTip = false

Vue.use(vuetify);


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
