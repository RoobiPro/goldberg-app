<template>
  <v-container
    id="user-profile"
    fluid
    tag="section"
  >
    <v-row justify="center">
      <v-col
        cols="12"
        md="8"
      >
        <base-material-card>
          <template v-slot:heading>
            <div class="display-2 font-weight-light">
              Edit Profile
            </div>

            <div class="subtitle-1 font-weight-light">
              Complete your profile
            </div>
          </template>

          <v-form>
            <v-container class="py-0">
              <v-row>
                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    label="Company (disabled)"
                    disabled
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    class="purple-input"
                    label="User Name"
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    label="Email Address"
                    class="purple-input"
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="6"
                >
                  <v-text-field
                    label="First Name"
                    class="purple-input"
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="6"
                >
                  <v-text-field
                    label="Last Name"
                    class="purple-input"
                  />
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    label="Adress"
                    class="purple-input"
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    label="City"
                    class="purple-input"
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    label="Country"
                    class="purple-input"
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    class="purple-input"
                    label="Postal Code"
                    type="number"
                  />
                </v-col>

                <v-col cols="12">
                  <v-textarea
                    class="purple-input"
                    label="About Me"
                    value="Lorem ipsum dolor sit amet, consectetur adipiscing elit."
                  />
                </v-col>

                <v-col
                  cols="12"
                  class="text-right"
                >
                  <v-btn
                    color="success"
                    class="mr-0"
                  >
                    Update Profile
                  </v-btn>
                </v-col>

                <v-col
                  cols="12"
                  class="text-right"
                >

                <v-file-input
                  v-model="file"
                  truncate-length="15"
                  label="Change your avatar"
                  accept=".jpg, .png"
                  prepend-icon="mdi-camera"
                  @change="updateAvatar">
                ></v-file-input>
                Uploading: {{ uploadPercent }} %
                </v-col>

                <div v-if="showUploadProgress">
                    Uploading: {{ uploadPercent }} %
                </div>

              </v-row>
            </v-container>
          </v-form>
        </base-material-card>
      </v-col>

      <v-col
        cols="12"
        md="4"
      >
        <base-material-card
          class="v-card-profile"
          avatar="https://demos.creative-tim.com/vue-material-dashboard/img/marc.aba54d65.jpg"
        >
          <v-card-text class="text-center">
            <h6 class="display-1 mb-1 grey--text">
              CEO / CO-FOUNDER
            </h6>

            <h4 class="display-2 font-weight-light mb-3 black--text">
              Alec Thompson
            </h4>

            <p class="font-weight-light grey--text">
              Don't be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...
            </p>

            <v-btn
              color="success"
              rounded
              class="mr-0"
            >
              Follow
            </v-btn>
          </v-card-text>
        </base-material-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from 'axios'
export default {

    name: "AvatarImageComponent",
    props: ['avatarUrl'],
    data() {
        return {
            file: [],
            uploadPercent: 0,
            avatarImageUrl: "",
            showUploadProgress: false,
            processingUpload: false
        }
    },
    mounted(){
        this.avatarImageUrl = this.avatarUrl
    },
    methods: {
        updateAvatar(){
          console.log("uploading")
          console.log(this.file)
          let formData = new FormData()
          formData.append('avatar', this.file)
          axios.post('/api/upload_avatar', formData, {
              onUploadProgress: (progressEvent) => {
                  this.uploadPercent = progressEvent.lengthComputable ? Math.round( (progressEvent.loaded * 100) / progressEvent.total ) : 0 ;
              }
          })
          .then( (response) => {
            console.log(response)
              this.$store.commit('auth/SET_USERAVATAR', response.data.avatar)
              this.avatarImageUrl = response.data.avatar_url
              this.showUploadProgress = false
              this.processingUpload = false
              this.$emit('imageUrl', response.data.secure_url )
  
              console.log(this.$store.getters["auth/user"])
          })
          .catch( (error) => {
              if(error.response){
                  console.log(error.message)
              }else{
                  console.log(error)
              }
              this.showUploadProgress = false
              this.processingUpload = false
          })
            if(this.$refs.photo){
                this.showUploadProgress = true
                this.processingUpload = true
                this.uploadPercent = 0
                let formData = new FormData()
                formData.append('avatar', this.$refs.photo)
                axios.post('/upload_avatar', formData, {
                    onUploadProgress: (progressEvent) => {
                        this.uploadPercent = progressEvent.lengthComputable ? Math.round( (progressEvent.loaded * 100) / progressEvent.total ) : 0 ;
                    }
                })
                .then( (response) => {
                    this.avatarImageUrl = response.data.avatar_url
                    this.showUploadProgress = false
                    this.processingUpload = false
                    this.$emit('imageUrl', response.data.secure_url )
                })
                .catch( (error) => {
                    if(error.response){
                        console.log(error.message)
                    }else{
                        console.log(error)
                    }
                    this.showUploadProgress = false
                    this.processingUpload = false
                })
            }
        }
    }
}
</script>
