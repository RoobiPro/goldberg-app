// =========================================================
// Project Goldberg
// =========================================================
//
//  By Rob & Schahin  @03/2021-05/2021
//
// =========================================================


// ------------- should be in Bootstrap---------------
//bootstrap the most important configs/axios
import axios from 'axios'
// require('./bootstrap.default.js');



import Vue from 'vue'
import App from './views/App.vue'
import router from './vue-router/router'
import store from './vuex/store'
// "@babel/core": "^7.9.0",

// ------------- Sorting ---------------
import './plugins/base'
import './plugins/chartist'
import vuetify from './plugins/vuetify'
import i18n from './vue-i18n/i18n'
import 'leaflet/dist/leaflet.css';
// import 'bootstrap/dist/js/bootstrap'

import 'material-design-icons-iconfont/dist/material-design-icons.css'
// import 'material-design-icons-iconfont';

// import './vendors/base' -> chartist, vuetify, i18n, material-design, leaflet

// ------------- Sorting ---------------



// ------------- should be in Bootstrap---------------
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

// ------------- should be in Bootstrap---------------


//------------- should be in Vendors---------------
import L from 'leaflet';
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png').default,
  iconUrl: require('leaflet/dist/images/marker-icon.png').default,
  shadowUrl: require('leaflet/dist/images/marker-shadow.png').default,
});

//------------- should be in Vendors---------------


Vue.use(vuetify);

const app = new Vue({
    router,
    store,
    vuetify,
    i18n,
    render: h => h(App),
  }).$mount('#app')
