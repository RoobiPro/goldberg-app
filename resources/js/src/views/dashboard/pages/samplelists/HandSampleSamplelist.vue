<template>
  <v-container
  class="d-flex justify-center"
  tag="section"
  style="margin-top:10vh;">

  <v-progress-circular
    v-if="!ready"
    :width="3"
    color="green"
    indeterminate
  ></v-progress-circular>


    <v-data-table
      v-if="ready"
      :headers="headers"
      :items="samplelist"
      :search="search"
      item-key="id"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <div class="hidden-md-and-down v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark pa-7"
            style="max-height: 90px; width: auto;">
            <i aria-hidden="true" class="v-icon notranslate mdi mdi-clipboard-list theme--dark" style="font-size: 32px;">
            </i>
          </div>
          <v-toolbar-title>Hand Sample Sample List</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>
        </v-toolbar>
      </template>


    </v-data-table>
  </v-container>
</template>

<script>
export default {
    data(){
      return {
        ready:false,
        headers:[],
        samplelist:[],
        search:null,
      }
    },
    async created() {

      await this.$store.dispatch("TableManager/get", 'well_sample_lists');
      this.headers = this.$store.getters["TableManager/headers"];
      this.headers.splice(0, 1);
      this.headers.splice(6, 4);

      console.log(this.$route.params)
      await this.$store.dispatch("ProjectsManager/getProjectHandSampleSampleList", this.$route.params.id);
      this.samplelist = this.$store.getters["ProjectsManager/projecthandsamplesamplelist"];
      this.ready=true

    },

    methods: {

    }
}

</script>

<style>

.v-label {
    /* display: inline-block; */
    margin-bottom: 0;
}
.v-input--radio-group--row .v-input--radio-group__input{
  justify-content: space-evenly;
  padding-top: 5%;
}

#vaddershr{
  background-color: white;
  margin-left: 10%;
  margin-right: 10%;
}

.headerItem{
  /* display:block; */
  display: flex;
  justify-content: center;
  align-items: center;
  width: 10%;
  height: 64px;
}

.customButton{
  display: flex;
  height: 50%;
  width: 50%;
  border-radius: 3px;
  color: white;
  font-size: 22px;
  justify-content: center;
}

.headerItem:hover{
  /* background-color: white; */
  opacity: 0.6;
  cursor: pointer;
}
/* .customButton > span {
  width: 100%;
} */
.v-sheet.v-toolbar:not(.v-sheet--outlined){
  box-shadow: none
}
#closealert{
  position: absolute;
  top: 5px;
  right: 5px;
}
#closealert:hover{
  /* background-color: white; */
  opacity: 0.6;
  cursor: pointer;
}
</style>
