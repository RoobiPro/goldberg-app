<template>

  <transition name="fade">

    <router-view />

  </transition>

</template>

<script>
  export default {
    name: 'App'
  }
</script>

<style>
/* 
.fade-enter-active, .fade-leave-active {
  transition: opacity 1s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
} */

</style>
