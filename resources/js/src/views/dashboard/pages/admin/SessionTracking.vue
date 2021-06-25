<template>
  <v-container
    fluid
    tag="section"
    style="margin-top:10vh;"
    class="d-flex justify-center"
  >
      <v-data-table
        :headers="headers"
        :items="users"
        :search="search"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar
            flat
          >
            <div class="v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 pa-7 d-none d-sm-flex d-md-flex"
              style="max-height: 90px; width: auto;">
              <i aria-hidden="true" class="v-icon notranslate mdi mdi-account-clock theme--dark" style="font-size: 32px;">
              </i>
            </div>
            <v-toolbar-title>Session Tracking</v-toolbar-title>
            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              label="Search"
              append-icon="mdi-magnify"
              class="mx-4"
              single-line
              hide-details
            ></v-text-field>
            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>
            <v-spacer></v-spacer>
            <v-btn
              color="red"
              dark
              class="mb-2"
              v-on:click="showDeleteAllDialog"
            >
              Delete All Logs
              <v-icon size=26 :color="'white'" style="padding-left:10px">
                mdi-trash-can
              </v-icon>
            </v-btn>
          </v-toolbar>
        </template>

        <template  v-slot:item.avatar="{ item }">
          <v-avatar size="35">
            <img :src="'/storage/user-avatar/'+item.avatar" :alt="item.name">
          </v-avatar>
        </template>
        <template  v-slot:item.actions="{ item }">
          <v-icon
            class="mr-2"
            @click="openLog(item)">
            mdi-clock-start
          </v-icon>
        </template>
      </v-data-table>

      <v-dialog
        v-model="logdialog"
        max-width="700px"
      >

        <v-card>
          <v-card-title>
            <span class="headline">User Sessions</span>
          </v-card-title>
          <v-card-text>
            <v-banner two-line v-if="logdialog && sessions.length != 0">
                <v-icon
                  icon="mdi-web-clock"
                >
                  mdi-web-clock
                </v-icon>
              <template><u>Last activity:</u> <b><em style="padding-right: 30px">{{sessions[0].last_alive}}</em></b></template> <template><u>Total Sessions:</u> <b><em style="padding-right: 30px">{{sessions.length}}</em></b></template>  <template><u>Total Duration:</u> <b><em style="padding-right: 30px">{{total_duration }}</em></b></template>
              <div class="text-center pt-2">
                <v-btn
                  color="red"
                  dark
                  class="mb-2"
                  v-on:click="showDeleteSingleDialog"
                >
                  Delete Logs
                  <v-icon size=26 :color="'white'" style="padding-left:10px">
                    mdi-trash-can
                  </v-icon>
                </v-btn>
              </div>
            </v-banner>
                <v-data-table
                  :headers="session_header"
                  :items="sessions"
                  :search="search"
                  class="elevation-1"
                >
                  <template v-slot:top>
                  </template>
                  <template  v-slot:item.avatar="{ item }">
                    <v-avatar size="35">
                      <img :src="'/storage/user-avatar/'+item.avatar" :alt="item.name">
                    </v-avatar>
                  </template>
                  <template  v-slot:item.actions="{ item }">
                    <v-icon
                      class="mr-2"
                      @click="openLog(item)">
                      mdi-clock-start
                    </v-icon>
                  </template>
                </v-data-table>
          </v-card-text>
        </v-card>
      </v-dialog>

      <v-dialog v-model="dialogDeleteAll" max-width="400px">
        <v-card>
          <v-card-title class="text-heading-5">Are you sure you want to delete als session logs?</v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="closeDialogs">Cancel</v-btn>
            <v-btn color="blue darken-1" text @click="confirmDeleteAll">OK</v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog v-model="dialogSingle" max-width="400px">
        <v-card>
          <v-card-title class="text-heading-5">Are you sure you want to delete the logs of this user?</v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="closeDialogs">Cancel</v-btn>
            <v-btn color="blue darken-1" text @click="confirmDeleteSingle">OK</v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>

</v-container>
</template>
<script>
import { validationMixin } from 'vuelidate'
import { required, sameAs, minLength, email } from 'vuelidate/lib/validators'
export default {
  mixins: [validationMixin],
  data: () => ({
      sessions: [],
      total_duration: 0,
      user: null,
      users: [],
      search: '',
      logdialog: false,
      dialogDeleteAll: false,
      dialogSingle: false,
      session_header: [],
      open_item:[],
    }),

    computed: {
      headers () {
        return [
          { text: 'ID', align: 'start', value: 'id', },
          { text: 'Avatar', align: 'start', value: 'avatar'},
          { text: 'Name', value: 'name' },
          { text: 'E-mail', value: 'email' },
          { text: 'Role', value: 'role_name' },
          { text: 'Actions', align: 'center', value: 'actions', sortable: false },
        ]
      },
    },
    created () {
      this.getList();
    },
    methods: {
      async confirmDeleteAll(){
        await this.$store.dispatch("UsersManager/deleteallsessions");
        this.dialogDeleteAll = false
      },
      async confirmDeleteSingle(){
        console.log(this.open_item.id)
        await this.$store.dispatch("UsersManager/deleteusersessions", this.open_item.id);
        this.dialogSingle = false
          this.sessions = []
      },
      showDeleteAllDialog(){
        this.dialogDeleteAll = true
      },
      showDeleteSingleDialog(){
        this.dialogSingle = true
      },
      closeDialogs(){
        this.dialogDeleteAll = false
        this.dialogSingle = false
      },
      async openLog(item) {
        this.open_item = item
        this.total_duration = 0
        await this.$store.dispatch("TableManager/get", 'sessions');
        this.session_header = this.$store.getters["TableManager/headers"];
        this.session_header.splice(0, 1);
        this.session_header.splice(2, 1);
        this.session_header.splice(3, 1);
        console.log(item)
        await this.$store.dispatch("UsersManager/usersessions", item.id);
        this.sessions = await this.$store.getters["UsersManager/sessions"];

        for (var i=0; i<this.sessions.length; i++){
          if(Number.isInteger(this.sessions[i].duration_in_s)){
            this.total_duration = this.total_duration + this.sessions[i].duration_in_s
          }
        }
        if(this.sessions.length != 0){
          console.log(this.total_duration)
          this.total_duration = new Date(this.total_duration * 1000).toISOString().substr(11, 8)
          console.log(this.sessions)
        }
        this.logdialog = true
        console.log('open log!')

      },
      async getList () {
         await this.$store.dispatch("UsersManager/getAll");
         this.users = await this.$store.getters["UsersManager/users"];
      },
    },
}
</script>
<style>
.v-messages {
    display: block !important;
}
.v-data-table{
  margin-bottom: 0
  !important;
}
</style>
