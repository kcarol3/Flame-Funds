<template>
  <h1 class = "p-3 mx-auto w-75 shadow lilita-one rounded container-sm">
    {{title}}
  </h1>
</template>

<script>
export default {
  name: "HeaderComponent",

  props: {
    title: String,
  }
}
</script>

<style scoped>
h1{
  color: #9646e3;
  font-size: 48px;
  background-color: rgba(255,255,255,0.8);
}
</style>