<template>
<v-container id="regular-tables" fluid tag="section" style="margin-top:10vh;">
  <!-- <base-v-component
      heading="User Management"
      link="components/simple-tables"
    /> -->
  <v-data-table :headers="headers" :items="projects" :search="search" :single-expand="singleExpand" :expanded.sync="expanded" item-key="name" show-expand class="elevation-1">

    <template v-slot:top>
      <v-toolbar flat>
        <div class="v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 pa-7 d-none d-sm-flex d-md-flex"
          style="max-height: 90px; width: auto;">
          <i aria-hidden="true" class="v-icon notranslate mdi mdi-clipboard-text theme--dark" style="font-size: 32px;">
          </i>
        </div>
        <v-toolbar-title>Project Management</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-switch
          v-model="singleExpand"
          style="margin-bottom: 0px;"
          label="Single expand"
          class="ma-0 pa-0" >
        </v-switch>

        <v-divider class="mx-4" inset vertical></v-divider>

        <v-dialog v-model="newProjectDialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
              New Project
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">New Project</span>
            </v-card-title>

            <v-card-text>
              <v-container>

                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="newProject.name"
                      :error-messages="nameErrors"
                      label="Project name"
                      required
                      @input="$v.newProject.name.$touch()"
                      @blur="$v.newProject.name.$touch()"
                    ></v-text-field>

                    </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialogs">
                Cancel
              </v-btn>
              <v-btn color="blue darken-1" text @click="saveNewProject">
                Create
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="assignUserDialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
            <!-- <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
              Assign User
            </v-btn> -->
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">Assign User</span>
            </v-card-title>

            <v-card-text>
              <v-container>

                <v-row>
                  <v-col cols="12">
                    <v-autocomplete
                      :items="filteredUsers"
                      color="white"
                      item-text="name"
                      label="Users"
                      item-value="id"
                      return-object
                      v-model="selectedUser"
                      dense
                      filled
                    ></v-autocomplete>
                  </v-col>

                  <v-col cols="12">
                    Select role:
                    <v-radio-group v-model="newrole" required>
                    <v-radio
                      :key="0"
                      :label="`Viewer`"
                      :value="0"
                    ></v-radio>
                    <v-radio
                      :key="1"
                      :label="`Editor`"
                      :value="1"
                    ></v-radio>
                    <v-radio
                      :key="2"
                      :label="`Admin`"
                      :value="2"
                    ></v-radio>
                    </v-radio-group>
                  </v-col>

                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialogs">
                Cancel
              </v-btn>
              <v-btn color="blue darken-1" text @click="saveAssignedUser">
                Assign
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="assignClientDialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">Assign Client</span>
            </v-card-title>

            <v-card-text>
              <v-container>

                <v-row>
                  <v-col cols="12">
                    <v-autocomplete
                      :items="clients"
                      color="white"
                      item-text="name"
                      label="Users"
                      item-value="id"
                      return-object
                      v-model="selectedClient"
                      dense
                      filled
                    ></v-autocomplete>
                  </v-col>

                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialogs">
                Cancel
              </v-btn>
              <v-btn color="blue darken-1" text @click="saveAssignedClient">
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="unassignUserDialog" max-width="400px">
          <v-card>
            <v-card-title class="headline">Are you sure you want to remove the user from the project?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialogs">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="saveUnassignUser">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="deleteProjectDialog" max-width="400px">
          <v-card>
            <v-card-title class="headline">Are you sure you want to delete this project? THIS CANNOT BE UNDONE</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialogs">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteProjectConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>

      </v-toolbar>
    </template>

    <template v-slot:expanded-item="{ headers, item }">
      <td :colspan="headers.length" style="padding-left: 0px; padding-right: 0px;">
        <v-list subheader style="width:100%">
          <!-- <v-subheader>Recent chat</v-subheader> -->
          <v-list-item class="borderline">
            <v-list-item-content>

              <div class="text-center" style="max-width=100px">

                <v-list-item-icon class="justify-center" style="cursor: pointer; padding: 0; margin: 0;" v-on:click="showAssignUser(item, item.id)">
                    <v-icon size=30 :color="'green lighten-2'">
                      mdi-account-plus
                    </v-icon>
                </v-list-item-icon>

                <v-list-item-icon class="justify-center" style="cursor: pointer; padding: 0; margin: 0;" v-on:click="showAssignClient(item, item.id)">
                    <v-icon size=30 :color="'amber accent-2'">
                      mdi-crown
                    </v-icon>
                </v-list-item-icon>

                <v-divider class="ml-10" inset vertical></v-divider>

                <v-list-item-icon class="justify-end" style="cursor: pointer; padding: 0; margin: 0;" v-on:click="showDeleteProject(item, item.id)">
                    <v-icon size=30 :color="'red accent-2'">
                      mdi-briefcase-remove
                    </v-icon>
                </v-list-item-icon>


              </div>


            </v-list-item-content>
          </v-list-item>

          <div class="borderline theme--light">
            <div style="margin-left:20px">
              <h5 style="margin-bottom:0px; font-size: 1rem;">Client:</h5>
            </div>
          </div>

          <v-list-item v-if="item.client!=null" class="borderline">
            <v-list-item-avatar>
              <v-img :alt="`${item.name} avatar`" :src="'https://cdn.vuetifyjs.com/images/lists/1.jpg'"></v-img>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title v-text="item.client.name"></v-list-item-title>
              <v-list-item-subtitle v-text="'Client'"></v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-list-item v-else class="borderline">
            <v-list-item-content>
              <v-list-item-title v-text="'No Client assigned yet.'"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <div class="borderline theme--light" style="max-height: 5px; min-height: 5px;">
          </div>

          <div class="borderline theme--light">
            <div style="margin-left:20px">
              <h5 style="margin-bottom:0px; font-size: 1rem;">Users:</h5>
            </div>
          </div>


          <template v-if="item.users.length!=0">
            <v-list-item  v-for="(item, index) in item.users" :key="item.id" v-bind:class="getBottomLine(item.pivot.project_id, index)">
              <v-list-item-avatar>
                <v-img :alt="`${item.name} avatar`" :src="'https://cdn.vuetifyjs.com/images/lists/1.jpg'"></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title v-text="item.name"></v-list-item-title>
                <v-list-item-subtitle v-text="getRoleName(item.pivot.role)"></v-list-item-subtitle>
              </v-list-item-content>

              <v-list-item-icon style="cursor: pointer;" v-on:click="userEdit">
                  <v-icon size=30 :color="'blue lighten-2'">
                    mdi-account-edit
                  </v-icon>
              </v-list-item-icon>

              <v-list-item-icon style="cursor: pointer;" v-on:click="showUnassingUser(item)">
                  <v-icon size=30 :color="'red lighten-2'">
                    mdi-account-cancel
                  </v-icon>
              </v-list-item-icon>

            </v-list-item>
          </template>

          <v-list-item v-else class="borderline">
            <v-list-item-content>
              <v-list-item-title v-text="'No Users assigned yet.'"></v-list-item-title>
            </v-list-item-content>
          </v-list-item>

        </v-list>
      </td>

    </template>
  </v-data-table>

