<template>
  <div>
    <header-component title="Witaj!" class="mt-3"></header-component>
    <div @click="this.$router.push('/history')" class="mx-auto mt-4 py-3 lilita-one rounded border border-1 border-black shadow click-animation" style="width: 60%; background: #d9c5ed;color: gray;max-width: 400px">
      <div style="color:black; font-size: 26px">{{accountName}}</div>
      <div style="font-size: 18px">Saldo:</div>
      <div style="font-size: 24px; color: dimgrey">{{balance}}zł</div>
    </div>
    <div class="container-fluid d-flex align-items-center tap-buttons pt-4 pb-3 align-self-end">
      <div class="mx-auto ">
        <router-link to="/income" class="button-primary" style="padding: 0 13px;border-radius: 200px"><i class="bi bi-plus" style="font-size: 48px"/></router-link>
        <div class=" mt-1 text">Przychód</div>
      </div>
      <div class="mx-auto">
        <router-link to="/expense" class="button-secondary" style="padding: 0 13px;border-radius: 200px"><i class="bi bi-dash" style="font-size: 48px"/></router-link>
        <div class=" mt-1 text">Wydatek</div>
      </div>
    </div>
    <home-chart class="mt-1 chart sh"></home-chart>
  </div>
</template>

<script>
import HeaderComponent from "@/components/Header.vue";
import axios from 'axios';
import HomeChart from "@/components/HomeComponents/HomeChart.vue";


export default {
  name: "HomeView",
  components: {HomeChart, HeaderComponent},

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
.sh{
  box-shadow: 0 0 20px 20px rgba(255, 255, 255, 0.8);
  background-color: rgba(255, 255, 255, 0.8);
}

 .text{
   font-family: 'Open Sans', sans-serif;
   font-size: 26px
 }

 chart {
   background: white;
 }


</style>