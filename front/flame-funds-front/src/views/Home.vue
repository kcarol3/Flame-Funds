<template>
  <div>
    <header-component title="Witaj!" class="mt-3"></header-component>
    <div class="mx-auto mt-4 py-3 lilita-one rounded border border-1 border-black shadow click-animation" style="width: 60%; background: #d9c5ed;color: gray;max-width: 400px">
      <h3>Saldo</h3>
      <h4>{{balance}}zł</h4>
    </div>
    <div class="container-fluid d-flex align-items-center tap-buttons pt-4 pb-3 align-self-end">
      <div class="mx-auto ">
        <router-link to="/" class="button-primary" style="padding: 0 13px;border-radius: 200px"><i class="bi bi-plus" style="font-size: 48px"/></router-link>
        <div class=" mt-1 text">Przychód</div>
      </div>
      <div class="mx-auto">
        <router-link to="/expense" class="button-secondary" style="padding: 0 13px;border-radius: 200px"><i class="bi bi-dash" style="font-size: 48px"/></router-link>
        <div class=" mt-1 text">Wydatek</div>
      </div>
    </div>
  </div>
</template>

<script>
import HeaderComponent from "@/components/Header.vue";
import axios from 'axios';

export default {
  name: "HomeView",
  components: {HeaderComponent},

  data(){
    return {
      balance: 0.0,
      accountName: "",
    }
  },

  beforeMount() {
    this.getBalance()
  },
  methods:{
    getBalance(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: { Authorization: `Bearer ${token}` }
      };
      axios.get("http://localhost:8741/api/account/current-account",config)
          .then(response=>{
            console.log(response)
            this.balance = response.data.balance
            this.accountName = response.data.name
          })
          .catch(error =>{
            console.log(error)
          })
    }
  },
}
</script>

<style scoped>
 .text{
   font-family: 'Open Sans', sans-serif;
   font-size: 26px
 }


</style>