<template>
<v-container id="regular-tables" class="d-flex justify-center" tag="section" style="margin-top:10vh;">

  <v-data-table
    class="elevation-0"
    :headers="headers"
    :items="projects"
    :search="search"
    :single-expand="singleExpand"
    :expanded.sync="expanded"
    item-key="name"
    show-expand
  >

    <template v-slot:top>
      <v-toolbar flat>
        <div class="hidden-md-and-down v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 pa-7" style="max-height: 90px; width: auto;">
          <i aria-hidden="true" class="v-icon notranslate mdi mdi-briefcase theme--dark" style="font-size: 32px;">
          </i>
        </div>
        <v-toolbar-title>Project Management</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-switch v-model="singleExpand" style="margin-bottom: 0px;" label="Single expand" class="ma-0 pa-0">
        </v-switch>

        <v-divider class="mx-4" inset vertical></v-divider>

        <v-dialog v-model="editUserRoleDialog" max-width="500px">
          <v-card>
            <v-card-title>
              <span class="headline">Change user role</span>
            </v-card-title>

            <v-card-text>
              <v-container>

                <v-row>
                  <v-col cols="12">
                    Select a new role for the user {{UserToEdit.name}}:
                    <v-radio-group v-model="UserToEdit.role" required>
                      <v-radio :key="0" :label="`Viewer`" :value="0"></v-radio>
                      <v-radio :key="1" :label="`Editor`" :value="1"></v-radio>
                      <v-radio :key="2" :label="`Admin`" :value="2"></v-radio>
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
              <v-btn color="blue darken-1" text @click="saveEditUserRole">
                Update
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="newProjectDialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
              New Project
              <v-icon size=26 :color="'white'" style="padding-left:10px">
                mdi-briefcase-plus
              </v-icon>
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
                    <v-text-field v-model="newProject.name" label="Project name"></v-text-field>

                    <v-menu
                    ref="menu"
                    v-model="menu"
                    :close-on-content-click="false"
                    :return-value.sync="newProject.dateFormatted"
                    transition="scale-transition"
                    offset-y min-width="auto">
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="computedDateFormatted"
                          label="Project start date"
                          prepend-icon="mdi-calendar"
                          readonly v-bind="attrs"
                          v-on="on"
                          @blur="date = parseDate(newProject.dateFormatted)">
                        </v-text-field>
                      </template>
                      <v-date-picker
                        v-model="newProject.date"
                        color="primary"
                        no-title
                        scrollable
                        style="background: white;"
                        @input="menu = false">
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu = false">
                          Cancel
                        </v-btn>
                        <v-btn text color="primary" @click="$refs.menu.save(newProject.date)">
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-menu>

                    <v-text-field v-model="newProject.coordinates_x" label="Main coordinate X"></v-text-field>

                    <v-text-field v-model="newProject.coordinates_y" label="Main coordinate Y"></v-text-field>

                    <v-text-field v-model="newProject.coordinates_z" label="Main coordinate Z"></v-text-field>

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

        <v-dialog v-model="editProjectDialog" max-width="500px">

          <v-card>
            <v-card-title>
              <span class="headline">Edit project</span>
            </v-card-title>

            <v-card-text>
              <v-container>

                <v-row>
                  <v-col cols="12">
                    <v-text-field v-model="projectToEdit.name" label="Project name"></v-text-field>

                    <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :return-value.sync="projectToEdit.dateFormatted" transition="scale-transition" offset-y min-width="auto">
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="editComputedDateFormatted" label="Project start date" prepend-icon="mdi-calendar" v-bind="attrs" v-on="on" @blur="projectToEdit.date = parseDate(projectToEdit.dateFormatted)"></v-text-field>
                      </template>

                      <v-date-picker v-model="projectToEdit.date" color="primary" no-title scrollable style="background: white;" @input="menu2 = false">
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu2 = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.menu2.save(projectToEdit.dateFormatted)">OK</v-btn>
                      </v-date-picker>

                    </v-menu>

                    <v-text-field v-model="projectToEdit.coordinates_x" label="Main coordinate X"></v-text-field>

                    <v-text-field v-model="projectToEdit.coordinates_y" label="Main coordinate Y"></v-text-field>

                    <v-text-field v-model="projectToEdit.coordinates_z" label="Main coordinate Z"></v-text-field>

                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDialogs">
                Cancel
              </v-btn>
              <v-btn color="blue darken-1" text @click="saveEditProject">
                Update
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-dialog v-model="assignUserDialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">Assign User</span>
            </v-card-title>

            <v-card-text>
              <v-container>

                <v-row>
                  <v-col cols="12">
                    <v-autocomplete :items="filteredUsers" color="white" item-text="name" label="Users" item-value="id" return-object v-model="selectedUser" dense filled></v-autocomplete>
                  </v-col>

                  <v-col cols="12">
                    Select role:
                    <v-radio-group v-model="newrole" required>
                      <v-radio :key="0" :label="`Viewer`" :value="0"></v-radio>
                      <v-radio :key="1" :label="`Editor`" :value="1"></v-radio>
                      <v-radio :key="2" :label="`Admin`" :value="2"></v-radio>
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
                    <v-autocomplete :items="clients" color="white" item-text="name" label="Users" item-value="id" return-object v-model="selectedClient" dense filled></v-autocomplete>
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

    <template style="" v-slot:expanded-item="{ headers, item }">
      <td :colspan="headers.length" style="padding-left: 0px; padding-right: 0px;">
        <v-list subheader style="width:100%">
          <div class="theme--light ma-4">
            <div>
              <h5 style="margin-bottom:0px; font-size: 1rem;">Project settings:</h5>
            </div>
          </div>

          <div class="ma-4 d-flex justify-space-around flex-wrap custom">
            <v-list-item-icon style=" padding: 0; margin: 0; margin-bottom:10px;" v-on:click="showAssignUser(item, item.id)">
              <v-btn min-width="120" max-width="180" color="primary" dark>
                Assign User
                <v-icon size=26 :color="'white'" style="padding-left:10px">
                  mdi-account-plus
                </v-icon>
              </v-btn>
            </v-list-item-icon>

            <v-list-item-icon style="padding: 0; margin: 0;margin-bottom:10px;" v-on:click="showAssignClient(item, item.id)">
              <v-btn min-width="120" max-width="180" color="primary" dark>
                Assign Client
                <v-icon size=26 :color="'white'" style="padding-left:10px">
                  mdi-account-tie
                </v-icon>
              </v-btn>
            </v-list-item-icon>

            <v-list-item-icon style="padding: 0; margin: 0;margin-bottom:10px;" v-on:click="showEditProjectDialog(item, item.id)">
              <v-btn min-width="120" max-width="180" color="blue darken-1" dark>
                Edit Project
                <v-icon size=26 :color="'white'" style="padding-left:10px">
                  mdi-briefcase-edit
                </v-icon>
              </v-btn>
            </v-list-item-icon>

            <v-list-item-icon style="padding: 0; margin: 0;margin-bottom:10px;" v-on:click="showDeleteProject(item, item.id)">
              <v-btn min-width="120" max-width="180" color="red darken-1" dark>
                Delete Project
                <v-icon size=26 :color="'white'" style="padding-left:10px">
                  mdi-briefcase-remove
                </v-icon>
              </v-btn>
            </v-list-item-icon>
          </div>
          <v-divider class="ma-0" />

          <div class="theme--light ma-4">
            <div style="">
              <h5 style="margin-bottom:0px; font-size: 1rem;">Client:</h5>
            </div>
          </div>

          <v-list-item v-if="item.client!=null" class="d-flex justify-center">
            <v-list-item-avatar>
              <v-img :alt="`${item.name} avatar`" :src="'/storage/user-avatar/'+item.client.avatar"></v-img>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title v-text="item.client.name"></v-list-item-title>
              <v-list-item-subtitle v-text="'Client'"></v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-divider v-if="item.client!=null" class="ma-0" />

          <template v-else>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title v-text="'No Client assigned yet.'"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-divider class="ma-0" />
          </template>


          <div class="theme--light" style="max-height: 5px; min-height: 5px;">
          </div>

          <div class="theme--light ma-4">
            <div style="">
              <h5 style="margin-bottom:0px; font-size: 1rem;">Users:</h5>
            </div>
          </div>

          <template v-if="item.users.length!=0">
            <template v-for="(item, index) in item.users">
              <v-list-item :key="item.id">
                <v-list-item-avatar>
                  <v-img :alt="`${item.name} avatar`" :src="'/storage/user-avatar/'+item.avatar"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                  <v-list-item-title v-text="item.name"></v-list-item-title>
                  <v-list-item-subtitle v-text="getRoleName(item.pivot.role)"></v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-icon style="cursor: pointer;" v-on:click="showEditUserRoleDialog(item)">
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
              <v-divider class="ma-0" v-if="getBottomLine(item.pivot.project_id, index)" />
            </template>
          </template>

          <template v-else>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title v-text="'No Users assigned yet.'"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>

        </v-list>
      </td>

    </template>
  </v-data-table>

