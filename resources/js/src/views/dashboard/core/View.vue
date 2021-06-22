<template>

  <v-main>

    <v-breadcrumbs
      class="customBreadcrumbs"
      :items="items"
      divider="/">
    </v-breadcrumbs>

    <router-view></router-view>

    <v-snackbar
      :timeout="3500"
      :value="checkNotificationStatus.status"
      outlined
      top
      :color="checkNotificationStatus.type"
      right
      text
    >
      {{checkNotificationStatus.text}}
    </v-snackbar>

    <dashboard-core-footer />
  </v-main>

</template>

<script>
  export default {
    name: 'DashboardCoreView',

   //  data: () => ({
   //   items: this.setBreadcrumbs()
   // }),
   // created() {
   //   this.items = this.$route.meta.breadcrumb
   //   console.log(this.$route.meta.breadcrumb)
   // },

   computed:{
      items: function(){
        console.log(this.$route.meta.breadcrumb)
        return this.$route.meta.breadcrumb
      },
      checkNotificationStatus(){
        return this.$store.getters['NotificationsManager/getNotificationStatus']
      }
    },
    components: {
      DashboardCoreFooter: () => import('./Footer'),
    }
}
</script>

<style>

body::-webkit-scrollbar {
display: none !important;
}

body {
-ms-overflow-style: none !important;
scrollbar-width: none !important;
}

/* .customBreadcrumbs {
  position: absolute;
  color: black;
  left: 1vw;
  top: 2vh;
} */
</style>
