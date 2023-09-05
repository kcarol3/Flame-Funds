<template>
<div>
  <div @click="this.$router.push(link)" class="button-secondary w-100 d-flex mx-auto mb-3" style="font-size: 26px">
    <div class="mx-auto">{{name}}</div>
    <i :class=icon style="font-size: 26px"></i>
  </div>
</div>
</template>
<script>
export default {
  name: "MenuElement",
  props:{
    name: String,
    icon: String,
    link: String,
  }
}
</script>
<style scoped>

</style>