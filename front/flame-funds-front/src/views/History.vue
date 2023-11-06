<template>
  <div>
    <icon-header title="Historia" icon="bi bi-hourglass-split" class="mt-3"></icon-header>
    <div class="jump">
      <i class="bi bi-arrow-up-short icon " style="font-size:34px; color: rebeccapurple"></i>
    </div>
    <ScrollPanel class="mx-auto sh">
      <div v-for="(transactions, date) in transactions" :key="date">
        <Panel :header=date>
          <div v-for="(transaction, index) in transactions" :key="transaction.name">
            <div @click="showDetails(transaction.type, transaction.id)">
              <div class="lilita-one" style="font-size: 24px; color: rebeccapurple">
                {{ transaction.name }}
              </div>
              <div>
                <div class="d-flex mx-auto mt-1">
                  <div class="w-75" style="text-align: left; font-size: 17px; color: grey">
                    {{ transaction.details }}
                  </div>
                  <div class="numeric" :class=transaction.type>
                    <div v-if="transaction.type == 'expense'">-{{ transaction.amount }}</div>
                    <div v-else>{{ transaction.amount }}</div>
                  </div>
              <div class="numeric" :class=transaction.type>
                <div v-if="transaction.type == 'financialGoal'">-{{ transaction.currentAmount }}</div>
                <div v-else>{{ transaction.currentAmount }}</div>

              </div>

                </div>
              </div>
              <Divider v-if="index < transactions.length - 1"/>
            </div>
          </div>
        </Panel>
      </div>
    </ScrollPanel>
    <div class="jump">
      <i class="bi bi-arrow-down-short icon " style="font-size:34px; color: rebeccapurple"></i>
    </div>
    <return-button link="/home" class="m-auto  mt-4"></return-button>
  </div>
</template>

<script>
import IconHeader from "@/components/IconHeader.vue";
import axios from "axios";
import ReturnButton from "@/components/ReturnButton.vue";
// import OneElement from "@/components/History/oneElement.vue";

export default {
  name: "HistoryView",
  components: {ReturnButton, IconHeader},

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
      axios.get("http://localhost:8741/api/history", config)
          .then(response => {
            console.log(response)
            this.transactions = response.data
          })
          .catch(error => {
            console.log(error)
          })
    },

    showDetails(type, id){
      this.$router.push(`/history/${type}/${id}`);
    },
  },

}
</script>
<style scoped>
.sh {
  box-shadow: 0 0 40px 40px white;
  height: 60vh;
  max-width: 800px;
  background-color: white;
}

@media screen and (max-width: 424px) {
  .sh {
    width: 98%;
    height: 50vh;
  }
}

.expense {
  color: #ff0000;
  /* Dodaj inne style dla typu 'expense' */
}

.periodicDetail {
  color: #0000ff;
  /* Dodaj inne style dla typu 'periodic' */
}

.financialGoal {
  color: #9646e3;
  /* Dodaj inne style dla typu 'financialGoal' */
}

.income {
  color: #05c900;
  /* Dodaj inne style dla typu 'income' */
}

.numeric {
  font-size: 18px;
  text-align: right;

}
</style>