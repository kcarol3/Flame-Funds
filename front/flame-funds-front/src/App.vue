<template>

  <div :class="{ 'mobile-style': isMobile }">
    <navbar-component v-if="!$route.meta.hideNavbar"></navbar-component>
  <router-view v-slot="{ Component }">
    <transition name="slide-fade" mode="out-in">
      <component :is="Component"></component>
    </transition>
  </router-view>
  </div>
</template>

<script>

import NavbarComponent from "@/components/Navbar.vue";
import 'mosha-vue-toastify/dist/style.css'

export default {
  name: 'App',
  components: {
    NavbarComponent
  },
  data() {
    return {
      isMobile: false,
      id: 0,
      animation: {
        enter: {
          opacity: [1, 0],
          translateX: [0, -300],
          scale: [1, 0.2],
        },
        leave: {
          opacity: 0,
          height: 0,
        },
      },
    };
  },

  created() {
    this.isMobile = window.innerWidth <= 768;
  },
}
</script>

<style>
body {
  background: rgba(177, 114, 234, 0.2);
}

.error {
  border: 1px solid red;
  background-color: #f19e9e;
  animation: shake 0.5s ease-in-out;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
  20%, 40%, 60%, 80% { transform: translateX(10px); }
}

.shake {
  animation: shake 0.5s ease-in-out;
}

.jump{
  animation: myAnim 3s linear 0s infinite alternate forwards;
}

@keyframes myAnim {
  0%,
  100% {
    transform: translateY(0);
  }

  10%,
  30%,
  50%,
  70% {
    transform: translateY(-8px);
  }

  20%,
  40%,
  60% {
    transform: translateY(8px);
  }

  80% {
    transform: translateY(6.4px);
  }

  90% {
    transform: translateY(-6.4px);
  }
}

@media screen and (max-width: 424px){
  body {
    background-image: url('assets/flame_upscaled-mobile.jpg');
    background-size: cover;
    width: 100%;
    height: 100%;
  }

  .tap-buttons{
    position: sticky;
    bottom: 0px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0%), rgba(255, 255, 255, 80%));
  }
}

#app {
  background: linear-gradient(to bottom, rgba(255, 255, 255, 0%), rgba(255, 255, 255, 70%), rgba(255, 255, 255, 0%));
}


.icon {
  margin:  10px;
  font-size: 32px;
}

/* route transitions */
.route-enter-from {
  opacity: 0;
  transform: translateX(100px);
}
.route-enter-active {
  transition: all 0.3s ease-out;
}
.route-leave-to {
  opacity: 0;
  transform: translateX(-100px);
}
.route-leave-active {
  transition: all 0.2s ease-in;
}
.slide-fade-enter-active {
  transition: all .2s ease;
}
.slide-fade-leave-active {
  transition: all .4s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to{
  transform: translateX(10px);
  opacity: 0;
}

.bgcolor {
  background-color: #9646e3;
}
.lilita-one {
  font-family: 'Lilita One', sans-serif;
}

.button-primary {
  box-shadow: 0 0 8px 5px rgba(0, 0, 0, 0.31);
  background: radial-gradient(circle, rgba(177, 114, 234, 0.82), #9646e3);
  display: inline-block;
  font-size: 32px;
  padding: 10px 20px;
  max-width: 312px;
  max-height: 96px;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Lilita One', sans-serif;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.button-secondary {
  background: radial-gradient(circle, rgba(193, 161, 222, 0.69), #b172ea);
  box-shadow: 0 0 8px 5px rgba(0, 0, 0, 0.31);
  display: inline-block;
  font-size: 32px;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Lilita One', sans-serif;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
  color: rgb(255, 255, 255);
}

.button-primary:hover {
  box-shadow: 0 0 10px 7px rgba(0, 0, 0, 0.4), 0 0 8px 8px rgba(0, 0, 0, 0.35);
  transform: scale(105%);
}
.button-primary:active {
  animation: clickAnimation 0.3s;
}

.button-secondary:hover {
  box-shadow: 0 0 10px 7px rgba(0, 0, 0, 0.4), 0 0 8px 8px rgba(0, 0, 0, 0.35);
  transform: scale(105%);}

.button-secondary:active {
  animation: clickAnimation 0.3s;
}
.click-animation:active{
  animation: clickAnimation 0.3s;
}

@keyframes clickAnimation {
  0% {
    transform: scale(95%);
  }
  100% {
    transform: scale(105%);
  }
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

</style>
