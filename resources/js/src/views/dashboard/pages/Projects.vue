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
        <v-spacer></v-spacer>
        <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
        <v-switch v-model="singleExpand" label="Single expand" class="mt-2"></v-switch>
      </v-toolbar>
    </template>

    <template v-slot:expanded-item="{ headers, item }">
      <td :colspan="headers.length" style="padding-left: 0px; padding-right: 0px;">
        <v-list subheader style="width:100%">
          <!-- <v-subheader>Recent chat</v-subheader> -->
          <v-list-item class="borderline">
            <v-list-item-content>

              <div class="text-center">
                <v-list-item-icon style="cursor: pointer; padding: 0; margin: 0;" v-on:click="showAssignUser(item, item.id)">
                    <v-icon size=30 :color="'green lighten-2'">
                      mdi-account-plus
                    </v-icon>
                </v-list-item-icon>

                <v-list-item-icon style="cursor: pointer; padding: 0; margin: 0;" v-on:click="showAssignClient(item, item.id)">
                    <v-icon size=30 :color="'amber accent-2'">
                      mdi-crown
                    </v-icon>
                </v-list-item-icon>
              </div>

            </v-list-item-content>
          </v-list-item>

          <v-list-item v-for="(item, index) in item.users" :key="item.id" v-bind:class="getBottomLine(item.pivot.project_id, index)">
            <v-list-item-avatar>
              <v-img :alt="`${item.name} avatar`" :src="'https://cdn.vuetifyjs.com/images/lists/1.jpg'"></v-img>
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title v-text="item.name"></v-list-item-title>
            </v-list-item-content>

            <v-list-item-content>
              <v-list-item-subtitle v-text="getRoleName(item.pivot.role)"></v-list-item-subtitle>
            </v-list-item-content>


            <v-list-item-icon style="cursor: pointer;" v-on:click="userEdit">
                <v-icon size=30 :color="'blue lighten-2'">
                  mdi-account-edit
                </v-icon>
            </v-list-item-icon>

            <v-list-item-icon style="cursor: pointer;" v-on:click="deleteItem(item)">
                <v-icon size=30 :color="'red lighten-2'">
                  mdi-account-cancel
                </v-icon>
            </v-list-item-icon>

          </v-list-item>

        </v-list>
      </td>

    </template>
  </v-data-table>


  <v-data-table :headers="headers" :items="projects" :search="search" class="elevation-1">
    <template v-slot:top>
      <v-toolbar flat>

        <div class="mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 success pa-7 d-none d-sm-flex d-md-flex" style="max-height: 90px; width: auto;">
          <i aria-hidden="true" class="v-icon notranslate mdi mdi-clipboard-text theme--dark" style="font-size: 32px;">
          </i>
        </div>

        <v-toolbar-title>Project Management</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>

        <v-dialog v-model="assignUserDialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
              Assign User
            </v-btn>
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
              <v-btn color="blue darken-1" text @click="closeDialog">
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
            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
              Assign Client
            </v-btn>
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
              <v-btn color="blue darken-1" text @click="closeDialog">
                Cancel
              </v-btn>
              <v-btn color="blue darken-1" text @click="saveAssignedClient">
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>


        <v-dialog v-model="dialogDelete" max-width="400px">
          <v-card>
            <v-card-title class="headline">Are you sure you want to remove the user from the project?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>

      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon small class="mr-2" @click="editItem(item)">
        mdi-pencil
      </v-icon>
      <v-icon small @click="deleteItem(item)">
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="getProjects">
        Reset
      </v-btn>
    </template>
  </v-data-table>

</v-container>
</template>

<script>
// import Pagination from "@/components/Pagination";

export default {
  components: {
    // pagination: Pagination,
  },
  data: () => ({
    // usersrole:[],
    projectToAssing:null,
    selectedClient:[],
    clients:[],
    filteredClients:[],
    filteredUsers: [],
    newrole:0,
    value: null,
    selectedUser: null,
    users: [],
    assignUserDialog: false,
    assignClientDialog: false,
    editedUser:[],
    expanded: [],
    projects: [],
    search: '',
    singleExpand: false,
    dialog: false,
    dialogDelete: false,
    editedIndex: -1,
    editedItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    defaultItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
  }),

  computed: {
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
  watch: {
    dialog(val) {
      val || this.close()
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
  },
  created() {
    this.getProjects(),
    this.getUsers(),
    this.getClients()
  },
  methods: {
    getRoleName(rolenr) {
      if (rolenr == 0) {
        return 'User-Viewer'
      } else if (rolenr == 1) {
        return 'User-Editor'
      } else if (rolenr == 2) {
        return 'User-Admin'
      } else {
        return 'Role: ' + rolenr
      }
    },
    async getProjects() {
      await this.$store.dispatch("projectsmod/list");
      this.projects = await this.$store.getters["projectsmod/list"];
      this.projects.forEach(element => element.users.sort((a, b) => (a.pivot.role < b.pivot.role) ? 1 : -1));
    },
    async getUsers() {
      await this.$store.dispatch("usersmod/getusers");
      this.users = await this.$store.getters["usersmod/users_role"];
    },
    async getClients() {
      await this.$store.dispatch("usersmod/getclients");
      this.clients = await this.$store.getters["usersmod/clients_role"];
    },
    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },
    deleteItem(item) {
      this.dialogDelete = true
      console.log(item);
      this.editedUser = Object.assign({}, item)
    },
    deleteItemConfirm() {
      var item=this.editedUser
      var project = this.projects.find(project => project.id === item.pivot.project_id)
      var indexProject = this.projects.indexOf(project)
      var indexUser = project.users.indexOf(item)
      project.users.splice(this.indexUser, 1)
      this.closeDelete()
    },
    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    saveAssignedUser() {

      if(this.selectedUser === null){
        this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: 'Please select a user.'});
        return false;
      }
      console.log(this.projectToAssing);
      console.log(this.selectedUser.id);
      console.log(this.newrole);
      this.close()
    },
    saveAssignedClient() {
      if(this.selectedUser === null){
        this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: 'Please select a user.'});
        return false;
      }
      console.log(this.projectToAssing);
      console.log(this.selectedUser.id);
      this.close()
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
    showAssignUser(item, project_id){
      this.projectToAssing = project_id;
      var idsToDelete = item.users.map(function(elt) {return elt.id;});
      this.filteredUsers = this.users.filter(function(elt) {return idsToDelete.indexOf(elt.id) === -1;});
      this.assignUserDialog = true;
    },
    showAssignClient(item, project_id){
      this.projectToAssing = project_id;
      // var idsToDelete = item.clients.map(function(elt) {return elt.id;});
      // this.filteredClients = this.clients.filter(function(elt) {return idsToDelete.indexOf(elt.id) === -1;});
      this.assignClientDialog = true;
    },
    closeDialog(){
      this.assignUserDialog = false;
      this.assignClientDialog = false;
    }
  },
}
</script>

<style>
.borderline {
  border-bottom: 1px solid;
}
.v-card__text, .v-card__title {
  word-break: normal; /* maybe !important  */
}
</style>
