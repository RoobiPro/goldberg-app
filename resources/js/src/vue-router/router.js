import Vue from 'vue'
import Router from 'vue-router'
import store from '@/vuex/store'

Vue.use(Router)

const routes = [
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/views/LoginNew'),
      // component: () => import('@/views/Login'),
      props: true,
      meta: {
        guest: true
      },
    },
    {
      path: '/',
      redirect: '/dashboard',
      component: () => import('@/views/dashboard/Index'),
      props: true,
      meta: {
        requiresAuth: true
      },
      children: [
        // Dashboard
        {
          name: 'Dashboard',
          path: 'dashboard',
          component: () => import('@/views/dashboard/Dashboard'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        // Pages
        {
          name: 'User Management',
          path: 'management/user',
          component: () => import('@/views/dashboard/pages/Users'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Project Management',
          path: 'management/project',
          component: () => import('@/views/dashboard/pages/Projects'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'User Profile',
          path: '/pages/user',
          component: () => import('@/views/dashboard/pages/UserProfile'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Notifications',
          path: 'components/notifications',
          component: () => import('@/views/dashboard/component/Notifications'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Icons',
          path: 'components/icons',
          component: () => import('@/views/dashboard/component/Icons'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Typography',
          path: 'components/typography',
          component: () => import('@/views/dashboard/component/Typography'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        // Tables
        {
          name: 'Regular Tables',
          path: 'tables/regular-tables',
          component: () => import('@/views/dashboard/tables/RegularTables'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        // Maps
        {
          name: 'Google Maps',
          path: 'maps/google-maps',
          component: () => import('@/views/dashboard/maps/GoogleMapsNew'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        // Upgrade
        {
          name: 'Upgrade',
          path: 'upgrade',
          component: () => import('@/views/dashboard/Upgrade'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'My Projects',
          path: 'myprojects',
          component: () => import('@/views/dashboard/pages/projects/ProjectsOverview'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Project',
          path: 'project/:project_id/',
          component: () => import('@/views/dashboard/pages/projects/SingleProject'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Campaigns',
          path: 'project/:id/campaigns',
          component: () => import('@/views/dashboard/pages/projects/tables/Campaigns'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Campaign',
          path: 'project/:project_id/campaign/:campaign_id',
          component: () => import('@/views/dashboard/pages/campaigns/Campaign'),
          props: true,
          meta: {
            requiresAuth: true
          },
        },
        {
          name: 'Drillings',
          path: 'project/:project_id/campaign/:campaign_id/drillings',
          component: () => import('@/views/dashboard/pages/campaigns/Drillings'),
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
  routes: [
    { path: '/', redirect: '/dashboard' }
  ],
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach(async (to, from, next) => {
  await store.dispatch("auth/refresh")
  const authUser = store.getters["auth/authenticated"];
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
