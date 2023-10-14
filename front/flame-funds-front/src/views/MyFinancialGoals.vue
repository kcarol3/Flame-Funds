<template>
  <div>
    <icon-header title="Moje cele finansowe" icon="bi bi-piggy-bank-fill" class="mt-3"></icon-header>
    <div class="jump">
      <i class="bi bi-arrow-up-short icon " style="font-size:34px; color: rebeccapurple"></i>
    </div>
    <ScrollPanel class="mx-auto sh" style="width: 98%; height: 60vh">
      <div v-for="(transactions, date) in transactions" :key="date">
        <Panel :header=date>
          <div v-for="(transaction, index) in transactions" :key="transaction.name">
            <div class="lilita-one" style="font-size: 24px; color: rebeccapurple">
              {{ transaction.name }}
            </div>
            <div class="d-flex mx-auto mt-1">
              <div class="w-75" style="text-align: left; font-size: 17px; color: grey">
                {{transaction.details}}
              </div>
              <div class="numeric" :class=transaction.type>
                <div v-if="transaction.type == 'financialGoal'">-{{ transaction.currentAmount }}</div>
                <div v-else>{{ transaction.currentAmount }}</div>
              </div>
            </div>
            <Divider v-if="index < transactions.length - 1"/>
          </div>
        </Panel>
      </div>
    </ScrollPanel>
    <div class="jump">
      <i class="bi bi-arrow-down-short icon " style="font-size:34px; color: rebeccapurple"></i>
    </div>  </div>
</template>
<script>
import IconHeader from "@/components/IconHeader.vue";
import axios from "axios";

export default {
  name: "MyFinancialGoalsView",
  components: {IconHeader},

  data() {
    return {
      transactions: [],
    }
  },
  created() {
    this.getTransactions()
  },

  methods: {
    getTransactions() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.get("http://localhost:8741/api/myfinancialgoals", config)
          .then(response => {
            console.log(response)
            this.transactions = response.data
          })
          .catch(error => {
            console.log(error)
          })
    }
  },

}
</script>
<style scoped>
.sh{
  box-shadow: 0 0 40px 40px white;
}

.financialGoal {
  color: #9646e3;
  /* Dodaj inne style dla typu 'financialGoal' */
}

.numeric{
  font-size: 18px;
  text-align: right;

}
</style>