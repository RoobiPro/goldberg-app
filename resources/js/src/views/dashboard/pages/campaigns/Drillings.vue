<template>
  <v-container
  v-if="ready&&auth"
  class="d-flex justify-center"
  tag="section"
  style="margin-top:10vh;">

    <v-data-table
      :headers="headers"
      :items="campaign.drillings"
      :search="search"
      :single-expand="singleExpand"
      :expanded.sync="expanded"
      item-key="id"
      show-expand
    >
      <template v-slot:top>
        <v-toolbar flat>
          <div class="hidden-md-and-down v-application primary mr-4 text-start v-card--material__heading mb-n6 v-sheet theme--dark pa-7"
            style="max-height: 90px; width: auto;">
            <i aria-hidden="true" class="v-icon notranslate mdi mdi-screw-lag theme--dark" style="font-size: 32px;">
            </i>
          </div>
          <v-toolbar-title>Drillings</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" class="mx-4" single-line hide-details></v-text-field>

          <template>
            <v-divider v-if="project.pivot.role==2" class="mx-4" inset vertical></v-divider>
            <v-dialog v-if="project.pivot.role==2" v-model="newDillingDialog" max-width="500px">
              <template v-slot:activator="{ on, attrs }">
                <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                  New drilling
                  <v-icon size=26 :color="'white'" style="padding-left:10px">
                    mdi-screw-lag
                  </v-icon>
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">New drilling</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <v-col cols="12">

                      <v-text-field
                        v-model="newDrilling.coordinates_x"
                        label="Coordinate X"
                        @input="$v.newDrilling.coordinates_x.$touch()"
                        @blur="$v.newDrilling.coordinates_x.$touch()"
                        :error-messages="coordinates_x_Errors"
                      ></v-text-field>
                      <v-text-field
                        v-model="newDrilling.coordinates_y"
                        label="Coordinate Y"
                        @input="$v.newDrilling.coordinates_y.$touch()"
                        @blur="$v.newDrilling.coordinates_y.$touch()"
                        :error-messages="coordinates_y_Errors"
                      ></v-text-field>
                      <v-text-field
                        v-model="newDrilling.coordinates_z"
                        label="Coordinate Z"
                        @input="$v.newDrilling.coordinates_z.$touch()"
                        @blur="$v.newDrilling.coordinates_z.$touch()"
                        :error-messages="coordinates_z_Errors"
                      ></v-text-field>
                      <v-text-field
                        v-model="newDrilling.azimuth"
                        label="Azimuth"
                        @input="$v.newDrilling.azimuth.$touch()"
                        @blur="$v.newDrilling.azimuth.$touch()"
                        :error-messages="azimuth_Errors"
                      ></v-text-field>
                      <v-text-field
                        v-model="newDrilling.dip"
                        label="Dip"
                        @input="$v.newDrilling.dip.$touch()"
                        @blur="$v.newDrilling.dip.$touch()"
                        :error-messages="dip_Errors"
                      ></v-text-field>
                      <v-text-field
                        v-model="newDrilling.length"
                        label="Length"
                        @input="$v.newDrilling.length.$touch()"
                        @blur="$v.newDrilling.length.$touch()"
                        :error-messages="length_Errors"
                      ></v-text-field>

                      <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        :return-value.sync="newDrilling.start_date_formatted"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="computedStartDateFormatted"
                            label="Drilling start date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            @blur="date = parseDate(newDrilling.start_date_formatted)"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="newDrilling.start_date"
                          color="primary"
                          no-title
                          scrollable
                          style="background: white;"
                          @input="menu = false"
                        >
                          <v-spacer></v-spacer>
                          <v-btn
                            text
                            color="primary"
                            @click="menu = false"
                          >
                            Cancel
                          </v-btn>
                          <v-btn
                            text
                            color="primary"
                            @click="$refs.menu.save(newDrilling.start_date)"
                          >
                            OK
                          </v-btn>
                        </v-date-picker>
                      </v-menu>

                      <v-menu
                        ref="menu2"
                        v-model="menu2"
                        :close-on-content-click="false"
                        :return-value.sync="newDrilling.end_date_formatted"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="computedEndDateFormatted"
                            label="Drilling end date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            @blur="date = parseDate(newDrilling.end_date_formatted)"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="newDrilling.end_date"
                          color="primary"
                          no-title
                          scrollable
                          style="background: white;"
                          @input="menu2 = false"
                        >
                          <v-spacer></v-spacer>
                          <v-btn
                            text
                            color="primary"
                            @click="menu2 = false"
                          >
                            Cancel
                          </v-btn>
                          <v-btn
                            text
                            color="primary"
                            @click="$refs.menu2.save(newDrilling.end_date)"
                          >
                            OK
                          </v-btn>
                        </v-date-picker>
                      </v-menu>

                    </v-col>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="closeDialogs">
                    Cancel
                  </v-btn>
                  <v-btn color="blue darken-1" text @click="saveDrilling">
                    Create
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </template>

        </v-toolbar>
      </template>
      <template  v-slot:item.actions="{ item }">
          <v-icon v-if="!project.pivot.role==0" size=26 :color="'blue'"
          @click="editItem(item)">
            mdi-file-edit
          </v-icon>
          <v-icon v-if="project.pivot.role==2" size=26 :color="'red'"
          @click="removeItem(item)">
            mdi-file-remove
          </v-icon>
      </template>

      <template style="" v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length" style="padding-left: 0px; padding-right: 0px;">
          hello I'm Wayan!
        </td>

      </template>

    </v-data-table>
  </v-container>