</v-container>
</template>

<script>
import axios from 'axios'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data: () => ({
    singleExpand: true,
    newProject:{
      name: ''
    },
    deleteProjectDialog: false,
    newProjectDialog: false,
    assignUserDialog: false,
    assignClientDialog: false,
    unassignUserDialog: false,

    projectToAssing:null,
    selectedClient:[],
    clients:[],
    filteredClients:[],
    filteredUsers: [],
    newrole:0,
    value: null,
    selectedUser: null,
    users: [],

    UserToUnassign:[],
    UnassignFromProjectID: 0,
    editedUser:[],
    expanded: [],
    projects: [],
    search: '',
    editedIndex: -1,

  }),
  validations: {
    newProject:{
      name: { required }
    }
  },

  computed: {
    nameErrors () {
      const errors = []
      if (!this.$v.newProject.name.$dirty) return errors
      !this.$v.newProject.name.required && errors.push('Name is required.')
      return errors
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
          text: 'E-mail',
          value: 'created_at'
        },
        {
          text: 'Role',
          value: 'updated_at'
        },
        {
          text: 'Actions',
          value: 'actions',
          sortable: false
        },
        {
          text: '',
          value: 'data-table-expand'
        },
      ]
    },
  },

  created() {
    this.getProjects(),
    this.getUsers(),
    this.getClients()
  },
  methods: {
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
      await this.$store.dispatch("projects/getAll");
      this.projects = await this.$store.getters["projects/list"];
      this.projects.forEach(element => element.users.sort((a, b) => (a.pivot.role < b.pivot.role) ? 1 : -1));
    },
    async getUsers() {
      await this.$store.dispatch("users/getusers");
      this.users = await this.$store.getters["users/users_role"];
    },
    async getClients() {
      await this.$store.dispatch("users/getclients");
      this.clients = await this.$store.getters["users/clients_role"];
    },


    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },
    async showAssignUser(item, project_id){
      await this.$store.dispatch("projects/filterUsers", project_id);
      this.filteredUsers = await this.$store.getters["projects/filteredusers"];
      console.log(this.filteredUsers);
      this.projectToAssing = project_id;
      this.assignUserDialog = true;
    },
    saveAssignedUser() {
      if(this.selectedUser === null){
        this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: 'Please select a user.'});
        return false;
      }
      // console.log(this.projectToAssing);
      // console.log(this.selectedUser.id);
      // console.log(this.newrole);
      var projectData = {
        projectId : this.projectToAssing,
        id: this.selectedUser.id,
        role: this.newrole
      }
      axios.post(`http://goldberg.local/assignuser`, projectData)
        .then(response => {
          console.log(response);
          if(response.status == 200){
            this.getProjects();
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
          }
          else{
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        }).catch(error => {
          if (error.response.status != 200){
            console.log(error);
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        });
      this.getProjects(),
      this.projectToAssing = ''
      this.selectedUser.id = ''
      this.newrole = 0
      this.selectedUser = undefined
      this.assignUserDialog = false;
      this.closeDialogs()
    },

    showAssignClient(item, project_id){
      this.projectToAssing = project_id;
      this.assignClientDialog = true;
    },
    saveAssignedClient() {
      if(this.selectedClient === null){
        this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: 'Please select a user.'});
        return false;
      }
      console.log(this.projectToAssing);
      console.log(this.selectedClient.id);
      var ClientData = {
        projectId : this.projectToAssing,
        client_id: this.selectedClient.id,
      }
      axios.post(`http://goldberg.local/assignclient`, ClientData)
        .then(response => {
          console.log(response);
          if(response.status == 200){
            this.getProjects();
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
          }
          else{
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        }).catch(error => {
          if (error.response.status != 200){
            console.log(error);
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        });
      this.assignClientDialog = false;
      this.closeDialogs()
    },

    showUnassingUser(item, project_id){
      this.UserToUnassign = item
      this.unassignUserDialog = true;
      this.UnassignFromProjectID = item.pivot.project_id
      console.log("showUnassingUser")
      console.log(this.UserToUnassign)
      console.log(this.UnassignFromProjectID)
    },
    saveUnassignUser() {
      var deleteUser={
        user_id: this.UserToUnassign.id,
        project_id: this.UnassignFromProjectID
      }
      axios.post(`http://goldberg.local/unassignUser`, deleteUser)
        .then(response => {
          console.log(response);
          if(response.status == 200){
            this.getProjects();
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
          }
          else{
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        }).catch(error => {
          if (error.response.status != 200){
            console.log(error);
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        });
        this.getProjects(),
      this.closeDialogs()
    },

    saveNewProject(){
      this.$v.$touch()
      if (this.$v.$invalid) {
        console.log('invalid form entries')
        this.submitStatus = 'ERROR'
      }
      else{
        axios.post(`http://goldberg.local/api/projects`, this.newProject)
          .then(response => {
            console.log(response);
            if(response.status == 200){
              this.getProjects();
              this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
              this.$v.$reset()
              this.newProject={
                user:''
              }
            }
            else{
              this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
            }
          }).catch(error => {
            if (error.response.status != 200){
              console.log(error);
              this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
            }
          });
          // this.newProject = null;
          this.newProjectDialog = false;
          this.closeDialogs()
      }

    },

    userEdit() {
      console.log("editing..")
    },

    getBottomLine(project_id, index) {
      var project = this.projects.find(project => project.id === project_id);
      if (project.users.length == index + 1) {
        return ''
      } else {
        return 'borderline'
      }
    },

    showDeleteProject(item, project_id){
      this.projectToDelete = project_id;
      this.deleteProjectDialog = true;

      console.log(this.projectToDelete)
    },

    deleteProjectConfirm(){
      console.log(this.projectToDelete)
      axios.delete(`http://goldberg.local/api/projects/`+this.projectToDelete )
        .then(response => {
          console.log(response);
          if(response.status == 200){
            this.getProjects();
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
          }
          else{
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        }).catch(error => {
          if (error.response.status != 200){
            console.log(error);
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
          }
        });
        this.getProjects()
        this.closeDialogs()
    },

    closeDialogs(){
      this.unassignUserDialog = false;
      this.assignUserDialog = false;
      this.assignClientDialog = false;
      this.newProjectDialog = false;
      this.deleteProjectDialog = false;
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
.v-messages{
  display:none;
}
.v-label{
  margin-bottom: 0px;
}
.v-input__slot{
  margin-bottom: 0px;
}
</style>
