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
        requiresAuth: true,
        breadcrumb: 'Dashboard',
        // breadcrumbs:
      },
      children: [
        // Dashboard
        {
          name: 'Dashboard',
          path: 'dashboard',
          component: () => import(/* webpackChunkName: "dashboard" */'@/views/dashboard/Dashboard'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'Dashboard',
                to: { name: 'Dashboard' }
              },
            ],
          },
        },
        // Pages
        {
          name: 'User Management',
          path: 'management/user',
          component: () => import(/* webpackChunkName: "usermanagement" */'@/views/dashboard/pages/Users'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'User Management',
                to: { name: 'User Management' }
              }
            ],
          },
        },
        {
          name: 'Project Management',
          path: 'management/project',
          component: () => import(/* webpackChunkName: "projectmanagement" */'@/views/dashboard/pages/Projects'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'Project Management',
                to: { name: 'Project Management' }
              }
            ],
          },
        },
        {
          name: 'User Sessions',
          path: 'management/sessions',
          component: () => import(/* webpackChunkName: "projectmanagement" */'@/views/dashboard/pages/admin/SessionTracking'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'User Sessions',
                to: { name: 'User Sessions' }
              }
            ],
          },
        },
        {
          name: 'User Profile',
          path: '/pages/user',
          component: () => import(/* webpackChunkName: "userprofile" */'@/views/dashboard/pages/UserProfile'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'Profile',
                to: { name: 'User Profile' }
              }
            ],
          },
        },

        {
          name: 'My Projects',
          path: 'myprojects',
          component: () => import(/* webpackChunkName: "myprojects" */'@/views/dashboard/pages/projects/ProjectsOverview'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              }
            ],
          },
        },
        {
          name: 'Project',
          path: 'project/:project_id',
          component: () => import(/* webpackChunkName: "project" */'@/views/dashboard/pages/projects/SingleProject'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              },
              {
                text: 'Project',
                to: { name: 'Project' }
              }
            ],
          },
        },
        {
          name: 'Drillings',
          path: 'project/:id/drillings',
          component: () => import(/* webpackChunkName: "drillings" */'@/views/dashboard/pages/campaigns/Drillings'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              },
              {
                text: 'Project',
                exact: true,
                disabled: false,
              },
              {
                text: 'Drillings',
                to: { name: 'Drillings' }
              }
            ],
          },
          beforeEnter: (to, from, next) => {
            to.meta.breadcrumb[1].name = "Project"
            to.meta.breadcrumb[1].to = '/project/'+to.params.id
            next();
          }
        },
        {
          name: 'Hand Samples',
          path: 'project/:id/handsamples',
          component: () => import(/* webpackChunkName: "handsamples" */'@/views/dashboard/pages/campaigns/HandSamples'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              },
              {
                text: 'Project',
                exact: true,
                disabled: false,
              },
              {
                text: 'Hand Samples',
                to: { name: 'Hand Samples' }
              }
            ],
          },
          beforeEnter: (to, from, next) => {
            to.meta.breadcrumb[1].name = "Project"
            to.meta.breadcrumb[1].to = '/project/'+to.params.id
            next();
          }
        },
        {
          name: 'Spatials',
          path: 'project/:id/spatials',
          component: () => import(/* webpackChunkName: "spatials" */'@/views/dashboard/pages/campaigns/Spatials'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb:  [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              },
              {
                text: 'Project',
                exact: true,
                disabled: false,
              },
              {
                text: 'Spatials',
                to: { name: 'Spatials' }
              }
            ],
          },
          beforeEnter: (to, from, next) => {
            to.meta.breadcrumb[1].name = "Project"
            to.meta.breadcrumb[1].to = '/project/'+to.params.id
            next();
          }
        },
        {
          name: 'Wells',
          path: 'project/:id/wells',
          component: () => import(/* webpackChunkName: "wells" */'@/views/dashboard/pages/campaigns/Wells'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              },
              {
                text: 'Project',
                exact: true,
                disabled: false,
              },
              {
                text: 'Wells',
                to: { name: 'Wells' }
              }
            ],
          },
          beforeEnter: (to, from, next) => {
            to.meta.breadcrumb[1].name = "Project"
            to.meta.breadcrumb[1].to = '/project/'+to.params.id
            next();
          }
        },
        {
          name: 'Drilling Sample List',
          path: 'project/:id/drillingsamplelist',
          component: () => import(/* webpackChunkName: "samplelist" */'@/views/dashboard/pages/samplelists/DrillingSamplelist'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              },
              {
                text: 'Project',
                exact: true,
                disabled: false,
              },
              {
                text: 'Sample List',
                to: { name: 'Drilling Sample List' }
              }
            ],
          },
          beforeEnter: (to, from, next) => {
            to.meta.breadcrumb[1].name = "Project"
            to.meta.breadcrumb[1].to = '/project/'+to.params.id
            next();
          }
        },
        {
          name: 'Well Sample List',
          path: 'project/:id/wellsamplelist',
          component: () => import(/* webpackChunkName: "samplelist" */'@/views/dashboard/pages/samplelists/WellSamplelist'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              },
              {
                text: 'Project',
                exact: true,
                disabled: false,
              },
              {
                text: 'Sample List',
                to: { name: 'Well Sample List' }
              }
            ],
          },
          beforeEnter: (to, from, next) => {
            to.meta.breadcrumb[1].name = "Project"
            to.meta.breadcrumb[1].to = '/project/'+to.params.id
            next();
          }
        },
        {
          name: 'Hand Sample Sample List',
          path: 'project/:id/handsamplesamplelist',
          component: () => import(/* webpackChunkName: "samplelist" */'@/views/dashboard/pages/samplelists/HandSampleSamplelist'),
          props: true,
          meta: {
            requiresAuth: true,
            breadcrumb: [
              {
                text: 'My Projects',
                to: { name: 'My Projects' }
              },
              {
                text: 'Project',
                exact: true,
                disabled: false,
              },
              {
                text: 'Sample List',
                to: { name: 'Hand Sample Sample List' }
              }
            ],
          },
          beforeEnter: (to, from, next) => {
            to.meta.breadcrumb[1].name = "Project"
            to.meta.breadcrumb[1].to = '/project/'+to.params.id
            next();
          }
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
  try {
    console.log("Navigation triggered from:", from.fullPath, "to:", to.fullPath);
    
    // Refresh authentication status
    await store.dispatch("AuthManager/refresh");
    console.log("AuthManager refresh called");

    // Get authenticated user status from the store
    const authUser = store.getters["AuthManager/authenticated"];
    console.log("Authenticated user:", authUser);

    // Check if the route requires authentication
    const reqAuth = to.matched.some((record) => record.meta.requiresAuth);
    console.log("Route requires authentication:", reqAuth);

    const loginQuery = { path: "/login", query: { redirect: to.fullPath } };

    // Handle redirection based on authentication status
    if (reqAuth && !authUser) {
      console.log("Authentication required and user not authenticated. Redirecting to login.");
      return next(loginQuery);
    } else if (!reqAuth && authUser) {
      console.log("Route does not require authentication but user is authenticated. Redirecting to dashboard.");
      return next({ path: '/dashboard' });
    } else {
      console.log("Proceeding to the route:", to.fullPath);
      return next(); // Always call next() to proceed to the route
    }
  } catch (error) {
    console.error("Error in navigation guard:", error);
    // Handle error case
    return next({ path: '/error' }); // Optional: redirect to an error page or handle appropriately
  }
});

export default router
