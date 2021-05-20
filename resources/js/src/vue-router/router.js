import Vue from 'vue'
import Router from 'vue-router'
import store from '@/vuex/store'

Vue.use(Router)

const routes = [
    {
      path: '/login',
      name: 'Login',
      component: () => import(/* webpackChunkName: "Login" */'@/views/Login'),
      props: true,
      meta: {
        guest: true
      },
    },
    {
      path: '/',
      redirect: '/dashboard',
      component: () => import(/* webpackChunkName: "index" */'@/views/dashboard/Index'),
      props: true,
      meta: {
        requiresAuth: true
      },
      children: [
        // Dashboard
        {
          name: 'Dashboard',
          path: 'dashboard',
          component: () => import(/* webpackChunkName: "dashboard" */'@/views/dashboard/Dashboard'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        // Pages
        {
          name: 'User Management',
          path: 'management/user',
          component: () => import(/* webpackChunkName: "usermanagement" */'@/views/dashboard/pages/Users'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Project Management',
          path: 'management/project',
          component: () => import(/* webpackChunkName: "projectmanagement" */'@/views/dashboard/pages/Projects'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'User Profile',
          path: '/pages/user',
          component: () => import(/* webpackChunkName: "userprofile" */'@/views/dashboard/pages/UserProfile'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        // {
        //   name: 'Notifications',
        //   path: 'components/notifications',
        //   component: () => import(/* webpackChunkName: "notifications" */'@/views/dashboard/component/Notifications'),
        //   props: true,
        //   meta: {
        //     requiresAuth: true
        //   },
        // },
        // {
        //   name: 'Icons',
        //   path: 'components/icons',
        //   component: () => import(/* webpackChunkName: "icons" */'@/views/dashboard/component/Icons'),
        //   props: true,
        //   meta: {
        //     requiresAuth: true
        //   },
        // },
        // {
        //   name: 'Typography',
        //   path: 'components/typography',
        //   component: () => import(/* webpackChunkName: "typography" */'@/views/dashboard/component/Typography'),
        //   props: true,
        //   meta: {
        //     requiresAuth: true
        //   },
        // },
        // Tables
        {
          name: 'Regular Tables',
          path: 'tables/regular-tables',
          component: () => import(/* webpackChunkName: "regulartables" */'@/views/dashboard/tables/RegularTables'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        // Maps
        {
          name: 'Google Maps',
          path: 'maps/google-maps',
          component: () => import(/* webpackChunkName: "googlemaps" */'@/views/dashboard/maps/GoogleMapsNew'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        // Upgrade
        {
          name: 'Upgrade',
          path: 'upgrade',
          component: () => import(/* webpackChunkName: "upgrade" */'@/views/dashboard/Upgrade'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'My Projects',
          path: 'myprojects',
          component: () => import(/* webpackChunkName: "myprojects" */'@/views/dashboard/pages/projects/ProjectsOverview'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Project',
          path: 'project/:project_id/',
          component: () => import(/* webpackChunkName: "project" */'@/views/dashboard/pages/projects/SingleProject'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Campaigns',
          path: 'project/:id/campaigns',
          component: () => import(/* webpackChunkName: "campaigns" */'@/views/dashboard/pages/projects/tables/Campaigns'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Campaign',
          path: 'project/:project_id/campaign/:campaign_id',
          component: () => import(/* webpackChunkName: "campaign" */'@/views/dashboard/pages/campaigns/Campaign'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Drillings',
          path: 'project/:project_id/campaign/:campaign_id/drillings',
          component: () => import(/* webpackChunkName: "drillings" */'@/views/dashboard/pages/campaigns/Drillings'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
      ],
    },
    { path: '*', redirect: { name: 'Dashboard' }}
  ]

const router = new Router({
  // routes: [
  //   { path: '/', redirect: '/dashboard' }
  // ],
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach(async (to, from, next) => {
  await store.dispatch("AuthManager/refresh")
  const authUser = store.getters["AuthManager/authenticated"];
  const reqAuth = to.matched.some((record) => record.meta.requiresAuth);
  const loginQuery = { path: "/login", query: { redirect: to.fullPath } };
  if (reqAuth && !authUser) {
    next(
         {path: '/login'}
       );
  }
  else if(!reqAuth && authUser){
    next(
      {path: '/dashboard'}
    );
  }
  else {
    next(); // make sure to always call next()!
  }
});
export default router
