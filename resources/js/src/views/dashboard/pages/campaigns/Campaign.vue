<template >
  <v-card v-if="ready"
    class="mx-auto"
    max-width="500"
  >
    <v-container fluid>
      <v-row dense>
        <v-col :cols="12">
          <v-card>
            <v-card-title> Name: {{campaign.name}}</v-card-title>
            <v-card-text>
              <div class="my-4 subtitle-1">
                Description: <i><u>{{campaign.description}}</u></i><br>
                Start date: {{campaign.start_date}} <br>
                End date: {{campaign.end_date}} <br>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col
          :cols="6"
        >
          <v-card>
            <v-img
              src="/images/drillings.png"
              class="white--text align-end"
              gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
              height="200px"
            >
              <div class="mycount">
                {{campaign.drillings.length}}
              </div>
              <v-card-title v-text="'Drillings'"></v-card-title>
            </v-img>
            <v-card-actions class="justify-center">
                <v-btn
                  color="primary"
                  style="margin-right:0px"
                  @click="openDrillings"
                >
                  Open
                </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
        <v-col
          :cols="6"
        >
          <v-card>
            <v-img
              src="/images/wells.png"
              class="white--text align-end"
              gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
              height="200px"
            >
            <div class="mycount">
              {{campaign.wells.length}}
            </div>
              <v-card-title v-text="'Wells'"></v-card-title>
            </v-img>
            <v-card-actions class="justify-center">
                <v-btn
                  color="primary"
                  style="margin-right:0px"
                  @click="openWells"
                >
                  Open
                </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
        <v-col
          :cols="6"
        >
          <v-card>
            <v-img
              src="/images/samples.png"
              class="white--text align-end"
              gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
              height="200px"
            >
            <div class="mycount">
              {{campaign.samples.length}}
            </div>
              <v-card-title v-text="'Samples'"></v-card-title>
            </v-img>
            <v-card-actions class="justify-center">
                <v-btn
                  color="primary"
                  style="margin-right:0px"
                  @click="openSamples"
                >
                  Open
                </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
        <v-col
          :cols="6"
        >
          <v-card>
            <v-img
              src="/images/spatials.png"
              class="white--text align-end"
              gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
              height="200px"
            >
            <div class="mycount">
              {{campaign.samples.length}}
            </div>
              <v-card-title v-text="'Spatial data'"></v-card-title>
            </v-img>
            <v-card-actions class="justify-center">
                <v-btn
                  color="primary"
                  style="margin-right:0px"
                  @click="openSpatial"
                >
                  Open
                </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
</template>
<script>
  export default {
    data: () => ({
      ready: false,
      project: null,
      // campaign: null,
      campaign: {name:'John Doe'},
      me: null
    }),
    mounted() {
      this.checkAuth()
      this.getCampaign()
    },
    methods:{
      openDrillings(){
        console.log('openDrillings')
        console.log(this.project)
        console.log(this.$route.params)
        this.$router.push({ path: '/project/'+this.$route.params.project_id+'/campaign/'+this.$route.params.campaign_id+'/drillings' })
      },
      openWells(){
        console.log('openWells')
      },
      openSamples(){
        console.log('Samples')
      },
      openSpatial(){
        console.log('openSpatial')
      },
      async getCampaign(){
        await this.$store.dispatch('CampaignManager/getcampaign', this.$route.params.campaign_id);
        this.campaign = this.$store.getters["CampaignManager/campaign"];
        this.ready = true
      },
      async checkAuth(){
        this.me = await this.$store.getters["AuthManager/user"];
        console.log(this.me)
        await this.$store.dispatch('UsersManager/userprojects', this.me.id);
        this.projects = await this.$store.getters["UsersManager/projects"];
        this.project = this.projects.filter(obj => {
          return obj.id == this.$route.params.project_id
        })[0]
        if(this.project==undefined){
          console.log('Not authorized!')
          this.$router.push({ path: '/myprojects' })
        }
        else{
          console.log('authorized!')
          // this.ready = true
        }
      },
    }
  }
</script>
<style>
.mycount{
  font-size: 70px;
  height: 100%
}
</style>
