import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const routes = [
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/views/Login'),
      props: true,
      meta: {
        guest: true
      },
    },
    {
      path: '/',
      component: () => import('@/views/dashboard/Index'),
      props: true,
      meta: {
        requiresAuth: true
      },
      children: [
        // Dashboard
        {
          name: 'Dashboard',
          path: '/',
          component: () => import('@/views/dashboard/Dashboard'),
        },
        // Pages
        {
          name: 'User Profile',
          path: 'pages/user',
          component: () => import('@/views/dashboard/pages/UserProfile'),
        },
        {
          name: 'Notifications',
          path: 'components/notifications',
          component: () => import('@/views/dashboard/component/Notifications'),
        },
        {
          name: 'Icons',
          path: 'components/icons',
          component: () => import('@/views/dashboard/component/Icons'),
        },
        {
          name: 'Typography',
          path: 'components/typography',
          component: () => import('@/views/dashboard/component/Typography'),
        },
        // Tables
        {
          name: 'Regular Tables',
          path: 'tables/regular-tables',
          component: () => import('@/views/dashboard/tables/RegularTables'),
        },
        // Maps
        {
          name: 'Google Maps',
          path: 'maps/google-maps',
          component: () => import('@/views/dashboard/maps/GoogleMaps'),
        },
        // Upgrade
        {
          name: 'Upgrade',
          path: 'upgrade',
          component: () => import('@/views/dashboard/Upgrade'),
        },
      ],
    },
  ]
const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})
//
// router.beforeEach((to, from, next) => {
//   if (to.matched.some(record => record.meta.requiresAuth)) {
//       // var token = store.getters.loggedIn;
//       // const loggedIn = localStorage.getItem('user')
//       var loggedIn = localStorage.getItem('access_token')
//       //     // this route requires auth, check if logged in
//       //     // if not, redirect to login page.
//       if (!loggedIn ) {
//         next({
//           path: '/login',
//         })
//       } else {
//         next()
//       }
//     }else if (to.matched.some(record => record.meta.guest)) {
//       var loggedIn = localStorage.getItem('access_token')
//       if (loggedIn) {
//         next({
//           path: '/dashboard',
//           // query: { redirect: to.fullPath }
//         })
//       } else {
//         next()
//       }
//     }
// })



export default router
