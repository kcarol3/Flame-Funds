<template>
  <div class="mx-auto justify-content-center">
    <icon-header title="Moje cele finansowe" icon="bi bi-piggy-bank-fill" font-size="44px"></icon-header>
    <div v-if = "this.financialGoals !== []" class="mx-auto" style="max-width: 600px">
      <table-financialGoals :financialGoals= financialGoals></table-financialGoals>
    </div>
    <div v-else>
      <h4>Brak celów finansowych</h4>
    </div>
  </div>
</template>

<script>
import IconHeader from "@/components/IconHeader.vue";

import TableFinancialGoals from "@/components/FinancialGoalComponents/tableFinancialGoals.vue";
import axios from "axios";

export default {
  name: "FinancialGoals",
  components: {TableFinancialGoals,IconHeader},
  data(){
    return{
      financialGoals: [],
    }
  },

  created() {
    this.getAllFinancialGoals()
  },

  methods:{
    getAllFinancialGoals(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.get("http://localhost:8741/api/financialGoal/all-financialGoals",config)
          .then(response => {
            console.log(response)
            this.financialGoals = response.data
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