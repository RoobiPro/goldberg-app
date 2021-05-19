<template>

  <v-app>

    <DashboardCoreAppBar />

    <DashboardCoreDrawer />

    <DashboardCoreSettings />

    <DashboardCoreView />

  </v-app>

</template>

<script>
import DashboardCoreAppBar from './core/AppBar'
import DashboardCoreDrawer from './core/Drawer'
import DashboardCoreSettings from './core/Settings'
import DashboardCoreView from './core/View'
import store from '../../vuex/store'

export default {
    name: 'DashboardIndex',


    components: {
      DashboardCoreAppBar, DashboardCoreDrawer, DashboardCoreSettings, DashboardCoreView
    },


    data: () => ({
      expandOnHover: false,
    }),
    mounted() {
      const theme = localStorage.getItem("dark\_theme");
      const color = localStorage.getItem("color");
      const barimage = localStorage.getItem("barImage");

      if (theme) {
          if (theme == "true") {
              this.$vuetify.theme.dark = true;
          } else {
              this.$vuetify.theme.dark = false;
          }
      }

      if (color) {
        this.$vuetify.theme.themes[this.isDark ? 'dark' : 'light'].primary = color
      }

      if(barimage) {
        this.$store.commit('hs/SET_BAR_IMAGE', barimage)
      }
  },
}
</script>
