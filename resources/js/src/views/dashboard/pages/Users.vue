<template>
  <v-container
    fluid
    tag="section"
    style="margin-top:10vh;"
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

            <v-toolbar-title>User Management</v-toolbar-title>

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

            <v-dialog
              v-model="dialog"
              max-width="500px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  New User
                </v-btn>
              </template>

              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
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
        <template  v-slot:item.actions="{ item }">
          <v-icon v-show="canBeDeleted(item)"
            small
            class="mr-2"
            @click="editItem(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon v-show="canBeDeleted(item)"
            small
            @click="deleteItem(item)"
          >
            mdi-delete
          </v-icon>
        </template>
        <template v-slot:no-data>
          <v-btn
            color="primary"
            @click="getList"
          >
            Reset
          </v-btn>
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
      dialog: false,
      dialogDelete: false,
      editedIndex: -1,
      editedItem: {
        name: '',
        email: '',
        password: '',
        password_confirm: '',
        role: 0,
      },
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
      formTitle () {
        return this.editedIndex === -1 ? 'New User' : 'Edit User'
      },
      headers () {
        return [
          { text: 'ID', align: 'start', value: 'id', },
          { text: 'Name', value: 'name' },
          { text: 'E-mail', value: 'email' },
          { text: 'Role', value: 'role_name' },
          { text: 'Actions', value: 'actions', sortable: false },
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
       await this.$store.dispatch("usersmod/list");
       this.users = await this.$store.getters["usersmod/list"];
       console.log(this.users);
        },

      editItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        console.log(this.editedItem)
        this.dialog = true
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
        this.$store.dispatch("usersmod/destroy", this.editedItem.id).then((response) => {
          if(response.status==200){
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: response.data});
          }
          console.log(response.data)
        })

        this.closeDelete()

      },

      close () {
        this.dialog = false
        this.$v.$reset()
        this.name = ''
        this.email = ''
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      async save () {
        this.$v.$touch()
        if (this.$v.$invalid) {
          console.log('invalid form entries')
          this.submitStatus = 'ERROR'
        }
        else{
          console.log('Valid form...submitting')
          console.log(this.user)
          // delete this.user.repeatPassword;
          await this.$store.dispatch("usersmod/add", this.user)
          var resp = await this.$store.getters["usersmod/response"]
          if (resp.status==409){
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: resp.data});
            // this.dialog = false;
            // this.getList();
          }
          else if (resp.status==200){
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'green', text: resp.data});
            this.dialog = false;
            this.getList();
          }
          else{
            this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: resp.data});
          }

        }
      },
    },
}
</script>
