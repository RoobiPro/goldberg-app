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
              <i aria-hidden="true" class="v-icon notranslate mdi mdi-clipboard-text theme--dark" style="font-size: 32px;">
              </i>
            </div>

            <v-toolbar-title>Wells</v-toolbar-title>

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
            <v-spacer></v-spacer>

            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-on:click="showNewUserDialog"

            >
              Add wells
            </v-btn>

            <v-dialog
              ref="newUser"
              v-model="newUserDialog"
              max-width="500px"
            >



              <v-card>
                <v-card-title>
                  <span class="headline">New well</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-form style="width: 100%">

                        <v-col cols="12" sm="12" md="12">
                          <v-text-field
                            v-model="user.name"
                            :error-messages="nameErrors"
                            label="Name"
                            required
                            @input="$v.user.name.$touch()"
                            @blur="$v.user.name.$touch()"
                          ></v-text-field>

                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                          <v-text-field
                            v-model="user.email"
                            :error-messages="emailErrors"
                            label="E-mail"
                            required
                            @input="$v.user.email.$touch()"
                            @blur="$v.user.email.$touch()"
                          ></v-text-field>
                          <!-- <v-text-field v-model="editedItem.email" label="E-mail" required></v-text-field> -->
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                        <v-text-field
                          label="Password"
                          v-model="user.password"
                          :error-messages="passwordErrors"
                          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                          :type="show1 ? 'text' : 'password'"
                          name="input-10-1"
                          hint="At least 8 characters"
                          counter
                          @click:append="show1 = !show1"
                          required
                        ></v-text-field>

                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                        <v-text-field
                          :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                          v-model="user.repeatPassword"
                          :error-messages="repeatPasswordErrors"
                          :type="show2 ? 'text' : 'password'"
                          name="input-10-2"
                          label="Password confirmation"
                          hint="At least 8 characters"
                          class="input-group--focused"
                          @click:append="show2 = !show2"
                          required
                        ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                          <v-radio-group v-model="user.role" required>
                          <v-radio
                            :key="0"
                            :label="`User`"
                            :value="0"
                          ></v-radio>
                          <v-radio
                            :key="1"
                            :label="`Client`"
                            :value="1"
                          ></v-radio>
                          <v-radio
                            :key="2"
                            :label="`Superadmin`"
                            :value="2"
                          ></v-radio>
                          </v-radio-group>
                        </v-col>

                    </v-form>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="save">Create</v-btn>
                </v-card-actions>
              </v-card>

            </v-dialog>

            <v-dialog
              v-model="editUserDialog"
              max-width="500px"
            >

              <v-card>
                <v-card-title>
                  <span class="headline">Edit User</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-form style="width: 100%">

                        <v-col cols="12" sm="12" md="12">
                          <v-text-field
                            v-model="editUser.name"
                            :error-messages="editNameErrors"
                            label="Name"
                            required
                            @input="$v.editUser.name.$touch()"
                            @blur="$v.editUser.name.$touch()"
                          ></v-text-field>

                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                          <v-text-field
                            v-model="editUser.email"
                            :error-messages="editEmailErrors"
                            label="E-mail"
                            required
                            @input="$v.editUser.email.$touch()"
                            @blur="$v.editUser.email.$touch()"
                          ></v-text-field>
                          <!-- <v-text-field v-model="editedItem.email" label="E-mail" required></v-text-field> -->
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                          <v-radio-group v-model="editUser.role" required>
                          <v-radio
                            :key="0"
                            :label="`User`"
                            :value="0"
                          ></v-radio>
                          <v-radio
                            :key="1"
                            :label="`Client`"
                            :value="1"
                          ></v-radio>
                          <v-radio
                            :key="2"
                            :label="`Superadmin`"
                            :value="2"
                          ></v-radio>
                          </v-radio-group>
                        </v-col>

                    </v-form>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="saveEditUser">Create</v-btn>
                </v-card-actions>
              </v-card>

            </v-dialog>

            <v-dialog
              v-model="newPasswordDialog"
              max-width="500px"
            >

              <v-card>
                <v-card-title>
                  <span class="headline">New password for user</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-form style="width: 100%">

                        <v-col cols="12" sm="12" md="12">
                        <v-text-field
                          label="Password"
                          v-model="newPassword"
                          :error-messages="newPasswordErrors"
                          @input="$v.newPassword.$touch()"
                          @blur="$v.newPassword.$touch()"
                          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                          :type="show1 ? 'text' : 'password'"
                          name="input-10-1"
                          hint="At least 8 characters"
                          counter
                          @click:append="show1 = !show1"
                          required
                        ></v-text-field>

                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                        <v-text-field
                          :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                          v-model="newPassword_confirm"
                          :error-messages="newRepeatPasswordErrors"
                          @input="$v.newPassword_confirm.$touch()"
                          @blur="$v.newPassword_confirm.$touch()"
                          :type="show2 ? 'text' : 'password'"
                          name="input-10-2"
                          label="Password confirmation"
                          hint="At least 8 characters"
                          class="input-group--focused"
                          @click:append="show2 = !show2"
                          required
                        ></v-text-field>
                        </v-col>

                    </v-form>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="saveNewPassword">Create</v-btn>
                </v-card-actions>
              </v-card>

            </v-dialog>


            <v-dialog v-model="dialogDelete" max-width="400px">
              <v-card>
                <v-card-title class="text-heading-5">Are you sure you want to delete this User?</v-card-title>
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
        <template  v-slot:item.avatar="{ item }">
          <v-avatar size="35">
            <img :src="'/storage/user-avatar/'+item.avatar" :alt="item.name">
          </v-avatar>
        </template>
        <template  v-slot:item.actions="{ item }">

          <v-icon v-show="canBeDeleted(item)"
            small
            class="mr-2"
            @click="showEditItem(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon v-show="canBeDeleted(item)"
            small
            class="mr-2"
            @click="showNewPasswordDialog(item)"
          >
            mdi-key-arrow-right
          </v-icon>
          <v-icon v-show="canBeDeleted(item)"
            small
            @click="deleteItem(item)"
          >
            mdi-delete
          </v-icon>
        </template>

      </v-data-table>

</v-container>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, sameAs, minLength, email } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],
  data: () => ({
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
          { text: 'Avatar', align: 'start', value: 'avatar'},
          { text: 'Name', value: 'name' },
          { text: 'E-mail', value: 'email' },
          { text: 'Role', value: 'role_name' },
          { text: 'Actions', align: 'center', value: 'actions', sortable: false },
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
      this.$v.$reset()
      this.getList();
    },

    methods: {
      canBeDeleted(item) {
        if(item.id == 1 || item.id == 2){
          return false;
        }
        else{
          return true;
        }
      },
     async getList () {
       await this.$store.dispatch("UsersManager/list");
       this.users = await this.$store.getters["UsersManager/list"];
       console.log(this.users);
        },

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
