<template>
  <v-container
     id="user-profile"
     fluid tag="section"
     style="max-width: 1000px"
     class="mt-10"
  >

    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card
          class="v-card--material pa-3"
        >
          <div class="d-flex grow flex-wrap">
            <div class="text-start v-card--material__heading mb-n6 v-sheet elevation-6 primary pa-7">
              <i aria-hidden="true" class="v-icon notranslate mdi mdi-attachment white--text" style="font-size: 32px;"></i>
            </div>
          </div>
          <v-form>
            <v-container class="py-0">
              <v-row class="mt-4">
                <v-col
                  class="text-right"
                >
                <v-file-input
                  v-model="file"
                  ref="fileInput"
                  truncate-length="15"
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
                    :disabled="file === undefined"
                    v-on:click="updateAvatar()"
                  >
                    Upload
                  </v-btn>
                </v-col>
              </v-row>

            </v-container>
          </v-form>
          <!---->
        </div>
        </v-card>
      </v-col>
    </v-row>

    <v-row justify="center">
      <v-data-table
        :headers="headers"
        :items="spatials"
        :search="search"
        item-key="name"
         class="elevation-0"
      >

        <template v-slot:top>
          <v-toolbar flat>
            <div class="hidden-md-and-down v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 pa-7"
              style="max-height: 90px; width: auto;">
              <i aria-hidden="true" class="v-icon notranslate mdi mdi-hammer-wrench theme--dark" style="font-size: 32px;">
              </i>
            </div>
            <v-toolbar-title>My projects</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>
            <v-divider class="mx-4" inset vertical></v-divider>

            <!-- <v-divider class="mx-4" inset vertical></v-divider> -->
          </v-toolbar>
        </template>

        <template  v-slot:item.actions="{ item }">

          <a href="/images/myw3schoolsimage.jpg" download>

          </a>

          <v-btn small color="primary" dark
           @click="displaySpatial(item.id)"
           >

            <v-icon size=26 :color="'white'">
              mdi-eye
            </v-icon>
          </v-btn>

          <v-btn small color="primary" dark
           @click="downloadSpatial(item.id)"
           >
            <v-icon size=26 :color="'white'">
              mdi-download
            </v-icon>
          </v-btn>

          <v-btn small color="red" dark
           @click="showDeleteDialog(item.id)"
           >
            <v-icon size=26 :color="'white'">
              mdi-trash-can
            </v-icon>
          </v-btn>


        </template>
      </v-data-table>
    </v-row>

    <v-dialog v-model="deleteProjectDialog" max-width="400px">
      <v-card>
        <v-card-title class="headline">Are you sure?</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="closeDialogs">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="deleteProjectConfirm">OK</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-container>
</template>

<script>
import axios from 'axios'
import {
  mapState
} from 'vuex'
export default {
  name: "AvatarImageComponent",
  props: ['avatarUrl'],
  data() {
    return {
      delete_id: null,
      deleteProjectDialog: false,
      search: '',
      headers:[],
      file: [],
      myuser:[],
      spatials:[],
      uploadPercent: 0,
      avatarImageUrl: "",
      fullAvatarUrl: "",
      showUploadProgress: false,
      processingUpload: false
    }
  },
  computed: {
    ...mapState("LayoutManager", ['barColor', 'barImage']),

  },
  async created() {
    this.createHeader()
    await this.$store.dispatch('ProjectsManager/getSpatials', this.$route.params.id)
    this.spatials =  this.$store.getters["ProjectsManager/spatials"]
    // console.log(this.headers)
  },
  async mounted() {
    const user =  this.$store.getters["AuthManager/user"]
    this.myuser = JSON.parse(JSON.stringify(user));
    this.avatarImageUrl = this.avatarUrl
    this.fullAvatarUrl = '/storage/user-avatar/' + this.myuser.avatar
    console.log(this.$route.params)

    // console.log(spatials)
  },
  methods: {
    showDeleteDialog(id){
      this.delete_id = id
      this.deleteProjectDialog = true;
    },
    closeDialogs(){
      this.deleteProjectDialog = false;
    },
    async deleteProjectConfirm(){
      console.log("deleting")
      console.log(this.delete_id)
      await axios.get('/deletespatial/'+this.delete_id)
        .then((response) => {
          console.log(response)
          this.deleteProjectDialog = false;
        })
      await this.$store.dispatch('ProjectsManager/getSpatials', this.$route.params.id)
      this.spatials =  this.$store.getters["ProjectsManager/spatials"]
    },
    async createHeader(){
      await this.$store.dispatch("TableManager/get", 'spatials');
      this.headers = this.$store.getters["TableManager/headers"];
      var actions = {
        text: 'Actions',
        align: 'center',
        value: 'actions'
      }
      this.headers.push(actions)
    },
    displaySpatial(id){
      window.open('/displayspatial/'+id, '_blank').focus();
    },
    downloadSpatial(id){
      window.location.href = '/downloadspatial/'+id;
    },
    updateProfile(){
      this.$store.dispatch('AuthManager/update', this.myuser)
    },
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
        await this.$store.dispatch("TableManager/get", 'spatials');
        this.headers = this.$store.getters["TableManager/headers"];
        this.createHeader()
        await this.$store.dispatch('ProjectsManager/getSpatials', this.$route.params.id)
        this.spatials =  this.$store.getters["ProjectsManager/spatials"]
        console.log(this.spatials)
        this.showUploadProgress = false
        // this.file = []

    }
  }
}
</script>
