<template>
  <div>
    <icon-header title="Dodaj płatność cykliczną" icon="bi bi-piggy-bank" class="mt-3"></icon-header>

    <div class="card flex justify-content-center">
      <Dialog v-model:visible="visible" modal header="Dodaj kategorię" :style="{ width: '350px' }">
          <span class="p-float-label mt-4 mb-4">
              <InputText id="catName" class="w-100" v-model="categoryName"/>
              <label for="catName">Nazwa kategorii</label>
            </span>
        <span class="p-float-label mt-4 mb-4">
              <textarea id="catDetails" class="w-100" v-model="categoryDetails" rows="3" cols="33"/>
              <label for="catDetails">Szczegóły</label>
          </span>
        <template #footer>
          <Button label="anuluj" icon="bi bi-times" @click="visible = false" text />
          <button type="button" class="button-primary" @click="addCategory" style="font-size: 18px">Zapisz</button>
        </template>
      </Dialog>
    </div>

    <div class="container" style="width: 300px">

      <div class="mb-4">
        <label for="name">Nazwa płatności cyklicznej:</label>
        <span class="p-float-label  mb-4">
          <InputText id="name" class="w-100" v-model="name"/>
        </span>
      </div>

      <div class="mb-4">
        <label for="amount">Kwota płatności:</label>
        <span class="p-float-label mb-4">
          <InputNumber id="amount" v-model="amount" inputId="stacked-buttons" showButtons mode="currency" currency="PLN" :min=0 :step="10" />
        </span>
      </div>   

      <div class="mb-4" style="display: flex; justify-content: space-between;">
        <div style="flex: 1">
          <label for="calendar-24hStart">Data początkowa płatności:</label>
          <Calendar class="w-100" inputId="dateStart" id="dateStart" v-model="dateStart" showTime hourFormat="24" showButtonBar touchUI/>
        </div>
        <div style="flex: 1; margin-left: 20px;">
          <label for="calendar-24hEnd">Data końcowa płatności:</label>
          <Calendar class="w-100" inputId="dateEnd" id="dateEnd" v-model="dateEnd" showTime hourFormat="24" showButtonBar touchUI/>
        </div>
      </div>

      <div class="mb-4">
        <label for="days">Płatność co ile dni:</label>
        <span class="p-float-label mb-4">
          <InputText type="number" v-model="days" id="days"/>
        </span>
      </div>

      <div class="p-float-label mb-4">
        <Dropdown v-model="category" inputId="dd" id="category" :options="categories" optionLabel="name" placeholder="Kategoria"
                  class="w-75"/>
        <Button @click="visible = true"><i class="bi bi-plus"/></Button>
        <label for="dd">Wybierz kategorię</label>
      </div>

      <div class="form-group">
        <label for="details">Opis (opcjonalny)</label>
        <textarea class="w-100" id="details" v-model="details" rows="2" cols="33"/>
      </div>

      <button @click="addPeriodic" class="button-primary mt-3 mb-4 "><i class="bi bi-cash-stack me-1"/>Dodaj</button>

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
  name: "PeriodicView",
  components: {
    ReturnButton,
    IconHeader,
  },
  data() {
    return {
      name: "",
      dateStart: "",
      dateEnd: "",
      amount: 0,
      days: 0,
      details: "",
      category: "",
      categories: null,
      categoryName: "",
      categoryDetails: "",
      visible: false,
    };
  },

  beforeMount() {
    this.getCategories()
  },

  methods: {
    getCategories() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.get("http://localhost:8741/api/category/get-expense",config)
          .then(response => {
            console.log(response)
            this.categories = response.data
          })
          .catch(error => {
            console.log(error)
          })
    },

    categoryValidation(){
      const nameValidator = new Validation(this.categoryName, "catName", "nazwa kategorii");
      const textValidator = new Validation(this.describe, "catDetails", "opis kategorii");

      nameValidator.required().specialChars().check();
      textValidator.specialChars().check();

      return nameValidator.isValid() && textValidator.isValid();
    },

    periodicValidation(){
      const nameValidator = new Validation(this.name, "name", "nazwa");
      const amountValidator = new Validation(this.amount, "amount", "kwota")
      const dateStartValidator = new Validation(this.dateStart, "dateStart", "data początek")
      const dateEndValidator = new Validation(this.dateEnd, "dateEnd", "data koniec")
      const daysValidator = new Validation(this.days, "days", "dni")
      const textValidator = new Validation(this.describe, "describe", "opis")
      const categoryValidator = new Validation(this.category, "category", "kategoria")

      nameValidator.required().specialChars().check();
      amountValidator.required().check();
      dateStartValidator.required().check();
      dateEndValidator.required().check();
      daysValidator.required().check();
      textValidator.specialChars().check();
      categoryValidator.required().check();

      return nameValidator.isValid() && amountValidator.isValid() && dateStartValidator.isValid() && dateEndValidator.isValid() && daysValidator.isValid() && textValidator.isValid() && categoryValidator.isValid();
    },

    addCategory() {
      if(this.categoryValidation()){
        let token = sessionStorage.getItem("token");
        const config = {
          headers: {Authorization: `Bearer ${token}`}
        };
        axios.post("http://localhost:8741/api/category/add-expense", {
          "name": this.categoryName,
          "details": this.categoryDetails,
        }, config)
            .then(response => {
              this.getCategories()
              this.visible = false
              createToast({
                    title: 'Dodano kategorię wydatku',
                    description: 'Możesz teraz wybrać nową kategorię przy dodawaniu wydatku.'
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
    },

    addPeriodic() {
      if (this.periodicValidation()){
        let token = sessionStorage.getItem("token");
        const config = {
          headers: {Authorization: `Bearer ${token}`}
        };
        this.dateStart = this.dateStart.toLocaleString("pl-PL", {timeZone: "Europe/Warsaw"})
        this.dateEnd = this.dateEnd.toLocaleString("pl-PL", {timeZone: "Europe/Warsaw"})
        axios.post("http://localhost:8741/api/periodic/add-periodic", {
          "name": this.name,
          "dateStart": this.dateStart,
          "dateEnd": this.dateEnd,
          "amount": this.amount,
          "days": this.days,
          "details": this.details,
          "category": this.category,
        }, config)
            .then(response => {
              createToast({
                    title: 'Dodano płatność cykliczną',
                    description: 'Płatność cykliczną sprawdzisz w historii.'
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