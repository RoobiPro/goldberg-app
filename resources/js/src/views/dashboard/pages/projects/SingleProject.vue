<template>
  <v-card v-if="ready"
    class="mx-auto my-12"
    max-width="700px"
  >
    <template slot="progress">
      <v-progress-linear
        color="deep-purple"
        height="10"
        indeterminate
      ></v-progress-linear>
    </template>

    <MapsComponent v-if="(typeof project.utm_x !== 'undefined')" :coordinates_x="project.latitude"  :coordinates_y="project.longitude"/>

    <v-card-title>{{this.project.name}}</v-card-title>
    <v-card-text>

      <div class="my-4 subtitle-1">
        Project code: <i><u>{{project.project_code}}</u></i><br>
        Type: {{project.type}} <br>
      </div>

      <div class="my-4 subtitle-1">
        My role: <i><u>{{project.role}}</u></i><br>
        Start date: {{project.project_start_date}} <br>
        UTM X: {{project.utm_x}} <br>
        UTM Y: {{project.utm_y}} <br>
        UTM Z: {{project.utm_z}} <br>
      </div>

    </v-card-text>

    <v-divider class="mx-4"></v-divider>

    <v-card-title>View data</v-card-title>

    <v-card-text>
      <v-btn class="ma-2" rounded color="primary" dark @click="goTo('spatials')">Spatial Data</v-btn>
      <v-btn class="ma-2" rounded color="primary" dark @click="goTo('handsamples')">Hand Samples</v-btn>
      <v-btn class="ma-2" rounded color="primary" dark @click="goTo('drillings')">Drillings</v-btn>
      <v-btn class="ma-2" rounded color="primary" dark @click="goTo('wells')">Wells</v-btn>
      <v-btn class="ma-2" rounded color="primary" dark @click="goTo('samplelist')">Sample List</v-btn>

    </v-card-text>

  </v-card>
</template>


<script>
import MapsComponent from './../../maps/GoogleMapsNew'

export default {
  components:{
    MapsComponent
  },
  data: () => ({
    ready:false,
    project:[],
    projects:[]
  }),
  mounted() {
    this.checkAuth()
  },

  methods: {
    goTo(destination){
      this.$router.push({ path: this.$route.params.project_id+'/'+destination })
    },
    async checkAuth(){
      this.me = await this.$store.getters["AuthManager/user"];
      await this.$store.dispatch('UsersManager/userprojects', this.me.id);
      this.projects = await this.$store.getters["UsersManager/projects"];
      this.project = this.projects.filter(obj => {
        return obj.id == this.$route.params.project_id
      })[0]
      console.log(this.project)
      if(this.project==undefined){
        this.$router.push({ path: '/myprojects' })
      }
      else{
        this.ready = true
      }
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },
    getRoleName(params) {
      if (params == 0) {
        return 'Viewer'
      } else if (params == 1) {
        return 'Editor'
      } else if (params == 2) {
        return 'Admin'
      } else {
        return 'Role: ' + params
      }
    },
  }
}
</script>
