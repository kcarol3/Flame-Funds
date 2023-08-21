<template>
  <div>
    <img src="../assets/logo.png"  class="mb-4 container">
    <header-component title = 'Rejestracja' class="mb-4"></header-component>
    <div class="container" style="width: 300px">
      <div class="form-group mb-2">
        <label for="username">Nazwa użytkownika</label>
        <input id="username" v-model="username" class="form-control" :class="{ 'error': errors.username === true }" placeholder="Wprowadź nazwę użytkownika">
      </div>
      <div class="form-group mb-2">
        <label for="email">Adres email</label>
        <input type="email" v-model="email" class="form-control" id="email" :class="{ 'error': errors.email === true }" placeholder="Wprowadź email">
      </div>
      <div class="form-group ">
        <label for="password">Hasło</label>
        <input type="password" v-model="password" class="form-control" id="password" :class="{ 'error': errors.password === true }" placeholder="Wprowadź hasło">
      </div>
      <div class="form-group ">
        <label for="cpassword">Potwierdź hasło</label>
        <input type="password" v-model="confirmPassword" class="form-control" id="cpassword" :class="{ 'error': errors.confirmPassword === true }" placeholder="Potwierdź hasło">
      </div>
      <button @click="validateForm" class="button-primary mt-5 mb-4"><i class="bi bi-person-add me-3"/>Zarejestruj</button>
    </div>
    <return-button link="/start" class="m-auto mt-3 mb-3"></return-button>
  </div>
</template>

<script>
import ReturnButton from "@/components/ReturnButton.vue";
import HeaderComponent from "@/components/Header.vue";
import axios from "axios";

export default {
  name: "RegisterView",
  components: { HeaderComponent, ReturnButton}
,

  data(){
    return{
      username: "",
      email: "",
      password: "",
      confirmPassword: "",
      errors: {
        username: false,
        email: false,
        password: false,
        confirmPassword: false,
      }
    }
  },

  methods: {
    validateForm() {
      this.errors = {}; // Wyczyść poprzednie błędy
      const usernameRegex = /^[a-zA-Z0-9_-]{3,16}$/;
      //const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


      if (usernameRegex.test(this.username)) {
        this.errors.username = true;
        this.$moshaToast("Zła nazwa użytkownika!", "error")
      }

      // if (!emailRegex.test(this.email)) {
      //   this.errors.email = true;
      //   this.$moshaToast("Zły email!", "error")
      // }
      //
      // if(!this.password === this.confirmPassword){
      //   this.errors.confirmPassword = true
      //   this.$moshaToast("Hasła muszą być identyczne!", "error")
      // }

      if (Object.keys(this.errors).length === 0) {
        this.register()
      }
    },

     register(){
      axios.post("http://localhost:8741/api/register", {
        "username": this.username,
        "email":this.email,
        "password":this.password,
      })
          .then((response) => {
            console.log(response)
            this.$moshaToast('Pomyślnie zarejestrowano!', "success")
            this.$router.push("/login")
          })
          .catch((error)=>{
            this.$moshaToast('Nie udało się zarejestrować', "error")
            console.log(error)
          })
    }
  }
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