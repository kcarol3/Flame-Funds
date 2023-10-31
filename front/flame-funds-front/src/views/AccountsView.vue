<template>
<div class="mx-auto justify-content-center">
  <icon-header title="Konta" icon="bi bi-wallet2" font-size="44px"></icon-header>
  <div v-if = "this.accounts !== []" class="mx-auto mt-2" style="max-width: 600px; max-height: 50vh">
    <table-accounts :accounts= accounts></table-accounts>
  </div>
  <div v-else>
    <h4>Brak kont</h4>
  </div>
  <return-button link="/home" class="m-auto mt-5"></return-button>
</div>
</template>

<script>
import IconHeader from "@/components/IconHeader.vue";

import TableAccounts from "@/components/AccountComponents/tableAccounts.vue";
import axios from "axios";
import ReturnButton from "@/components/ReturnButton.vue";

export default {
  name: "AccountsView",
  components: {ReturnButton,TableAccounts, IconHeader},
  data(){
    return{
      accounts: [],
    }
  },

  created() {
    this.getAccounts()
  },

  methods:{
    getAccounts(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.get("http://localhost:8741/api/account",config)
          .then(response => {
            console.log(response)
            this.accounts = response.data
          })
          .catch(error => {
            console.log(error)
          })
    }
  },
}

</script>
<style scoped>

</style>