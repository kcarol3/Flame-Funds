<template>
  <div class="container">
    <div style="text-align: center;">
      <img src="../assets/logo.png" style="max-height: 60%; max-width: 60%;" class="mb-3">
    </div>
    <div class="container mb-5">
      <h2 class="lilita-one" style="font-size: 46px;color: #7d3fdb">
        Flame Funds
      </h2>
    </div>
    <div style="text-align: center;">
      <router-link to="/login" class="button-primary mb-3"><i class="bi bi-person icon"/>Zaloguj</router-link>
    </div>
    <div style="text-align: center;">
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