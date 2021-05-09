<template>
  <router-view />
</template>

<script>
  export default {
    name: 'App',
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
