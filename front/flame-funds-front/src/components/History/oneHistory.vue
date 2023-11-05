<template>
  <div style="text-align: center">
    <icon-header title="Szczegóły" icon="bi bi-hourglass-split" class="mt-3"></icon-header>
    <div class="head">
      <h1 style="font-size: 32px; min-height: 64px; flex: 3; margin-top: 5px"
          class="shadow lilita-one rounded container-sm p-3">
        {{ transaction.name }}
      </h1>
      <button label="Toggle" @click="toggle" aria-haspopup="true" aria-controls="overlay_menu"
              class="button-primary" style="flex: 1;max-width: 68px">
        <i class="bi bi-gear" style="font-size: 33px;"></i>
      </button>
      <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
      <Dialog v-model:visible="delModalVisible" modal header="Potwierdzenie" :style="{ width: '350px' }">
        <h2>
          Czy chcesz usunąć tą transakcję?
        </h2>
        <template #footer>
          <Button label="anuluj" icon="bi bi-times" @click="delModalVisible = false" text/>
          <button type="button" class="button-primary" @click="deleteCategory" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">
            Usuń
          </button>
        </template>
      </Dialog>
      <Dialog v-model:visible="changeModalVisible" modal header="Edytuj kategorie" :style="{ width: '350px' }">
      <span class="p-float-label mt-4 mb-4">
              <InputText id="name" class="w-100" v-model="newName"/>
              <label for="name">Nazwa</label>
      </span>
        <span class="p-float-label mt-4 mb-4">
              <InputText id="amount" class="w-100" v-model="newAmount"/>
              <label for="amount">Kwota</label>
      </span>
        <div class="p-float-label mb-4">
          <Dropdown v-model="newCategory" inputId="dd" id="category" :options="categories" optionLabel="name" placeholder="Kategoria"
                    class="w-100"/>
          <label for="dd">Wybierz kategorię</label>
        </div>
        <span class="p-float-label mt-4 mb-4">
              <InputText id="describe" class="w-100" v-model="newDetails"/>
              <label for="describe">Opis(opcjonalny)</label>
      </span>
        <template #footer>
          <Button label="anuluj" icon="bi bi-times" @click="this.changeModalVisible = false" text/>
          <button type="button" class="button-primary" @click="this.editTransaction" style="font-size: 18px; font-family: Lato, Helvetica, sans-serif">Zapisz</button>
        </template>
      </Dialog>
    </div>
    <div class="mt-5 wt">
      <h3 class="lilita-one">Kwota</h3>
      <h5>{{ transaction.amount }} zł</h5>
      <Divider/>
      <h3 class="lilita-one">Data</h3>
      <h5>{{ transaction.date }}</h5>
      <Divider/>
      <h3 class="lilita-one">Kategoria</h3>
      <h5>{{ transaction.category }}</h5>
      <Divider/>
      <h3 class="lilita-one">Opis</h3>
      <h5 v-if="transaction.details !== null">{{ transaction.details }}</h5>
      <h5 v-else>Brak opisu</h5>
    </div>
    <return-button link="/history" class="mx-auto mt-5"/>
  </div>
</template>
<script>
import IconHeader from "@/components/IconHeader.vue";
import axios from "axios";
import ReturnButton from "@/components/ReturnButton.vue";
import Validation from "@/Validation";
import {createToast} from "mosha-vue-toastify";

export default {
  name: "oneHistory",
  data() {
    return {
      newName: "",
      newAmount: "",
      newCategory: "",
      newDetails: "",
      categories: [],
      transaction: Object,
      delModalVisible: false,
      changeModalVisible: false,
      items:
          [{
            label: 'Właściwości',
            items: [
              {
                label: 'Edytuj',
                icon: 'bi bi-pencil-square',
                command: () => {
                  this.changeModalVisible = true;
                },
              },

              {
                label: 'Usuń',
                icon: 'bi bi-trash',
                command: () => {
                  this.delModalVisible = true;
                }
              },
            ]
          }
          ],
    }
  },
  components: {ReturnButton, IconHeader},

  created() {
    this.getOneTransaction();
    this.getCategories();
  },

  methods: {
    toggle(event) {
      this.$refs.menu.toggle(event);
    },

    transactionValidation(){
      const nameValidator = new Validation(this.newName, "name", "nazwa");
      const amountValidator = new Validation(this.newAmount, "amount", "kwota")
      const textValidator = new Validation(this.describe, "describe", "opis")
      const categoryValidator = new Validation(this.category, "category", "kategoria")

      nameValidator.required().specialChars().check();
      amountValidator.required().check();
      textValidator.specialChars().check();
      categoryValidator.required().check();

      return nameValidator.isValid() && amountValidator.isValid() && textValidator.isValid() && categoryValidator.isValid();
    },

    getCategories() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.get(`http://localhost:8741/api/category/${this.$route.params.type}`,config)
          .then(response => {
            console.log(response)
            this.categories = response.data
          })
          .catch(error => {
            console.log(error)
          })
    },

    editTransaction(){
      if(this.transactionValidation()){
        let token = sessionStorage.getItem("token");
        const config = {
          headers: {Authorization: `Bearer ${token}`}
        };
        axios.put(`http://localhost:8741/api/transaction/${this.$route.params.type}/${this.$route.params.id}`,
            {
              "name": this.newName !== this.transaction.name ? this.newName : null,
              "amount": this.newAmount !== this.transaction.amount ? this.newAmount : null,
              "category": this.newCategory !== this.transaction.category ? this.newCategory : null,
              "details": this.newCategory !== this.transaction.details ? this.newDetails : null,
            }, config)
            .then(response => {
              console.log(response)
              this.changeModalVisible = false
              createToast({
                    title: 'Pomyślnie zapisano zmiany',
                  },
                  {
                    showIcon: 'true',
                    position: 'top-center',
                    type: 'success',
                    transition: 'zoom',
                  })
              this.getOneTransaction();
            })
            .catch(error => {
              console.log(error)
            })
      }
    },

    getOneTransaction() {
      let token = sessionStorage.getItem("token");
      const config = {
        headers: {Authorization: `Bearer ${token}`}
      };
      axios.get(`http://localhost:8741/api/transaction/${this.$route.params.type}/${this.$route.params.id}`, config)
          .then(response => {
            console.log(response)
            this.transaction = response.data
            this.newName = this.transaction.name;
            this.newAmount = this.transaction.amount;
            this.newCategory = this.transaction.category;
            this.newDetails = this.transaction.details;
          })
          .catch(error => {
            console.log(error)
          })
    },
  },
}
</script>

<style scoped>
h1 {
  color: #9646e3;
  font-size: 48px;
  background-color: rgba(255, 255, 255, 0.8);
}


h5 {
  color: grey;
}

.wt {
  box-shadow: 0 0 20px 20px rgba(255, 255, 255, 0.95);
  background-color: rgba(255, 255, 255, 0.9);
  max-width: 400px;
  margin: 0 auto;
  text-align: left;
}

.head {
  display: flex;
  align-items: center;
  max-width: 500px;
  margin: 0 auto;
}

@media screen and (max-width: 424px) {
  .head {
    max-width: 300px
  }

  .wt {
    max-width: 300px;
  }
}
</style>