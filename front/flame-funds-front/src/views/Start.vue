<template>
  <div class="container mb-3">
    <div style="text-align: center;">
      <img src="../assets/logo.png" style="height: 60%; width: 60%; max-width: 256px; max-height: 256px" class="mb-3">
    </div>
    <div class="container mb-5">
      <h2 class="lilita-one" style="font-size: 46px;color: #7d3fdb">
        Flame Funds
      </h2>
    </div>
    <div>
      <router-link to="/login" class="button-primary mb-4"><i class="bi bi-person icon"/>Zaloguj</router-link>
    </div>
    <div>
      <router-link to="/register" class="button-primary"><i class="bi bi-person-add me-3 icon"/>Zarejestruj</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "StartView"
}
</script>

<style scoped>

</style>