<template>
<v-container
id="regular-tables"
class="d-flex justify-center"
tag="section"
style="margin-top:10vh;">

  <v-data-table
    :headers="headers"
    :items="projects"
    :search="search"
    item-key="name"
     class="elevation-0"
  >

    <template v-slot:top>
      <v-toolbar flat>
        <div class="hidden-md-and-down v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 pa-7"
          style="max-height: 90px; width: auto;">
          <i aria-hidden="true" class="v-icon notranslate mdi mdi-hammer-wrench theme--dark" style="font-size: 32px;">
          </i>
        </div>
        <v-toolbar-title>My projects</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-switch
          v-model="singleExpand"
          style="margin-bottom: 0px;"
          label="Single expand"
          class="ma-0 pa-0" >
        </v-switch>

        <!-- <v-divider class="mx-4" inset vertical></v-divider> -->


      </v-toolbar>
    </template>

    <template  v-slot:item.actions="{ item }">
      <v-btn small color="primary" dark
       @click="openProject(item)"
       >
        Open
        <v-icon size=26 :color="'white'" style="padding-left:10px">
          mdi-book-open-variant
        </v-icon>
      </v-btn>
    </template>


  </v-data-table>

</v-container>
</template>

<script>
import axios from 'axios'

export default {
  data: vm => ({
    mydate:null,

    menu: false,
    menu2: false,

    singleExpand: true,

    editProjectDialog: false,
    editUserRoleDialog: false,
    deleteProjectDialog: false,
    newProjectDialog: false,
    assignUserDialog: false,
    assignClientDialog: false,
    unassignUserDialog: false,

    projectToAssing:null,
    selectedClient: undefined,
    clients:[],
    filteredClients:[],
    filteredUsers: [],
    newrole:0,
    value: null,
    selectedUser: undefined,
    users: [],

    projectToEdit:{
      id: null,
      name: null,
      date: new Date().toISOString().substr(0, 10),
      dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
      coordinates_x: null,
      coordinates_y: null,
      coordinates_z: null,
    },

    UserToEdit: {
      name: '',
      role: 0,
      user_id: 0,
      project_id: 0
    },

    UserToUnassign:[],
    UnassignFromProjectID: 0,
    editedUser:[],
    expanded: [],
    projects: [],
    search: '',
    editedIndex: -1,

  }),

  computed: {
    computedDateFormatted () {
      return this.formatDate(this.newProject.date)
    },
    editComputedDateFormatted () {
      return this.formatDate(this.projectToEdit.date)
    },
    formTitle() {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
    headers() {
      return [{
          text: 'ID',
          align: 'start',
          value: 'id',
        },
        {
          text: 'Name',
          value: 'name'
        },
        {
          text: 'My role',
          value: 'role'
        },
        {
          text: 'Start date',
          value: 'project_start_date'
        },
        {
          text: 'Coordinate X',
          value: 'coordinates_x',
          sortable: false
        },
        {
          text: 'Coordinate Y',
          value: 'coordinates_y',
          sortable: false
        },
        {
          text: 'Coordinate Z',
          value: 'coordinates_z',
          sortable: false
        },
        {
           text: 'Actions', align: 'center' ,value: 'actions'
        },
      ]
    },
  },
  watch: {
    'newProject.date': function(val){
      this.newProject.dateFormatted = this.formatDate(this.newProject.date)
    },
    'projectToEdit.date': function(val){
      this.projectToEdit.dateFormatted = this.formatDate(this.projectToEdit.date)
    }
  },

  created() {
    this.getProjects()
    // this.getUsers(),
    // this.getClients()
  },
  methods: {
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },
    parseDate (date) {
      if (!date) return null

      const [day, month, year] = date.split('.')
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
    },
    hasDecimal (num) {
    	return !!(num % 1);
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
    async getProjects() {
      var me = await this.$store.getters["auth/user"];
      console.log(me)
      axios.get(`/getUserProjects/`+me.id)
        .then(response => {
          if(response.status == 200){
            const projectsJson = response.data
            this.projects = projectsJson.map(projects => ({...projects, project_start_date: this.formatDate(projects.project_start_date), role: this.getRoleName(projects.pivot.role)}))
            console.log(response)
          }
          else{
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        })
    },
    async openProject(item){
      await this.$store.dispatch('projects/selectedProject', item);
      var pro = await this.$store.getters["projects/project"];
      this.$router.push({ path: `/project/${item.id}` })
      console.log(pro)
      console.log("openning project")
      console.log(item)
    }
  },
}
</script>

<style>
.borderline {
  border-bottom: 1px solid rgba(0,0,0,.22)
}
.v-card__text, .v-card__title {
  word-break: normal; /* maybe !important  */
}
.v-messages {
    display: block !important;
}

.v-label{
  margin-bottom: 0px;
}
.v-input__slot{
  margin-bottom: 0px;
}
.v-data-table>.v-data-table__wrapper tbody tr.v-data-table__expanded__content {
    -webkit-box-shadow: none;
    box-shadow: none;
}
.v-menu__content--fixed {
  background: white;
}



@media only screen and (max-width: 1054px) {
  .custom > div {
    justify-content: space-around;
    flex-shrink: 1;
    flex-basis: auto;
    flex: 50%;
  }
}

@media only screen and (max-width: 470px) {
  .custom > div {
    flex:none;
  }
}


</style>
