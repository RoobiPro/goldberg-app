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

    <v-expansion-panels accordion multiple :value="expanded">
      <v-expansion-panel :key="1">
        <v-expansion-panel-header><v-card-title>{{this.project.name}}</v-card-title></v-expansion-panel-header>
        <v-expansion-panel-content>
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
            </div>
        </v-expansion-panel-content>
      </v-expansion-panel>

      <v-expansion-panel :key="2">
        <v-expansion-panel-header>
          <v-card-title>View Data</v-card-title>
          </v-expansion-panel-header>
        <v-expansion-panel-content>

          <div class="data-item">

            <div class="d-md-flex flex-row justify-end">
              <v-progress-circular
                v-if="loading"
                :width="3"
                size="25"
                class="text-center"
                color="green"
                indeterminate
              >
              </v-progress-circular>
              <div style="min-height:37px">
                <v-btn
                v-if="!loading"
                x-small

                rounded color="primary"
                dark
                @click="fetchData">
                <v-icon size="16" :color="'white'">
                mdi-autorenew
                </v-icon>
                </v-btn>
              </div>

            </div>
          </div>
          <div class="data-item">
            <span>Spatial:</span>
            <div class="d-md-flex flex-row justify-start mb-5 mt-2">
              <v-btn class="mr-2 mb-2" rounded color="primary" dark @click="goTo('spatials')">Data <template v-if="projectdataready">({{projectdata.count_spatial}})</template></v-btn>
            </div>
          </div>
          <div class="data-item">
            <span>Campaigns:</span>
            <div class="d-md-flex flex-row justify-start mb-5 mt-2">
              <v-btn class="mr-2 mb-2" rounded color="primary" dark @click="goTo('drillings')">Drillings <template v-if="projectdataready">({{projectdata.count_drilling}})</template></v-btn>
              <v-btn class="mr-2 mb-2" rounded color="primary" dark @click="goTo('wells')">Wells <template v-if="projectdataready">({{projectdata.count_wells}})</template></v-btn>
              <v-btn class="mr-2 mb-2" rounded color="primary" dark @click="goTo('handsamples')">Hand Samples <template v-if="projectdataready">({{projectdata.count_handsamples}})</template></v-btn>
            </div>
          </div>
          <div class="data-item">
            <span>Sample Lists:</span>
            <div class="d-md-flex flex-row justify-start mb-5 mt-2">
              <v-btn class="mr-2 mb-2" rounded color="primary" dark @click="goTo('drillingsamplelist')">Drilling <template v-if="projectdataready">({{projectdata.count_drilling_sample_lists}})</template></v-btn>
              <v-btn class="mr-2 mb-2" rounded color="primary" dark @click="goTo('wellsamplelist')">Well <template v-if="projectdataready">({{projectdata.count_well_sample_lists}})</template></v-btn>
              <v-btn class="mr-2 mb-2" rounded color="primary" dark @click="goTo('samplelist')">Hand Sample <template v-if="projectdataready">({{projectdata.count_well_sample_lists}})</template></v-btn>
            </div>
          </div>


        </v-expansion-panel-content>
      </v-expansion-panel>

      <v-expansion-panel :key="3" v-if="this.project.pivot.role>=2">
        <v-expansion-panel-header><v-card-title>Import Data</v-card-title></v-expansion-panel-header>
        <v-expansion-panel-content>
            <div class="data-item">
              <span>1. Import Campaigns (Drillings and Wells):</span>
              <div class="d-flex flex-row justify-start mb-5 mt-2">
                <CSVCampaignsDialog />
              </div>
            </div>
            <div class="data-item">
              <span>2. Import Campaign Data (Assay, Mineralization, ...):</span>
              <div class="d-md-flex flex-row justify-start mb-5 mt-2">
                <CSVDrillingDataDialog />
                <CSVWellDataDialog />
              </div>
            </div>
            <div class="data-item">
              <span>3. Import Sample Table:</span>
              <div class="d-flex flex-row justify-start mb-5 mt-2">
                <CSVSampleListDialog />
              </div>
            </div>
            <div class="data-item">
              <span>Manage Imports:</span>
              <div class="d-flex flex-row justify-start mt-2">
                <CSVHistoryDialog />
              </div>
            </div>
        </v-expansion-panel-content>
      </v-expansion-panel>


  </v-expansion-panels>

  </v-card>
</template>
<script>
import MapsComponent from './../../maps/GoogleMapsNew'
import CSVCampaignsDialog from './components/CSVCampaignsDialog'
import CSVWellDataDialog from './components/CSVWellDataDialog'
import CSVDrillingDataDialog from './components/CSVDrillingDataDialog'
import CSVHistoryDialog from './components/CSVHistoryDialog'
import CSVSampleListDialog from './components/CSVSampleListDialog'
export default {
  components:{
    MapsComponent, CSVCampaignsDialog, CSVWellDataDialog, CSVHistoryDialog, CSVDrillingDataDialog, CSVSampleListDialog
  },
  data: () => ({
    expanded: [],
    ready:false,
    loading:false,
    projectdata:{},
    projectdataready: false,
    project:[],
    projects:[],
  }),
  mounted() {
    this.checkAuth()
  },
  methods: {
    async fetchData(){
      this.loading = true
      await this.$store.dispatch('ProjectsManager/getProjectData', this.$route.params.project_id);
      this.projectdata = await this.$store.getters["ProjectsManager/projectdata"];
      this.projectdataready = true
      console.log(this.projectdata)
      this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: this.projectdata.type, text: this.projectdata.message});
      this.loading = false
    },
    goTo(destination){
      this.$router.push({ path: this.$route.params.project_id+'/'+destination })
    },
    async checkAuth(){
      this.me = await this.$store.getters["AuthManager/user"];
      console.log(this.me)
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
        if(this.project.pivot.role>=2){
          this.expanded = [0,1]
        }
        else{
          this.expanded = [0,1]
        }
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
<style>
.v-expansion-panel-header{
  max-height:20px;
}
</style>
