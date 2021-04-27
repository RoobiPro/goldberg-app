<template>
  <v-list-item class="mb-0 justify-space-between pa-3">
    <v-list-item-avatar>
      <v-img
        src="http://goldberg.local/images/favicon.png"
      />
    </v-list-item-avatar>

    <v-list-item-content class="pa-6 pl-0">
      <v-img
        src="http://goldberg.local/images/goldberg_font.png"
      />
    </v-list-item-content>
  </v-list-item>
</template>

<script>
  // Utilities
  import { get } from 'vuex-pathify'

  export default {
    name: 'DefaultDrawerHeader',

    computed: { version: get('app/version') },
    // data() {
    //   return {
    //     logoPath: path.join(__dirname, 'src', 'main', 'resources', 'assets', 'logo.png')
    //   }
    // }
  }
</script>
