<template>
  <v-container
    id="regular-tables"
    fluid
    tag="section"
  >
    <base-v-component
      heading="Simple Tables"
      link="components/simple-tables"
    />

    <base-material-card
      icon="mdi-clipboard-text"
      title="Simple Table"
      class="px-5 py-3"
    >
      <v-simple-table>
        <thead>
          <tr>
            <th class="primary--text">
              ID
            </th>
            <th class="primary--text">
              Name
            </th>
            <th class="primary--text">
              Country
            </th>
            <th class="primary--text">
              City
            </th>
            <th class="text-right primary--text">
              Salary
            </th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>1</td>
            <td>Dakota Rice</td>
            <td>Niger</td>
            <td>Oud-Turnhout</td>
            <td class="text-right">
              $36,738
            </td>
          </tr>

          <tr>
            <td>2</td>
            <td>Minverva Hooper</td>
            <td>Curaçao</td>
            <td>Sinaas-Waas</td>
            <td class="text-right">
              $23,789
            </td>
          </tr>

          <tr>
            <td>3</td>
            <td>Sage Rodriguez</td>
            <td>Netherlands</td>
            <td>Baileux</td>
            <td class="text-right">
              $56,142
            </td>
          </tr>

          <tr>
            <td>4</td>
            <td>Philip Chaney</td>
            <td>Korea, South</td>
            <td>Overland Park</td>
            <td class="text-right">
              $38,735
            </td>
          </tr>

          <tr>
            <td>5</td>
            <td>Doris Greene</td>
            <td>Malawi</td>
            <td>Feldkirchen in Kärnten</td>
            <td class="text-right">
              $63,542
            </td>
          </tr>

          <tr>
            <td>6</td>
            <td>Mason Porter</td>
            <td>Chile</td>
            <td>Gloucester</td>
            <td class="text-right">
              $78,615
            </td>
          </tr>
        </tbody>
      </v-simple-table>
    </base-material-card>


  </v-container>
</template>

<script>
// import Pagination from "@/components/Pagination";

export default {
  components: {
    // pagination: Pagination,
  },

  data: () => ({
    searchedUser: "",
    table: [],
    footerTable: ["Name", "Email", "Role", "Created At", "Actions"],

    query: null,

    sortation: {
      field: "created_at",
      order: "asc",
    },

    pagination: {
      perPage: 5,
      currentPage: 1,
      perPageOptions: [5,10,25,50],
    },

    total: 0,
  }),

  computed: {
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
  },

  created() {
    this.getList();
  },

  methods: {
    typedSearch(){
      console.log(this.searchedUser);
    },
    async getList() {
      await this.$store.dispatch("usersmod/list");
      this.table = await this.$store.getters["usersmod/list"];
      console.log(this.table);


      this.meta = await this.$store.getters["usersmod/meta"];
      this.total = this.meta.page.total;
    },
    async deleteUser($id){
      console.log($id);
      try {
        this.answer = await this.$store.dispatch("usersmod/destroy", $id);
        if(this.answer.status=200){
          this.$store.dispatch("alerts/success", "User deleted");
          const myindex = this.table.findIndex(x => x.id === $id);
          console.log(myindex);
          this.table.splice(myindex, 1);
          this.getList();
        }
      }
      catch(err) {
        // console.log(err.response.data);
        this.$store.dispatch("alerts/error", err.response.data);
      }

    },

    onProFeature() {
      // this.$store.dispatch("alerts/error", "This is a PRO feature.");
      this.$router.push('/components/createuser');
    },

    customSort() {
      return false;
    },
  },
};
</script>
