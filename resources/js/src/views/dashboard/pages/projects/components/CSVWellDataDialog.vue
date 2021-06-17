<template>
  <v-dialog
   transition="dialog-bottom-transition"
   max-width="600"
   min-height="1400"
   max-height="1400"
  >
   <template v-slot:activator="{ on, attrs }">
     <v-btn
       class="ma-2"
       color="primary"
       rounded
       v-bind="attrs"
       v-on="on"
     >Import Well Data</v-btn>
   </template>
   <template v-slot:default="dialog">
     <v-card>

       <div class="header d-flex justify-space-between align-center">

       <v-toolbar
         color="primary"
       >
       Import Well Data
      </v-toolbar>

       <!-- <v-card-actions
       color="primary"
       > -->
       <div class=" primary headerItem" @click="dialog.value = false">

         <div class="primary customButton"  >
           <span>X</span>
         </div>

       </div>
       <!-- </v-card-actions> -->

     </div>

       <!-- <v-card-text>
         <div class="text-h2 pa-12">Fick Dich!</div>
       </v-card-text> -->

       <div class="d-flex flex-column pt-5">


         <v-radio-group
           row
           v-model="csv_type"
           class="d-flex flex-row align-center"
           style="width: 100%; height: 5vh; margin: 0;"
         >
         </v-radio>
         <v-radio
           label="Assay-Data"
           value="assay"
         >
         </v-radio>
         <v-radio
           label="Lithology-Data"
           value="lithology"
         >
         </v-radio>
         <v-radio
           label="Survey-Data"
           value="survey"
         >
         </v-radio>

       </v-radio-group>

       <hr id="vaddershr">

       <v-container class="py-0">
         <v-row class="mt-4">
           <v-col
           >
           <v-file-input
             v-model="file"
             ref="fileInput"
             truncate-length="10"
             label="Upload spatial"
             :clearable="false"
           ></v-file-input>
           </v-col>
           <!-- <div v-if="showUploadProgress">
               Uploading: {{ uploadPercent }} %
           </div> -->
         </v-row>

         <v-alert
            v-if="show_error"
            prominent
            type="error"
          >
            <v-row align="center">
              <v-col class="grow">
                <div v-if="error_line">
                  <u>Error-line:</u> {{error_line}} <br>
                  <u>Error-data:</u> {{error_data}}
                </div>
                <div>
                  <u>Error-message:</u> {{error_text}}
                </div>
              </v-col>
            </v-row>

            <span
              @click="closeAlert()"
              id="closealert"
              aria-atomic="true"
              aria-label="Badge"
              aria-live="polite"
              role="status"
              class="v-badge__badge black">
              <span>
                x
              </span>
            </span>

          </v-alert>

         <v-row class="mb-4">
           <v-col
             cols="12"
             class="text-center"
           >
             <v-progress-circular
             v-if="showUploadProgress"
               :width="3"
               color="green"
               indeterminate
             ></v-progress-circular>
             <v-btn
               v-if="!showUploadProgress"
               color="primary"
               class="mr-0"
               :disabled="file === undefined || file === null"
               v-on:click="upload()"
             >
               Upload
             </v-btn>
           </v-col>
         </v-row>

       </v-container>

     </div>

     </v-card>
   </template>
  </v-dialog>
</template>


<script>

export default {
    name: 'CSVDrillingDataDialog',
    data(){
      return {
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
    created() {

      this.file = null
    },
    methods: {
      closeAlert(){
        this.show_error = false
      },
      async upload() {
        console.log(this.csv_type)
        let formData = new FormData()
        formData.append('csvfile', this.file)
        axios.post('/importcsv/well/'+this.csv_type, formData, {
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