</template>
<script>
import { validationMixin } from 'vuelidate'
import { required, decimal, numeric } from 'vuelidate/lib/validators'
  export default {
    mixins: [validationMixin],
    data: vm  => ({
      newDrilling:{
        campaign_id:null,
        coordinates_x:null,
        coordinates_y:null,
        coordinates_z:null,
        azimuth:null,
        dip:null,
        length:null,
        start_date: new Date().toISOString().substr(0, 10),
        start_date_formatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
        end_date: new Date().toISOString().substr(0, 10),
        end_date_formatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
      },
      menu: false,
      menu2: false,
      ready: false,
      auth: false,
      project: null,
      campaign: null,
      me: null,
      search: '',
      newDillingDialog: false,
      expanded: [],
      singleExpand: true,
    }),
    validations: {
      newDrilling:{
        coordinates_x: {
          required,
          mydecimal(coordinates_x) {
            return (
              /^\d{1,3}$|^\d{1,3}\.\d{1,7}$/.test(coordinates_x)
            );
          }
        },
        coordinates_y: {
          required,
          mydecimal(coordinates_x) {
            return (
              /^\d{1,3}$|^\d{1,3}\.\d{1,7}$/.test(coordinates_x)
            );
          }
        },
        coordinates_z: { required,
          mydecimal(coordinates_x) {
            return (
              /^\d{1,2}$|^\d{1,2}\.\d{1,4}$/.test(coordinates_x)
            );
          }
        },
        azimuth: { required,
          mydecimal(coordinates_x) {
            return (
              /^\d{1,2}$|^\d{1,2}\.\d{1,4}$/.test(coordinates_x)
            );
          }
        },
        dip: { required,
          mydecimal(coordinates_x) {
            return (
              /^\d{1,2}$|^\d{1,2}\.\d{1,4}$/.test(coordinates_x)
            );
          }
        },
        length: { required,
          mydecimal(coordinates_x) {
            return (
              /^\d{1,2}$|^\d{1,2}\.\d{1,4}$/.test(coordinates_x)
            );
          }
        },
      }
    },
    mounted() {
      this.checkAuth()
      this.getCampaign()
    },
    computed: {
        coordinates_x_Errors () {
          const errors = []
          if (!this.$v.newDrilling.coordinates_x.$dirty) return errors
          !this.$v.newDrilling.coordinates_x.required && errors.push('Coordinate X is required.')
          !this.$v.newDrilling.coordinates_x.mydecimal && errors.push('use the format: XXX.YYYYYYY')
          return errors
        },
        coordinates_y_Errors () {
          const errors = []
          if (!this.$v.newDrilling.coordinates_y.$dirty) return errors
          !this.$v.newDrilling.coordinates_y.required && errors.push('Coordinate Y is required.')
          !this.$v.newDrilling.coordinates_y.mydecimal && errors.push('use the format: XXX.YYYYYYY')
          return errors
        },
        coordinates_z_Errors () {
          const errors = []
          if (!this.$v.newDrilling.coordinates_z.$dirty) return errors
          !this.$v.newDrilling.coordinates_z.required && errors.push('Coordinate Z is required.')
          !this.$v.newDrilling.coordinates_z.mydecimal && errors.push('use the format: XX.YYYY')
          return errors
        },
        azimuth_Errors () {
          const errors = []
          if (!this.$v.newDrilling.azimuth.$dirty) return errors
          !this.$v.newDrilling.azimuth.required && errors.push('Azimuth is required.')
          !this.$v.newDrilling.azimuth.mydecimal && errors.push('use the format: XX.YYYY')
          return errors
        },
        dip_Errors () {
          const errors = []
          if (!this.$v.newDrilling.dip.$dirty) return errors
          !this.$v.newDrilling.dip.required && errors.push('Dip is required.')
          !this.$v.newDrilling.dip.mydecimal && errors.push('use the format: XX.YYYY')
          return errors
        },
        length_Errors () {
          const errors = []
          if (!this.$v.newDrilling.length.$dirty) return errors
          !this.$v.newDrilling.length.required && errors.push('Length is required.')
          !this.$v.newDrilling.length.mydecimal && errors.push('use the format: XX.YYYY')
          return errors
        },
        computedStartDateFormatted () {
          return this.formatDate(this.newDrilling.start_date)
        },
        computedEndDateFormatted () {
          return this.formatDate(this.newDrilling.end_date)
        },
        headers () {
          return [
            { text: 'ID', align: 'start', value: 'id', },
            { text: 'Coordinate X', align: 'start', value: 'coordinates_x'},
            { text: 'Coordinate Y', align: 'start', value: 'coordinates_y'},
            { text: 'Coordinate Z', align: 'start', value: 'coordinates_z'},
            { text: 'Azimuth', align: 'start', value: 'azimuth'},
            { text: 'Dip', align: 'center', value: 'dip' },
            { text: 'Length', align: 'center', value: 'length' },
            { text: 'Start date', align: 'center', value: 'start_date' },
            { text: 'End date', align: 'center', value: 'end_date' },
            { text: 'Actions', align: 'start' ,value: 'actions' },
            { text: '', align: 'center', value: 'data-table-expand' },
          ]
        },
    },
    watch: {
      'newDrilling.start_date': function(val){
        this.newDrilling.start_date_formatted = this.formatDate(this.newDrilling.start_date)
      },
      'newDrilling.end_date': function(val){
        this.newDrilling.end_date_formatted = this.formatDate(this.newDrilling.end_date)
      },
    },
    methods:{
      formatDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}.${month}.${year}`
      },
      parseDate (date) {
        if (!date) return null

        const [day, month, year] = date.split('.')
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
      },
      editItem(item){
        console.log(item)
      },
      removeItem(item){
        console.log(item)
      },
      closeDialogs(){
        this.newDillingDialog = false
      },
      saveDrilling(){
        this.$v.newDrilling.coordinates_x.$touch()
        this.$v.newDrilling.coordinates_y.$touch()
        this.$v.newDrilling.coordinates_z.$touch()
        this.$v.newDrilling.azimuth.$touch()
        this.$v.newDrilling.dip.$touch()
        this.$v.newDrilling.length.$touch()
        if(
          this.$v.newDrilling.coordinates_x.$invalid||
          this.$v.newDrilling.coordinates_y.$invalid||
          this.$v.newDrilling.coordinates_z.$invalid||
          this.$v.newDrilling.azimuth.$invalid||
          this.$v.newDrilling.dip.$invalid||
          this.$v.newDrilling.length.$invalid
        ){
        }
        else{
          this.newDrilling.campaign_id = this.campaign.id
          this.$store.dispatch('CampaignManager/createDrilling', this.newDrilling);
          this.getCampaign()
          this.newDillingDialog = false
          this.$v.$reset()
          this.newDrilling={
            campaign_id:'',
            coordinates_x:'',
            coordinates_y:'',
            coordinates_z:'',
            azimuth:'',
            dip:'',
            length:'',
            start_date: new Date().toISOString().substr(0, 10),
            start_date_formatted: this.formatDate(new Date().toISOString().substr(0, 10)),
            end_date: new Date().toISOString().substr(0, 10),
            end_date_formatted: this.formatDate(new Date().toISOString().substr(0, 10))
          }
        }

      },
      openDrillings(){
        this.$router.push({ path: '/project/'+this.$route.params.project_id+'/campaign/'+this.$route.params.campaign_id+'/drillings' })
      },
      openWells(){
        console.log('openWells')
      },
      openSamples(){
        console.log('Samples')
      },
      openSpatial(){
        console.log('openSpatial')
      },
      async getCampaign(){

        await this.$store.dispatch('CampaignManager/getcampaign', this.$route.params.campaign_id);
        this.campaign = this.$store.getters["CampaignManager/campaign"];
        this.ready = true
      },
      async checkAuth(){
        this.me = await this.$store.getters["AuthManager/user"];
        await this.$store.dispatch('UsersManager/userprojects', this.me.id);
        this.projects = await this.$store.getters["UsersManager/projects"];
        this.project = this.projects.filter(obj => {
          return obj.id == this.$route.params.project_id
        })[0]
        if(this.project==undefined){
          this.$router.push({ path: '/myprojects' })
        }
        else{
          this.auth=true
          // this.ready = true
        }
      },
    }
  }
</script>
<style>
  .v-menu__content{
    background: white;
  }
</style>
