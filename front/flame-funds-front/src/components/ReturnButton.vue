<template>
  <router-link :to="link" class = "btn rb" style = "background-color: #bc8ce6; width: 100px">
    <img src="../assets/arrow-return-left.svg">
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
.rb{
  box-shadow: 0 7px 14px rgba(0, 0, 0, 0.2), 0 6px 6px rgba(0, 0, 0, 0.25);
}

.rb:hover {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 6px 6px rgba(0, 0, 0, 0.25);
}
.rb:active {
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
</style>