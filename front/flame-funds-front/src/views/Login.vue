<template>
  <div>
  <img src="../assets/logo.png" style="width: 24%; height: 24%" class="mb-4 container">
  <header-component title = 'Logowanie' class="mb-4"></header-component>
  <div class="container" style="width: 300px">
  <div class="form-group mb-2">
    <label for="email">Adres email</label>
    <input type="email" class="form-control" id="email" placeholder="Wprowadź email">
  </div>
    <div class="form-group ">
      <label for="password">Hasło</label>
      <input type="password" class="form-control" id="password" placeholder="Wprowadź hasło">
    </div>
    <button class="button-primary mt-5 mb-4"><i class = "bi bi-person icon"/>Zaloguj</button>
  </div>
  <return-button link="/start" class="m-auto"></return-button>
  </div>
</template>

<script>
import HeaderComponent from "@/components/Header.vue";
import ReturnButton from "@/components/ReturnButton.vue";

export default {
  name: "LoginView",
  components: {ReturnButton, HeaderComponent}
}
</script>

<style scoped>

</style>