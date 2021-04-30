<template>
  <section class="container container--fluid" id="regular-tables">
    <!-- <div class="v-card--material pa-3 px-5 py-3 v-card v-sheet theme--light v-card--material--has-heading"> -->
    <div>
      <v-data-table
        :headers="headers"
        :items="users"
        item-key="name"
        class="elevation-1 pa-3"
        :search="search"
      >
        <template v-slot:top>
          <v-text-field
            v-model="search"
            label="Search"
            class="mx-4"
          ></v-text-field>
        </template>

      </v-data-table>
    </div>
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
      users: [],
      search: '',
      calories: '',

    }
  },
  computed: {
    headers () {
      return [
        {
          text: 'ID',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        {
          text: 'Name',
          value: 'name',
          filter: value => {
            if (!this.calories) return true

            return value < parseInt(this.calories)
          },
        },
        { text: 'E-mail', value: 'email' },
        { text: 'Role', value: 'role_name' },

      ]
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
