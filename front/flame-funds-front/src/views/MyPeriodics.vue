<template>
  <div class="mx-auto justify-content-center">
    <icon-header title="Moje płatności cykliczne" icon="bi bi-repeat" font-size="44px"></icon-header>
    <div v-if = "this.periodics !== []" class="mx-auto" style="max-width: 600px">
      <table-periodics :periodics= periodics></table-periodics>
    </div>
    <div v-else>
      <h4>Brak płatności cyklicznych</h4>
    </div>
  </div>
</template>

<script>
import IconHeader from "@/components/IconHeader.vue";

import TablePeriodics from "@/components/PeriodicComponents/tablePeriodics.vue";
import axios from "axios";

export default {
  name: "PeriodicsView",
  components: {TablePeriodics, IconHeader},
  data(){
    return{
      periodics: [],
    }
  },

  created() {
    this.getAllperiodics()
  },

  methods:{
    getAllperiodics(){
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.get("http://localhost:8741/api/periodic/all-periodics",config)
          .then(response => {
            console.log(response)
            this.periodics = response.data
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