<template>
  <v-container
  v-if="ready"
    id="dashboard"
    fluid
    tag="section"
  >

    <!-- <v-row> -->
      <v-card style="width:100%">
        <v-banner two-line>
          <v-avatar
            slot="icon"
            color="primary"
            size="40"
          >
            <v-icon
              icon="mdi-human-greeting"
              color="white"
            >
              mdi-human-greeting
            </v-icon>
          </v-avatar>
          Welcome<template v-if="user.last_logout!=null"> back</template>, {{user.name.split(" ")[0]}}!
        </v-banner>
      </v-card>
    <!-- </v-row> -->
    <v-card v-for="project in projects" :key="project.id">
      <v-card-title class="d-flex justify-space-between">
        <span>{{project.name}}</span>
        <div class="text-right">
          <v-btn small color="primary" dark
          @click="openProject(item)"
          >
          Open
          <v-icon size=26 :color="'white'" style="padding-left:10px">
            mdi-book-open-variant
          </v-icon>
        </v-btn>
      </div>
      </v-card-title>
      <div class="d-flex justify-space-around mt-3">
        <span class="d-flex justify-start" >Project Start Date:  {{project.project_start_date}}</span>
        <span class="d-flex justify-start" >Projecttype:  {{project.type}}</span>
        <span class="d-flex justify-start">My Role:  {{project.role}}</span>
      </div>

      <v-row class="ma-2">
          <v-col
            cols="12"
            sm="6"
            lg="3"
          >
            <base-material-stats-card
              color="info"
              icon="mdi-screw-lag"
              title="Drillings"
              :value="project.projectdata.count_drilling"
            />
          </v-col>

          <v-col
            cols="12"
            sm="6"
            lg="3"
          >
            <base-material-stats-card
              color="success"
              icon="mdi-water-well"
              title="Wells"
              :value="project.projectdata.count_wells"
            />
          </v-col>

          <v-col
            cols="12"
            sm="6"
            lg="3"
          >
            <base-material-stats-card
              color="primary"
              icon="mdi-hand-okay"
              title="Hand Samples"
              :value="project.projectdata.count_handsamples"
            />
          </v-col>

          <v-col
            cols="12"
            sm="6"
            lg="3"
          >
            <base-material-stats-card
              color="orange"
              icon="mdi-clipboard-list"
              title="Sample Lists"
              :value="(Number(project.projectdata.count_drilling_sample_lists) + Number(project.projectdata.count_well_sample_lists)).toString()"
            />
          </v-col>
      </v-row>
    </v-card>

  </v-container>
</template>

<script>
  export default {
    name: 'Dashboard',

    data () {
      return {
        ready:false,
        user:[],
        projects:[]

      }
    },
    async created(){
      this.user = this.$store.getters["AuthManager/user"];
      console.log(this.user)
      await this.$store.dispatch("UsersManager/userprojects", this.user.id);
      this.projects = this.$store.getters["UsersManager/projects"];
      console.log(this.projects)
      var index=0
      for (var project in this.projects) {
        await this.$store.dispatch('ProjectsManager/getProjectData', this.projects[index].id);
        var projectdata = this.$store.getters["ProjectsManager/projectdata"];
        this.projects[index].projectdata = projectdata
        index = index+1
      }
      this.ready = true
      console.log(this.projects)
    },
    methods: {
      complete (index) {
        this.list[index] = !this.list[index]
      },
    },
  }
</script>
