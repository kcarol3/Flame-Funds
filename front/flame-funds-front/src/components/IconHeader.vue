<template>
  <div>
    <h1 class="p-3 mx-auto w-75 shadow lilita-one rounded container-sm">
      <i :class="icon"/>
      <div :style="{'font-size': fontSize}">
        {{ title }}
      </div>
    </h1>
  </div>
</template>

<script>

export default {
  name: "IconHeader",

  props: {
    title: String,
    icon: String,
    fontSize: {
      type: String,
      default: "48px"
    }
  }
}
</script>

<style scoped>
h1 {
  color: #9646e3;
  font-size: 48px;
  background-color: rgba(255, 255, 255, 0.8);
}
</style>