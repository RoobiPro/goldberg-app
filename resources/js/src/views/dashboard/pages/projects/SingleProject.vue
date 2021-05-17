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

    <MapsComponent v-if="(typeof project.coordinates_x !== 'undefined')" :coordinates_x="project.coordinates_x"  :coordinates_y="project.coordinates_y"/>

    <v-card-title>{{this.project.name}}</v-card-title>
    <v-card-text>

      <div class="my-4 subtitle-1">
        My role: <i><u>{{project.role}}</u></i><br>
        Start date: {{project.project_start_date}} <br>
        Coordinate X: {{project.coordinates_x}} <br>
        Coordinate Y: {{project.coordinates_y}} <br>
        Coordinate Z: {{project.coordinates_z}} <br>
      </div>

    </v-card-text>

    <v-divider class="mx-4"></v-divider>

    <v-card-title>View data</v-card-title>

    <v-card-text>
      <v-chip-group
        active-class="deep-purple accent-4 white--text"
        column
      >
        <v-chip @click="openCampaings">Campaings</v-chip>

        <!-- <v-chip>Drilling</v-chip>

        <v-chip>Samples</v-chip>

        <v-chip>Spatial</v-chip> -->
      </v-chip-group>
    </v-card-text>

  </v-card>
</template>


<script>
import MapsComponent from './../../maps/GoogleMapsNew'
import axios from 'axios'

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
    openCampaings(){
      console.log('opening project campaings')
      console.log('/project/'+this.$route.params.project_id+'/campaigns')
      this.$router.push({ path: this.$route.params.project_id+'/campaigns' })
      // .catch(()=>{});
    },
    async checkAuth(){
      this.me = await this.$store.getters["auth/user"];
      console.log(this.me)
      axios.get(`/getUserProjects/`+this.me.id)
        .then(response => {
          if(response.status == 200){
            const projectsJson = response.data
            console.log(this.$route.params.project_id)
            const projects = projectsJson.map(projects => ({...projects}))
            this.project = projects.filter(obj => {
              return obj.id == this.$route.params.project_id
            })[0]
            if(this.project==undefined){
              console.log('Not authorized!')
              this.$router.push({ path: '/myprojects' })
            }
            else{
              this.ready = true
            }

          }
          else{
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        })
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