</v-container>
</template>

<script>
import axios from 'axios'

export default {
  data: vm => ({
    itemsPerPage: [10, 20, 30, -1],
    mydate: null,

    menu: false,
    menu2: false,

    singleExpand: true,
    newProject: {
      name: null,
      date: new Date().toISOString().substr(0, 10),
      dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
      coordinates_x: null,
      coordinates_y: null,
      coordinates_z: null,
    },
    editProjectDialog: false,
    editUserRoleDialog: false,
    deleteProjectDialog: false,
    newProjectDialog: false,
    assignUserDialog: false,
    assignClientDialog: false,
    unassignUserDialog: false,

    projectToAssing: null,
    selectedClient: undefined,
    clients: [],
    filteredClients: [],
    filteredUsers: [],
    newrole: 0,
    value: null,
    selectedUser: undefined,
    users: [],

    projectToEdit: {
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

    UserToUnassign: [],
    UnassignFromProjectID: 0,
    editedUser: [],
    expanded: [],
    projects: [],
    search: '',
    editedIndex: -1,

  }),

  computed: {
    computedDateFormatted() {
      return this.formatDate(this.newProject.date)
    },
    editComputedDateFormatted() {
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
          text: '',
          value: 'data-table-expand'
        },
      ]
    },
  },
  watch: {
    'newProject.date': function(val) {
      this.newProject.dateFormatted = this.formatDate(this.newProject.date)
    },
    'projectToEdit.date': function(val) {
      this.projectToEdit.dateFormatted = this.formatDate(this.projectToEdit.date)
    }
  },

  created() {
    this.getProjects(),
      this.getUsers(),
      this.getClients()
  },
  methods: {
    formatDate(date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },
    parseDate(date) {
      if (!date) return null

      const [day, month, year] = date.split('.')
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
    },
    hasDecimal(num) {
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
      await this.$store.dispatch("ProjectsManager/getAll");
      const projectsJson = await this.$store.getters["ProjectsManager/projects"];
      this.projects = projectsJson.projects.map(projects => ({
        ...projects,
        project_start_date: this.formatDate(projects.project_start_date)
      }))
    },

    async getUsers() {
      await this.$store.dispatch("UsersManager/getusers");
      this.users = await this.$store.getters["UsersManager/users_role"];
    },
    async getClients() {
      await this.$store.dispatch("UsersManager/getclients");
      this.clients = await this.$store.getters["UsersManager/clients_role"];
    },

    async showAssignUser(item, project_id) {
      await this.$store.dispatch("ProjectsManager/filterUsers", project_id);
      this.filteredUsers = await this.$store.getters["ProjectsManager/filteredusers"];
      if (this.filteredUsers.length == 0) {
        this.$store.dispatch('NotificationsManager/setNotificationStatus', {
          type: 'red',
          text: 'No more users to assign for this project.'
        });
      } else {
        this.projectToAssing = project_id;
        this.assignUserDialog = true;
      }

    },
    saveAssignedUser() {
      if (this.selectedUser === undefined) {
        this.$store.dispatch('NotificationsManager/setNotificationStatus', {
          type: 'red',
          text: 'Please select a user.'
        });
        return false;
      }
      var projectData = {
        projectId: this.projectToAssing,
        id: this.selectedUser.id,
        role: this.newrole
      }
      this.$store.dispatch('ProjectsManager/assignUser', projectData)
      this.getProjects(),
      this.projectToAssing = ''
      this.selectedUser.id = ''
      this.newrole = 0
      this.selectedUser = undefined
      this.assignUserDialog = false;
      this.closeDialogs()
    },

    showAssignClient(item, project_id) {
      this.projectToAssing = project_id;
      this.assignClientDialog = true;
    },
    saveAssignedClient() {
      if (this.selectedClient === undefined) {
        this.$store.dispatch('NotificationsManager/setNotificationStatus', {
          type: 'red',
          text: 'Please select a user.'
        });
        return false;
      }
      var ClientData = {
        projectId: this.projectToAssing,
        client_id: this.selectedClient.id,
      }
      axios.post(`/assignclient`, ClientData)
        .then(response => {
          if (response.status == 200) {
            this.getProjects();
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'green',
              text: response.data
            });
          } else {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        }).catch(error => {
          if (error.response.status != 200) {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        });
      this.selectedClient = undefined;
      this.assignClientDialog = false;
      this.closeDialogs()
    },

    showUnassingUser(item, project_id) {
      this.UserToUnassign = item
      this.unassignUserDialog = true;
      this.UnassignFromProjectID = item.pivot.project_id
    },
    saveUnassignUser() {
      var deleteUser = {
        user_id: this.UserToUnassign.id,
        project_id: this.UnassignFromProjectID
      }
      axios.post(`/unassignUser`, deleteUser)
        .then(response => {
          if (response.status == 200) {
            this.getProjects();
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'green',
              text: response.data
            });
          } else {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        }).catch(error => {
          if (error.response.status != 200) {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        });
      this.getProjects(),
        this.closeDialogs()
    },

    saveNewProject() {
      var conditionOne = false;
      var conditionTwo = false;
      if (!this.newProject.name) {
        this.$store.dispatch('NotificationsManager/setNotificationStatus', {
          type: 'red',
          text: 'Please enter a project name.'
        });
      } else {
        conditionOne = true;
      }
      if ((!this.hasDecimal(this.newProject.coordinates_x) || !this.hasDecimal(this.newProject.coordinates_y) || !this.hasDecimal(this.newProject.coordinates_z)) && conditionOne) {
        this.$store.dispatch('NotificationsManager/setNotificationStatus', {
          type: 'red',
          text: 'Coordinates only as decimal. Example: 10.22111'
        });
      } else {
        conditionTwo = true;
      }

      if (conditionOne && conditionTwo) {
        axios.post(`/api/projects`, this.newProject)
          .then(response => {
            if (response.status == 200) {
              this.getProjects();
              this.$store.dispatch('NotificationsManager/setNotificationStatus', {
                type: 'green',
                text: response.data
              });
              this.newProject = {
                name: null,
                date: new Date().toISOString().substr(0, 10),
                coordinates_x: null,
                coordinates_y: null,
                coordinates_z: null,
              }
            } else {
              this.$store.dispatch('NotificationsManager/setNotificationStatus', {
                type: 'red',
                text: response.data
              });
            }
          });
        this.newProjectDialog = false;

        this.closeDialogs()
      }

    },

    showEditUserRoleDialog(item) {
      this.UserToEdit.name = item.name
      this.UserToEdit.role = item.pivot.role
      this.UserToEdit.user_id = item.pivot.user_id
      this.UserToEdit.project_id = item.pivot.project_id
      this.editUserRoleDialog = true
    },
    saveEditUserRole() {
      axios.post(`/reassignUser`, this.UserToEdit)
        .then(response => {
          if (response.status == 200) {
            this.getProjects();
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'green',
              text: response.data
            });
          } else {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        }).catch(error => {
          if (error.response.status != 200) {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        });
      this.UserToEdit = {
          name: '',
          role: 0,
          user_id: 0,
          project_id: 0
        },
        this.editUserRoleDialog = false;
      this.closeDialogs()
    },

    showEditProjectDialog(item, item_id) {
      this.editProjectDialog = true;
      this.projectToEdit.id = item.id
      this.projectToEdit.name = item.name
      this.projectToEdit.date = this.parseDate(item.project_start_date)
      this.projectToEdit.dateFormatted = item.project_start_date
      this.projectToEdit.coordinates_x = item.coordinates_x
      this.projectToEdit.coordinates_y = item.coordinates_y
      this.projectToEdit.coordinates_z = item.coordinates_z
    },

    saveEditProject() {
      axios.patch(`/api/projects/` + this.projectToEdit.id, this.projectToEdit)
        .then(response => {
          if (response.status == 200) {
            this.getProjects();
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'green',
              text: response.data
            });
            // this.newProject.name = null
            this.closeDialogs();
          } else {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        });
    },

    getBottomLine(project_id, index) {
      var project = this.projects.find(project => project.id === project_id);
      if (project.users.length == index + 1) {
        return false
      } else {
        return true
      }
    },

    showDeleteProject(item, project_id) {
      this.projectToDelete = project_id;
      this.deleteProjectDialog = true;
    },

    deleteProjectConfirm() {
      axios.delete(`/api/projects/` + this.projectToDelete)
        .then(response => {
          if (response.status == 200) {
            this.getProjects();
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'green',
              text: response.data
            });
          } else {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        }).catch(error => {
          if (error.response.status != 200) {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {
              type: 'red',
              text: response.data
            });
          }
        });
      this.getProjects()
      this.closeDialogs()
    },

    closeDialogs() {
      this.editUserRoleDialog = false;
      this.unassignUserDialog = false;
      this.assignUserDialog = false;
      this.assignClientDialog = false;
      this.newProjectDialog = false;
      this.deleteProjectDialog = false;
      this.editProjectDialog = false;
    }
  },
}
</script>

<style>
.borderline {
  border-bottom: 1px solid rgba(0, 0, 0, .22)
}

.v-card__text,
.v-card__title {
  word-break: normal;
  /* maybe !important  */
}

.v-label {
  margin-bottom: 0px;
}

.v-input__slot {
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
  .custom>div {
    justify-content: space-around;
    flex-shrink: 1;
    flex-basis: auto;
    flex: 50%;
  }
}

@media only screen and (max-width: 470px) {
  .custom>div {
    flex: none;
  }
}
</style>
