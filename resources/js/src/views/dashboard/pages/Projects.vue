<template>
<v-container v-if="ready" id="regular-tables" class="d-flex justify-center" tag="section" style="margin-top:10vh;">

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
                    <v-text-field
                      v-model="project.name"
                      label="Project name"
                      @input="$v.project.name.$touch()"
                      @blur="$v.project.name.$touch()"
                      :error-messages="name_Errors"
                    ></v-text-field>

                    <v-text-field
                      v-model="project.project_code"
                      label="Project Code"
                      @input="$v.project.project_code.$touch()"
                      @blur="$v.project.project_code.$touch()"
                      :error-messages="project_code_Errors"
                    ></v-text-field>

                    <v-text-field
                      v-model="project.type"
                      label="Type"
                      @input="$v.project.type.$touch()"
                      @blur="$v.project.type.$touch()"
                      :error-messages="type_Errors"
                    ></v-text-field>

                    <v-menu
                    ref="menu"
                    v-model="menu"
                    :close-on-content-click="false"
                    :return-value.sync="project.dateFormatted"
                    transition="scale-transition"
                    offset-y min-width="auto">
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="computedDateFormatted"
                          label="Project start date"
                          prepend-icon="mdi-calendar"
                          readonly v-bind="attrs"
                          v-on="on"
                          @blur="date = parseDate(project.dateFormatted)">
                        </v-text-field>
                      </template>
                      <v-date-picker
                        v-model="project.date"
                        color="primary"
                        no-title
                        scrollable
                        style="background: white;"
                        @input="menu = false">
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu = false">
                          Cancel
                        </v-btn>
                        <v-btn text color="primary" @click="$refs.menu.save(project.date)">
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-menu>

                    <v-text-field
                    v-model="project.utm_x"
                    label="Main UTM X"
                    @input="$v.project.utm_x.$touch()"
                    @blur="$v.project.utm_x.$touch()"
                    :error-messages="utm_x_Errors"
                    ></v-text-field>

                    <v-text-field
                    v-model="project.utm_y"
                    label="Main UTM Y"
                    @input="$v.project.utm_y.$touch()"
                    @blur="$v.project.utm_y.$touch()"
                    :error-messages="utm_y_Errors"
                    ></v-text-field>

                    <v-text-field
                    v-model="project.utm_z"
                    label="Main UTM Z"
                    @input="$v.project.utm_z.$touch()"
                    @blur="$v.project.utm_z.$touch()"
                    :error-messages="utm_z_Errors"
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

        <v-dialog v-model="editProjectDialog" max-width="500px">

          <v-card>
            <v-card-title>
              <span class="headline">Edit project</span>
            </v-card-title>

            <v-card-text>
              <v-container>

                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="project.name"
                      label="Project name"
                      @input="$v.project.name.$touch()"
                      @blur="$v.project.name.$touch()"
                      :error-messages="name_Errors"
                    ></v-text-field>

                    <v-text-field
                      v-model="project.project_code"
                      label="Project Code"
                      @input="$v.project.project_code.$touch()"
                      @blur="$v.project.project_code.$touch()"
                      :error-messages="project_code_Errors"
                    ></v-text-field>

                    <v-text-field
                      v-model="project.type"
                      label="Type"
                      @input="$v.project.type.$touch()"
                      @blur="$v.project.type.$touch()"
                      :error-messages="type_Errors"
                    ></v-text-field>


                    <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :return-value.sync="project.dateFormatted" transition="scale-transition" offset-y min-width="auto">
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="editComputedDateFormatted" label="Project start date" prepend-icon="mdi-calendar" v-bind="attrs" v-on="on" @blur="project.date = parseDate(project.dateFormatted)"></v-text-field>
                      </template>

                      <v-date-picker v-model="project.date" color="primary" no-title scrollable style="background: white;" @input="menu2 = false">
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu2 = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.menu2.save(project.dateFormatted)">OK</v-btn>
                      </v-date-picker>

                    </v-menu>

                    <v-text-field
                    v-model="project.utm_x"
                    label="Main UTM X"
                    @input="$v.project.utm_x.$touch()"
                    @blur="$v.project.utm_x.$touch()"
                    :error-messages="utm_x_Errors"
                    ></v-text-field>

                    <v-text-field
                    v-model="project.utm_y"
                    label="Main UTM Y"
                    @input="$v.project.utm_y.$touch()"
                    @blur="$v.project.utm_y.$touch()"
                    :error-messages="utm_y_Errors"
                    ></v-text-field>

                    <v-text-field
                    v-model="project.utm_z"
                    label="Main UTM Z"
                    @input="$v.project.utm_z.$touch()"
                    @blur="$v.project.utm_z.$touch()"
                    :error-messages="utm_z_Errors"
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
import { validationMixin } from 'vuelidate'
import { required, decimal, numeric } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data: vm => ({
    ready:false,
    itemsPerPage: [10, 20, 30, -1],
    mydate: null,
    headers: null,

    menu: false,
    menu2: false,

    singleExpand: true,
    project: {
      name: null,
      project_code: null,
      type: null,
      date: new Date().toISOString().substr(0, 10),
      dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
      utm_x: null,
      utm_y: null,
      utm_z: null,
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

  validations: {
    project:{
      name: { required },
      project_code: { required },
      type: { required },
      utm_x: {
        required,
        mydecimal(utm_x) {
          return (
            /^\d{1,8}$|^\d{1,8}\.\d{1,10}$/.test(utm_x)
          );
        }
      },
      utm_y: {
        required,
        mydecimal(utm_y) {
          return (
            /^\d{1,8}$|^\d{1,8}\.\d{1,10}$/.test(utm_y)
          );
        }
      },
      utm_z: {
        required,
        mydecimal(utm_z) {
          return (
            /^\d{1,8}$|^\d{1,8}\.\d{1,10}$/.test(utm_z)
          );
        }
      }
    }
  },

  computed: {
    name_Errors () {
      const errors = []
      if (!this.$v.project.name.$dirty) return errors
      !this.$v.project.name.required && errors.push('Name is required.')
      return errors
    },
    project_code_Errors () {
      const errors = []
      if (!this.$v.project.project_code.$dirty) return errors
      !this.$v.project.project_code.required && errors.push('Project Code is required.')
      return errors
    },
    type_Errors () {
      const errors = []
      if (!this.$v.project.type.$dirty) return errors
      !this.$v.project.type.required && errors.push('Type is required.')
      return errors
    },
    utm_x_Errors () {
      const errors = []
      if (!this.$v.project.utm_x.$dirty) return errors
      !this.$v.project.utm_x.required && errors.push('UTM X is required.')
      !this.$v.project.utm_x.mydecimal && errors.push('Max number XXXXXXXX.YY')
      return errors
    },
    utm_y_Errors () {
      const errors = []
      if (!this.$v.project.utm_y.$dirty) return errors
      !this.$v.project.utm_y.required && errors.push('UTM Y is required.')
      !this.$v.project.utm_y.mydecimal && errors.push('Max number XXXXXXXX.YY')
      return errors
    },
    utm_z_Errors () {
      const errors = []
      if (!this.$v.project.utm_z.$dirty) return errors
      !this.$v.project.utm_z.required && errors.push('UTM Z is required.')
      !this.$v.project.utm_z.mydecimal && errors.push('Max number XXXXXXXX.YY')
      return errors
    },
    computedDateFormatted() {
      return this.formatDate(this.project.date)
    },
    editComputedDateFormatted() {
      return this.formatDate(this.project.date)
    },
  },
  watch: {
    'project.date': function(val) {
      this.project.dateFormatted = this.formatDate(this.project.date)
    },
    'project.date': function(val) {
      this.project.dateFormatted = this.formatDate(this.project.date)
    }
  },

  async created() {
      await this.$store.dispatch("TableManager/get", 'projects');
      this.headers = this.$store.getters["TableManager/headers"];
      var actions = {
        text: '', value: 'data-table-expand'
      }
      this.headers.push(actions)
      this.getProjects()
      this.getUsers()
      this.getClients()
      this.ready=true;

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
      console.log(projectsJson)
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
      console.log(this.clients)
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
    async saveAssignedUser() {
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
      await this.$store.dispatch('ProjectsManager/assignUser', projectData)

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
    async saveAssignedClient() {
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

      await this.$store.dispatch('ProjectsManager/assignClient', ClientData);
      this.getProjects(),
      this.selectedClient = undefined;
      this.assignClientDialog = false;
      this.closeDialogs()
    },

    showUnassingUser(item, project_id) {
      this.UserToUnassign = item
      this.unassignUserDialog = true;
      this.UnassignFromProjectID = item.pivot.project_id
    },
    async saveUnassignUser() {
      var deleteUser = {
        user_id: this.UserToUnassign.id,
        project_id: this.UnassignFromProjectID
      }
      await this.$store.dispatch('ProjectsManager/unassignUser', deleteUser)
      this.getProjects(),
      this.closeDialogs()
    },

    async saveNewProject() {
      this.$v.project.name.$touch()
      this.$v.project.project_code.$touch()
      this.$v.project.type.$touch()
      this.$v.project.utm_x.$touch()
      this.$v.project.utm_y.$touch()
      this.$v.project.utm_z.$touch()
      if(
        this.$v.project.name.$invalid||
        this.$v.project.project_code.$invalid||
        this.$v.project.type.$invalid||
        this.$v.project.utm_x.$invalid||
        this.$v.project.utm_y.$invalid||
        this.$v.project.utm_z.$invalid
      ){}
      else{
        this.$v.$reset()
        await this.$store.dispatch('ProjectsManager/create', this.project)
        this.getProjects()
        this.project = {
            name: null,
            project_code: null,
            type: null,
            coordinates_x: null,
            date: new Date().toISOString().substr(0, 10),
            utm_x: null,
            utm_y: null,
            utm_z: null,
          }
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
    async saveEditUserRole() {
      await this.$store.dispatch('ProjectsManager/reassignUser', this.UserToEdit)
      this.getProjects()
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
      this.project.id = item.id
      this.project.name = item.name
      this.project.project_code = item.project_code
      this.project.type = item.type
      this.project.date = this.parseDate(item.project_start_date)
      this.project.dateFormatted = item.project_start_date
      this.project.utm_x = item.utm_x
      this.project.utm_y = item.utm_y
      this.project.utm_z = item.utm_z
    },

    async saveEditProject() {
      this.$v.project.name.$touch()
      this.$v.project.project_code.$touch()
      this.$v.project.type.$touch()
      this.$v.project.utm_x.$touch()
      this.$v.project.utm_y.$touch()
      this.$v.project.utm_z.$touch()
      if(
        this.$v.project.name.$invalid||
        this.$v.project.project_code.$invalid||
        this.$v.project.type.$invalid||
        this.$v.project.utm_x.$invalid||
        this.$v.project.utm_y.$invalid||
        this.$v.project.utm_z.$invalid
      ){}
      else{
        this.$v.$reset()
        await this.$store.dispatch('ProjectsManager/update', this.project)
        this.getProjects()
        this.closeDialogs();
        this.project = {
            name: null,
            project_code: null,
            type: null,
            coordinates_x: null,
            date: new Date().toISOString().substr(0, 10),
            utm_x: null,
            utm_y: null,
            utm_z: null,
          }
      }

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

    async deleteProjectConfirm() {
      await this.$store.dispatch('ProjectsManager/destroy', this.projectToDelete)
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
