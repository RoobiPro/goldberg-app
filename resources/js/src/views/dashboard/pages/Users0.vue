<template>
  <section class="container container--fluid" id="regular-tables">
    


    <v-card >

      <v-card-title class="pl-5">
      Users Table
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        label="Search"
        append-icon="mdi-magnify"
        class="mx-4"
        single-line
        hide-details

      ></v-text-field>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="primary"
          dark
          class="mb-2"
          v-bind="attrs"
          v-on="on"
        >
          New Item
        </v-btn>
      </template>




    </v-card-title>

      <v-data-table
        :headers="headers"
        :items="users"
        item-key="name"
        class="elevation-1 pa-3"
        :search="search"
      >
      <!-- loading
      loading-text="Loading... Please wait" -->
        <!-- <template v-slot:top>

        </template> -->
        <template v-slot:item.actions="{ item }">
            <v-icon
              small
              class="mr-2"
              @click="editItem(item)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              small
              @click="deleteItem(item)"
            >
              mdi-delete
            </v-icon>
        </template>

      </v-data-table>
    </v-card>

    <!-- <template>
        <v-footer
       dark
       padless
     >
       <v-card
         class="flex"
         flat
         tile
       >
         <v-card-title class="teal">
           <strong class="subheading">Get connected with us on social networks!</strong>

           <v-spacer></v-spacer>

           <v-btn
             v-for="icon in icons"
             :key="icon"
             class="mx-4"
             dark
             icon
           >
             <v-icon size="24px">
               {{ icon }}
             </v-icon>
           </v-btn>
         </v-card-title>

         <v-card-text class="py-2 white--text text-center">
           {{ new Date().getFullYear() }} — <strong>Vuetify</strong>
         </v-card-text>
       </v-card>
     </v-footer>
    </template> -->

  </section>
</template>

<script>
// import Pagination from "@/components/Pagination";

export default {
  components: {
    // pagination: Pagination,
  },
  data () {
    return {
      dialog: false,
      dialogDelete: false,
      users: [],
      search: '',
      calories: '',
      icons: [
        'mdi-facebook',
        'mdi-twitter',
        'mdi-linkedin',
        'mdi-instagram',
      ],

    }
  },
  computed: {
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
  created(){
    console.log(this.desserts);
    this.getList()

  },
  methods: {
    filterOnlyCapsText (value, search, item) {
      return value != null &&
        search != null &&
        typeof value === 'string' &&
        value.toString().toLocaleUpperCase().indexOf(search) !== -1
    },
    async getList() {
      await this.$store.dispatch("usersmod/list");
      this.users = await this.$store.getters["usersmod/list"];
      console.log(this.users);
      // console.log(this.table);
      // this.meta = await this.$store.getters["usersmod/meta"];
      // this.total = this.meta.page.total;
    },
  },
}
  // data: () => ({
  //   searchedUser: "",
  //   table: [],
  //   calories: "",
  //   headers: [
  //      {
  //        text: 'Dessert (100g serving)',
  //        align: 'start',
  //        sortable: false,
  //        value: 'name',
  //      },
  //      { text: 'ID', value: 'ID' },
  //      { text: 'Name', value: 'name' },
  //      { text: 'Email', value: 'email' },
  //      { text: 'Protein (g)', value: 'protein' },
  //      { text: 'Iron (%)', value: 'iron' },
  //    ],
  //   footerTable: ["Name", "Email", "Role", "Created At", "Actions"],
  //
  //   query: null,
  //
  //   sortation: {
  //     field: "created_at",
  //     order: "asc",
  //   },
  //
  //   pagination: {
  //     perPage: 5,
  //     currentPage: 1,
  //     perPageOptions: [5, 10, 25, 50],
  //   },
  //
  //   total: 0,
  // }),
  //
  // computed: {
    //   sort() {
    //     if (this.sortation.order === "desc") {
    //       return `-${this.sortation.field}`;
    //     }
    //
    //     return this.sortation.field;
    //   },
    //
    //   from() {
    //     this.getList();
    //     return this.pagination.perPage * (this.pagination.currentPage - 1);
    //
    //   },
    //
    //   to() {
    //     let highBound = this.from + this.pagination.perPage;
    //     if (this.total < highBound) {
    //       highBound = this.total;
    //     }
    //     this.getList();
    //     return highBound;
    //   },
//   },
//
//   created() {
//     this.getList();
//   },
//
//   methods: {
//     typedSearch() {
//       console.log(this.searchedUser);
//     },
//     async getList() {
//       await this.$store.dispatch("usersmod/list");
//       this.table = await this.$store.getters["usersmod/list"];
//       console.log(this.table);
//
//
//       this.meta = await this.$store.getters["usersmod/meta"];
//       this.total = this.meta.page.total;
//     },
//     async deleteUser($id) {
//       console.log($id);
//       try {
//         this.answer = await this.$store.dispatch("usersmod/destroy", $id);
//         if (this.answer.status = 200) {
//           this.$store.dispatch("alerts/success", "User deleted");
//           const myindex = this.table.findIndex(x => x.id === $id);
//           console.log(myindex);
//           this.table.splice(myindex, 1);
//           this.getList();
//         }
//       } catch (err) {
//         // console.log(err.response.data);
//         this.$store.dispatch("alerts/error", err.response.data);
//       }
//
//     },
//
//     onProFeature() {
//       // this.$store.dispatch("alerts/error", "This is a PRO feature.");
//       this.$router.push('/components/createuser');
//     },
//
//     customSort() {
//       return false;
//     },
//     filterOnlyCapsText (value, search, item) {
//   return value != null &&
//     search != null &&
//     typeof value === 'string' &&
//     value.toString().toLocaleUpperCase().indexOf(search) !== -1
// },
//   },
// };
</script>
