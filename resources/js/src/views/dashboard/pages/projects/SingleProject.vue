<template>
  <v-card
    class="mx-auto my-12"
    min-width="400px"
    max-width="500px"
  >
    <template slot="progress">
      <v-progress-linear
        color="deep-purple"
        height="10"
        indeterminate
      ></v-progress-linear>
    </template>

    <v-img
      height="250"
      src="https://www.mining-technology.com/wp-content/uploads/sites/8/2017/10/3l-image-11.jpg"
    ></v-img>

    <v-card-title>{{this.project.name}}</v-card-title>

    <v-card-text>
      <!-- <v-row
        align="center"
        class="mx-0"
      >
        <v-rating
          :value="4.5"
          color="amber"
          dense
          half-increments
          readonly
          size="14"
        ></v-rating>

        <div class="grey--text ml-4">
          4.5 (413)
        </div>
      </v-row> -->

      <div class="my-4 subtitle-1">
        Start date: {{project.project_start_date}} <br>
        Coordinate X: {{project.coordinates_x}} <br>
        Coordinate Y: {{project.coordinates_y}} <br>
        Coordinate Z: {{project.coordinates_z}} <br>

      </div>

      <!-- <div>Small plates, salads & sandwiches - an intimate setting with 12 indoor seats plus patio seating.</div> -->
    </v-card-text>

    <v-divider class="mx-4"></v-divider>

    <v-card-title>View data</v-card-title>

    <v-card-text>
      <v-chip-group
        active-class="deep-purple accent-4 white--text"
        column
      >
        <v-chip>Wells</v-chip>

        <v-chip>Drilling</v-chip>

        <v-chip>Samples</v-chip>

        <v-chip>Spatial</v-chip>
      </v-chip-group>
    </v-card-text>

    <!-- <v-card-actions>
      <v-btn
        color="deep-purple lighten-2"
        text
        @click="reserve"
      >
        Reserve
      </v-btn>
    </v-card-actions> -->
  </v-card>
</template>


<script>
export default {
  data: () => ({
    project:[]
  }),
  created() {
    this.getProject()

  },

  methods: {
    async getProject () {
      this.project = await this.$store.getters["projects/project"];
      console.log(this.project)
    },
  }
}
</script>
