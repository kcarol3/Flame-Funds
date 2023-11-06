<template>
  <div>
    <h1 :style="{'font-size': fontSize}" class="p-3 mx-auto w-75 shadow lilita-one rounded container-sm mt-2">
      <div v-if="twoLines" >
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
  name: "IconHeader",

  props: {
    title: String,
    icon: String,
    twoLines:{
      type: Boolean,
      default: false
    },
    fontSize: {
      type: String,
      default: "42px"
    }
  }
}
</script>

<style scoped>
h1 {
  color: #9646e3;
  background-color: rgba(255, 255, 255, 0.8);
}
</style>