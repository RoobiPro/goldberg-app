<template>

  <fragment>
    <v-dialog v-model="showRevertDialog" max-width="400px">
        <v-card>


        <v-card-title class="headline">Are you sure you want to rollback this import?</v-card-title>
        <v-card-actions>
          <template v-if="!loading">
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" @click="hideDialog">Cancel</v-btn>
            <v-btn color="blue darken-1" text @click="revertConfirm">OK</v-btn>
            <v-spacer></v-spacer>
          </template>
          <div v-if="loading" class="text-center col col-12">
            <v-progress-circular
              :width="3"
              color="green"
              class="text-center"
              indeterminate
            >
            </v-progress-circular>
          </div>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
     transition="dialog-bottom-transition"
     max-width="600"
     min-height="1400"
     max-height="1400"
    >
     <template v-slot:activator="{ on, attrs }">
       <v-btn
         class="mr-2"
         color="primary"
         rounded
         v-bind="attrs"
         v-on="on"
         @click="fetch()"
       >Import History</v-btn>
     </template>

     <template v-slot:default="dialog">
       <v-card>

         <div class="header d-flex justify-space-between align-center">

         <v-toolbar
           color="primary"
         >
         Import History
        </v-toolbar>

         <div class=" primary headerItem" @click="dialog.value = false">
           <div class="primary customButton"  >
             <span>X</span>
           </div>
         </div>
       </div>
         <div class="d-flex flex-column pt-5">
           <v-data-table
             :headers="headers"
             :items="imports"
             :search="search"
             item-key="name"
              class="elevation-0"
           >
             <template v-slot:top>
               <v-toolbar flat>
                 <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>
               </v-toolbar>
             </template>

             <template v-slot:item.actions="{ item }" >
               <v-btn small color="red" dark @click="showDialog(item)">
                 <v-icon size=26 :color="'white'">
                   mdi-trash-can
                   <!-- mdi-hair-dryer -->
                 </v-icon>
               </v-btn>
             </template>



           </v-data-table>
         </div>
       </v-card>
     </template>

    </v-dialog>
  </fragment>
</template>

<script>
import { Fragment } from 'vue-fragment'
export default {
    components: { Fragment },
    name: 'CSVHistoryDialog',

    data(){
      return {
        loading:false,
        toRevert:null,
        showRevertDialog:false,
        search:null,
        headers:null,
        imports:null,
        error_line: null,
        error_data: null,
        show_error: false,
        error_text: null,
        csv_type:'drilling',
        file: [],
        uploadPercent: 0,
        showUploadProgress: false,
        processingUpload: false
      }
    },
    async created() {

      await this.$store.dispatch("TableManager/get", 'csv_imports');
      this.headers = this.$store.getters["TableManager/headers"];
      this.headers.splice(0, 1);
      this.headers.splice(4, 4);
      var actions = {
        text: 'Actions',
        align: 'center',
        value: 'actions'
      }
      this.headers.push(actions)
      console.log(this.headers)
      await this.$store.dispatch("ImportManager/get", this.$route.params.project_id);
      this.imports = this.$store.getters["ImportManager/imports"];
      this.file = null
    },

    methods: {
      showDialog(item){
        this.showRevertDialog = true
        this.toRevert = item
        console.log(this.showRevertDialog)
      },
      hideDialog(){
        this.showRevertDialog = false
      },
      async revertConfirm(){
        this.loading = true
        console.log(this.toRevert)
        await this.$store.dispatch("ImportManager/destroy", this.toRevert.id);
        await this.$store.dispatch("ImportManager/get", this.$route.params.project_id);
        this.imports = this.$store.getters["ImportManager/imports"];
        this.showRevertDialog = false
        this.loading = false
      },
      async fetch(){
        await this.$store.dispatch("ImportManager/get", this.$route.params.project_id);
        this.imports = this.$store.getters["ImportManager/imports"];
      },
      closeAlert(){
        this.show_error = false
      },
      async upload() {
        console.log(this.csv_type)
        let formData = new FormData()
        formData.append('csvfile', this.file)
        axios.post('/api/importcsv/'+this.csv_type, formData, {
            onUploadProgress: (progressEvent) => {
              this.showUploadProgress = true
              this.uploadPercent = progressEvent.lengthComputable ? Math.round((progressEvent.loaded * 100) / progressEvent.total) : 0;
            }
          })
          .then((response) => {
            // this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: response.data.type, text: response.data.message});
            console.log(response.data.hasOwnProperty('error_line'))
            if(!response.data.success){
              if(response.data.error_line !== undefined){
                this.error_line = response.data.error_line
              }
              if(response.data.error_data !== undefined){
                this.error_data = response.data.error_data
              }
              console.log(this.error_data)
              console.log(this.error_line)

              this.show_error = true;
              this.error_text = response.data.message
            }
            else{
              this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: response.data.type, text: response.data.message});
            }
            // this.file = []
            this.showUploadProgress = false

          })
          .catch((error) => {
            this.showUploadProgress = false
            this.processingUpload = false
            // this.file = []
          })
          this.file = null
          this.$nextTick(()=>{
            console.log(this.$refs.fileInput.$refs.input.file)
          })

          this.showUploadProgress = false
      }
    }
}

</script>

<style>
</style>
