<template>
  <div class="md-layout">
    <div class="md-layout-item md-size-100">
      <!-- <div class="alert alert-danger" style="z-index: 9 !important">
        <strong
          >Add, Edit, Delete features are not functional. This is a PRO feature!
          Click
          <a
            href="https://www.creative-tim.com/live/vue-material-dashboard-laravel-pro"
            target="_blank"
            id="pro-feature"
            >here</a
          >
          to see the PRO product.</strong
        >
      </div> -->

        <v-data-table
          :headers="headers"
          :items="desserts"
          :items-per-page="5"
          class="elevation-1"
        ></v-data-table>

      <md-card>
        <md-card-header class="md-card-header-icon md-card-header-green">
          <div class="card-icon">
            <md-icon>assignment</md-icon>
          </div>
          <h4 class="title">Users List</h4>
        </md-card-header>
        <md-card-content>
          <div class="text-right">
            <md-button class="md-primary md-dense" @click="onProFeature">
              Add User
            </md-button>


          </div>

          <input v-model="searchedUser" v-on:input="typedSearch" id="credit-limit-input" type="text">

          <md-table
            :value="table"
            :md-sort.sync="sortation.field"
            :md-sort-order.sync="sortation.order"
            :md-sort-fn="customSort"
            class="paginated-table table-striped table-hover"
          >
            <!-- <md-table-toolbar>
              <md-field>
                <label>Per page</label>
                <md-select v-model="pagination.perPage" name="pages">
                  <md-option
                    v-for="item in pagination.perPageOptions"
                    :key="item"
                    :label="item"
                    :value="item"
                  >
                    {{ item }}
                  </md-option>
                </md-select>
              </md-field>
            </md-table-toolbar> -->


            <md-table-row slot="md-table-row" slot-scope="{ item }">

              <md-table-cell md-label="ID" md-sort-by="id">{{
                item.id
              }}</md-table-cell>

              <md-table-cell md-label="Name" md-sort-by="name">{{
                item.name
              }}</md-table-cell>

              <md-table-cell md-label="Email" md-sort-by="email">{{
                item.email
              }}</md-table-cell>

              <md-table-cell md-label="Role" md-sort-by="role">{{
                item.role_name
              }}</md-table-cell>

              <md-table-cell md-label="Created At" md-sort-by="created_at">{{
                item.created_at
              }}</md-table-cell>

              <md-table-cell md-label="Actions">
                <md-button
                  class="md-icon-button md-raised md-round md-info"
                  @click="onProFeature"
                  style="margin: 0.2rem"
                >
                  <md-icon>edit</md-icon>
                </md-button>
                <template v-if="item.id != 1">
                  <template v-if="item.id != 2">
                    <md-button
                      class="md-icon-button md-raised md-round md-danger"

                      style="margin: 0.2rem"
                    >
                      <md-icon>delete</md-icon>
                    </md-button>
                   </template>
                </template>
              </md-table-cell>
            </md-table-row>

          </md-table>

          <div class="footer-table md-table">
            <table>
              <tfoot>
                <tr>
                  <th
                    v-for="item in footerTable"
                    :key="item.name"
                    class="md-table-head"
                  >
                    <div class="md-table-head-container md-ripple md-disabled">
                      <div class="md-table-head-label">
                        {{ item }}
                      </div>
                    </div>
                  </th>
                </tr>
              </tfoot>
            </table>
          </div>
        </md-card-content>
<!--
        <md-card-actions md-alignment="space-between">
          <div class="">
            <p class="card-category">
              Showing {{ from + 1 }} to {{ to }} of {{ total }} entries
            </p>
          </div>
          <pagination
            class="pagination-no-border pagination-success"
            v-model="pagination.currentPage"
            :per-page="pagination.perPage"
            :total="total"
          />
        </md-card-actions> -->

      </md-card>
    </div>
  </div>
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
    sort() {
      if (this.sortation.order === "desc") {
        return `-${this.sortation.field}`;
      }

      return this.sortation.field;
    },

    from() {
      this.getList();
      return this.pagination.perPage * (this.pagination.currentPage - 1);

    },

    to() {
      let highBound = this.from + this.pagination.perPage;
      if (this.total < highBound) {
        highBound = this.total;
      }
      this.getList();
      return highBound;
    },
  },

  created() {
    this.getList();
  },

  methods: {
    // typedSearch(){
    //   console.log(this.searchedUser);
    // },
    // async getList() {
    //   await this.$store.dispatch("users/list",
    //     this.pagination
    //   );
    //   this.table = await this.$store.getters["users/list"];
    //   // console.log(this.table);
    //
    //
    //   this.meta = await this.$store.getters["users/meta"];
    //   this.total = this.meta.page.total;
    // },
    // async deleteUser($id){
    //   console.log($id);
    //   try {
    //     this.answer = await this.$store.dispatch("users/destroy", $id);
    //     if(this.answer.status=200){
    //       this.$store.dispatch("alerts/success", "User deleted");
    //       const myindex = this.table.findIndex(x => x.id === $id);
    //       console.log(myindex);
    //       this.table.splice(myindex, 1);
    //       this.getList();
    //     }
    //   }
    //   catch(err) {
    //     // console.log(err.response.data);
    //     this.$store.dispatch("alerts/error", err.response.data);
    //   }
    //
    // },
    //
    // onProFeature() {
    //   // this.$store.dispatch("alerts/error", "This is a PRO feature.");
    //   this.$router.push('/components/createuser');
    // },
    //
    // customSort() {
    //   return false;
    // },
  },
};
</script>

<style>
#pro-feature {
  font-weight: bold;
}
</style>
