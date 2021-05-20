<template>
  <div id="settings-wrapper">
    <v-card
      id="settings"
      class="py-2 px-4"
      color="rgba(0, 0, 0, .3)"
      dark
      flat
      link
      min-width="100"
      style="position: fixed; bottom: -5px; right: -35px; border-radius: 8px;"
    >
      <v-icon large>
        mdi-cog
      </v-icon>
    </v-card>

    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      activator="#settings"
      bottom
      content-class="v-settings"
      left
      nudge-left="8"
      offset-x
      origin="bottom right"
      transition="scale-transition"
    >
      <v-card
        class="text-center mb-0"
        width="300"
      >
        <v-card-text>
          <strong class="mb-3 d-inline-block">Settings</strong>

          <v-item-group v-model="color">
            <v-item
              v-for="color in colors"
              :key="color"
              :value="color"
            >
              <template v-slot="{ active, toggle }">
                <v-avatar
                  :class="active && 'v-settings__item--active'"
                  :color="color"
                  class="v-settings__item"
                  size="25"
                  @click="toggle"
                />
              </template>
            </v-item>
          </v-item-group>

          <v-divider class="my-4 secondary" />

          <v-row
            align="center"
            no-gutters
          >
            <v-col cols="auto">
              Dark Mode
            </v-col>

            <v-spacer />

            <v-col cols="auto">
              <v-switch
                v-model="dark"
                class="ma-0 pa-0"
                color="secondary"
                hide-details
              />
            </v-col>
          </v-row>

          <v-divider class="my-4 secondary" />

          <v-row
            align="center"
            no-gutters
          >
            <v-col cols="auto">
              Sidebar Image
            </v-col>

            <v-spacer />

            <v-col cols="auto">
              <v-switch
                v-model="showImg"
                class="ma-0 pa-0"
                color="secondary"
                hide-details
              />
            </v-col>
          </v-row>

          <v-divider class="my-4 secondary" />

          <strong class="mb-3 d-inline-block">IMAGES</strong>

          <v-item-group
            v-model="image"
            class="d-flex justify-space-between mb-3"
          >
            <v-item
              v-for="image in images"
              :key="image"
              :value="image"
              class="mx-1"
            >
              <template v-slot="{ active, toggle }">
                <v-sheet
                  :class="active && 'v-settings__item--active'"
                  class="d-inline-block v-settings__item"
                  @click="toggle"
                >
                  <v-img
                    :src="image"
                    height="100"
                    width="50"
                  />
                </v-sheet>
              </template>
            </v-item>
          </v-item-group>

        </v-card-text>
      </v-card>
    </v-menu>
  </div>
</template>

<script>
  // Mixins
  import Proxyable from 'vuetify/lib/mixins/proxyable'
  import { mapMutations, mapState } from 'vuex'

  export default {
    name: 'DashboardCoreSettings',

    mixins: [Proxyable],

    data: () => ({
      color: localStorage.getItem("color"),
      colors: [
        '#9C27b0',
        '#00CAE3',
        '#4CAF50',
        '#ff9800',
        '#E91E63',
        '#FF5252',
      ],
      image: !localStorage.getItem("showBarImage") ? 'https://demos.creative-tim.com/material-dashboard/assets/img/sidebar-1.jpg' : (localStorage.getItem("showBarImage") == 'true' ? localStorage.getItem("barImage") : false),
      images: [
        'https://demos.creative-tim.com/material-dashboard/assets/img/sidebar-1.jpg',
        'https://demos.creative-tim.com/material-dashboard/assets/img/sidebar-2.jpg',
        'https://demos.creative-tim.com/material-dashboard/assets/img/sidebar-3.jpg',
        'https://demos.creative-tim.com/material-dashboard/assets/img/sidebar-4.jpg'
      ],
      menu: false,
      saveImage: '',
      showImg: !localStorage.getItem("showBarImage") ? false : (localStorage.getItem("showBarImage") == 'true' ? true : false),
      dark: !localStorage.getItem("dark\_theme") ? false : (localStorage.getItem("dark\_theme") == 'true' ? true : false),
    }),


    computed: {
      ...mapState("LayoutManager", ['barImage']),
    },

    watch: {
      dark(newValue){
        this.$vuetify.theme.dark = newValue;
        localStorage.setItem("dark\_theme", this.$vuetify.theme.dark.toString());

        //called whenever switch1 changes
        console.log(newValue);
      },
      image() {
        console.log(this.image)
        this.setBarImage(this.image)
      },
      color (val) {

        this.$vuetify.theme.themes[this.isDark ? 'dark' : 'light'].primary = val
        localStorage.setItem("color", val);
      },
      showImg (val) {
        console.log(val)
          if (!val) {
            this.toggleBarImage(false)
            this.setBarImage('')

          }
          else {
            this.toggleBarImage(true)
            this.setBarImage(this.image)
          }
      }
    },

    methods: {
      ...mapMutations({
        setBarImage: 'LayoutManager/SET_BAR_IMAGE',
        toggleBarImage: 'LayoutManager/SET_SHOWBARIMAGE',
      }),
    },
  }
</script>

<style lang="sass">
  .v-settings
    .v-item-group > *
      cursor: pointer

    &__item
      border-width: 3px
      border-style: solid
      border-color: transparent !important

      &--active
        border-color: #00cae3 !important
</style>
