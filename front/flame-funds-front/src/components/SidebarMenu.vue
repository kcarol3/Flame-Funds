<template>
  <div class="card flex justify-content-center mt-2 mx-auto">
    <Sidebar v-model:visible="visible">
      <template #header>
        <div class="container align-content-center justify-content-center">
          <h2>Menu</h2>
        </div>
      </template>
    </Sidebar>
    <i @click="visible = true" class="bi bi-list click-animation" style="font-size: 38px"/>
  </div>
</template>

<script setup>
import { ref } from "vue";

const visible = ref(false);
</script>
<style scoped>

</style>