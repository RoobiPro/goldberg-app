<template>
<v-container
id="regular-tables"
class="d-flex justify-center"
tag="section"
style="margin-top:10vh;">
  <v-data-table
    :headers="headers"
    :items="campaigns"
    :search="search"
    item-key="name"
  >
    <template v-slot:top>
      <v-toolbar flat>
        <div class="hidden-md-and-down v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark pa-7"
          style="max-height: 90px; width: auto;">
          <i aria-hidden="true" class="v-icon notranslate mdi mdi-clipboard-text theme--dark" style="font-size: 32px;">
          </i>
        </div>
        <v-toolbar-title>Project campaigns</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>

        <template v-if="roleFetched">
          <v-divider v-if="project.pivot.role==2" class="mx-4" inset vertical></v-divider>
          <v-dialog v-if="project.pivot.role==2" v-model="newCampaignDialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                New campaign
                <v-icon size=26 :color="'white'" style="padding-left:10px">
                  mdi-briefcase-plus
                </v-icon>
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">New campaign</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                        <v-text-field
                          v-model="newCampaign.name"
                          label="Name"
                          :error-messages="nameErrors"
                          required
                          @input="$v.newCampaign.name.$touch()"
                          @blur="$v.newCampaign.name.$touch()"
                        ></v-text-field>
                        <v-text-field
                          v-model="newCampaign.description"
                          label="Description"
                          :error-messages="descErrors"
                          required
                          @input="$v.newCampaign.description.$touch()"
                          @blur="$v.newCampaign.description.$touch()"
                        ></v-text-field>
                        <v-menu
                          ref="menu"
                          v-model="menu"
                          :close-on-content-click="false"
                          :return-value.sync="newCampaign.start_date_formatted"
                          transition="scale-transition"
                          offset-y
                          min-width="auto"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                              v-model="computedDateFormatted"
                              label="Campaign start date"
                              prepend-icon="mdi-calendar"
                              readonly
                              v-bind="attrs"
                              v-on="on"
                              @blur="date = parseDate(newCampaign.start_date_formatted)"
                            ></v-text-field>
                          </template>
                          <v-date-picker
                            v-model="newCampaign.start_date"
                            color="primary"
                            no-title
                            scrollable
                            style="background: white;"
                            @input="menu = false"
                          >
                            <v-spacer></v-spacer>
                            <v-btn
                              text
                              color="primary"
                              @click="menu = false"
                            >
                              Cancel
                            </v-btn>
                            <v-btn
                              text
                              color="primary"
                              @click="$refs.menu.save(newCampaign.start_date)"
                            >
                              OK
                            </v-btn>
                          </v-date-picker>
                        </v-menu>
                        <v-menu
                          ref="menu2"
                          v-model="menu2"
                          :close-on-content-click="false"
                          :return-value.sync="newCampaign.end_date_formatted"
                          transition="scale-transition"
                          offset-y
                          min-width="auto"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                              v-model="computedDateFormatted2"
                              label="Campaign end date"
                              prepend-icon="mdi-calendar"
                              readonly
                              v-bind="attrs"
                              v-on="on"
                              @blur="date = parseDate(newCampaign.end_date_formatted)"
                            ></v-text-field>
                          </template>
                          <v-date-picker
                            v-model="newCampaign.end_date"
                            color="primary"
                            no-title
                            scrollable
                            style="background: white;"
                            @input="menu2 = false"
                          >
                            <v-spacer></v-spacer>
                            <v-btn
                              text
                              color="primary"
                              @click="menu2 = false"
                            >
                              Cancel
                            </v-btn>
                            <v-btn
                              text
                              color="primary"
                              @click="$refs.menu.save(newCampaign.end_date)"
                            >
                              OK
                            </v-btn>
                          </v-date-picker>
                        </v-menu>
                      </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDialogs">
                  Cancel
                </v-btn>
                <v-btn color="blue darken-1" text @click="saveNewCampaign">
                  Create
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </template>

      </v-toolbar>
    </template>
    <template  v-slot:item.actions="{ item }">
      <v-btn small color="primary" dark
       @click="openCampaign(item)"
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
import { validationMixin } from 'vuelidate'
import { required, sameAs, minLength, email } from 'vuelidate/lib/validators'
export default {
  mixins: [validationMixin],
  data: vm => ({

    menu: false,
    menu2: false,
    newCampaignDialog:false,

    project: null,
    roleFetched: false,
    newCampaign:{
      name: null,
      description: null,
      start_date: new Date().toISOString().substr(0, 10),
      start_date_formatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
      end_date: new Date().toISOString().substr(0, 10),
      end_date_formatted: vm.formatDate(new Date().toISOString().substr(0, 10))
    },
    singleExpand: true,
    expanded: [],
    campaigns: [],
    newPasswordId: '',
    newPassword: '',
    newPassword_confirm: '',
    editUser:{},
    user:{
      name:'',
      email:'',
      password: '',
      repeatPassword: '',
      role:0,
    },
    submitStatus: null,
    show1: false,
    show2: false,
    users: [],
    search: '',
    newUserDialog: false,
    editUserDialog: false,
    newPasswordDialog: false,
    dialogDelete: false,
    editedIndex: -1,
    }),
    validations: {
      newCampaign:{
        name: { required },
        description: { required }
      },
  },
  computed: {
      computedDateFormatted () {
        return this.formatDate(this.newCampaign.start_date)
      },
      computedDateFormatted2 () {
        return this.formatDate(this.newCampaign.end_date)
      },
      nameErrors () {
        const errors = []
        if (!this.$v.newCampaign.name.$dirty) return errors
        !this.$v.newCampaign.name.required && errors.push('Name is required.')
        return errors
      },
      descErrors () {
        const errors = []
        if (!this.$v.newCampaign.description.$dirty) return errors
        !this.$v.newCampaign.description.required && errors.push('Description is required.')
        return errors
      },
      headers () {
        return [
          { text: 'ID', align: 'start', value: 'id', },
          { text: 'Name', align: 'start', value: 'name'},
          { text: 'Description', align: 'start', value: 'description'},
          { text: 'Start date', align: 'start', value: 'start_date'},
          { text: 'End date', align: 'start', value: 'end_date'},
          { text: 'Drllings number', align: 'center', value: 'drillings_count' },
          { text: 'Wells number', align: 'center', value: 'wells_count' },
          { text: 'Samples number', align: 'center', value: 'samples_count' },
          { text: 'Spatial number', align: 'center', value: 'spatials_count' },
          { text: 'Actions', align: 'center' ,value: 'actions' },
          // { text: '', value: 'data-table-expand' },
        ]
      },


  },
  watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
  },
  created () {
    this.getCampaings()
    this.getProject()
    console.log(this.campaigns)
    this.$v.$reset()
    // this.getList();
  },
  methods: {
    openCampaign(item){
      console.log("opening campaign")
      console.log( `/project/${this.$route.params.id}/campaign/${item.id}`)
      this.$router.push({ path: `/project/${this.$route.params.id}/campaign/${item.id}`})
    },
    async getProject() {
      var me = await this.$store.getters["AuthManager/user"];
      console.log(me)
      await axios.get(`/getUserProjects/`+me.id)
        .then(response => {
          if(response.status == 200){
            const projectsJson = response.data
            const projects = projectsJson.map(projects => ({...projects}))
            this.project = projects.filter(obj => {
              return obj.id == this.$route.params.id
            })[0]
            console.log(this.project)
            this.roleFetched = true
          }
          else{
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: response.data});
          }
        })
    },
    parseDate (date) {
      if (!date) return null
      const [day, month, year] = date.split('.')
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
    },
    saveNewCampaign(){
      this.$v.newCampaign.name.$touch()
      this.$v.newCampaign.description.$touch()
      if(this.$v.newCampaign.name.$invalid ||
        this.$v.newCampaign.description.$invalid){
        console.log('Invalid')
      }
      else{
        console.log('saving..')
        this.newCampaign.project_id = this.$route.params.id
        axios.post(`/api/campaign`, this.newCampaign)
          .then(response => {
            if(response.status == 200){
              this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'green', text: response.data});
              this.getCampaings()
              this.newCampaignDialog=false
            }
            else{
              this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: response.data});
            }
          });
        console.log(this.newCampaign)
      }

    },
    closeDialogs(){
      this.newCampaignDialog = false;
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },
    async getCampaings(){
      await axios.get('/api/project/'+this.$route.params.id+'/campaigns')
        .then(response => {
          console.log(response)
          if(response.status == 200){
            this.campaigns = response.data
            // this.campaigns = campaignsJson.map(campaigns => ({...campaigns, start_date: this.formatDate(campaigns.start_date), end_date: this.formatDate(campaigns.end_date)}))
          }
          else{
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: response.data});
          }
        });
    },
    // async getList () {
    //    await this.$store.dispatch("users/list");
    //    this.users = await this.$store.getters["users/list"];
    //    console.log(this.users);
    // },
    showEditItem (item) {
        this.editUser = item
        this.editUserDialog = true
    },
    deleteItem (item) {
         console.log(item)
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
    },
    deleteItemConfirm () {
        console.log(this.editedItem)
        this.users.splice(this.editedIndex, 1)
        this.$store.dispatch("UsersManager/destroy", this.editedItem.id).then((response) => {
          if(response.status==200){
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'green', text: response.data});
          }
          console.log(response.data)
        })
        this.closeDelete()
    },
    close () {
        this.newUserDialog = false
        this.editUserDialog = false
        this.newPasswordDialog = false
        this.$v.$reset()
        this.user = []
        this.editUser = []
    },
    closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
    },
    saveNewPassword(){
        this.$v.newPassword.$touch()
        this.$v.newPassword_confirm.$touch()
        if(this.$v.newPassword.$invalid ||
          this.$v.newPassword_confirm.$invalid){
          console.log('Invalid')
        }
        else{
          console.log("valid")
          var password = {
            password: this.newPassword
          }
          axios.patch(`/api/users/`+this.newPasswordId, password)
            .then(response => {
              if(response.status == 200){
                this.getList();
                this.close();
                this.newPassword = ''
                this.newPassword_confirm = ''
                this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'green', text: response.data});
              }
              else{
                this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: response.data});
              }
            });
          this.$v.$reset()
        }
    },
    async saveEditUser () {
        console.log(this.editUser)
        this.$v.editUser.name.$touch()
        this.$v.editUser.email.$touch()
        if(this.$v.editUser.name.$invalid ||
          this.$v.editUser.email.$invalid){
          console.log('Invalid')
        }
        else{
          console.log("valid")
          axios.patch(`/api/users/`+this.editUser.id, this.editUser)
            .then(response => {
              if(response.status == 200){
                this.getList();
                this.close();
                this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'green', text: response.data});
              }
              else{
                this.$store.dispatch('Notifications/setNotificationStatus', {type: 'red', text: response.data});
              }
            });
          this.$v.$reset()
        }
    },
    async save () {
        this.$v.user.name.$touch()
        this.$v.user.email.$touch()
        this.$v.user.password.$touch()
        this.$v.user.repeatPassword.$touch()
        if(this.$v.user.name.$invalid ||
          this.$v.user.email.$invalid ||
          this.$v.user.password.$invalid ||
          this.$v.user.repeatPassword.$invalid){
          console.log('Invalid')
          // this.$v.$reset()
        }
        else{
          console.log("valid")
          await this.$store.dispatch("UsersManager/add", this.user)
          var resp = await this.$store.getters["UsersManager/response"]
          if (resp.status==409){
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: resp.data});
            // this.dialog = false;
            // this.getList();
          }
          else if (resp.status==200){
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'green', text: resp.data});
            this.dialog = false;
            this.getList();
            this.$v.$reset()
            this.user = []
            this.user.role=0
            this.newUserDialog = false;
          }
          else{
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: resp.data});
          }
        }
    },
    showNewUserDialog(){
        this.$v.$reset()
        console.log('alo')
        this.newUserDialog = true;
      },
      showNewPasswordDialog(item){
        this.newPasswordId = item.id
        this.newPasswordDialog = true
      }
    },
}
</script>
<style>
.v-messages {
    display: block !important;
}
</style>
