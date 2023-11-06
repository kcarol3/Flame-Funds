<template>
  <div>
    <h1 class="p-3 mx-auto w-75 shadow lilita-one rounded container-sm mt-2">
      <div v-if="twoLines" :style="{'font-size': fontSize}">
        <i :class="icon"/>
        <div>
          {{ title }}
        </div>
      </div>
      <div v-else>
        <i :class="icon"/>
        {{ title }}
      </div>
    </h1>
  </div>
</template>

<script>

export default {
  name: "SmallIconHeader",

  props: {
    title: String,
    icon: String,
    twoLines:{
      type: Boolean,
      default: false
    },
    fontSize: {
      type: String,
      default: "20px"
    }
  }
}
</script>

<style scoped>
h1 {
  color: #9646e3;
  font-size: 17px;
  background-color: rgba(255, 255, 255, 0.8);
}
</style>