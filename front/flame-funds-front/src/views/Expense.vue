<template>
  <div>
    <icon-header title="Wydatek" icon="bi bi-cash-coin" class="mt-3"></icon-header>
    <div class="card flex justify-content-center">
      <Dialog v-model:visible="visible" modal header="Dodaj kategorię" :style="{ width: '350px' }">
          <span class="p-float-label mt-4 mb-4">
              <InputText id="catName" class="w-100" v-model="categoryName"/>
              <label for="catName">Nazwa kategorii</label>
            </span>
        <span class="p-float-label mt-4 mb-4">
              <textarea inputId="catDetails" class="w-100" v-model="categoryDetails" rows="3" cols="33"/>
              <label for="catDetails">Szczegóły</label>
          </span>
        <template #footer>
          <Button label="anuluj" icon="bi bi-times" @click="visible = false" text />
          <button type="button" class="button-primary" @click="addCategory" style="font-size: 18px">Zapisz</button>
        </template>
      </Dialog>
    </div>
    <div class="container" style="width: 300px">
    <span class="p-float-label mt-4 mb-4">
      <InputText id="name" class="w-100" v-model="name"/>
      <label for="name">Nazwa wydatku</label>
    </span>
      <span class="p-float-label mb-4">
      <InputNumber id="amount" v-model="amount" inputId="stacked-buttons" showButtons mode="currency" currency="PLN" :min=0 />
    </span>
      <div class=" mb-4 ">
        <label for="calendar-24h">Wybierz datę:</label>
        <Calendar class="w-100" inputId="calendar-24h" id="calendar" v-model="date" showTime hourFormat="24" showButtonBar touchUI/>
      </div>
      <div class="p-float-label mb-4">
        <Dropdown v-model="category" inputId="dd" id="category" :options="categories" optionLabel="name" placeholder="Kategoria"
                  class="w-75"/>
        <Button @click="visible = true"><i class="bi bi-plus"/></Button>
        <label for="dd">Wybierz kategorię</label>
      </div>
      <div class="form-group">
        <label for="describe">Opis (opcjonalny)</label>
        <textarea class="w-100" id="describe" v-model="describe" rows="2" cols="33"/>
      </div>
      <button @click="addExpense" class="button-primary mt-3 mb-4 "><i class="bi bi-cash-coin me-1"/>Dodaj</button>
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
  name: "ExpenseView",
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
      axios.get("http://localhost:8741/category/get-expense")
          .then(response => {
            console.log(response)
            this.categories = response.data
          })
          .catch(error => {
            console.log(error)
          })
    },
    expenseValidation(){
      const nameValidator = new Validation(this.name, "name", "nazwa");
      const amountValidator = new Validation(this.amount, "amount", "kwota")
      const dateValidator = new Validation(this.date, "calendar", "kalendarz")
      const textValidator = new Validation(this.describe, "describe", "opis")
      const categoryValidator = new Validation(this.category, "category", "kategoria")

      nameValidator.required().specialChars().check();
      amountValidator.required().check();
      dateValidator.required().check();
      textValidator.specialChars().check();
      categoryValidator.required().check();

      return nameValidator.isValid() && amountValidator.isValid() && dateValidator.isValid() && textValidator.isValid() && categoryValidator.isValid();
    },

    addCategory() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.post("http://localhost:8741/category/add-expense", {
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
    },

    addExpense() {
      if (this.expenseValidation()){
        let token = sessionStorage.getItem("token");
        const config = {
          headers: {Authorization: `Bearer ${token}`}
        };
        axios.post("http://localhost:8741/expense/add-expense", {
          "name": this.name,
          "date": this.date,
          "amount": this.amount,
          "describe": this.describe,
          "category": this.category,
        }, config)
            .then(response => {
              createToast({
                    title: 'Dodano wydatek',
                    description: 'Wydatki sprawdzisz w historii wydatków.'
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