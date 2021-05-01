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
                      <v-col
                        cols="12"
                        sm="12"
                        md="12"
                      >
                        <v-text-field
                          v-model="editedItem.name"
                          label="Name"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="12"
                        md="12"
                      >
                        <v-text-field
                          v-model="editedItem.email"
                          label="E-mail"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="12"
                        md="12"
                      >
                      <v-text-field
                        label="Password"
                        v-model="password"
                        :rules="passwordRules"
                        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show1 ? 'text' : 'password'"
                        name="input-10-1"
                        hint="At least 8 characters"
                        counter
                        @click:append="show1 = !show1"
                        required>
                      </v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="12"
                        md="12"
                      >
                      <v-text-field
                        :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                        v-model="confirmPassword"
                        :rules="confirmPasswordRules.concat(passwordConfirmationRule)"
                        :type="show2 ? 'text' : 'password'"
                        name="input-10-2"
                        label="Password confirmation"
                        hint="At least 8 characters"
                        class="input-group--focused"
                        @click:append="show2 = !show2"
                        required
                        >
                      </v-text-field>
                      </v-col>






                      <v-col
                        cols="12"
                        sm="12"
                        md="12"
                      >

                      <v-radio-group v-model="editedItem.role" required>
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
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="close"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="save"
                  >
                    Create
                  </v-btn>
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
          <v-icon v-if="item.role!=2"
            small
            class="mr-2"
            @click="editItem(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon v-if="item.role!=2"
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
// import Pagination from "@/components/Pagination";

export default {
  components: {
    // pagination: Pagination,
  },
  data: () => ({
      password: "",
      confirmPassword: "",
      passwordRules: [
      // v => (v && v.length >= 8) || "Password must be 8" ,
      v => !!v || "Password is required"
      ],
      confirmPasswordRules: [
        // v => (v && v.length >= 8) || "Password must be 8" ,
        v => !!v || "Password is required"
      ],
      show1: false,
      show2: false,
      rules: {
        required: value => !!value || 'Required.',
        passwordRules: v => !!v || "Password is required",
        confirmPasswordRule: v => !!v || "Password is required",
        min: v => v.length >= 8 || 'Min 8 characters',
        // passwordMatch: () => (`Password confirmation does not match`),

        // emailMatch: () => (`The email and password you entered don't match`),
      },
      users: [],
      search: '',
      dialog: false,
      dialogDelete: false,
      desserts: [],
      editedIndex: -1,
      editedItem: {
        name: '',
        email: '',
        password: '',
        password_confirm: '',
        role: 0,
      },
      defaultItem: {
        name: '',
        email: '',
        password: '',
        password_confirm: '',
        role: 0,
      },
    }),

    computed: {
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
      // passwordConfirmationRule() {
      //     return () => (this.editedItem.password === this.editedItem.password_confirm) || 'Password must match'
      // },
      passwordConfirmationRule() {
        return () =>
          this.password === this.confirmPassword || "Password must match";
      }
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
      // console.log(this.confirmPasswordRules.concat(this.passwordConfirmationRule));
      this.getList();
    },

    methods: {
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
        if(true){
          this.$store.dispatch('alerts/setNotificationStatus', {type: 'red', text: 'Please fill all the fields!'});
        }
        console.log(this.editedItem)
        await this.$store.dispatch("usersmod/add", this.editedItem)
        this.getList()
        if (this.editedIndex > -1) {
          Object.assign(this.desserts[this.editedIndex], this.editedItem)
        } else {
          this.desserts.push(this.editedItem)
        }
        this.close()
      },
    },
}
</script>
