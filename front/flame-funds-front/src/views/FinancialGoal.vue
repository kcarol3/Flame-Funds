<template>
  <div>
    <icon-header title="Cel finansowy" icon="bi bi-piggy-bank" class="mt-3"></icon-header>
    <div class="container" style="width: 300px">

      <div class="mb-4">
        <label for="name">Nazwa celu finansowego:</label>
        <span class="p-float-label  mb-4">
          <InputText id="name" class="w-100" v-model="name"/>
        </span>
      </div>

      <div class="mb-4">
        <label for="currentAmount">Kwota początkowa:</label>
        <span class="p-float-label mb-4">
          <InputNumber id="currentAmount" v-model="currentAmount" inputId="stacked-buttons" showButtons mode="currency" currency="PLN" :min=0 :step="100" />
        </span>
      </div>

      <div class="mb-4">
        <label for="goalAmount">Kwota końcowa:</label>
        <span class="p-float-label mb-4">
          <InputNumber id="goalAmount" v-model="goalAmount" inputId="stacked-buttons" showButtons mode="currency" currency="PLN" :min=0 :step="100" />
        </span>
      </div>

      <div class=" mb-4 ">
        <label for="calendar-24hStart">Data początkowa:</label>
        <Calendar class="w-100" inputId="dateStart" id="dateStart" v-model="dateStart" showTime hourFormat="24" showButtonBar touchUI/>
      </div>

      <div class=" mb-4 ">
        <label for="calendar-24hEnd">Data końcowa:</label>
        <Calendar class="w-100" inputId="dateEnd" id="dateEnd" v-model="dateEnd" showTime hourFormat="24" showButtonBar touchUI/>
      </div>


      <div class=" mb-4">
        <label for="account">Konto bankowe:</label>
        <Dropdown v-model="account" inputId="account" id="account" :options="account" optionLabel="name" placeholder="Konto bankowe" class="w-75"/>
      </div>

      <div class="form-group">
        <label for="details">Opis (opcjonalny)</label>
        <textarea class="w-100" id="details" v-model="details" rows="2" cols="33"/>
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
      dateStart: "",
      dateEnd: "",
      currentAmount: 0,
      goalAmount: 0,
      details: "",
      visible: false,
    };
  },


  methods: {

    financialGoalValidation(){
      const nameValidator = new Validation(this.name, "name", "nazwa");
      const amountValidator = new Validation(this.currentAmount, "currentAmount", "kwotaPoczątkowa")
      const amountValidator2 = new Validation(this.goalAmount, "goalAmount", "kwotaKońcowa")
      const textValidator = new Validation(this.details, "details", "opis")
      const dateStartValidator = new Validation(this.dateStart, "dateStart", "dataPoczątkowa")
      const dateEndValidator = new Validation(this.dateEnd, "dateEnd", "dataKońcowa")


      nameValidator.required().specialChars().check();
      amountValidator.required().check();
      amountValidator2.required().check();
      textValidator.specialChars().check();
      dateStartValidator.required().check();
      dateEndValidator.required().check();

      return nameValidator.isValid() && amountValidator.isValid() && amountValidator2.isValid() && dateStartValidator.isValid() && dateEndValidator.isValid() && textValidator.isValid();
    },

    addFinancialGoal() {
      if (this.financialGoalValidation()){
        let token = sessionStorage.getItem("token");
        const config = {
          headers: {Authorization: `Bearer ${token}`}
        };
        this.date = this.date.toLocaleString("pl-PL", {timeZone: "Europe/Warsaw"})
        axios.post("http://localhost:8741/api/income/add-income", {
          "name": this.name,
          "dateStart": this.dateStart,
          "dateEnd": this.dateEnd,
          "currentAmount": this.currentAmount,
          "goalAmount": this.goalAmount,
          "details": this.details,
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