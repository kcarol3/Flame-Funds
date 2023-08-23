<template>
  <div>
    <icon-header title="Wydatek" icon="bi bi-cash-coin" class="mt-3"></icon-header>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Dodaj Kategorię</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <span class="p-float-label mt-4 mb-4">
              <InputText id="catName" class="w-100" v-model="categoryName"/>
              <label for="catName">Nazwa kategorii</label>
            </span>
            <span class="p-float-label mt-4 mb-4">
              <Textarea inputId="catDetails" class="w-100" v-model="categoryDetails" rows="3" cols="33"/>
              <label for="catDetails">Szczegóły</label>
          </span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
            <button type="button" class="button-primary" style="font-size: 18px">Zapisz</button>
          </div>
        </div>
      </div>
    </div>
    <div class="container" style="width: 300px">
    <span class="p-float-label mt-4 mb-4">
      <InputText id="name" class="w-100" v-model="name"/>
      <label for="name">Nazwa wydatku</label>
    </span>
      <span class="p-float-label mb-4">
      <InputNumber v-model="amount" inputId="stacked-buttons" showButtons mode="currency" currency="PLN" min="0"/>
    </span>
      <div class=" mb-4 ">
        <label for="calendar-24h">Wybierz datę:</label>
        <Calendar class="w-100" inputId="calendar-24h" v-model="date" showTime hourFormat="24" showButtonBar touchUI/>
      </div>
      <div class="p-float-label mb-4">
        <Dropdown v-model="category" inputId="dd" :options="categories" optionLabel="name" placeholder="Kategoria"
                  class="w-75"/>
        <Button data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-plus"/></Button>
        <label for="dd">Wybierz kategorię</label>
      </div>
      <div class="form-group">
        <label for="describe">Opis (opcjonalny)</label>
        <textarea class="w-100" id="describe" v-model="describe" rows="3" cols="33"/>
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
      categoryDetails: ""
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

    addExpense() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.post("http://localhost:8741/expense/add-expense", {
        "name": this.name,
        "date": this.date,
        "amount": this.amount,
        "describe": this.describe,
        "category": this.category.name,
      }, config)
          .then(response => {
            console.log(response)
          })
          .catch(error => {
            console.log(error)
          })
    }
  },
};
</script>

<style scoped>

</style>