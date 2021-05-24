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
            <i aria-hidden="true" class="v-icon notranslate mdi mdi-account white--text" style="font-size: 32px;"></i>
          </div>
        </div>
        <v-form>
          <v-container class="py-0">
            <v-row class="mt-4">
              <v-col
                cols="2"
                class="text-left"
              >
              <v-avatar size="80">
                <img
                  :src="'/storage/user-avatar/'+myuser.avatar"
                  :alt="myuser.name"
                >
              </v-avatar>
              </v-col>
              <v-col
                cols="10"
                class="text-right"
              >
              <v-file-input
                v-model="file"
                truncate-length="15"
                label="Change your avatar"
                accept=".jpg, .png"
                prepend-icon="mdi-camera"
                :clearable="false"
                @change="updateAvatar">
              ></v-file-input>
              </v-col>
              <div v-if="showUploadProgress">
                  Uploading: {{ uploadPercent }} %
              </div>
            </v-row>
            <v-row>
              <v-col
                cols="12"
                md="6"
              >
                <v-text-field
                  v-model="myuser.name"
                  class="purple-input"
                  label="Name"
                />
              </v-col>
              <v-col
                cols="12"
                md="6"
              >
                <v-text-field
                  disabled
                  v-model="myuser.email"
                  label="Email Address"
                  class="purple-input"
                />
              </v-col>
            </v-row>
            <v-row class="mb-4">
              <v-col
                cols="12"
                class="text-center"
              >
                <v-btn
                  color="primary"
                  class="mr-0"
                  v-on:click="updateProfile()"
                >
                  Update Profile
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
      file: [],
      myuser:[],
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
  mounted() {
    const user =  this.$store.getters["AuthManager/user"]
    this.myuser = JSON.parse(JSON.stringify(user));
    this.avatarImageUrl = this.avatarUrl
    this.fullAvatarUrl = '/storage/user-avatar/' + this.myuser.avatar
  },
  methods: {
    updateProfile(){
      this.$store.dispatch('AuthManager/update', this.myuser)
    },
    updateAvatar() {
      let formData = new FormData()
      formData.append('avatar', this.file)
      axios.post('/api/upload_avatar', formData, {
          onUploadProgress: (progressEvent) => {
            this.showUploadProgress = true
            this.uploadPercent = progressEvent.lengthComputable ? Math.round((progressEvent.loaded * 100) / progressEvent.total) : 0;
          }
        })
        .then((response) => {
          this.$store.commit('AuthManager/SET_USERAVATAR', response.data.avatar)
          this.avatarImageUrl = response.data.avatar_url
          this.myuser.avatar = response.data.avatar
          this.showUploadProgress = false
          this.processingUpload = false
          this.$emit('imageUrl', response.data.secure_url)
        })
        .catch((error) => {
          this.showUploadProgress = false
          this.processingUpload = false
        })
      if (this.$refs.photo) {
        this.showUploadProgress = true
        this.processingUpload = true
        this.uploadPercent = 0
        let formData = new FormData()
        formData.append('avatar', this.$refs.photo)
        axios.post('/upload_avatar', formData, {
            onUploadProgress: (progressEvent) => {
              this.uploadPercent = progressEvent.lengthComputable ? Math.round((progressEvent.loaded * 100) / progressEvent.total) : 0;
            }
          })
          .then((response) => {
            this.avatarImageUrl = response.data.avatar_url
            this.myuser.avatar = response.data.avatar_url
            this.showUploadProgress = false
            this.processingUpload = false
            this.$emit('imageUrl', response.data.secure_url)
          })
          .catch((error) => {
            this.showUploadProgress = false
            this.processingUpload = false
          })
      }
    }
  }
}
</script>
