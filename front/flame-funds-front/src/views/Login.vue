<template>
  <div>
    <img src="../assets/logo.png" class="mb-4 container">
    <header-component title='Logowanie' class="mb-4"></header-component>
    <div class="container" style="width: 300px">
      <div class="form-group mb-2">
        <label for="email">E-mail</label>
        <input v-model="email" class="form-control" id="username" placeholder="Wprowadź e-mail"></div>
      <div class="form-group ">
        <label for="password">Hasło</label>
        <input type="password" v-model="password" class="form-control" id="password" placeholder="Wprowadź hasło"/>
      </div>
      <button @click="loginCheck" class="button-primary mt-5 mb-4 "><i class="bi bi-person icon"/>Zaloguj</button>
    </div>
    <return-button link="/start" class="m-auto mb-3"></return-button>
    <div>
      <progress-spinner v-if="isLoading" strokeWidth="6"/>
    </div>
  </div>
</template>

<script>
import HeaderComponent from "@/components/Header.vue";
import ReturnButton from "@/components/ReturnButton.vue";
import axios from 'axios';
import {createToast} from "mosha-vue-toastify";

export default {
  name: "LoginView",
  components: {ReturnButton, HeaderComponent,},

  data(){
    return{
      email: "",
      password: "",
      isLoading: false,
    }
  },

  methods: {
    loginCheck(){
      this.isLoading = true;
      axios.post("http://localhost:8741/api/login_check", {"email":this.email, "password":this.password})
          .then((response) => {
            this.isLoading = false;
            sessionStorage.setItem("token", response.data.token)
            this.$router.push("/home")
          })
          .catch((error)=>{
            this.isLoading = false;
            createToast({
                  title: 'Nie udało się zalogować',
                  description: 'Złe dane logowania'
                },
                {
                  showIcon: 'true',
                  position: 'top-center',
                  type: 'danger',
                  transition: 'zoom',
                })
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
  max-height: 256px;
  max-width: 256px;
}
</style>