<template>
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
     >Import Campaigns</v-btn>
   </template>
   <template v-slot:default="dialog">
     <v-card>

       <div class="header d-flex justify-space-between align-center">

       <v-toolbar
         color="primary"
       >
       Import Campaigns
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
         <v-radio
           label="Drillings"
           value="drilling"
         >
         </v-radio>
         <v-radio
           label="Hand Samples"
           value="handsample"
         >
         </v-radio>
         <v-radio
           label="Wells"
           value="well"
         >
         </v-radio>

       </v-radio-group>

       <hr id="vaddershr">
       <v-form>
       <v-container class="py-0">
         <v-row class="mt-4">

           <v-col
           >

             <v-file-input
               v-model="file"
               ref="fileInput"
               truncate-length="10"
               label="Upload spatial"
               accept=".csv"
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
       </v-form>
     </div>

     </v-card>
   </template>
  </v-dialog>
</template>


<script>

export default {
    name: 'CSVCampaignsDialog',
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

@media (max-width: 950px) {
  .headerItem{
    /* display:block; */
    display: flex;
    justify-content: center;
    align-items: center;
    width: 10%;
    height: 56px;
  }
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
