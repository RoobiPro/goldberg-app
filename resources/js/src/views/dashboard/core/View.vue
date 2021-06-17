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
    data: () => ({

     items: [
       {
         text: 'Dashboard',
         disabled: false,
         href: 'breadcrumbs_dashboard',
       },
       {
         text: 'Link 1',
         disabled: false,
         href: 'breadcrumbs_link_1',
       },
       {
         text: 'Link 2',
         disabled: true,
         href: 'breadcrumbs_link_2',
       },
     ],
   }),

    computed:{
      checkNotificationStatus(){
        return this.$store.getters['NotificationsManager/getNotificationStatus']
      }
    },
    components: {
      DashboardCoreFooter: () => import('./Footer'),
    },
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
