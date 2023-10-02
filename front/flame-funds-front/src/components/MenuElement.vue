<template>
<div >
  <Divider/>
  <div @click="this.$router.push(link)" class="w-100 d-flex mx-auto" style="font-size: 22px">
    <div class="mx-auto">{{name}}</div>
    <i :class=icon style="font-size: 22px"></i>
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