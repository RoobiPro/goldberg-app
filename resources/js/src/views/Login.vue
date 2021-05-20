<template>
<div class="main-area">
  <div v-if="showerror" data-v-7238ed21="" class="mycolor v-snack v-snack--active v-snack--right v-snack--text v-snack--top" style="padding-bottom: 0px; padding-top: 0px;">
      <div class="mycolor v-snack__wrapper v-sheet v-sheet--outlined theme--light">
      <div role="status" aria-live="polite" class="v-snack__content">
        The email and password you entered don't match
      </div>
      <div class="v-snack__action ">
      </div>
    </div>
  </div>
  <div class="mywrapper">

    <div class="left">
      <div class="mycard-wrap" @mousemove="handleMouseMove" @mouseenter="handleMouseEnter" @mouseleave="handleMouseLeave" ref="mycard">
        <div class="mycard" :style="cardStyle">
          <div class="mycard-bg" :style="[cardBgTransform, cardBgImage]"></div>
          <div class="mycard-info">
            <!-- <p name="header">Canyons</p> -->
            <v-img name="header" src="/images/HighResGoldberg.png" width="150" />
            <v-img name="content" src="/images/goldberg_font.png" width="150" style="margin-top: 10px;" />
            <!-- <p name="content">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p> -->
          </div>
        </div>
      </div>
    </div>

    <div class="right">
      <div class="my-card-wrap">
        <div class="card" style="background-color:transparent">
          <div class="card-info">
            <h1 class="text-center">Login</h1>
            <v-form
            @submit.prevent="Login"
            >
            <v-text-field
              v-model="email"
              :error-messages="emailErrors"
              label="E-mail:"
              required
              @input="$v.email.$touch()"
              @blur="$v.email.$touch()"
            ></v-text-field>

            <v-text-field
              label="Password:"
              v-model="password"
              :error-messages="passwordErrors"
              :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              :type="show1 ? 'text' : 'password'"
              name="input-10-1"
              @click:append="show1 = !show1"
              @input="$v.password.$touch()"
              @blur="$v.password.$touch()"
            ></v-text-field>

              <div class="text-center mt-4">
                <v-progress-circular
                v-if="loading"
                  :width="3"
                  color="green"
                  indeterminate
                ></v-progress-circular>

                  <v-btn
                  v-else
                    class="ma-2"
                    color="#71623f"
                    type="submit"
                  >
                    Login
                  </v-btn>

              </div>
            </v-form>
            </div>

        </div>
      </div>
    </div>

  </div>
</div>

</template>

<script>

import { validationMixin } from 'vuelidate'
import { required, sameAs, minLength, email } from 'vuelidate/lib/validators'
import { mapActions } from 'vuex'
import axios from 'axios'

export default {
  mounted() {
    this.width = this.$refs.mycard.offsetWidth;
    this.height = this.$refs.mycard.offsetHeight;
  },
  props: ["dataImage"],
  mixins: [validationMixin],

  data: () => ({
    loading:false,
    showerror:false,
    show1: false,
    show2: true,
    show3: false,
    show4: false,
    email: null,
    password: null,
    rules: {
      required: value => !!value || 'Required.',
      emailMatch: () => (`The email and password you entered don't match`),
    },
    width: 0,
    height: 0,
    mouseX: 0,
    mouseY: 0,
    mouseLeaveDelay: null,

  }),
  validations: {
    email: { required, email },
    password: { required }
  },

  computed: {
    // checkNotificationStatus(){
    //   return this.$store.getters['Notifications/getNotificationStatus']
    // },
    passwordErrors () {
      const errors = []
      if (!this.$v.password.$dirty) return errors
      !this.$v.password.required && errors.push('Password is required')
      return errors
    },
    emailErrors () {
      const errors = []
      if (!this.$v.email.$dirty) return errors
      !this.$v.email.email && errors.push('Must be valid e-mail')
      !this.$v.email.required && errors.push('E-mail is required')
      return errors
    },
    mousePX() {
      return this.mouseX / this.width;
    },
    mousePY() {
      return this.mouseY / this.height;
    },
    cardStyle() {
      const rX = this.mousePX * 30;
      const rY = this.mousePY * -30;
      return {
        transform: `rotateY(${rX}deg) rotateX(${rY}deg)`
      };
    },
    cardBgTransform() {
      const tX = this.mousePX * -40;
      const tY = this.mousePY * -40;
      return {
        transform: `translateX(${tX}px) translateY(${tY}px)`
      };
    },
    cardBgImage() {
      return {
        backgroundImage: `url(https://images.unsplash.com/photo-1479621051492-5a6f9bd9e51a?dpr=2&auto=compress,format&fit=crop&w=1199&h=811&q=80&cs=tinysrgb&crop=)`
      };
    }
  },
  methods: {
    async Login(){
      this.loading=true;
      this.$v.$reset()
      this.$v.email.$touch()
      this.$v.password.$touch()
      if(this.$v.password.$invalid || this.$v.email.$invalid){
        this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: 'Invalid inputs!'});
        this.loading=false;
      }
      else{
        var data={
          email: this.email,
          password: this.password
        }
        console.log('loggin in..')
        await this.signIn(data)
        var login = this.$store.getters['AuthManager/user']
        console.log(login)
        if(login ==null){
          this.showerror= true;
          setTimeout(() => this.showerror = false, 3500);
          this.$store.dispatch('NotificationsManager/setNotificationStatus', {type: 'red', text: 'Invalid login credentials!'});
          this.loading=false;

        }
        else{
          this.$router.replace({ path: '/dashboard' })
          // this.loading=false;
        }
      }


    },
    handleMouseMove(e) {
        this.mouseX = e.pageX - this.$refs.mycard.offsetLeft - this.width / 2;
        this.mouseY = e.pageY - this.$refs.mycard.offsetTop - this.height / 2;
    },
    handleMouseEnter() {
      clearTimeout(this.mouseLeaveDelay);
    },
    handleMouseLeave() {
      this.mouseLeaveDelay = setTimeout(() => {
        this.mouseX = 0;
        this.mouseY = 0;
      }, 1000);
    },
    ...mapActions({
      signIn: 'AuthManager/signIn'
    }),
  }

}
</script>

