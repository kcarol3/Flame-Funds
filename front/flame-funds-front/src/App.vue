<template>
  <div>
  <router-view v-slot="{ Component }">
    <transition name="slide-fade" mode="out-in">
      <component :is="Component"></component>
    </transition>
  </router-view>
  </div>
</template>

<script>

export default {
  name: 'App',
  components: {
  },
}
</script>

<style>
#app {text-align:center}

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
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 6px 6px rgba(0, 0, 0, 0.25);
  background: radial-gradient(circle, rgba(177, 114, 234, 0.82), #9646e3);
  display: inline-block;
  font-size: 32px;
  padding: 3% 13%;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-family: 'Lilita One', sans-serif;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.button-primary:hover {
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4), 0 8px 8px rgba(0, 0, 0, 0.35);
}
.button-primary:active {
  animation: clickAnimation 0.3s;
}


@keyframes clickAnimation {
  0% {
    background-color: rgba(177, 114, 234, 0.8);
    transform: scale(95%);
  }
  100% {
    background: radial-gradient(circle, rgba(177, 114, 234, 0.82), #9646e3);
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
