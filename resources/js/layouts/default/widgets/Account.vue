<template>
  <v-menu
    bottom
    left
    min-width="200"
    offset-y
    origin="top right"
    transition="scale-transition"
  >
    <template v-slot:activator="{ attrs, on }">
      <v-btn
        class="ml-2"
        min-width="0"
        text
        v-bind="attrs"
        v-on="on"
      >
        <v-icon>mdi-account</v-icon>
      </v-btn>
    </template>

    <v-list
      :tile="false"
      flat
      nav
    >
      <template v-for="(p, i) in profile">
        <v-divider
          v-if="p.divider"
          :key="`divider-${i}`"
          class="mb-2 mt-2"
        />

        <app-bar-item
          v-else
          :key="`item-${i}`"
          to="/dashboard"
        >
          <v-list-item-title v-text="p.title" />
        </app-bar-item>
      </template>

      <template >

        <app-bar-item
          to="/">
              <!-- <v-list-item-title v-text="Logout" /> -->
        </app-bar-item>
      </template>

    </v-list>
  </v-menu>
</template>

<script>
  export default {
    name: 'DefaultAccount',

    data: () => ({
      profile: [
        { title: 'Profile' },
        { title: 'Settings' },
        { divider: true },
        // { title: 'Log out' },
      ],
      // menuItem: {title: "Logout"},
    }),
    methods: {
          logout() {
            console.log("Logout Test")
        }
    }
  }
</script>
