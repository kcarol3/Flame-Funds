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
    <div>
      <progress-spinner v-if="isLoading" strokeWidth="6"/>
    </div>
  </div>
</template>

<script>
import ReturnButton from "@/components/ReturnButton.vue";
import HeaderComponent from "@/components/Header.vue";
import axios from "axios";
import { createToast } from 'mosha-vue-toastify';

import 'mosha-vue-toastify/dist/style.css'


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
      isLoading: false,
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
      this.isLoading = true;
      const usernameRegex = /^[a-zA-Z0-9_-]{3,16}$/;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const passwordRegex= /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;


      if (!usernameRegex.test(this.username)) {
        this.errors.username = true;
        createToast({
              title: 'Zła nazwa użytkownika!',
              description: 'Możesz użyć liter, cyfr, "_" i "-"'
            },
            {
              position: 'top-center',
              showIcon: 'true',
              type: 'danger',
              transition: 'bounce',
              showCloseButton: true,
              swipeClose: true,
            })
      }

      if (!emailRegex.test(this.email)) {
        this.errors.email = true;
        createToast({
              title: 'Zły email!',
              description: 'Musisz podać poprawny email.'
            },
            {
              position: 'top-center',
              showIcon: 'true',
              type: 'danger',
              transition: 'bounce',
              showCloseButton: true,
            })
      }

      if (!passwordRegex.test(this.password)) {
        this.errors.password= true;
        createToast({
              title: 'Złe hasło!',
              description: 'Ma mieć 8 znaków, cyfrę i znak specjalny.'
            },
            {
              position: 'top-center',
              showIcon: 'true',
              type: 'danger',
              transition: 'bounce',
              showCloseButton: true,
            })
      }

      if(!(this.password === this.confirmPassword)) {
        this.errors.confirmPassword = true
        createToast({
              title: 'Złe hasło!',
              description: 'Ma mieć 8 znaków, cyfrę i znak specjalny.'
            },
            {
              position: 'top-center',
              showIcon: 'true',
              type: 'danger',
              transition: 'zoo',
              showCloseButton: true,
            })

      }

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
            this.isLoading=false;
            console.log(response)
            createToast({
                  title: 'Udało Ci się zarejestrować.',
                  description: 'Możesz się teraz zalogować na twoje konto'
                },
                {
                  showIcon: 'true',
                  position: 'top-center',
                  type: 'success',
                  transition: 'zoom',
                })
            this.$router.push("/login")
          })
          .catch((error)=>{
            this.isLoading=false

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