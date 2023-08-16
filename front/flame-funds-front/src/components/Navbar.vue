<template>
  <div class="container-fluid d-flex align-items-center w-100" style="background: #d9c5ed; height: 70px;position: fixed; top: 0;">
    <i class="bi bi-list mx-auto mt-2 click-animation" style="font-size: 60px"/>
    <div style = "width: 70%;">
      <router-link to="/home">
        <img  src="../assets/logo.png" class="mx-auto mt-2 button-primary" style="padding: 0;width: 64px;height: 64px; border-radius: 200px">
      </router-link>
    </div>
    <i class="bi bi-person-fill-gear mx-auto mt-2 click-animation" style="font-size: 60px;padding: 0;"/>
  </div>
  <div style="background: linear-gradient(to bottom, #d9c5ed, rgba(255,255,255, 0%)); width: 100%; height: 30px; margin-top: 70px;"></div>
</template>

<script>
export default {
  name: "NavbarComponent"
}
</script>

<style scoped>

</style>