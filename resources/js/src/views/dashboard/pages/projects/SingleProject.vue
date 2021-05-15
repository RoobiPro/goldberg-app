<template>
  <v-card v-if="ready"
    class="mx-auto my-12"
    min-width="300px"
    max-width="500px"
  >
    <template slot="progress">
      <v-progress-linear
        color="deep-purple"
        height="10"
        indeterminate
      ></v-progress-linear>
    </template>

    <MapsComponent v-if="(typeof project.coordinates_x !== 'undefined')" :coordinates_x="project.coordinates_x"  :coordinates_y="project.coordinates_y"/>
    <!-- <v-img
      height="250"
      src="https://www.mining-technology.com/wp-content/uploads/sites/8/2017/10/3l-image-11.jpg"
    ></v-img> -->

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
  created() {
    this.getProjects()
    console.log('created:', this.projects)
  },
  mounted () {
    console.log('mounted:', this.projects)
  },
  beforeUpdate () {
    console.log('beforeUpdate:', this.projects)
  },

  methods: {
    openCampaings(){
      console.log('opening project campaings')
      console.log('/project/'+this.$route.params.id+'/campaigns')
      this.$router.push({ path: this.$route.params.id+'/campaigns' })
      // .catch(()=>{});
    },
    async getProjects() {
      var me = await this.$store.getters["auth/user"];
      console.log(me)
      axios.get(`/getUserProjects/`+me.id)
        .then(response => {
          if(response.status == 200){
            const projectsJson = response.data
            console.log(this.$route.params.id)
            this.projects = projectsJson.map(projects => ({...projects, project_start_date: this.formatDate(projects.project_start_date), role: this.getRoleName(projects.pivot.role)}))
            this.project = this.projects.filter(obj => {
              return obj.id == this.$route.params.id
            })[0]
            console.log(this.projects)
            console.log(this.project)
            this.ready = true
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
