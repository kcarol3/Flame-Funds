<template>
  <router-link :to="link" class = "btn rb tap-buttons border border-black">
    <i class="bi bi-arrow-return-left"/>
  </router-link>
</template>

<script>
export default {
  name: "ReturnButton",

  props:{
    link: String
  }
}
</script>

<style scoped>
.tap-buttons {
  background: radial-gradient(circle, rgb(255, 255, 255), rgba(219, 183, 255, 0.8));
  width: 80px
}
i {
  font-size: 32px;
}
.rb{
  box-shadow: 0 0 14px 10px  rgba(255, 255, 255, 0.8);
}

.rb:hover {
  animation: clickAnimation 0.3s;
  box-shadow: 0 0 20px 15px rgb(255, 255, 255);
  transform: scale(105%);
}
.rb:active {
  animation: clickAnimation 0.3s;
}


@keyframes clickAnimation {
  0% {
    transform: scale(90%);
  }
  100% {
    transform: scale(110%);
  }
}
</style>