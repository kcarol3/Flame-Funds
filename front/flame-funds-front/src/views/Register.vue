<template>
  <div>
    <img src="../assets/logo.png" class="mb-4 container">
    <header-component title='Rejestracja' class="mb-4"></header-component>
    <div class="container" style="width: 300px">
      <div class="form-group mb-2">
        <label for="username">Nazwa użytkownika</label>
        <input id="username" v-model="username" class="form-control" placeholder="Wprowadź nazwę użytkownika">
      </div>
      <div class="form-group mb-2">
        <label for="email">Adres email</label>
        <input type="email" v-model="email" class="form-control" id="email" placeholder="Wprowadź email">
      </div>
      <div class="form-group ">
        <label for="password">Hasło</label>
        <input type="password" v-model="password" class="form-control" id="password" placeholder="Wprowadź hasło">
      </div>
      <div class="form-group ">
        <label for="cpassword">Potwierdź hasło</label>
        <input type="password" v-model="confirmPassword" class="form-control" id="cpassword" placeholder="Potwierdź hasło">
      </div>
      <button @click="register" class="button-primary mt-5 mb-4"><i class="bi bi-person-add me-3"/>Zarejestruj
      </button>
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
import {createToast} from 'mosha-vue-toastify';

import 'mosha-vue-toastify/dist/style.css'
import Validation from "@/Validation";


export default {
  name: "RegisterView",
  components: {HeaderComponent, ReturnButton}
  ,

  data() {
    return {
      username: "",
      email: "",
      password: "",
      confirmPassword: "",
      isLoading: false,
    }
  },

  methods: {
    registerValidation() {
      const usernameValidator = new Validation(this.username, "username", "nazwa użytkownika")
      const emailValidator = new Validation(this.email, "email", "email");
      const passwordValidator = new Validation(this.password, "password", "hasło");
      const confirmPasswordValidator = new Validation(this.confirmPassword, "cpassword", "hasło")

      usernameValidator.required().specialChars().check();
      emailValidator.required().specialChars().isEmail().check();
      passwordValidator.required().isPassword().specialChars().check();
      confirmPasswordValidator.required().isPassword().specialChars().sameAs(this.password).check();

      return usernameValidator.isValid() && emailValidator.isValid() && passwordValidator.isValid() && confirmPasswordValidator.isValid();
    },

    register() {
      this.isLoading = true;
      if (this.registerValidation()) {
        axios.post("http://localhost:8741/api/register", {
          "username": this.username,
          "email": this.email,
          "password": this.password,
        })
            .then((response) => {
              this.isLoading = false;
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
            .catch((error) => {
              console.log(error)
            })
      }
      this.isLoading = false
    }
  }
}
</script>

<style scoped>
img {
  width: 24%;
  height: 24%;
  max-width: 128px;
  max-height: 128px;
}
</style>