<style scoped>
.main-area {
  width: 100vw;
  height: 100vh;
  font-size: 14px;
  font-weight: 500;
  -webkit-font-smoothing: antialiased;
  overflow: hidden;
  background-image: url("/images/loginbg.png");
  background-size: cover;
  display: grid;
  grid:
    "header header header"10vh
    "left1 main right2"80vh
    "footer footer footer"10vh
    / auto minmax(590px, 650px) auto;
}


.mywrapper {
  grid-area: main;
  display: grid;
  grid:
    "left right"1fr / 1fr 2fr;
}

.left {
  grid-area: left;
  justify-self: center;
  align-self: center;
}

.right {
  border-radius: 10px;
  margin: 10px;
  min-width: 400px;
  grid-area: right;
  justify-self: center;
  align-self: center;
  /* background-color: transparent; */
  background: rgba(255, 255, 255, .5);
  /* rgba(51, 170, 51, .1)    */
  color: #a58e58;
  min-height: 320px;
}

.title {
  font-family: "Raleway";
  font-size: 24px;
  font-weight: 700;
  color: #5d4037;
  text-align: center;
}

p {
  line-height: 1.5em;
}

h1+p,
p+p {
  margin-top: 10px;
}

.container {
  padding: 0;
  height: 100vh;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;

}

.mycard-wrap {
  margin: 10px;
  transform: perspective(800px);
  transform-style: preserve-3d;
  cursor: pointer;
}

.mycard-wrap:hover .mycard-info {
  transform: translateY(0);
}

.mycard-wrap:hover .mycard-info p {
  opacity: 1;
}

.mycard-wrap:hover .mycard-info,
.mycard-wrap:hover .mycard-info p {
  transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

.mycard-wrap:hover .mycard-info:after {
  transition: 5s cubic-bezier(0.23, 1, 0.32, 1);
  opacity: 1;
  transform: translateY(0);
}

.mycard-wrap:hover .mycard-bg {
  transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1), opacity 5s cubic-bezier(0.23, 1, 0.32, 1);
  opacity: 0.8;
}

.mycard-wrap:hover .mycard {
  transition: 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 2s cubic-bezier(0.23, 1, 0.32, 1);
  box-shadow: rgba(255, 255, 255, 0.2) 0 0 40px 5px, rgba(255, 255, 255, 1) 0 0 0 1px, rgba(0, 0, 0, 0.66) 0 30px 60px 0, inset #333 0 0 0 5px, inset white 0 0 0 6px;
}

.mycard {
  position: relative;
  flex: 0 0 240px;
  width: 240px;
  height: 320px;
  background-color: #333;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: rgba(0, 0, 0, 0.66) 0 30px 60px 0, inset #333 0 0 0 5px, inset rgba(255, 255, 255, 0.5) 0 0 0 6px;
  transition: 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
}

.mycard-bg {
  opacity: 0.5;
  position: absolute;
  top: -20px;
  left: -20px;
  width: 100%;
  height: 100%;
  padding: 20px;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  transition: 1s cubic-bezier(0.445, 0.05, 0.55, 0.95), opacity 5s 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
  pointer-events: none;
}

.mycard-info {
  padding: 20px;
  left: 25px;
  position: absolute;
  bottom: 0;
  transform: translateY(40%);
  transition: 0.6s 1.6s cubic-bezier(0.215, 0.61, 0.355, 1);
}

.mycard-info p {
  opacity: 0;
  text-shadow: rgba(0, 0, 0, 1) 0 2px 3px;
  transition: 0.6s 1.6s cubic-bezier(0.215, 0.61, 0.355, 1);
}

.mycard-info * {
  /* position: relative; */
  z-index: 1;
}

.mycard-info:after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  z-index: 0;
  width: 100%;
  height: 100%;
  /* background-image: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.6) 100%); */
  background-blend-mode: overlay;
  opacity: 0;
  transform: translateY(100%);
  transition: 5s 1s cubic-bezier(0.445, 0.05, 0.55, 0.95);
}

.mycard-info h1 {
  font-family: "Playfair Display";
  font-size: 36px;
  font-weight: 700;
  text-shadow: rgba(0, 0, 0, 0.5) 0 10px 10px;
}

*,
:after,
:before {
  background-repeat: no-repeat;
  box-sizing: initial;
  -webkit-box-sizing: initial;
}

*,
::before,
::after {
  background-repeat: no-repeat;
  -webkit-box-sizing: initial;
  box-sizing: initial;
}

.my-card-wrap {
  margin: 20px;
}

.card {
  border: none;
}

@media only screen and (max-width: 760px) {
  .main-area {
    /* background-color: lightblue; */
    display: flex;
  }

  .mywrapper {
    grid-area: main;
    /* background: red; */
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    justify-content: center;
  }
  .mycard{
    height: 220px;
  }

  .right {
    min-width: 80%;
  }
}

.v-messages__wrapper {
  color: #eb4141;
}

.v-input .v-label {
  color: #a58e58;
}

.v-icon.v-icon{
  color: #dbbd76;
}
.theme--light.v-messages {
    color: rgb(212 69 69)!important;
}
.mycolor {
    color: rgb(226 39 39 / 87%)!important;
}


</style>
