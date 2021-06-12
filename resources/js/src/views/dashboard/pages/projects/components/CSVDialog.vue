<template>
  <v-dialog
   transition="dialog-bottom-transition"
   max-width="600"
   min-height="1400"
   max-height="1400"
  >
   <template v-slot:activator="{ on, attrs }">
     <v-btn
       color="primary"
       v-bind="attrs"
       v-on="on"
     >Import Data</v-btn>
   </template>
   <template v-slot:default="dialog">
     <v-card>

       <div class="header d-flex justify-space-between align-center">

       <v-toolbar
         color="primary"
         dark
       >
       Import CSV Data
      </v-toolbar>

       <!-- <v-card-actions
       color="primary"
       > -->
         <v-btn
           text
           color="primary"
           @click="dialog.value = false">
           X
         </v-btn>
       <!-- </v-card-actions> -->

     </div>

       <!-- <v-card-text>
         <div class="text-h2 pa-12">Fick Dich!</div>
       </v-card-text> -->

       <div class="d-flex flex-column pt-5">


         <v-radio-group
         row
         class="d-flex flex-row align-center"
        style="width: 100%; height: 5vh; margin: 0;"
         >
         <v-radio
         label="Drillings"
         value="radio-1"
         >
         </v-radio>
        <v-radio
           label="Hand Samples"
           value="radio-2"
           >
         </v-radio>
         <v-radio
            label="Wells"
            value="radio-3"
            >
          </v-radio>

       </v-radio-group>

       <hr id="vaddershr">

       <v-container class="py-0">
         <v-row class="mt-4">
           <v-col
             class="text-right"
           >
           <v-file-input
             v-model="file"
             ref="fileInput"
             truncate-length="10"
             label="Upload spatial"
             :clearable="false"
           ></v-file-input>
           </v-col>
           <div v-if="showUploadProgress">
               Uploading: {{ uploadPercent }} %
           </div>
         </v-row>

         <v-row class="mb-4">
           <v-col
             cols="12"
             class="text-center"
           >
             <v-btn
               color="primary"
               class="mr-0"
               :disabled="file === undefined || file === null"
               v-on:click="updateAvatar()"
             >
               Upload
             </v-btn>
           </v-col>
         </v-row>

       </v-container>

     </div>

     <!-- <hr id="vaddershr"> -->





     </v-card>
   </template>
  </v-dialog>
</template>


<script>

export default {
    name: 'CSVDialog',
    data(){
      return {
        file: [],
        uploadPercent: 0,
        showUploadProgress: false,
        processingUpload: false
      }
    },
    created() {
      this.file = null
    },
    methods: {
      async updateAvatar() {
        let formData = new FormData()
        formData.append('spatial', this.file)
        axios.post('/uploadspatial/'+this.$route.params.id, formData, {

            onUploadProgress: (progressEvent) => {

              this.showUploadProgress = true
              this.uploadPercent = progressEvent.lengthComputable ? Math.round((progressEvent.loaded * 100) / progressEvent.total) : 0;
            }
          })
          .then((response) => {
            this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: response.data.type, text: response.data.message});
            console.log(response)
            // this.file = []

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
          // await this.$store.dispatch("TableManager/get", 'spatials');
          // this.headers = this.$store.getters["TableManager/headers"];
          // this.createHeader()
          // await this.$store.dispatch('ProjectsManager/getSpatials', this.$route.params.id)
          // this.spatials =  this.$store.getters["ProjectsManager/spatials"]
          // console.log(this.spatials)
          this.showUploadProgress = false


      }
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
</style>
