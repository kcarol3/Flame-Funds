<template>
  <div>
    <img src="../assets/logo.png" style="width: 24%; height: 24%" class="mb-4 container">
    <header-component title='Logowanie' class="mb-4"></header-component>
    <div class="container" style="width: 300px">
      <div class="form-group mb-2">
        <label for="email">Nazwa użytkownika</label>
        <input v-model="username" class="form-control" id="username" placeholder="Wprowadź nazwę użytkownika"></div>
      <div class="form-group ">
        <label for="password">Hasło</label>
        <input type="password" v-model="password" class="form-control" id="password" placeholder="Wprowadź hasło"/>
      </div>
      <button @click="loginCheck" class="button-primary mt-5 mb-4"><i class="bi bi-person icon"/>Zaloguj</button>
    </div>
    <return-button link="/start" class="m-auto"></return-button>
  </div>
</template>

<script>
import HeaderComponent from "@/components/Header.vue";
import ReturnButton from "@/components/ReturnButton.vue";
import axios from 'axios';

export default {
  name: "LoginView",
  components: {ReturnButton, HeaderComponent},

  data(){
    return{
      username: "",
      password: ""
    }
  },

  methods: {
    loginCheck(){
      axios.post("http://localhost:8741/api/login_check", {"username":this.username, "password":this.password})
          .then((response) => {
            localStorage.setItem("token", response.data.token)
            this.$router.push("/home")
          })
          .catch((error)=>{
            console.log(error)
          })
    }
  }
}
</script>

<style scoped>

</style>