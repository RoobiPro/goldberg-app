<template>

  <v-main>
    <router-view />

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
    computed:{
      checkNotificationStatus(){
        return this.$store.getters['alerts/getNotificationStatus']
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
</style>
