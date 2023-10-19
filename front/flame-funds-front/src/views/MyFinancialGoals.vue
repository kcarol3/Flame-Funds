<!--<template>-->
<!--  <div class="mx-auto justify-content-center">-->
<!--    <icon-header title="Moje cele finansowe" icon="bi bi-piggy-bank-fill" font-size="44px"></icon-header>-->
<!--    <div v-if = "this.financialGoals !== []" class="mx-auto" style="max-width: 600px">-->
<!--      <table-financialGoals :financialGoals= financialGoals></table-financialGoals>-->
<!--    </div>-->
<!--    <div v-else>-->
<!--      <h4>Brak celów finansowych</h4>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<script>-->
<!--import IconHeader from "@/components/IconHeader.vue";-->

<!--import TableFinancialGoals from "@/components/FinancialGoalComponents/tableFinancialGoals.vue";-->
<!--import axios from "axios";-->

<!--export default {-->
<!--  name: "FinancialGoals",-->
<!--  components: {TableFinancialGoals,IconHeader},-->
<!--  data(){-->
<!--    return{-->
<!--      financialGoals: [],-->
<!--    }-->
<!--  },-->

<!--  created() {-->
<!--    this.getAllFinancialGoals()-->
<!--  },-->

<!--  methods:{-->
<!--    getAllFinancialGoals(){-->
<!--      let token = sessionStorage.getItem("token");-->
<!--      const config = {-->
<!--        headers: {Authorization: `Bearer ${token}`}-->
<!--      };-->
<!--      axios.get("http://localhost:8741/api/financialGoal/all-financialGoals",config)-->
<!--          .then(response => {-->
<!--            console.log(response)-->
<!--            this.financialGoals = response.data-->
<!--          })-->
<!--          .catch(error => {-->
<!--            console.log(error)-->
<!--          })-->
<!--    }-->
<!--  },-->
<!--}-->

<!--</script>-->
<!--<style scoped>-->

<!--</style>-->


<template>
  <div>
    <icon-header title="Moje cele finansowe" icon="bi bi-hourglass-split" class="mt-3"></icon-header>
    <div class="jump">
      <i class="bi bi-arrow-up-short icon " style="font-size:34px; color: rebeccapurple"></i>
    </div>
    <ScrollPanel class="mx-auto sh" style="width: 98%; height: 60vh">
      <div v-for="(transactions, date, id) in transactions" :key="id">
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

              <div class="mx-auto my-auto">
              <button class="delete" style="border-radius: 200px" @click="deleteFinancialGoal(transaction.id)">
                <i class="bi bi-trash" />
              </button>
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
// import OneElement from "@/components/History/oneElement.vue";

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
    },

    deleteFinancialGoal(id) {
      const token = sessionStorage.getItem("token");
      const config = {
        headers: { Authorization: `Bearer ${token}` },
      };

      // Przechowaj referencję do danych transactions
      const transactions = this.transactions;

      // Przeszukaj wszystkie daty w transactions
      for (const date in transactions) {
        if (transactions.hasOwnProperty(date)) {
          // Znajdź tablicę transakcji dla danej daty
          const transactionsForDate = transactions[date];

          // Znajdź indeks transakcji o podanym ID w tablicy transakcji
          const indexToDelete = transactionsForDate.findIndex((transaction) => transaction.id === id);

          if (indexToDelete !== -1) {
            // Jeśli znaleziono indeks, usuń transakcję z tablicy transakcji
            transactionsForDate.splice(indexToDelete, 1);

            // Wyślij żądanie usunięcia na serwer itd.
            axios
                .delete(`http://localhost:8741/api/financialGoal/delete-financialGoal/${id}`, config)
                .then((response) => {
                  if (response.status === 200) {
                    console.log("Sukces: Cel finansowy został usunięty.");
                  } else {
                    console.error("Błąd: Usunięcie nie powiodło się.");
                  }
                })
                .catch((error) => {
                  console.error("Błąd podczas usuwania celu finansowego:", error);
                });
          }
        }
      }
    },
  },

}
</script>
<style scoped>
.sh{
  box-shadow: 0 0 40px 40px white;
}
.expense {
  color: #ff5a5a;
  /* Dodaj inne style dla typu 'expense' */
}

.periodic {
  color: #f19e9e;
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
.numeric{
  font-size: 18px;
  text-align: right;

}
</style>