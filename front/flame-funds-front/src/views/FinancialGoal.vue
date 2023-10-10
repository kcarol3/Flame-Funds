<template>
  <div>
    <icon-header title="Cel finansowy" icon="bi bi-piggy-bank" class="mt-3"></icon-header>
    <div class="container" style="width: 300px">
    <span class="p-float-label mt-4 mb-4">
      <InputText id="name" class="w-100" v-model="name"/>
      <label for="name">Nazwa celu finansowego</label>
    </span>
      <span class="p-float-label mb-4">
      <InputNumber id="amount" v-model="amount" inputId="stacked-buttons" showButtons mode="currency" currency="PLN" :min=0 />
    </span>
      <div class=" mb-4 ">
        <label for="calendar-24h">Wybierz datę końcową:</label>
        <Calendar class="w-100" inputId="calendar-24h" id="calendar" v-model="date" showTime hourFormat="24" showButtonBar touchUI/>
      </div>
      <div class="form-group">
        <label for="describe">Opis (opcjonalny)</label>
        <textarea class="w-100" id="describe" v-model="describe" rows="2" cols="33"/>
      </div>
      <button @click="addFinancialGoal" class="button-primary mt-3 mb-4 "><i class="bi bi-cash-stack me-1"/>Dodaj</button>
    </div>
    <return-button link="/home" class="m-auto mb-3"></return-button>
  </div>
</template>

<script>
import IconHeader from "@/components/IconHeader.vue";
import ReturnButton from "@/components/ReturnButton.vue";
import axios from "axios";
import {createToast} from "mosha-vue-toastify";
import Validation from "@/Validation";

export default {
  name: "FinancialGoalView",
  components: {
    ReturnButton,
    IconHeader,
  },
  data() {
    return {
      name: "",
      date: "",
      amount: 0,
      describe: "",
      visible: false,
    };
  },


  methods: {

    financialGoalValidation(){
      const nameValidator = new Validation(this.name, "name", "nazwa");
      const amountValidator = new Validation(this.amount, "amount", "kwota")
      const dateValidator = new Validation(this.date, "calendar", "kalendarz")
      const textValidator = new Validation(this.describe, "describe", "opis")

      nameValidator.required().specialChars().check();
      amountValidator.required().check();
      dateValidator.required().check();
      textValidator.specialChars().check();

      return nameValidator.isValid() && amountValidator.isValid() && dateValidator.isValid() && textValidator.isValid();
    },

    addFinancialGoal() {
      if (this.financialGoalValidation()){
        let token = sessionStorage.getItem("token");
        const config = {
          headers: {Authorization: `Bearer ${token}`}
        };
        this.date = this.date.toLocaleString("pl-PL", {timeZone: "Europe/Warsaw"})
        axios.post("http://localhost:8741/income/add-income", {
          "name": this.name,
          "date": this.date,
          "amount": this.amount,
          "describe": this.describe,
        }, config)
            .then(response => {
              createToast({
                    title: 'Dodano cel finansowy',
                    description: 'Przychody sprawdzisz w historii.'
                  },
                  {
                    showIcon: 'true',
                    position: 'top-center',
                    type: 'success',
                    transition: 'zoom',
                  })
              console.log(response)
            })
            .catch(error => {
              console.log(error)
            })
      }

    }
  },
};
</script>

<style scoped>

</style>