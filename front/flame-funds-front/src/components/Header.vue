<template>
  <h1 class = "p-3 mx-auto w-75 shadow lilita-one rounded container-sm" style="color: #9646e3;font-size: 48px">
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

</style>