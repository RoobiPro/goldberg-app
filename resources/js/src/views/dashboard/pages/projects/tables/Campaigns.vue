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
    :single-expand="singleExpand"
    :expanded.sync="expanded"
    item-key="name"
    show-expand class="elevation-0"
  >

    <template v-slot:top>
      <v-toolbar flat>
        <div class="hidden-md-and-down v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 pa-7"
          style="max-height: 90px; width: auto;">
          <i aria-hidden="true" class="v-icon notranslate mdi mdi-clipboard-text theme--dark" style="font-size: 32px;">
          </i>
        </div>
        <v-toolbar-title>Project campaigns</v-toolbar-title>
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

        <v-dialog v-model="newCampaignDialog" max-width="500px">
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
                      ></v-text-field>

                      <v-text-field
                        v-model="newCampaign.description"
                        label="Description"
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


          <!-- <v-list-item class="borderline"> -->
              <div class="ma-4 d-flex justify-space-around flex-wrap custom">
                <!-- justify="space-around"> -->
                <!-- <v-col
                align-self="center"
                  md="4"> -->
                    <v-list-item-icon style=" padding: 0; margin: 0; margin-bottom:10px;">
                      <v-btn min-width="120" max-width="180" color="primary" dark>
                        Assign User
                        <v-icon size=26 :color="'white'" style="padding-left:10px">
                          mdi-account-plus
                        </v-icon>
                      </v-btn>
                    </v-list-item-icon>

                    <v-list-item-icon style="padding: 0; margin: 0;margin-bottom:10px;">
                      <v-btn min-width="120" max-width="180" color="primary" dark>
                        Assign Client
                        <v-icon size=26 :color="'white'" style="padding-left:10px">
                          mdi-account-tie
                        </v-icon>
                      </v-btn>
                    </v-list-item-icon>

                    <v-list-item-icon style="padding: 0; margin: 0;margin-bottom:10px;">
                      <v-btn min-width="120" max-width="180" color="blue darken-1" dark>
                        Edit Project
                        <v-icon size=26 :color="'white'" style="padding-left:10px">
                          mdi-briefcase-edit
                        </v-icon>
                      </v-btn>
                    </v-list-item-icon>

                    <v-list-item-icon style="padding: 0; margin: 0;margin-bottom:10px;">
                      <v-btn min-width="120" max-width="180" color="red darken-1" dark>
                        Delete Project
                        <v-icon size=26 :color="'white'" style="padding-left:10px">
                          mdi-briefcase-remove
                        </v-icon>
                      </v-btn>
                    </v-list-item-icon>
              <!-- </v-col> -->
            <!-- </v-row> -->
          </div>
          <!-- </v-list-item> -->
          <v-divider class="ma-0"/>



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

          <v-divider v-if="item.client!=null" class="ma-0"/>

          <template v-else>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title v-text="'No Client assigned yet.'"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-divider class="ma-0"/>
          </template>


          <div class="theme--light" style="max-height: 5px; min-height: 5px;">
          </div>

          <div class="theme--light ma-4">
            <div style="">
              <h5 style="margin-bottom:0px; font-size: 1rem;">Users:</h5>
            </div>
          </div>


          <template v-if="true">
            <template v-for="(item, index) in item.users">
              <v-list-item :key="item.id">
                <v-list-item-avatar>
                  <v-img :alt="`${item.name} avatar`" :src="'/storage/user-avatar/'+item.avatar"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                  <v-list-item-title v-text="item.name"></v-list-item-title>
                  <!-- <v-list-item-subtitle v-text="getRoleName(item.pivot.role)"></v-list-item-subtitle> -->
                </v-list-item-content>

              </v-list-item>
              <!-- <v-divider class="ma-0" v-if="getBottomLine(item.pivot.project_id, index)"/> -->
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
import { validationMixin } from 'vuelidate'
import { required, sameAs, minLength, email } from 'vuelidate/lib/validators'
export default {
  mixins: [validationMixin],
  data: vm => ({
    menu: false,
    menu2: false,
    newCampaignDialog:false,
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
      user:{
        name: { required },
        email: { required, email },
        password: { required, minLength: minLength(8) },
        repeatPassword: {
          required,
          sameAsPassword: sameAs('password')
        }
      },
    editUser:{
      name: { required },
      email: { required, email },
    },
    newPassword: { required, minLength: minLength(8) },
    newPassword_confirm: {
      required,
      sameAsPassword: sameAs('newPassword')
    }
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
        if (!this.$v.user.name.$dirty) return errors
        !this.$v.user.name.required && errors.push('Name is required.')
        return errors
      },
      emailErrors () {
        const errors = []
        if (!this.$v.user.email.$dirty) return errors
        !this.$v.user.email.email && errors.push('Must be valid e-mail')
        !this.$v.user.email.required && errors.push('E-mail is required')
        return errors
      },
      passwordErrors () {
        const errors = []
        if (!this.$v.user.password.$dirty) return errors
        !this.$v.user.password.minLength && errors.push('Must have at least 8 characters')
        !this.$v.user.password.required && errors.push('Password is required')
        return errors
      },
      repeatPasswordErrors () {
        const errors = []
        if (!this.$v.user.repeatPassword.$dirty) return errors
        !this.$v.user.repeatPassword.required && errors.push('Password is required')
        !this.$v.user.repeatPassword.sameAsPassword && errors.push('Must match password')
        return errors
      },
      editNameErrors () {
        const errors = []
        if (!this.$v.editUser.name.$dirty) return errors
        !this.$v.editUser.name.required && errors.push('Name is required.')
        return errors
      },
      editEmailErrors () {
        const errors = []
        if (!this.$v.editUser.email.$dirty) return errors
        !this.$v.editUser.email.email && errors.push('Must be valid e-mail')
        !this.$v.editUser.email.required && errors.push('E-mail is required')
        return errors
      },
      newPasswordErrors () {
        const errors = []
        if (!this.$v.newPassword.$dirty) return errors
        !this.$v.newPassword.minLength && errors.push('Must have at least 8 characters')
        !this.$v.newPassword.required && errors.push('Password is required')
        return errors
      },
      newRepeatPasswordErrors () {
        const errors = []
        if (!this.$v.newPassword_confirm.$dirty) return errors
        !this.$v.newPassword.required && errors.push('Password confirmation is required')
        !this.$v.newPassword_confirm.sameAsPassword && errors.push('Must match password')
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
          { text: '', value: 'data-table-expand' },
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
    console.log(this.campaigns)
    this.$v.$reset()
    // this.getList();
  },
  methods: {
    parseDate (date) {
      if (!date) return null

      const [day, month, year] = date.split('.')
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
    },
    saveNewCampaign(){
      console.log('saving..')
      this.newCampaign.project_id = this.$route.params.id
      console.log(this.newCampaign)
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
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
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
        this.$store.dispatch("users/destroy", this.editedItem.id).then((response) => {
          if(response.status==200){
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
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
                this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
              }
              else{
                this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
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
                this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
              }
              else{
                this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: response.data});
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
          await this.$store.dispatch("users/add", this.user)
          var resp = await this.$store.getters["users/response"]
          if (resp.status==409){
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: resp.data});
            // this.dialog = false;
            // this.getList();
          }
          else if (resp.status==200){
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: resp.data});
            this.dialog = false;
            this.getList();
            this.$v.$reset()
            this.user = []
            this.user.role=0
            this.newUserDialog = false;
          }
          else{
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: resp.data});
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
