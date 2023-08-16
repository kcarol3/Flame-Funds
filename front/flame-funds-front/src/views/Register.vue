<template>
  <div>
    <img src="../assets/logo.png"  class="mb-4 container">
    <header-component title = 'Rejestracja' class="mb-4"></header-component>
    <div class="container" style="width: 300px">
      <div class="form-group mb-2">
        <label for="email">Nazwa użytkownika</label>
        <input type="email" class="form-control" id="email" placeholder="Wprowadź nazwę użytkownika">
      </div>
      <div class="form-group mb-2">
        <label for="email">Adres email</label>
        <input type="email" class="form-control" id="email" placeholder="Wprowadź email">
      </div>
      <div class="form-group ">
        <label for="password">Hasło</label>
        <input type="password" class="form-control" id="password" placeholder="Wprowadź hasło">
      </div>
      <div class="form-group ">
        <label for="password"> Potwierdź hasło</label>
        <input type="password" class="form-control" id="password" placeholder="Wprowadź hasło">
      </div>
      <button class="button-primary mt-5 mb-4"><i class="bi bi-person-add me-3"/>Zarejestruj </button>
    </div>
    <return-button link="/start" class="m-auto mt-3 mb-3"></return-button>
  </div>
</template>

<script>
import ReturnButton from "@/components/ReturnButton.vue";
import HeaderComponent from "@/components/Header.vue";


export default {
  name: "RegisterView",
  components: { HeaderComponent, ReturnButton}
}
</script>

<style scoped>
img{
  width: 24%;
  height: 24%;
  max-width: 128px;
  max-height: 128px;
}
</style>