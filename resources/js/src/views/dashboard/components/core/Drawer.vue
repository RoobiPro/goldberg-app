<template>
<v-navigation-drawer id="core-navigation-drawer" v-model="drawer" :dark="barColor !== 'rgba(228, 226, 226, 1), rgba(255, 255, 255, 0.7)'" :expand-on-hover="expandOnHover" :right="$vuetify.rtl" :src="barImage" mobile-breakpoint="960" app width="260"
  v-bind="$attrs">
  <template v-slot:img="props">
    <v-img :gradient="`to bottom, ${barColor}`" v-bind="props" />
  </template>

  <v-divider/>

  <v-list dense nav>
    <v-list-item>
      <v-list-item-avatar>
        <v-img src="http://goldberg.local/images/favicon.png" />
      </v-list-item-avatar>
      <v-list-item-content class="pa-4 pl-0">
        <v-img src="http://goldberg.local/images/goldberg_font.png" />
      </v-list-item-content>
    </v-list-item>
  </v-list>

  <v-divider/>

  <v-list dense nav class="d-flex justify-start">
    <div class="d-flex justify-content-center">
      <v-list-item>
          <v-avatar size="64">
            <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
          </v-avatar>
      </v-list-item>
      <v-list-item class="pl-0 ml-0">
        <v-list-item-content>
          <v-list-item-title class="text-subtitle-1">
            {{user.name}}
          </v-list-item-title>
          <!-- <v-list-item-subtitle>{{user.email}}</v-list-item-subtitle> -->
        </v-list-item-content>
      </v-list-item>
    </div>
  </v-list>

  <v-divider/>

  <v-list expand nav>
    <!-- Style cascading bug  -->
    <!-- https://github.com/vuetifyjs/vuetify/pull/8574 -->
    <div />

    <template v-for="(item, i) in computedItems">
      <base-item-group v-if="item.children" :key="`group-${i}`" :item="item">
        <!--  -->
      </base-item-group>

      <base-item v-else :key="`item-${i}`" :item="item" />
    </template>

    <div />
  </v-list>

  <template #append>
    <div class="pa-4 ml-3 text-left mouseover">
      <div @click="signOut()">
        <v-icon>power_settings_new</v-icon>
      </div>
    </div>

  </template>
</v-navigation-drawer>
</template>

<script>
// Utilities
import {
  mapState,
  mapActions,
} from 'vuex'

export default {
  name: 'DashboardCoreDrawer',

  props: {
    expandOnHover: {
      type: Boolean,
      default: false,
    },
  },

  data: () => ({
    user:'',
    items: [{
        icon: 'mdi-view-dashboard',
        title: 'dashboard',
        to: '/dashboard',
      },
      {
        icon: 'mdi-account-multiple',
        title: 'User Management',
        to: '/management/user',
      },
      {
        icon: 'mdi-briefcase',
        title: 'Project Management',
        to: '/management/project',
      },
      {
        icon: 'mdi-account',
        title: 'user',
        to: '/pages/user',
      },
      {
        title: 'rtables',
        icon: 'mdi-clipboard-outline',
        to: '/tables/regular-tables',
      },
      {
        title: 'typography',
        icon: 'mdi-format-font',
        to: '/components/typography',
      },
      {
        title: 'icons',
        icon: 'mdi-chart-bubble',
        to: '/components/icons',
      },
      {
        title: 'google',
        icon: 'mdi-map-marker',
        to: '/maps/google-maps',
      },
      {
        title: 'notifications',
        icon: 'mdi-bell',
        to: '/components/notifications',
      },
    ],
  }),

  computed: {
    ...mapState(['barColor', 'barImage']),

    drawer: {
      get() {
        return this.$store.state.drawer
      },
      set(val) {
        this.$store.commit('SET_DRAWER', val)
      },
    },
    computedItems() {
      return this.items.map(this.mapItem)
    },
    profile() {
      return {
        avatar: true,
        title: this.$t('avatar'),
      }
    },
  },
  created () {
    this.getUser()
  },
  methods: {
    ...mapActions({
      signOutAction: 'auth/signOut'
    }),
    getUser(){
      this.user = this.$store.getters["auth/user"];
      console.log(this.user);
    },
    async signOut() {
      await this.signOutAction()

      this.$router.replace({
        name: 'Login'
      })
    },
    mapItem(item) {
      return {
        ...item,
        children: item.children ? item.children.map(this.mapItem) : undefined,
        title: this.$t(item.title),
      }
    },
    logout() {
      console.log("Vadder");
      // axios.post('/logout', { withCredentials: true }).then( () => {
      //   location.reload()
      // console.log(response)
      // })
    }
  },
}
</script>

<style lang="sass">
  @import '~vuetify/src/styles/tools/_rtl.sass'

  #core-navigation-drawer
    .mouseover
      cursor: pointer

    .v-list-group__header.v-list-item--active:before
      opacity: .24

    .v-list-item
      &__icon--text,
      &__icon:first-child
        justify-content: center
        text-align: center
        width: 20px

        +ltr()
          margin-right: 24px
          margin-left: 12px !important

        +rtl()
          margin-left: 24px
          margin-right: 12px !important

    .v-list--dense
      .v-list-item
        &__icon--text,
        &__icon:first-child
          margin-top: 10px

    .v-list-group--sub-group
      .v-list-item
        +ltr()
          padding-left: 8px

        +rtl()
          padding-right: 8px

      .v-list-group__header
        +ltr()
          padding-right: 0

        +rtl()
          padding-right: 0

        .v-list-item__icon--text
          margin-top: 19px
          order: 0

        .v-list-group__header__prepend-icon
          order: 2

          +ltr()
            margin-right: 8px

          +rtl()
            margin-left: 8px
</style>
